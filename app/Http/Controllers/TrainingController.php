<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dataset;
use App\Models\PreprocessingResult;
use Illuminate\Support\Facades\Storage;

use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\ModelManager;

class TrainingController extends Controller
{
    /**
     * Display the model training page
     */
    public function index()
    {
        return view('training.training');
    }

    /**
     * Execute the entire Machine Learning Training pipeline
     */
    public function process()
    {
        // Training ML can consume a lot of memory and time, so we temporarily remove the limits
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        // 1. Fetch preprocessed data that possesses a valid sentiment label
        $data = PreprocessingResult::join('datasets', 'preprocessing_results.dataset_id', '=', 'datasets.id')
            ->select('preprocessing_results.processed_text', 'datasets.label')
            ->whereNotNull('datasets.label')
            ->where('datasets.label', '!=', '')
            ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Gagal: Tidak ada data dataset yang memiliki label. Pastikan dataset sudah diberi label (positif/negatif, dll).');
        }

        $samples = [];
        $labels = [];

        foreach ($data as $row) {
            if (!empty($row->processed_text)) {
                // In PreprocessingController, words were separated by ' | '. 
                // We change it to a standard space so the WhitespaceTokenizer can parse it.
                $samples[] = str_replace(' | ', ' ', $row->processed_text);
                $labels[] = $row->label;
            }
        }

        if (count($samples) < 2) {
            return back()->with('error', 'Gagal: Data terlalu sedikit untuk di-training. Minimal butuh 2 data.');
        }

        try {
            // 2. Term Frequency: Token Count Vectorizer (counts occurrences of words)
            $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
            $vectorizer->fit($samples);
            $vectorizer->transform($samples); // $samples now becomes an array of token counts

            // 3. Inverse Document Frequency: Transformer (converts raw counts to TF-IDF weights)
            $tfIdfTransformer = new TfIdfTransformer($samples);
            $tfIdfTransformer->fit($samples);
            $tfIdfTransformer->transform($samples); // $samples now becomes an array of decimal TF-IDF weights

            // 4. Train the Support Vector Machine (SVM) Classification Model
            $classifier = new SVC(Kernel::LINEAR, $cost = 1000);
            $classifier->train($samples, $labels);

            // 5. Save the trained Model and Extractors for future use during Testing/Prediction
            // In a Vercel Serverless environment, the filesystem is read-only.
            // We use Laravel Cache (configured to database driver in vercel.json) to persist models.
            
            \Illuminate\Support\Facades\Cache::put('ml_svm_model', serialize($classifier));
            \Illuminate\Support\Facades\Cache::put('ml_vectorizer', serialize($vectorizer));
            \Illuminate\Support\Facades\Cache::put('ml_tfidf', serialize($tfIdfTransformer));

            // Extract IDF weights and Vocabulary for display
            $vocabulary = $vectorizer->getVocabulary(); // returns [index => word]
            
            // Use Reflection to access private $idf property of TfIdfTransformer
            $reflector = new \ReflectionClass($tfIdfTransformer);
            $property = $reflector->getProperty('idf');
            $property->setAccessible(true);
            $idfWeights = $property->getValue($tfIdfTransformer);
            
            $wordWeights = [];
            foreach ($vocabulary as $index => $word) {
                $wordWeights[$word] = isset($idfWeights[$index]) ? round($idfWeights[$index], 4) : 0;
            }
            
            // Sort by weight descending
            arsort($wordWeights);
            
            $stats = [
                'svm_config' => [
                    'kernel' => 'Linear',
                    'cost' => 1000,
                    'degree' => 3,
                    'gamma' => 'null',
                    'coef0' => 0.0,
                    'tolerance' => 0.001
                ],
                'tf_idf_weights' => $wordWeights,
                'total_words' => count($wordWeights)
            ];
            
            \Illuminate\Support\Facades\Cache::put('ml_training_stats', $stats);

            return back()->with('success', 'Training SVM berhasil dilakukan pada ' . count($labels) . ' data komentar.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat training: ' . $e->getMessage());
        }
    }
}
