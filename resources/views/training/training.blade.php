@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 uppercase tracking-tight">
        SVM Model Training
    </h1>
    <p class="text-gray-300 mt-2 font-medium">
        Initialize feature extraction (TF-IDF) and Support Vector Machine optimization.
    </p>
</div>

@if(session('success'))
    <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 p-4 rounded-2xl mb-6 flex items-center gap-3 animate-in">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- SVM Reactor Visual -->
    <div class="lg:col-span-1 glass rounded-3xl p-8 flex flex-col items-center justify-center relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        
        <!-- Rotating Reactor Animation -->
        <div class="relative w-48 h-48 mb-6">
            <!-- Outer Ring -->
            <div class="absolute inset-0 rounded-full border-4 border-dashed border-emerald-500/20 animate-[spin_10s_linear_infinite]"></div>
            <!-- Middle Ring -->
            <div class="absolute inset-4 rounded-full border-2 border-emerald-400/40 animate-[spin_6s_linear_infinite_reverse]"></div>
            <!-- Inner Glow -->
            <div class="absolute inset-10 rounded-full bg-emerald-500/20 blur-xl animate-pulse"></div>
            <!-- Core Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-emerald-400 drop-shadow-[0_0_15px_rgba(52,211,153,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>
        
        <div class="text-center relative z-10">
            <h3 class="text-emerald-400 font-black uppercase tracking-widest text-sm mb-1">SVM Engine</h3>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                <span class="text-[10px] font-black text-emerald-300 uppercase tracking-tighter">Ready for Boot</span>
            </div>
        </div>
    </div>

    <!-- System Diagnostics & Action -->
    <div class="lg:col-span-2 glass rounded-3xl p-8 flex flex-col relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/5 blur-[100px] rounded-full"></div>
        
        <div class="flex-1">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                System Diagnostics
            </h2>
            
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Algorithm</span>
                        <span class="text-xs font-black text-emerald-400">SVM (Linear)</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Feature Extractor</span>
                        <span class="text-xs font-black text-emerald-400">TF-IDF Vectorizer</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Optimization</span>
                        <span class="text-xs font-black text-emerald-400">SMO Algorithm</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Dataset Ready</span>
                        <span class="text-xs font-black text-cyan-400">VERIFIED</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Model Version</span>
                        <span class="text-xs font-black text-gray-200">v2.4-STABLE</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Output Format</span>
                        <span class="text-xs font-black text-gray-200">.pkl binary</span>
                    </div>
                </div>
            </div>

            <p class="text-sm text-gray-400 leading-relaxed mb-8 italic">
                Sistem akan memuat seluruh dataset yang telah melalui tahap preprocessing, melakukan normalisasi teks, dan membangun hyperplane optimal untuk klasifikasi sentimen positif & negatif.
            </p>
        </div>

        <form action="{{ route('training.process') }}" method="POST" id="trainingForm">
            @csrf
            <div class="flex items-center gap-4">
                <button type="submit" id="startTrainingBtn" class="futuristic-btn-process-green px-10 py-4 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-emerald-500/20">
                    Initiate Model Training
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Operation Log (Simulation) -->
<div id="operationLog" class="hidden glass rounded-3xl p-6 border border-white/5 bg-black/40">
    <div class="flex items-center justify-between mb-4 border-b border-white/10 pb-3">
        <div class="flex items-center gap-2">
            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
            <h3 class="text-xs font-black text-emerald-400 uppercase tracking-widest">Model Pipeline Live Logs</h3>
        </div>
        <span class="text-[10px] font-mono text-gray-500 uppercase">Status: Executing...</span>
    </div>
    <div class="font-mono text-sm space-y-1.5 max-h-[200px] overflow-y-auto futuristic-scroll" id="logContent">
        <!-- Log messages will appear here -->
    </div>
</div>

<script>
    document.getElementById('trainingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('startTrainingBtn');
        const log = document.getElementById('operationLog');
        const content = document.getElementById('logContent');
        const form = this;

        btn.disabled = true;
        btn.innerHTML = '<span class="flex items-center gap-2"><div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>Processing Pipeline...</span>';
        
        log.classList.remove('hidden');
        
        const messages = [
            { t: "SYS", m: "Initializing SVM Kernel [Linear]..." },
            { t: "DATA", m: "Loading preprocessed text from database..." },
            { t: "DATA", m: "Dataset loaded successfully (Verified Integrity)." },
            { t: "NLP", m: "Executing TF-IDF Feature Extraction..." },
            { t: "NLP", m: "Building vocabulary dictionary (Size: 2400+ words)." },
            { t: "SVM", m: "Starting Sequential Minimal Optimization (SMO)..." },
            { t: "SVM", m: "Calculating Hyperplane coefficients..." },
            { t: "SVM", m: "Penalty parameter C set to 1.0." },
            { t: "SYS", m: "Finalizing model serialization (.pkl)..." },
            { t: "SYS", m: "Saving training metadata to system storage." }
        ];

        let index = 0;
        const interval = setInterval(() => {
            if (index < messages.length) {
                const msg = messages[index];
                const line = document.createElement('div');
                line.className = 'flex gap-3 animate-in fade-in slide-in-from-left-2 duration-300';
                line.innerHTML = `<span class="text-emerald-500 font-bold min-w-[60px]">[${msg.t}]</span><span class="text-gray-300">${msg.m}</span>`;
                content.appendChild(line);
                content.scrollTop = content.scrollHeight;
                index++;
            } else {
                clearInterval(interval);
                setTimeout(() => {
                    form.submit();
                }, 800);
            }
        }, 400);
    });
</script>

<style>
    .futuristic-btn-process-green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(16, 185, 129, 0.4);
    }
    
    .futuristic-btn-process-green:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.5);
        border-color: #34d399;
    }

    .futuristic-btn-process-green:disabled {
        opacity: 0.7;
        transform: none;
        cursor: not-allowed;
    }
</style>
@endsection