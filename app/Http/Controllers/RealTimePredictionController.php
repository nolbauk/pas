<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phpml\ModelManager;
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
        $path = storage_path('app/models/');
        if (!file_exists($path . 'svm_model.phpml') || !file_exists($path . 'vectorizer.phpml') || !file_exists($path . 'tfidf.phpml')) {
            return back()
                ->withInput()
                ->with('error', 'Model belum ditraining! Silakan lakukan training terlebih dahulu.');
        }

        // Load the trained SVM classifier and text transformers into memory
        $modelManager = new ModelManager();
        $classifier = $modelManager->restoreFromFile($path . 'svm_model.phpml');
        $vectorizer = unserialize(file_get_contents($path . 'vectorizer.phpml'));
        $tfIdfTransformer = unserialize(file_get_contents($path . 'tfidf.phpml'));

        // Preprocess the user's input text using the same rules applied during training
        $preprocessor = new TextPreprocessor();
        $processedText = $preprocessor->processText($inputText);

        // Transform the processed text into numerical TF-IDF features
        $samples = [$processedText];
        $vectorizer->transform($samples);
        $tfIdfTransformer->transform($samples);
        
        // Predict the sentiment using the trained SVM model
        $prediction = $classifier->predict($samples);

        // Format the predicted label
        $predictedLabel = is_array($prediction) ? (int) $prediction[0] : (int) $prediction;

        // Prepare the prediction result array to pass back to the view
        $result = [
            'original'  => $inputText,
            'processed' => $processedText,
            'label'     => $predictedLabel === 1 ? 'Positif' : 'Negatif',
        ];

        return view('real-time-prediction.real-time-prediction', compact('result', 'inputText'));
    }
}
