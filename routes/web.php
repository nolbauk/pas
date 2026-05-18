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

Route::get('/', function () {
    return view('landing.landing');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); // Using GET for simplicity as requested/used in sidebar
    
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/visualization', [DashboardController::class, 'index'])->name('visualization');
    
    Route::get('/upload-dataset', [DatasetController::class, 'index'])->name('dataset.index');
    
    // Admin Only
    Route::middleware('admin')->group(function () {
        Route::post('/upload-dataset', [DatasetController::class, 'upload'])->name('dataset.upload');
        Route::delete('/upload-dataset/clear', [DatasetController::class, 'clear'])->name('dataset.clear');
        
        Route::get('/preprocessing', [PreprocessingController::class, 'index'])->name('preprocessing.index');
        Route::post('/preprocessing/process', [PreprocessingController::class, 'process'])->name('preprocessing.process');
        
        Route::get('/training', [TrainingController::class, 'index'])->name('training.index');
        Route::post('/training/process', [TrainingController::class, 'process'])->name('training.process');
        
        Route::delete('/testing/clear', [TestingController::class, 'clear'])->name('testing.clear');
    });

    Route::get('/testing', [TestingController::class, 'index'])->name('testing');
    Route::post('/testing/predict', [TestingController::class, 'predict'])->name('testing.predict');

    Route::get('/evaluation', [EvaluationController::class, 'index'])->name('evaluation');
    
    Route::get('/real-time-prediction', [RealTimePredictionController::class, 'index'])->name('real-time-prediction');
    Route::post('/real-time-prediction/predict', [RealTimePredictionController::class, 'predict'])->name('real-time-prediction.predict');
});