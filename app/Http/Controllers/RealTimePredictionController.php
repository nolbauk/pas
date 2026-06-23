<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\TextPreprocessor;

class RealTimePredictionController extends Controller
{
    /**
     * Display the real-time prediction page
     */
    public function index()
    {
        return view('real-time-prediction.real-time-prediction');
    }

    /**
     * Handle the real-time prediction request for a single custom text input
     */
    public function predict(Request $request)
    {
        // Increase memory and execution limits for loading large ML models
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '120');

        // Validate the incoming text input
        $request->validate([
            'text' => 'required|string|max:5000',
        ]);

        $inputText = $request->input('text');

        // Verify that the trained SVM model and TF-IDF transformers exist
        $classifierSer = Cache::get('ml_svm_model');
        $vectorizerSer = Cache::get('ml_vectorizer');
        $tfidfSer = Cache::get('ml_tfidf');

        if (!$classifierSer || !$vectorizerSer || !$tfidfSer) {
            return back()
                ->withInput()
                ->with('error', 'Model belum ditraining! Silakan lakukan training terlebih dahulu.');
        }

        // Load the trained SVM classifier and text transformers into memory
        $classifier = unserialize($classifierSer);
        // Vercel only allows writing to /tmp directory
        $classifier->setVarPath(sys_get_temp_dir());
        
        $vectorizer = unserialize($vectorizerSer);
        $tfIdfTransformer = unserialize($tfidfSer);

        // Preprocess the user's input text using the same rules applied during training
        $preprocessor = new TextPreprocessor();
        $processedText = $preprocessor->processText($inputText);

        // Transform the processed text into numerical TF-IDF features
        $samples = [$processedText];
        $vectorizer->transform($samples);

        // Capture TF (Term Frequency) counts BEFORE TF-IDF transformation
        $tfCounts = $samples[0]; // raw token counts per vocabulary index

        // Extract the IDF array from the transformer using Reflection
        $idfValues = [];
        try {
            $ref = new \ReflectionClass($tfIdfTransformer);
            $idfProp = $ref->getProperty('idf');
            $idfProp->setAccessible(true);
            $idfValues = $idfProp->getValue($tfIdfTransformer);
        } catch (\Exception $e) {
            // Fallback: empty
        }

        $tfIdfTransformer->transform($samples);

        // Build the "perhitungan" (calculation) array for each word in the vocabulary
        $vocabulary = $vectorizer->getVocabulary();
        $perhitungan = [];
        foreach ($vocabulary as $index => $word) {
            $tf = $tfCounts[$index] ?? 0;
            if ($tf > 0) { // Only show words that actually appear in the input
                $idf = $idfValues[$index] ?? 0;
                $tfidf = $samples[0][$index] ?? 0;
                $perhitungan[] = [
                    'word'  => $word,
                    'tf'    => (int) $tf,
                    'idf'   => round($idf, 4),
                    'tfidf' => round($tfidf, 4),
                ];
            }
        }
        
        // Predict the sentiment using the trained SVM model
        $prediction = $classifier->predict($samples);

        // Format the predicted label correctly (it can be string "1", "0", "Positif", "Negatif" depending on training data)
        $rawLabel = is_array($prediction) ? $prediction[0] : $prediction;
        $mappedLabel = 'Negatif'; // default
        if ($rawLabel === 1 || $rawLabel === '1' || strtolower((string)$rawLabel) === 'positif') {
            $mappedLabel = 'Positif';
        }

        // --- Extract SVM weights and compute f(x) = w·x + b ---
        $svmDecision = [];
        try {
            // The classifier is an instance of SVC, which extends SupportVectorMachine.
            // The 'model' property is private in SupportVectorMachine.
            $classifierRef = new \ReflectionClass($classifier);
            $parentRef = $classifierRef->getParentClass();
            if ($parentRef && $parentRef->hasProperty('model')) {
                $modelProp = $parentRef->getProperty('model');
            } else {
                $modelProp = $classifierRef->getProperty('model'); // Fallback
            }
            $modelProp->setAccessible(true);
            $modelText = $modelProp->getValue($classifier);

            // Parse rho (bias) from the model
            $rho = 0.0;
            if (preg_match('/^rho\s+(.+)$/m', $modelText, $rhoMatch)) {
                $rho = (float) trim($rhoMatch[1]);
            }
            $bias = -$rho; // In libsvm, decision = w·x - rho, so b = -rho

            // Compute w = sum(alpha_i * sv_i) for linear kernel
            $vocabSize = count($vectorizer->getVocabulary());
            $w = array_fill(0, $vocabSize, 0.0);

            // Parse support vectors and their alpha coefficients efficiently
            // Format: alpha_coef index1:value1 index2:value2 ...
            $inSV = false;
            $token = strtok($modelText, "\n");
            
            while ($token !== false) {
                $line = trim($token);
                if (!$inSV) {
                    if ($line === 'SV') {
                        $inSV = true;
                    }
                } else {
                    if ($line !== '') {
                        // Use fast string functions instead of regex
                        $parts = explode(' ', preg_replace('/\s+/', ' ', $line)); // Normalize spaces first
                        $alpha = (float) $parts[0];
                        $partCount = count($parts);
                        
                        for ($i = 1; $i < $partCount; $i++) {
                            if (strpos($parts[$i], ':') !== false) {
                                $idxVal = explode(':', $parts[$i]);
                                $idx = (int) $idxVal[0] - 1; // libsvm is 1-indexed
                                if ($idx >= 0 && $idx < $vocabSize) {
                                    $w[$idx] += $alpha * (float) $idxVal[1];
                                }
                            }
                        }
                    }
                }
                $token = strtok("\n"); // Get next line
            }

            // Compute f(x) = w·x + b (where b = -rho)
            $tfidfVector = $samples[0];
            $dotProduct = 0.0;
            $svmTerms = []; // Individual w_i * x_i terms for display

            $vocabulary = $vectorizer->getVocabulary();
            foreach ($vocabulary as $idx => $word) {
                $xi = $tfidfVector[$idx] ?? 0;
                $wi = $w[$idx] ?? 0;
                if ($xi != 0 && $wi != 0) {
                    $product = $wi * $xi;
                    $dotProduct += $product;
                    $svmTerms[] = [
                        'word'    => $word,
                        'w'       => round($wi, 4),
                        'x'      => round($xi, 4),
                        'wx'      => round($product, 4),
                    ];
                }
            }

            $fx = $dotProduct + $bias;

            // LibSVM memetakan kelas pertama yang ditemui saat training ke +1 (f(x) > 0) 
            // dan kelas kedua ke -1 (f(x) < 0). Kita deduksi mapping-nya dari hasil saat ini:
            if ($fx >= 0) {
                $classForPositiveFx = $mappedLabel;
                $classForNegativeFx = $mappedLabel === 'Positif' ? 'Negatif' : 'Positif';
            } else {
                $classForNegativeFx = $mappedLabel;
                $classForPositiveFx = $mappedLabel === 'Positif' ? 'Negatif' : 'Positif';
            }

            $svmDecision = [
                'terms'      => $svmTerms,
                'dot'        => round($dotProduct, 4),
                'bias'       => round($bias, 4),
                'rho'        => round($rho, 4),
                'fx'         => round($fx, 4),
                'label'      => $mappedLabel,
                'class_pos'  => $classForPositiveFx,
                'class_neg'  => $classForNegativeFx,
            ];
        } catch (\Throwable $e) {
            $svmDecision = ['error' => 'Gagal membongkar logika SVM: ' . $e->getMessage()];
        }

        // Load stats to get TF-IDF weights for the breakdown
        $tfIdfWeights = [];
        $stats = Cache::get('ml_training_stats');
        if ($stats && isset($stats['tf_idf_weights'])) {
            $tfIdfWeights = $stats['tf_idf_weights'];
        }

        // Get the detailed word-by-word breakdown
        $analysis = $preprocessor->analyzeText($inputText);

        // Attach TF-IDF weight to the analysis breakdown
        foreach ($analysis as &$item) {
            $item['weight'] = '-'; // Default
            if ($item['processed'] !== '-') {
                // The processed text might contain multiple words
                $processedWords = explode(' ', $item['processed']);
                $weights = [];
                foreach ($processedWords as $pw) {
                    if (isset($tfIdfWeights[$pw])) {
                        $weights[] = number_format($tfIdfWeights[$pw], 4);
                    } else {
                        $weights[] = '0.0000';
                    }
                }
                $item['weight'] = implode('<br>', $weights);
            }
        }

        // Prepare the prediction result array to pass back to the view
        $result = [
            'original'     => $inputText,
            'processed'    => $processedText,
            'label'        => $mappedLabel,
            'breakdown'    => $analysis,
            'perhitungan'  => $perhitungan,
            'svm_decision' => $svmDecision,
        ];

        return view('real-time-prediction.real-time-prediction', compact('result', 'inputText'));
    }
}
