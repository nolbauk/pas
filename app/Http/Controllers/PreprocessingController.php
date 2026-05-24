<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\PreprocessingResult;

class PreprocessingController extends Controller
{
    /**
     * Display the text preprocessing results page
     */
    public function index()
    {
        // Fetch all preprocessing results ordered by dataset ID
        $results = PreprocessingResult::orderBy('dataset_id', 'asc')->get();
        return view('train-model.train-model', compact('results'));
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

        // Iterate through each dataset and preprocess its text
        foreach ($datasets as $data) {
            $original = $data->full_text;
            // Clean, normalize, tokenize, and stem the text
            $processedText = $preprocessor->processText($original);

            // Save the preprocessed text to the database
            PreprocessingResult::create([
                'dataset_id' => $data->id,
                'original_text' => $original,
                'processed_text' => $processedText
            ]);
        }

        // Programmatically trigger the model training process immediately after preprocessing
        return app(TrainingController::class)->process();
    }
}