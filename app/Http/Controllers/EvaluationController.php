<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        $metrics = null;

        // Load saved testing results if they exist
        $path = storage_path('app/models/testing_results.json');
        if (file_exists($path)) {
            $saved = json_decode(file_get_contents($path), true);
            $metrics = $saved['metrics'] ?? null;
        }

        return view('evaluation.evaluation', compact('metrics'));
    }
}
