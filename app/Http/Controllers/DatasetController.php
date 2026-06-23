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
        try {
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
                // Parse date safely to avoid Y2K38 MySQL TIMESTAMP limit (max 2038-01-19)
                $createdAt = $now;
                if (!empty($row[1])) {
                    $parsed = strtotime($row[1]);
                    if ($parsed !== false && $parsed > 0 && $parsed <= 2147483647) {
                        $createdAt = date('Y-m-d H:i:s', $parsed);
                    }
                }

                $batch[] = [
                    'conversation_id_str'      => isset($row[0]) && $row[0] !== '' ? $row[0] : null,
                    'created_at'               => $createdAt,
                    'updated_at'               => $now,
                    'favorite_count'           => isset($row[2]) && $row[2] !== '' ? $row[2] : null,
                    'full_text'                => isset($row[3]) && $row[3] !== '' ? $row[3] : null,
                    'id_str'                   => isset($row[4]) && $row[4] !== '' ? $row[4] : null,
                    'image_url'                => isset($row[5]) && $row[5] !== '' ? $row[5] : null,
                    'in_reply_to_screen_name'  => isset($row[6]) && $row[6] !== '' ? $row[6] : null,
                    'lang'                     => isset($row[7]) && $row[7] !== '' ? $row[7] : null,
                    'location'                 => isset($row[8]) && $row[8] !== '' ? $row[8] : null,
                    'quote_count'              => isset($row[9]) && $row[9] !== '' ? $row[9] : null,
                    'reply_count'              => isset($row[10]) && $row[10] !== '' ? $row[10] : null,
                    'retweet_count'            => isset($row[11]) && $row[11] !== '' ? $row[11] : null,
                    'tweet_url'                => isset($row[12]) && $row[12] !== '' ? $row[12] : null,
                    'user_id_str'              => isset($row[13]) && $row[13] !== '' ? $row[13] : null,
                    'username'                 => isset($row[14]) && $row[14] !== '' ? $row[14] : null,
                    'label'                    => isset($row[15]) && $row[15] !== '' ? $row[15] : null,
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage() . ' di baris ' . $e->getLine()
            ], 500);
        }
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