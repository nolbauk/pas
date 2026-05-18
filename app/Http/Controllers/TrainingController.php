<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dataset;
use App\Models\PreprocessingResult;
use Illuminate\Support\Facades\Storage;

use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\ModelManager;

class TrainingController extends Controller
{
    public function index()
    {
        return view('training.training');
    }

    public function process()
    {
        // Training ML bisa membutuhkan banyak memory dan waktu, jadi kita lepas limitnya sementara
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0');

        // 1. Ambil data yang sudah dipreprocessing dan memiliki label
        $data = PreprocessingResult::join('datasets', 'preprocessing_results.dataset_id', '=', 'datasets.id')
            ->select('preprocessing_results.processed_text', 'datasets.label')
            ->whereNotNull('datasets.label')
            ->where('datasets.label', '!=', '')
            ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Gagal: Tidak ada data dataset yang memiliki label. Pastikan dataset sudah diberi label (positif/negatif, dll).');
        }

        $samples = [];
        $labels = [];

        foreach ($data as $row) {
            if (!empty($row->processed_text)) {
                // Di PreprocessingController, kata dipisah dengan ' | '. Kita ubah jadi spasi agar WhitespaceTokenizer bisa membaca.
                $samples[] = str_replace(' | ', ' ', $row->processed_text);
                $labels[] = $row->label;
            }
        }

        if (count($samples) < 2) {
            return back()->with('error', 'Gagal: Data terlalu sedikit untuk di-training. Minimal butuh 2 data.');
        }

        try {
            // 2. TF-IDF: Token Count Vectorizer (menghitung kemunculan kata)
            $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
            $vectorizer->fit($samples);
            $vectorizer->transform($samples); // $samples sekarang menjadi array angka (jumlah kata)

            // 3. TF-IDF: Transformer (mengubah ke bobot TF-IDF)
            $tfIdfTransformer = new TfIdfTransformer($samples);
            $tfIdfTransformer->fit($samples);
            $tfIdfTransformer->transform($samples); // $samples sekarang menjadi array bobot desimal TF-IDF

            // 4. Training Model SVM (Support Vector Classification)
            $classifier = new SVC(Kernel::LINEAR, $cost = 1000);
            $classifier->train($samples, $labels);

            // 5. Simpan Model dan Extractor untuk digunakan saat Testing
            $modelManager = new ModelManager();
            
            $path = storage_path('app/models/');
            
            // Buat folder storage/app/models jika belum ada
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $modelManager->saveToFile($classifier, $path . 'svm_model.phpml');
            
            // ModelManager hanya menerima Estimator, jadi untuk Vectorizer dan Transformer kita gunakan serialize() bawaan PHP
            file_put_contents($path . 'vectorizer.phpml', serialize($vectorizer));
            file_put_contents($path . 'tfidf.phpml', serialize($tfIdfTransformer));

            return back()->with('success', 'Training SVM berhasil dilakukan pada ' . count($labels) . ' data komentar.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat training: ' . $e->getMessage());
        }
    }
}
