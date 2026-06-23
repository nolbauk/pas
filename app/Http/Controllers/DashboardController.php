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

        // Load testing results from Cache
        $saved = \Illuminate\Support\Facades\Cache::get('ml_testing_results');
        if ($saved) {
            $results = $saved['results'] ?? [];
            $totalData = count($results); // Total testing data evaluated
            
            // Loop through results to separate into Positives and Negatives based on Prediction
            foreach ($results as $res) {
                // Predicted Positive
                if ($res['predicted'] == 1) {
                    $positiveCount++;
                    $positiveData->push((object) [
                        'full_text' => $res['komentar'],
                        'label' => $res['predicted']
                    ]);
                } 
                // Predicted Negative
                elseif ($res['predicted'] == 0) {
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
        
        // Load testing results from Cache
        $saved = \Illuminate\Support\Facades\Cache::get('ml_testing_results');
        if ($saved) {
            // Retrieve evaluation metrics (accuracy, precision, recall, f1)
            $metrics = $saved['metrics'] ?? null;
            
            $results = $saved['results'] ?? [];
            
            // Count predicted positive and negative sentiments
            foreach ($results as $res) {
                // Predicted Positive
                if ($res['predicted'] == 1) {
                    $positiveCount++;
                } 
                // Predicted Negative
                elseif ($res['predicted'] == 0) {
                    $negativeCount++;
                }
            }
            
            // Total Data based on predictions
            $totalData = $positiveCount + $negativeCount; 
        }
        
        // Calculate percentages based on all predicted data
        $posPercent = $totalData > 0 ? round(($positiveCount / $totalData) * 100, 1) : 0;
        $negPercent = $totalData > 0 ? round(($negativeCount / $totalData) * 100, 1) : 0;
        
        // For display purpose of total evaluated data on the dashboard card
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
