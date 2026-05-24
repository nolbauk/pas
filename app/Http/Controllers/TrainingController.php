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
            $modelManager = new ModelManager();
            
            $path = storage_path('app/models/');
            
            // Create the storage/app/models folder if it doesn't exist
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            // Save the SVM classifier model
            $modelManager->saveToFile($classifier, $path . 'svm_model.phpml');
            
            // ModelManager only accepts Estimators, so for Vectorizer and Transformer we use PHP's native serialize()
            file_put_contents($path . 'vectorizer.phpml', serialize($vectorizer));
            file_put_contents($path . 'tfidf.phpml', serialize($tfIdfTransformer));

            return back()->with('success', 'Training SVM berhasil dilakukan pada ' . count($labels) . ' data komentar.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat training: ' . $e->getMessage());
        }
    }
}
