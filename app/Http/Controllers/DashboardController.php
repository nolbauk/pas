<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalData = 0;
        $positiveCount = 0;
        $negativeCount = 0;
        
        // Use collections so we can use count() like $positiveData->count()
        $positiveData = collect();
        $negativeData = collect();

        $path = storage_path('app/models/testing_results.json');
        if (file_exists($path)) {
            $saved = json_decode(file_get_contents($path), true);
            $results = $saved['results'] ?? [];
            $totalData = count($results); // Total testing data
            
            foreach ($results as $res) {
                // True Positive (actual 1, predicted 1)
                if ($res['actual'] == 1 && $res['predicted'] == 1) {
                    $positiveCount++;
                    $positiveData->push((object) [
                        'full_text' => $res['komentar'],
                        'label' => $res['predicted']
                    ]);
                } 
                // True Negative (actual 0, predicted 0)
                elseif ($res['actual'] == 0 && $res['predicted'] == 0) {
                    $negativeCount++;
                    $negativeData->push((object) [
                        'full_text' => $res['komentar'],
                        'label' => $res['predicted']
                    ]);
                }
            }
        }

        return view('visualization.visualization', compact('totalData', 'positiveCount', 'negativeCount', 'positiveData', 'negativeData'));
    }

    public function dashboard()
    {
        $totalData = 0;
        $positiveCount = 0;
        $negativeCount = 0;
        
        $metrics = null;
        $path = storage_path('app/models/testing_results.json');
        if (file_exists($path)) {
            $saved = json_decode(file_get_contents($path), true);
            $metrics = $saved['metrics'] ?? null;
            
            $results = $saved['results'] ?? [];
            
            foreach ($results as $res) {
                // True Positive
                if ($res['actual'] == 1 && $res['predicted'] == 1) {
                    $positiveCount++;
                } 
                // True Negative
                elseif ($res['actual'] == 0 && $res['predicted'] == 0) {
                    $negativeCount++;
                }
            }
            
            // For percentage calculations on the dashboard, we want the sum of TP and TN to represent the whole distribution of correct predictions
            $totalData = $positiveCount + $negativeCount; 
        }
        
        // Calculate percentages based on correctly predicted data
        $posPercent = $totalData > 0 ? round(($positiveCount / $totalData) * 100, 1) : 0;
        $negPercent = $totalData > 0 ? round(($negativeCount / $totalData) * 100, 1) : 0;
        
        // For display purpose of total evaluated data, we can reset it to total rows if preferred,
        // but here we keep it as the sum of TP + TN so the ratio bar equals 100%.
        $totalData = isset($results) ? count($results) : 0;

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
