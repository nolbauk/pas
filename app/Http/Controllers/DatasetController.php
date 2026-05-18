<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\PreprocessingResult;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = Dataset::orderBy('id', 'asc')->get();

        return view('upload-dataset.upload-dataset', compact('datasets'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'dataset_file' => 'required|mimes:csv,txt'
        ]);

        /*
        HAPUS DATA LAMA
        urutan penting:
        preprocessing dulu → dataset
        */
        PreprocessingResult::query()->delete();
        Dataset::query()->delete();

        $file = $request->file('dataset_file');
        $handle = fopen($file->getRealPath(), 'r');

        // skip header csv
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            Dataset::create([
                'conversation_id_str'      => $row[0] ?? null,
                'created_at'               => $row[1] ?? null,
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
            ]);
        }

        fclose($handle);

        return response()->json([
            'message' => 'Dataset berhasil diupload'
        ]);
    }

    public function clear()
    {
        // hapus preprocessing juga
        PreprocessingResult::query()->delete();
        Dataset::query()->delete();

        return redirect('/upload-dataset')
            ->with('success', 'Semua data berhasil dihapus');
    }
}