<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\PreprocessingResult;
use Illuminate\Support\Facades\Cache;

class PreprocessingController extends Controller
{
    /**
     * Display the text preprocessing results page
     */
    public function index()
    {
        // Fetch all preprocessing results ordered by dataset ID
        $results = PreprocessingResult::orderBy('dataset_id', 'asc')->get();
        
        $stats = Cache::get('ml_training_stats');

        return view('train-model.train-model', compact('results', 'stats'));
    }

    /**
     * Process the raw dataset texts using NLP text preprocessing
     */
    public function process()
    {
        // Clear old preprocessing results to prevent duplicates
        PreprocessingResult::query()->delete();

        // Fetch all raw datasets
        $datasets = Dataset::all();

        // Initialize the TextPreprocessor service
        $preprocessor = new \App\Services\TextPreprocessor();
        
        $now = now();
        $batch = [];

        // Iterate through each dataset and preprocess its text
        foreach ($datasets as $data) {
            $original = $data->full_text;
            // Clean, normalize, tokenize, and stem the text
            $processedText = $preprocessor->processText($original);

            // Add to batch instead of creating one-by-one to avoid Vercel timeouts
            $batch[] = [
                'dataset_id' => $data->id,
                'original_text' => $original,
                'processed_text' => $processedText,
                'created_at' => $now,
                'updated_at' => $now
            ];

            if (count($batch) >= 500) {
                PreprocessingResult::insert($batch);
                $batch = [];
            }
        }
        
        if (count($batch) > 0) {
            PreprocessingResult::insert($batch);
        }

        // Programmatically trigger the model training process immediately after preprocessing
        return app(TrainingController::class)->process();
    }

    /**
     * Return word-by-word analysis of the preprocessing steps
     */
    public function analyze($id)
    {
        $result = PreprocessingResult::findOrFail($id);
        
        $preprocessor = new \App\Services\TextPreprocessor();
        $analysis = $preprocessor->analyzeText($result->original_text);

        // Load stats from cache to get TF-IDF weights
        $tfIdfWeights = [];
        $stats = Cache::get('ml_training_stats');
        if ($stats && isset($stats['tf_idf_weights'])) {
            $tfIdfWeights = $stats['tf_idf_weights'];
        }

        // Attach TF-IDF weight to the analysis
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

        return response()->json([
            'success' => true,
            'data' => $analysis
        ]);
    }
}