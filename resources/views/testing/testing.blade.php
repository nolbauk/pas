@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Testing Dataset</h1>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-500/50 text-green-300">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/50 text-red-300">
    {{ session('error') }}
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/50 text-red-300">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(Auth::user()->role === 'admin')
<!-- Upload Section -->
<div class="glass rounded-2xl p-6 mb-8">
    <form id="testingForm" action="{{ route('testing.predict') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="glass rounded-2xl p-6 mb-6">
            <!-- Premium Choose File UI -->
            <label for="testingFile"
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
                    <span id="testingLabel" class="font-semibold tracking-wide text-cyan-100">
                        Choose Testing File (CSV)
                    </span>
                    <span class="text-xs text-gray-300">
                        Upload dataset for model performance verification
                    </span>
                </div>

                <!-- right indicator -->
                <div class="relative z-10 text-cyan-300 text-sm font-medium">
                    Browse
                </div>
            </label>

            <input type="file"
                name="testingFile"
                id="testingFile"
                class="hidden"
                accept=".csv,.txt"
                onchange="updateFileName(this, 'testingLabel')" required>
        </div>

        <!-- Button + Progress Bar -->
        <div class="flex items-center gap-4 mt-4 w-full">
            <button type="submit" id="submitBtn" class="futuristic-btn-process whitespace-nowrap">
                Mulai Testing Model
            </button>

            <!-- Progress Bar -->
            <div class="flex-1 hidden" id="processProgressContainer">
                <div class="futuristic-progress-track">
                    <div id="processProgressBar"
                        class="futuristic-progress-bar flex items-center justify-center" style="width: 0%;">
                        <span id="processProgressText">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@else
<div class="glass rounded-2xl p-6 mb-8 flex items-center gap-4 bg-cyan-500/5 border border-cyan-500/20">
    <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <div>
        <p class="text-cyan-100 font-bold">Hasil Testing Terakhir</p>
        <p class="text-xs text-gray-400 font-medium">Menampilkan data hasil pengujian yang telah dilakukan oleh Administrator.</p>
    </div>
</div>
@endif

@if(isset($metrics))
<!-- Metrics Section -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Accuracy -->
    <div class="metric-card accuracy glass rounded-2xl p-6 hover-glow animate-in">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-gray-300 text-xs font-bold uppercase tracking-tight mb-1">Accuracy</p>
                <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
                    {{ $metrics['accuracy'] }}%
                </p>
            </div>
            <div class="relative w-16 h-16">
                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 88 88">
                    <defs><linearGradient id="gradAccuracy" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#22d3ee"/><stop offset="1" stop-color="#3b82f6"/></linearGradient></defs>
                    <circle cx="44" cy="44" r="40" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="6"/>
                    <circle cx="44" cy="44" r="40" fill="none" stroke="url(#gradAccuracy)" stroke-width="6"
                        stroke-linecap="round" class="progress-ring-circle"
                        stroke-dasharray="251.2"
                        stroke-dashoffset="{{ 251.2 - (251.2 * $metrics['accuracy'] / 100) }}"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Precision -->
    <div class="metric-card precision glass rounded-2xl p-6 hover-glow animate-in">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-gray-300 text-xs font-bold uppercase tracking-tight mb-1">Precision</p>
                <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500">
                    {{ $metrics['precision'] }}%
                </p>
            </div>
            <div class="relative w-16 h-16">
                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 88 88">
                    <defs><linearGradient id="gradPrecision" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#a855f7"/><stop offset="1" stop-color="#ec4899"/></linearGradient></defs>
                    <circle cx="44" cy="44" r="40" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="6"/>
                    <circle cx="44" cy="44" r="40" fill="none" stroke="url(#gradPrecision)" stroke-width="6"
                        stroke-linecap="round" class="progress-ring-circle"
                        stroke-dasharray="251.2"
                        stroke-dashoffset="{{ 251.2 - (251.2 * $metrics['precision'] / 100) }}"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Recall -->
    <div class="metric-card recall glass rounded-2xl p-6 hover-glow animate-in">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-gray-300 text-xs font-bold uppercase tracking-tight mb-1">Recall</p>
                <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-500">
                    {{ $metrics['recall'] }}%
                </p>
            </div>
            <div class="relative w-16 h-16">
                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 88 88">
                    <defs><linearGradient id="gradRecall" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#10b981"/><stop offset="1" stop-color="#14b8a6"/></linearGradient></defs>
                    <circle cx="44" cy="44" r="40" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="6"/>
                    <circle cx="44" cy="44" r="40" fill="none" stroke="url(#gradRecall)" stroke-width="6"
                        stroke-linecap="round" class="progress-ring-circle"
                        stroke-dasharray="251.2"
                        stroke-dashoffset="{{ 251.2 - (251.2 * $metrics['recall'] / 100) }}"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- F1-Score -->
    <div class="metric-card f1 glass rounded-2xl p-6 hover-glow animate-in">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-gray-300 text-xs font-bold uppercase tracking-tight mb-1">F1-Score</p>
                <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-500">
                    {{ $metrics['f1'] }}%
                </p>
            </div>
            <div class="relative w-16 h-16">
                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 88 88">
                    <defs><linearGradient id="gradF1" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#f97316"/><stop offset="1" stop-color="#eab308"/></linearGradient></defs>
                    <circle cx="44" cy="44" r="40" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="6"/>
                    <circle cx="44" cy="44" r="40" fill="none" stroke="url(#gradF1)" stroke-width="6"
                        stroke-linecap="round" class="progress-ring-circle"
                        stroke-dasharray="251.2"
                        stroke-dashoffset="{{ 251.2 - (251.2 * $metrics['f1'] / 100) }}"/>
                </svg>
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($results) && count($results) > 0)
<!-- Result Table Header -->
<div class="glass rounded-t-2xl p-4 border-b border-cyan-500/20 flex items-center gap-3 bg-cyan-500/5 mt-8">
    <div class="w-2 h-2 rounded-full bg-cyan-400 shadow-lg shadow-cyan-400/50 animate-pulse"></div>
    <h2 class="text-lg font-bold text-cyan-400 uppercase tracking-tight">Hasil Prediksi Sentimen</h2>
    <span class="ml-auto text-xs font-black text-cyan-300/80 bg-cyan-500/10 px-3 py-1 rounded-full border border-cyan-500/20">
        {{ count($results) }} TOTAL ENTRIES
    </span>
</div>

<!-- Table Body -->
<div class="glass rounded-b-2xl overflow-hidden max-h-[60vh] overflow-y-auto futuristic-scroll border-t-0">
    <table class="w-full text-left relative">
        <thead class="bg-slate-900 sticky top-0 z-10">
            <tr class="border-b border-gray-700/50">
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] w-20">No</th>
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] w-[35%]">Komentar Asli</th>
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] w-[35%]">Hasil Preprocessing</th>
                @if(isset($metrics))
                    <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] w-32">Aktual</th>
                @endif
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Prediksi Model</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-700/50">
            @foreach($results as $res)
            <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                <td class="p-4 align-top">
                    <span class="text-sm font-black text-gray-400 group-hover:text-cyan-400 transition-colors">{{ $res['no'] }}</span>
                </td>
                <td class="p-4 align-top">
                    <p class="text-base text-gray-300 leading-relaxed font-medium group-hover:text-gray-100 transition-colors">
                        {{ $res['komentar'] }}
                    </p>
                </td>
                <td class="p-4 align-top">
                    <div class="bg-slate-900/50 p-3 rounded-xl border border-white/5 group-hover:border-cyan-500/20 transition-all">
                        <p class="text-sm text-cyan-100 font-mono leading-relaxed">
                            {{ str_replace(' | ', ' ', $res['processed']) }}
                        </p>
                    </div>
                </td>
                @if(isset($metrics))
                    <td class="p-4 align-top">
                        <div class="pt-1">
                            @if($res['actual'] === 1)
                                <span class="px-2 py-1 rounded-lg text-[11px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Positif</span>
                            @else
                                <span class="px-2 py-1 rounded-lg text-[11px] font-black uppercase tracking-wider bg-red-500/10 text-red-400 border border-red-500/20">Negatif</span>
                            @endif
                        </div>
                    </td>
                @endif
                <td class="p-4 align-top">
                    <div class="flex flex-col gap-2 pt-1">
                        <div>
                            @if($res['predicted'] === 1)
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 text-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.1)]">Positif</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-gradient-to-r from-red-500/20 to-rose-500/20 border border-red-500/30 text-red-400 shadow-[0_0_10px_rgba(239,68,68,0.1)]">Negatif</span>
                            @endif
                        </div>
                        
                        @if(isset($metrics))
                            @if($res['predicted'] !== $res['actual'])
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-400/80 bg-red-400/5 px-2 py-0.5 rounded-full w-fit border border-red-400/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Meleset
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-400/80 bg-emerald-400/5 px-2 py-0.5 rounded-full w-fit border border-emerald-400/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Tepat
                                </span>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(Auth::user()->role === 'admin')
<div class="mt-4 flex justify-end">
    <form id="clearTestingForm" action="{{ route('testing.clear') }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="button" 
                onclick="showConfirmModal('clearTestingForm', 'Hapus Hasil Testing', 'Yakin ingin menghapus semua hasil testing?')" 
                class="futuristic-btn-red">
            Hapus Semua Data
        </button>
    </form>
</div>
@endif
@endif

<script>
    function updateFileName(input, labelId) {
        const label = document.getElementById(labelId);
        if (input.files && input.files.length > 0) {
            label.textContent = input.files[0].name;
            label.classList.add('text-cyan-400');
        } else {
            label.textContent = 'Choose Testing File (CSV)';
            label.classList.remove('text-cyan-400');
        }
    }

    if (document.getElementById('testingForm')) {
        document.getElementById('testingForm').addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const form = this;
            const progressContainer = document.getElementById('processProgressContainer');
            const progressBar = document.getElementById('processProgressBar');
            const progressText = document.getElementById('processProgressText');
            const processBtn = document.getElementById('submitBtn');

            progressContainer.classList.remove('hidden');
            processBtn.disabled = true;
            processBtn.innerText = 'Processing...';

            let percent = 0;
            const interval = setInterval(() => {
                percent += 10;
                progressBar.style.width = percent + '%';
                progressText.innerText = percent + '%';

                if (percent >= 100) {
                    clearInterval(interval);
                    progressText.innerText = 'Processing selesai';
                    setTimeout(() => {
                        form.submit();
                    }, 1000);
                }
            }, 150);
        });
    }
</script>
@endsection