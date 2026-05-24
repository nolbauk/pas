<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the data visualization page
     * This method retrieves data to be displayed in charts and tables
     */
    public function index()
    {
        $totalData = 0;
        $positiveCount = 0;
        $negativeCount = 0;
        
        // Use collections so we can use count() like $positiveData->count()
        $positiveData = collect();
        $negativeData = collect();

        // Load testing results from the JSON file
        $path = storage_path('app/models/testing_results.json');
        if (file_exists($path)) {
            $saved = json_decode(file_get_contents($path), true);
            $results = $saved['results'] ?? [];
            $totalData = count($results); // Total testing data evaluated
            
            // Loop through results to separate into True Positives and True Negatives
            foreach ($results as $res) {
                // True Positive (actual is 1 and predicted is 1)
                if ($res['actual'] == 1 && $res['predicted'] == 1) {
                    $positiveCount++;
                    $positiveData->push((object) [
                        'full_text' => $res['komentar'],
                        'label' => $res['predicted']
                    ]);
                } 
                // True Negative (actual is 0 and predicted is 0)
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

    /**
     * Display the main summary dashboard page
     * This method calculates overall sentiment percentages and model metrics
     */
    public function dashboard()
    {
        $totalData = 0;
        $positiveCount = 0;
        $negativeCount = 0;
        
        $metrics = null;
        
        // Load testing results from the JSON file
        $path = storage_path('app/models/testing_results.json');
        if (file_exists($path)) {
            $saved = json_decode(file_get_contents($path), true);
            // Retrieve evaluation metrics (accuracy, precision, recall, f1)
            $metrics = $saved['metrics'] ?? null;
            
            $results = $saved['results'] ?? [];
            
            // Count correctly predicted positive and negative sentiments
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
            
            // For percentage calculations on the dashboard, we use the sum of TP and TN 
            // to represent the whole distribution of correct predictions
            $totalData = $positiveCount + $negativeCount; 
        }
        
        // Calculate percentages based on correctly predicted data
        $posPercent = $totalData > 0 ? round(($positiveCount / $totalData) * 100, 1) : 0;
        $negPercent = $totalData > 0 ? round(($negativeCount / $totalData) * 100, 1) : 0;
        
        // For display purpose of total evaluated data on the dashboard card, 
        // we reset totalData to the actual total rows evaluated.
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
