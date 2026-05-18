<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phpml\ModelManager;
use App\Services\TextPreprocessor;

class TestingController extends Controller
{
    private function getResultsPath()
    {
        return storage_path('app/models/testing_results.json');
    }

    public function index()
    {
        $results = null;
        $metrics = null;

        // Load saved results if they exist
        $path = $this->getResultsPath();
        if (file_exists($path)) {
            $saved = json_decode(file_get_contents($path), true);
            $results = $saved['results'] ?? null;
            $metrics = $saved['metrics'] ?? null;
        }

        return view('testing.testing', compact('results', 'metrics'));
    }

    public function predict(Request $request)
    {
        // Increase memory limit because loading large ML models takes a lot of RAM
        ini_set('memory_limit', '1024M');
        // Increase execution time in case the dataset is very large
        ini_set('max_execution_time', '300');

        $request->validate([
            'testingFile' => 'required|mimes:csv,txt|max:10240',
        ]);

        $path = storage_path('app/models/');
        if (!file_exists($path . 'svm_model.phpml') || !file_exists($path . 'vectorizer.phpml')) {
            return back()->with('error', 'Model belum ditraining! Silakan lakukan training terlebih dahulu.');
        }

        // Load models
        $modelManager = new ModelManager();
        $classifier = $modelManager->restoreFromFile($path . 'svm_model.phpml');
        $vectorizer = unserialize(file_get_contents($path . 'vectorizer.phpml'));
        $tfIdfTransformer = unserialize(file_get_contents($path . 'tfidf.phpml'));

        $preprocessor = new TextPreprocessor();

        $file = $request->file('testingFile');
        $handle = fopen($file->getPathname(), "r");
        
        $header = fgetcsv($handle, 1000, ",");
        
        // Find indices
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
        
        if ($textIndex === -1) $textIndex = 0; // Default to first column
        if ($labelIndex === -1) $labelIndex = 1; // Default to second column

        $rawTexts = [];
        $processedTexts = [];
        $actualLabels = [];

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

        // Transform data
        $samples = $processedTexts;
        $vectorizer->transform($samples);
        $tfIdfTransformer->transform($samples);

        $results = [];
        $tp = 0; $tn = 0; $fp = 0; $fn = 0;
        $hasLabels = false;

        // Predict in chunks of 500 to balance memory usage (prevents 1GB RAM crash)
        // and execution time (prevents 30s timeout by not running it 2000 times).
        $predictions = [];
        foreach (array_chunk($samples, 500, true) as $chunk) {
            $chunkPredictions = $classifier->predict(array_values($chunk));
            
            // Re-index predictions to match original keys
            $i = 0;
            foreach ($chunk as $originalIndex => $val) {
                $predictions[$originalIndex] = $chunkPredictions[$i++];
            }
        }

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
                if ($actual === 1 && $predicted === 1) $tp++;
                if ($actual === 0 && $predicted === 0) $tn++;
                if ($actual === 0 && $predicted === 1) $fp++;
                if ($actual === 1 && $predicted === 0) $fn++;
            }
        }

        $metrics = null;
        if ($hasLabels) {
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

        // Save results to file so they persist across page navigation
        file_put_contents($this->getResultsPath(), json_encode([
            'results' => $results,
            'metrics' => $metrics,
            'tested_at' => now()->toDateTimeString(),
        ]));

        return view('testing.testing', compact('results', 'metrics'))->with('success', 'Testing berhasil dilakukan!');
    }
    public function clear()
    {
        $path = $this->getResultsPath();
        if (file_exists($path)) {
            unlink($path);
        }

        return redirect()->route('testing')->with('success', 'Data testing berhasil dihapus!');
    }
}
