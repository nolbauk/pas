@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Upload Dataset</h1>

@if(session('success'))
<div class="mb-4 p-4 rounded-xl bg-green-500/20 border border-green-400">
    {{ session('success') }}
</div>
@endif

@if(Auth::user()->role === 'admin')
<div class="glass rounded-2xl p-6 mb-6">
    <form id="uploadForm" action="{{ route('dataset.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="glass rounded-2xl p-6 mb-6">

            <!-- Choose File UI tetap sama -->
            <label for="datasetFile"
                class="cursor-pointer relative flex items-center gap-4 w-full px-6 py-4 rounded-2xl
                    bg-white/10 backdrop-blur-md
                    border border-cyan-400/30
                    shadow-[0_0_20px_rgba(34,211,238,0.15)]
                    hover:border-cyan-300
                    hover:shadow-[0_0_30px_rgba(34,211,238,0.35)]
                    hover:scale-[1.005]
                    transition-all duration-300
                    overflow-hidden">

                <!-- futuristic glow overlay -->
                <div class="absolute inset-0 opacity-20 shine-effect
                            bg-gradient-to-r from-transparent via-cyan-300/40 to-transparent">
                </div>

                <!-- icon -->
                <div class="relative z-10 w-10 h-10 rounded-xl
                            bg-cyan-400/20
                            border border-cyan-300/30
                            flex items-center justify-center
                            shadow-[0_0_15px_rgba(34,211,238,0.25)]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-cyan-300"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>

                <!-- text area -->
                <div class="relative z-10 flex flex-col flex-1">
                    <span id="fileLabel" class="font-semibold tracking-wide text-cyan-100">
                        Choose Dataset File
                    </span>
                    <span class="text-xs text-gray-300">
                        CSV format for sentiment analysis
                    </span>
                </div>

                <!-- right indicator -->
                <div class="relative z-10 text-cyan-300 text-sm font-medium">
                    Browse
                </div>
            </label>

            <input
                type="file"
                name="dataset_file"
                id="datasetFile"
                class="hidden"
                accept=".csv"
                onchange="updateFileName(this, 'fileLabel')"
            >
        </div>

        <!-- Button + Progress -->
        <div class="flex items-center gap-4 mt-4 w-full">
            <button
                type="button"
                onclick="uploadDataset()"
                id="uploadBtn"
                class="futuristic-btn-blue whitespace-nowrap">
                Upload Dataset
            </button>

            <!-- Futuristic Progress Bar -->
            <div class="flex-1 hidden" id="progressContainer">
                <div class="futuristic-progress-track">
                    <div id="progressBar" class="futuristic-progress-bar flex items-center justify-center">
                        <span id="progressText">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@else
<div class="glass rounded-2xl p-6 mb-6 flex items-center gap-4 bg-cyan-500/5 border border-cyan-500/20">
    <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <div>
        <p class="text-cyan-100 font-bold">Mode View-Only</p>
        <p class="text-xs text-gray-400 font-medium">Hanya Administrator yang dapat mengunggah atau menghapus dataset.</p>
    </div>
</div>
@endif

<!-- Table Header -->
<div class="glass rounded-t-2xl p-4 border-b border-cyan-500/20 flex items-center gap-3 bg-cyan-500/5">
    <div class="w-2 h-2 rounded-full bg-cyan-400 shadow-lg shadow-cyan-400/50 animate-pulse"></div>
    <h2 class="text-lg font-bold text-cyan-400 uppercase tracking-tight">Dataset Preview</h2>
    <span class="ml-auto text-xs font-black text-cyan-300/80 bg-cyan-500/10 px-3 py-1 rounded-full border border-cyan-500/20">
        {{ $datasets->count() }} TOTAL ENTRIES
    </span>
</div>

<!-- Table Body -->
<div class="glass rounded-b-2xl overflow-hidden max-h-[50vh] overflow-y-auto futuristic-scroll border-t-0">
    <table class="w-full text-left">
        <thead class="bg-slate-900 sticky top-0 z-10">
            <tr class="border-b border-gray-700/50">
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] w-20">No</th>
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] w-56">Tanggal</th>
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Komentar</th>
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] text-center w-28">Link</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700/50">
            @forelse($datasets as $index => $data)
            <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                <td class="p-4">
                    <span class="text-sm font-black text-gray-400 group-hover:text-cyan-400 transition-colors">{{ $index + 1 }}</span>
                </td>
                <td class="p-4">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-gray-200 tracking-tight">{{ $data->created_at->format('d M Y') }}</span>
                        <span class="text-xs text-gray-400 font-bold tracking-wide mt-0.5">{{ $data->created_at->format('H:i') }}</span>
                    </div>
                </td>
                <td class="p-4">
                    <p class="text-base text-gray-100 leading-relaxed font-medium group-hover:text-white transition-colors">
                        {{ $data->full_text }}
                    </p>
                </td>
                <td class="p-4 text-center">
                    <a href="{{ $data->tweet_url }}" target="_blank"
                       class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 hover:bg-cyan-500/20 hover:border-cyan-400 hover:scale-110 transition-all duration-300"
                       title="View on Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="p-10 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center border border-dashed border-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium italic">Belum ada data dalam dataset ini.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(Auth::user()->role === 'admin' && $datasets->count() > 0)
<div class="mt-4 flex justify-end">
    <form id="clearDatasetForm" action="{{ route('dataset.clear') }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="button" 
                onclick="showConfirmModal('clearDatasetForm', 'Hapus Dataset', 'Yakin ingin menghapus semua data dalam dataset?')" 
                class="futuristic-btn-red">
            Hapus Semua Data
        </button>
    </form>
</div>
@endif

<script>
function updateFileName(input, labelId) {
    const label = document.getElementById(labelId);

    if (input.files.length > 0) {
        label.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <span>${input.files[0].name}</span>
        `;
    }
}

function uploadDataset() {
    const form = document.getElementById('uploadForm');
    const fileInput = document.getElementById('datasetFile');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const uploadBtn = document.getElementById('uploadBtn');

    if (!fileInput.files.length) {
        alert('Pilih file terlebih dahulu');
        return;
    }

    const formData = new FormData(form);

    const xhr = new XMLHttpRequest();

    progressContainer.classList.remove('hidden');
    uploadBtn.disabled = true;
    uploadBtn.innerText = 'Uploading...';

    xhr.open('POST', form.action, true);

    xhr.setRequestHeader(
        'X-CSRF-TOKEN',
        document.querySelector('input[name="_token"]').value
    );

    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);

            progressBar.style.width = percent + '%';
            progressText.innerText = percent + '%';
        }
    });

    xhr.onload = function() {
        if (xhr.status === 200) {
            progressBar.style.width = '100%';
            progressText.innerText = 'Upload selesai';

            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            alert('Upload gagal');
            uploadBtn.disabled = false;
            uploadBtn.innerText = 'Upload Dataset';
        }
    };

    xhr.onerror = function() {
        alert('Terjadi kesalahan saat upload');
        uploadBtn.disabled = false;
        uploadBtn.innerText = 'Upload Dataset';
    };

    xhr.send(formData);
}
</script>
@endsection