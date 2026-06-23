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

        // Load saved testing results from Cache
        $saved = \Illuminate\Support\Facades\Cache::get('ml_testing_results');
        if ($saved) {
            // Retrieve evaluation metrics (accuracy, precision, recall, f1)
            $metrics = $saved['metrics'] ?? null;
        }

        return view('evaluation.evaluation', compact('metrics'));
    }
}
