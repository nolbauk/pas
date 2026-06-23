<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\TextPreprocessor;

class TestingController extends Controller
{
    public function index()
    {
        $results = null;
        $metrics = null;

        // Load saved testing results if they exist to persist data across page reloads
        $saved = Cache::get('ml_testing_results');
        if ($saved) {
            $results = $saved['results'] ?? null;
            $metrics = $saved['metrics'] ?? null;
        }

        return view('testing.testing', compact('results', 'metrics'));
    }

    /**
     * Handle the upload of a test dataset and evaluate the trained model
     */
    public function predict(Request $request)
    {
        // Increase memory limit because loading large ML models takes a lot of RAM
        ini_set('memory_limit', '1024M');
        // Increase execution time in case the dataset is very large
        ini_set('max_execution_time', '300');

        // Validate the uploaded test dataset
        $request->validate([
            'testingFile' => 'required|mimes:csv,txt|max:10240',
        ]);

        // Verify that the required machine learning models exist
        $classifierSer = Cache::get('ml_svm_model');
        $vectorizerSer = Cache::get('ml_vectorizer');
        $tfidfSer = Cache::get('ml_tfidf');
        
        if (!$classifierSer || !$vectorizerSer || !$tfidfSer) {
            return back()->with('error', 'Model belum ditraining! Silakan lakukan training terlebih dahulu.');
        }

        // Load trained classification models and feature extractors
        $classifier = unserialize($classifierSer);
        $vectorizer = unserialize($vectorizerSer);
        $tfIdfTransformer = unserialize($tfidfSer);

        $preprocessor = new TextPreprocessor();

        // Open the uploaded CSV file
        $file = $request->file('testingFile');
        $handle = fopen($file->getPathname(), "r");
        
        $header = fgetcsv($handle, 1000, ",");
        
        // Dynamically find the indices for text and label columns based on headers
        $textIndex = -1;
        $labelIndex = -1;
        
        for ($i = 0; $i < count($header); $i++) {
            $colName = strtolower(trim($header[$i]));
            if (str_contains($colName, 'text') || str_contains($colName, 'komentar')) {
                $textIndex = $i;
            }
            if (str_contains($colName, 'label') || str_contains($colName, 'sentimen')) {
                $labelIndex = $i;
            }
        }
        
        // Default to first and second columns if headers are missing
        if ($textIndex === -1) $textIndex = 0; 
        if ($labelIndex === -1) $labelIndex = 1;

        $rawTexts = [];
        $processedTexts = [];
        $actualLabels = [];

        // Read and preprocess the CSV data
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (empty($data[$textIndex])) continue;

            $rawText = $data[$textIndex];
            $actualLabel = isset($data[$labelIndex]) ? (int)trim($data[$labelIndex]) : null;

            $processedText = $preprocessor->processText($rawText);
            
            $rawTexts[] = $rawText;
            $processedTexts[] = $processedText;
            $actualLabels[] = $actualLabel;
        }
        fclose($handle);

        if (empty($processedTexts)) {
            return back()->with('error', 'File tidak memiliki data yang valid.');
        }

        // Transform processed texts to TF-IDF vectors
        $samples = $processedTexts;
        $vectorizer->transform($samples);
        $tfIdfTransformer->transform($samples);

        $results = [];
        $tp = 0; $tn = 0; $fp = 0; $fn = 0;
        $hasLabels = false;

        // Predict in chunks of 500 to balance memory usage (prevents 1GB RAM crash)
        // and execution time (prevents 30s timeout by not running it 2000 times sequentially).
        $predictions = [];
        foreach (array_chunk($samples, 500, true) as $chunk) {
            $chunkPredictions = $classifier->predict(array_values($chunk));
            
            // Re-index predictions to match original keys
            $i = 0;
            foreach ($chunk as $originalIndex => $val) {
                $predictions[$originalIndex] = $chunkPredictions[$i++];
            }
        }

        // Calculate evaluation metrics (Confusion Matrix)
        foreach ($samples as $index => $sample) {
            $predicted = (int) $predictions[$index];
            $actual = $actualLabels[$index];

            $results[] = [
                'no' => $index + 1,
                'komentar' => $rawTexts[$index],
                'processed' => $processedTexts[$index],
                'actual' => $actual,
                'predicted' => $predicted
            ];

            if ($actual !== null) {
                $hasLabels = true;
                if ($actual === 1 && $predicted === 1) $tp++; // True Positive
                if ($actual === 0 && $predicted === 0) $tn++; // True Negative
                if ($actual === 0 && $predicted === 1) $fp++; // False Positive
                if ($actual === 1 && $predicted === 0) $fn++; // False Negative
            }
        }

        $metrics = null;
        if ($hasLabels) {
            // Compute standard machine learning metrics
            $total = $tp + $tn + $fp + $fn;
            $accuracy = $total > 0 ? ($tp + $tn) / $total : 0;
            $precision = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;
            $recall = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;
            $f1 = ($precision + $recall) > 0 ? 2 * ($precision * $recall) / ($precision + $recall) : 0;

            $metrics = [
                'accuracy' => round($accuracy * 100, 2),
                'precision' => round($precision * 100, 2),
                'recall' => round($recall * 100, 2),
                'f1' => round($f1 * 100, 2),
                'confusion_matrix' => [
                    'tp' => $tp, 'tn' => $tn, 'fp' => $fp, 'fn' => $fn
                ]
            ];
        }

        // Save results to Cache so they persist across page navigation
        Cache::put('ml_testing_results', [
            'results' => $results,
            'metrics' => $metrics,
            'tested_at' => now()->toDateTimeString(),
        ]);

        return view('testing.testing', compact('results', 'metrics'))->with('success', 'Testing berhasil dilakukan!');
    }
    
    /**
     * Clear all saved testing results
     */
    public function clear()
    {
        // Delete the testing results from Cache
        Cache::forget('ml_testing_results');

        return redirect()->route('testing')->with('success', 'Data testing berhasil dihapus!');
    }
}
