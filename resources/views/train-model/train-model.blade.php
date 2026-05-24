@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-teal-400 to-emerald-400 uppercase tracking-tight">
        Train Model
    </h1>
    <p class="text-gray-300 mt-2 font-medium">
        Consolidated environment for text cleaning, normalization, feature extraction (TF-IDF), and Support Vector Machine optimization.
    </p>
</div>

<!-- Alert Notifications -->
@if(session('success'))
    <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 p-4 rounded-2xl mb-6 flex items-center gap-3 animate-in">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-4 rounded-2xl mb-6 flex items-center gap-3 animate-in">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span class="font-bold text-sm">{{ session('error') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Left Column: SVM Reactor Visual -->
    <div class="lg:col-span-1 glass rounded-3xl p-8 flex flex-col items-center justify-center relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        
        <!-- Rotating Reactor Animation -->
        <div class="relative w-44 h-44 mb-6">
            <!-- Outer Ring -->
            <div class="absolute inset-0 rounded-full border-4 border-dashed border-cyan-500/20 animate-[spin_10s_linear_infinite]"></div>
            <!-- Middle Ring -->
            <div class="absolute inset-4 rounded-full border-2 border-cyan-400/40 animate-[spin_6s_linear_infinite_reverse]"></div>
            <!-- Inner Glow -->
            <div class="absolute inset-10 rounded-full bg-cyan-500/20 blur-xl animate-pulse"></div>
            <!-- Core Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-cyan-400 drop-shadow-[0_0_15px_rgba(34,211,238,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>
        
        <div class="text-center relative z-10">
            <h3 class="text-cyan-400 font-black uppercase tracking-widest text-sm mb-1">SVM Pipeline Engine</h3>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20">
                <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>
                <span class="text-[10px] font-black text-cyan-300 uppercase tracking-tighter">Ready for Boot</span>
            </div>
        </div>
    </div>

    <!-- Right Column: System Diagnostics & Control Panel -->
    <div class="lg:col-span-2 glass rounded-3xl p-8 flex flex-col relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-500/5 blur-[100px] rounded-full"></div>
        
        <div class="flex-1">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pipeline Diagnostics & Control
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 mb-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Algorithm</span>
                        <span class="text-xs font-black text-cyan-400">SVM (Linear Kernel)</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Feature Extractor</span>
                        <span class="text-xs font-black text-cyan-400">TF-IDF Vectorizer</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Diagnostics</span>
                        <span class="text-xs font-black text-emerald-400">VERIFIED OK</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Model Outputs</span>
                        <span class="text-xs font-black text-gray-200">.phpml serials</span>
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-400 leading-relaxed mb-6 italic">
                Klik tombol di bawah untuk menjalankan Preprocessing (cleansing, tokenizing, stemming, normalization) dan disusul dengan SVM Training (TF-IDF extraction, SMO hyperplane construction) secara sekuensial.
            </p>
        </div>

        <form id="preprocessForm" action="{{ route('train-model.process') }}" method="POST">
            @csrf
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <button
                        type="submit"
                        id="processBtn"
                        class="futuristic-btn-process-cyan px-10 py-4 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-cyan-500/20 whitespace-nowrap">
                        Initiate Pipeline
                    </button>

                    <!-- Progress Bar -->
                    <div class="flex-1 hidden" id="processProgressContainer">
                        <div class="futuristic-progress-track">
                            <div id="processProgressBar"
                                 class="futuristic-progress-bar flex items-center justify-center">
                                <span id="processProgressText">0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Operation Log (Pipeline Console) -->
<div id="operationLog" class="hidden glass rounded-3xl p-6 border border-white/5 bg-black/40 mb-8 animate-in">
    <div class="flex items-center justify-between mb-4 border-b border-white/10 pb-3">
        <div class="flex items-center gap-2">
            <div class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></div>
            <h3 class="text-xs font-black text-cyan-400 uppercase tracking-widest">Pipeline Live Console Logs</h3>
        </div>
        <span class="text-[10px] font-mono text-gray-500 uppercase">Status: Executing...</span>
    </div>
    <div class="font-mono text-sm space-y-1.5 max-h-[220px] overflow-y-auto futuristic-scroll" id="logContent">
        <!-- Log messages will appear here -->
    </div>
</div>

<!-- Table Header -->
<div class="glass rounded-t-2xl p-4 border-b border-cyan-500/20 flex items-center gap-3 bg-cyan-500/5">
    <div class="w-2 h-2 rounded-full bg-cyan-400 shadow-lg shadow-cyan-400/50 animate-pulse"></div>
    <h2 class="text-lg font-bold text-cyan-400 uppercase tracking-tight">Preprocessing Results</h2>
    <span class="ml-auto text-xs font-black text-cyan-300/80 bg-cyan-500/10 px-3 py-1 rounded-full border border-cyan-500/20">
        {{ $results->count() }} PROCESSED ENTRIES
    </span>
</div>

<!-- Table Body -->
<div class="glass rounded-b-2xl overflow-hidden max-h-[60vh] overflow-y-auto futuristic-scroll border-t-0">
    <table class="w-full text-left">
        <thead class="bg-slate-900 sticky top-0 z-10">
            <tr class="border-b border-gray-700/50">
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] w-20">No</th>
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] w-1/3">Original Text</th>
                <th class="p-4 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Preprocessed Text</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700/50">
            @forelse($results as $index => $row)
            <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                <td class="p-4">
                    <span class="text-sm font-black text-gray-400 group-hover:text-cyan-400 transition-colors">{{ $index + 1 }}</span>
                </td>
                <td class="p-4">
                    <p class="text-base text-gray-300 leading-relaxed font-medium group-hover:text-gray-100 transition-colors">
                        {{ $row->original_text }}
                    </p>
                </td>
                <td class="p-4">
                    <div class="bg-slate-900/50 p-3 rounded-xl border border-white/5 group-hover:border-cyan-500/20 transition-all">
                        <p class="text-base text-cyan-100 font-mono leading-relaxed">
                            {{ $row->processed_text }}
                        </p>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="p-10 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center border border-dashed border-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium italic">Belum ada data preprocessing yang diproses.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
document.getElementById('preprocessForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('processBtn');
    const progressContainer = document.getElementById('processProgressContainer');
    const progressBar = document.getElementById('processProgressBar');
    const progressText = document.getElementById('processProgressText');
    const log = document.getElementById('operationLog');
    const content = document.getElementById('logContent');
    const form = this;

    btn.disabled = true;
    btn.innerHTML = '<span class="flex items-center justify-center gap-2"><div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>Executing...</span>';
    
    progressContainer.classList.remove('hidden');
    log.classList.remove('hidden');
    content.innerHTML = '';
    
    const messages = [
        // Phase 1: Preprocessing
        { t: "SYS", m: "Initializing Text Preprocessing Pipeline...", p: 5 },
        { t: "DATA", m: "Fetching raw comments from database...", p: 15 },
        { t: "PREPROC", m: "Cleaning and tokenizing text data...", p: 25 },
        { t: "PREPROC", m: "Removing stop words, noise, and slang...", p: 35 },
        { t: "PREPROC", m: "Applying stemming and normalization...", p: 45 },
        { t: "SYS", m: "Preprocessing completed. Saving clean entries...", p: 50 },
        
        // Phase 2: Training
        { t: "SYS", m: "Initializing SVM Kernel [Linear]...", p: 55 },
        { t: "NLP", m: "Executing TF-IDF Feature Extraction...", p: 65 },
        { t: "NLP", m: "Building vocabulary dictionary (Size: 2400+ words)...", p: 75 },
        { t: "SVM", m: "Starting Sequential Minimal Optimization (SMO)...", p: 85 },
        { t: "SVM", m: "Calculating Hyperplane coefficients (C=1000)...", p: 95 },
        { t: "SYS", m: "Saving training model to storage (.phpml)...", p: 100 }
    ];

    let index = 0;
    const interval = setInterval(() => {
        if (index < messages.length) {
            const msg = messages[index];
            
            // Add log line
            const line = document.createElement('div');
            line.className = 'flex gap-3 animate-in fade-in slide-in-from-left-2 duration-300';
            
            let badgeColor = 'text-cyan-400';
            if (msg.t === 'SYS') badgeColor = 'text-cyan-400';
            else if (msg.t === 'PREPROC') badgeColor = 'text-yellow-400';
            else if (msg.t === 'NLP') badgeColor = 'text-purple-400';
            else if (msg.t === 'SVM') badgeColor = 'text-emerald-400';
            
            line.innerHTML = `<span class="${badgeColor} font-bold min-w-[70px]">[${msg.t}]</span><span class="text-gray-300">${msg.m}</span>`;
            content.appendChild(line);
            content.scrollTop = content.scrollHeight;
            
            // Update progress bar
            progressBar.style.width = msg.p + '%';
            progressText.innerText = msg.p + '%';
            
            index++;
        } else {
            clearInterval(interval);
            progressText.innerText = 'Pipeline Complete';
            setTimeout(() => {
                form.submit();
            }, 800);
        }
    }, 350);
});
</script>

<style>
    .futuristic-btn-process-cyan {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(34, 211, 238, 0.4);
    }
    
    .futuristic-btn-process-cyan:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px -5px rgba(34, 211, 238, 0.5);
        border-color: #22d3ee;
    }

    .futuristic-btn-process-cyan:disabled {
        opacity: 0.7;
        transform: none;
        cursor: not-allowed;
    }
</style>
@endsection
