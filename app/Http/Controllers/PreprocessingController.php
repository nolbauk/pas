<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\PreprocessingResult;

class PreprocessingController extends Controller
{
    public function index()
    {
        $results = PreprocessingResult::orderBy('dataset_id', 'asc')->get();
        return view('preprocessing.preprocessing', compact('results'));
    }

    public function process()
    {
        PreprocessingResult::query()->delete();

        $datasets = Dataset::all();

        $preprocessor = new \App\Services\TextPreprocessor();

        foreach ($datasets as $data) {
            $original = $data->full_text;
            $processedText = $preprocessor->processText($original);

            PreprocessingResult::create([
                'dataset_id' => $data->id,
                'original_text' => $original,
                'processed_text' => $processedText
            ]);
        }

        return redirect('/preprocessing')
            ->with('success', 'Preprocessing berhasil dilakukan');
    }
}