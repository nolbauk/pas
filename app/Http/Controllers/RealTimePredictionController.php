<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phpml\ModelManager;
use App\Services\TextPreprocessor;

class RealTimePredictionController extends Controller
{
    public function index()
    {
        return view('real-time-prediction.real-time-prediction');
    }

    public function predict(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '120');

        $request->validate([
            'text' => 'required|string|max:5000',
        ]);

        $inputText = $request->input('text');

        // Check if model files exist
        $path = storage_path('app/models/');
        if (!file_exists($path . 'svm_model.phpml') || !file_exists($path . 'vectorizer.phpml') || !file_exists($path . 'tfidf.phpml')) {
            return back()
                ->withInput()
                ->with('error', 'Model belum ditraining! Silakan lakukan training terlebih dahulu.');
        }

        // Load trained model and transformers
        $modelManager = new ModelManager();
        $classifier = $modelManager->restoreFromFile($path . 'svm_model.phpml');
        $vectorizer = unserialize(file_get_contents($path . 'vectorizer.phpml'));
        $tfIdfTransformer = unserialize(file_get_contents($path . 'tfidf.phpml'));

        // Preprocess the input text
        $preprocessor = new TextPreprocessor();
        $processedText = $preprocessor->processText($inputText);

        // Transform & predict
        $samples = [$processedText];
        $vectorizer->transform($samples);
        $tfIdfTransformer->transform($samples);
        $prediction = $classifier->predict($samples);

        $predictedLabel = is_array($prediction) ? (int) $prediction[0] : (int) $prediction;

        $result = [
            'original'  => $inputText,
            'processed' => $processedText,
            'label'     => $predictedLabel === 1 ? 'Positif' : 'Negatif',
        ];

        return view('real-time-prediction.real-time-prediction', compact('result', 'inputText'));
    }
}
