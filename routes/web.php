<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\RealTimePredictionController;
use App\Http\Controllers\TestingController;

/**
 * --------------------------------------------------------------------------
 * Public Routes
 * --------------------------------------------------------------------------
 * These routes are accessible to anyone visiting the website without authentication.
 */
Route::get('/', function () {
    return view('landing.landing');
});

/**
 * --------------------------------------------------------------------------
 * Guest Routes
 * --------------------------------------------------------------------------
 * These routes are only accessible to users who are NOT logged in.
 * If an authenticated user tries to access these, they will be redirected.
 */
Route::middleware('guest')->group(function () {
    // Authentication endpoints
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Registration endpoints
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/**
 * --------------------------------------------------------------------------
 * Authenticated Routes
 * --------------------------------------------------------------------------
 * These routes require the user to be successfully logged in.
 */
Route::middleware('auth')->group(function () {
    // Logout (Using GET for simplicity as requested/used in the sidebar navigation)
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); 
    
    // Main Dashboard & Visualization pages (Accessible by all logged-in users)
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/visualization', [DashboardController::class, 'index'])->name('visualization');
    
    // View Datasets (Accessible by all logged-in users)
    Route::get('/upload-dataset', [DatasetController::class, 'index'])->name('dataset.index');
    
    /**
     * --------------------------------------------------------------------------
     * Admin-Only Routes
     * --------------------------------------------------------------------------
     * These routes require the authenticated user to have an 'admin' role.
     * Actions that mutate system-wide data (Uploading, Training, Deleting) are restricted here.
     */
    Route::middleware('admin')->group(function () {
        // Dataset Management
        Route::post('/upload-dataset', [DatasetController::class, 'upload'])->name('dataset.upload');
        Route::delete('/upload-dataset/clear', [DatasetController::class, 'clear'])->name('dataset.clear');
        
        // Machine Learning Pipeline (Preprocessing & Training)
        Route::get('/train-model', [PreprocessingController::class, 'index'])->name('train-model.index');
        Route::post('/train-model/process', [PreprocessingController::class, 'process'])->name('train-model.process');
        
        // Clear Testing Results
        Route::delete('/testing/clear', [TestingController::class, 'clear'])->name('testing.clear');
    });

    /**
     * --------------------------------------------------------------------------
     * Testing & Evaluation Routes (Read-Only features)
     * --------------------------------------------------------------------------
     * General users can still perform testing and predictions, but they cannot 
     * retrain or delete the underlying dataset models (restricted above).
     */

    // Batch Testing Pipeline
    Route::get('/testing', [TestingController::class, 'index'])->name('testing');
    Route::post('/testing/predict', [TestingController::class, 'predict'])->name('testing.predict');

    // Evaluation Metrics Page
    Route::get('/evaluation', [EvaluationController::class, 'index'])->name('evaluation');
    
    // Real-Time Custom Input Prediction
    Route::get('/real-time-prediction', [RealTimePredictionController::class, 'index'])->name('real-time-prediction');
    Route::post('/real-time-prediction/predict', [RealTimePredictionController::class, 'predict'])->name('real-time-prediction.predict');
});