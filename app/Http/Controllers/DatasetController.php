<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\PreprocessingResult;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    /**
     * Display a listing of all uploaded datasets
     */
    public function index()
    {
        // Fetch all datasets sorted by their ID in ascending order
        $datasets = Dataset::orderBy('id', 'asc')->get();

        return view('upload-dataset.upload-dataset', compact('datasets'));
    }

    /**
     * Handle the upload and import of a new dataset (CSV file)
     */
    public function upload(Request $request)
    {
        // Validate that the uploaded file exists and is in csv or txt format
        $request->validate([
            'dataset_file' => 'required|mimes:csv,txt'
        ]);

        /*
         * DELETE OLD DATA BEFORE IMPORT
         * It is important to delete preprocessing results first, 
         * and then the raw dataset to maintain referential integrity if applicable.
         */
        PreprocessingResult::query()->delete();
        Dataset::query()->delete();

        $file = $request->file('dataset_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Skip the header row of the CSV file
        $header = fgetcsv($handle);

        // Read and parse the CSV file row by row
        $batch = [];
        $now = now();
        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            $batch[] = [
                'conversation_id_str'      => $row[0] ?? null,
                'created_at'               => $row[1] ?? $now,
                'updated_at'               => $now,
                'favorite_count'           => $row[2] ?? null,
                'full_text'                => $row[3] ?? null,
                'id_str'                   => $row[4] ?? null,
                'image_url'                => $row[5] ?? null,
                'in_reply_to_screen_name'  => $row[6] ?? null,
                'lang'                     => $row[7] ?? null,
                'location'                 => $row[8] ?? null,
                'quote_count'              => $row[9] ?? null,
                'reply_count'              => $row[10] ?? null,
                'retweet_count'            => $row[11] ?? null,
                'tweet_url'                => $row[12] ?? null,
                'user_id_str'              => $row[13] ?? null,
                'username'                 => $row[14] ?? null,
                'label'                    => $row[15] ?? null,
            ];

            // Batch insert every 500 rows to prevent Vercel execution timeout (10s limit)
            if (count($batch) >= 500) {
                Dataset::insert($batch);
                $batch = [];
            }
        }

        // Insert remaining rows
        if (count($batch) > 0) {
            Dataset::insert($batch);
        }

        fclose($handle);

        // Return a success JSON response after uploading is complete
        return response()->json([
            'message' => 'Dataset berhasil diupload'
        ]);
    }

    /**
     * Clear all datasets and preprocessing results
     */
    public function clear()
    {
        // Delete all preprocessing results to clean up dependent data
        PreprocessingResult::query()->delete();
        
        // Delete the entire dataset
        Dataset::query()->delete();

        return redirect('/upload-dataset')
            ->with('success', 'Semua data berhasil dihapus');
    }
}