<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Display the model evaluation metrics page
     */
    public function index()
    {
        $metrics = null;

        // Load saved testing results from the JSON file if they exist
        $path = storage_path('app/models/testing_results.json');
        if (file_exists($path)) {
            $saved = json_decode(file_get_contents($path), true);
            // Retrieve evaluation metrics (accuracy, precision, recall, f1)
            $metrics = $saved['metrics'] ?? null;
        }

        return view('evaluation.evaluation', compact('metrics'));
    }
}
