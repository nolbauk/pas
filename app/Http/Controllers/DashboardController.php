<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalData = Dataset::count();
        $positiveCount = Dataset::where('label', 1)->count();
        $negativeCount = Dataset::where('label', 0)->count();
        
        // Get positive and negative comments separately
        $positiveData = Dataset::where('label', 1)->latest('created_at')->get();
        $negativeData = Dataset::where('label', 0)->latest('created_at')->get();

        return view('visualization.visualization', compact('totalData', 'positiveCount', 'negativeCount', 'positiveData', 'negativeData'));
    }

    public function dashboard()
    {
        $totalData = Dataset::count();
        $positiveCount = Dataset::where('label', 1)->count();
        $negativeCount = Dataset::where('label', 0)->count();
        
        // Calculate percentages
        $posPercent = $totalData > 0 ? round(($positiveCount / $totalData) * 100, 1) : 0;
        $negPercent = $totalData > 0 ? round(($negativeCount / $totalData) * 100, 1) : 0;

        // Load saved testing results for metrics
        $metrics = null;
        $path = storage_path('app/models/testing_results.json');
        if (file_exists($path)) {
            $saved = json_decode(file_get_contents($path), true);
            $metrics = $saved['metrics'] ?? null;
        }

        return view('dashboard.dashboard', compact(
            'totalData', 
            'positiveCount', 
            'negativeCount', 
            'posPercent', 
            'negPercent', 
            'metrics'
        ));
    }
}
