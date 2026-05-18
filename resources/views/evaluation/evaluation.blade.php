@extends('layouts.app')

@section('content')
<style>
    /* Confusion matrix cell hover */
    .cm-cell {
        transition: all 0.3s ease;
    }

    .cm-cell:hover {
        transform: scale(1.05);
        z-index: 10;
    }
</style>

<div class="relative">

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400">
            Evaluation Model
        </h1>
        <p class="text-gray-300 mt-2 font-medium">
            Hasil evaluasi performa model SVM berdasarkan data testing yang telah diproses.
        </p>
    </div>

    @if(isset($metrics))

    <!-- Metrics Cards -->
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
            <p class="text-xs text-gray-300">Persentase prediksi yang benar dari seluruh data</p>
        </div>

        <!-- Precision -->
        <div class="metric-card precision glass rounded-2xl p-6 hover-glow animate-in">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-medium mb-1">Precision</p>
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
            <p class="text-xs text-gray-300">Ketepatan prediksi positif terhadap semua prediksi positif</p>
        </div>

        <!-- Recall -->
        <div class="metric-card recall glass rounded-2xl p-6 hover-glow animate-in">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-medium mb-1">Recall</p>
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
            <p class="text-xs text-gray-300">Kemampuan model mendeteksi semua data positif yang sebenarnya</p>
        </div>

        <!-- F1-Score -->
        <div class="metric-card f1 glass rounded-2xl p-6 hover-glow animate-in">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-medium mb-1">F1-Score</p>
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
            <p class="text-xs text-gray-300">Rata-rata harmonis antara Precision dan Recall</p>
        </div>
    </div>

    <!-- Confusion Matrix Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Confusion Matrix Visual -->
        <div class="glass rounded-2xl p-6 border border-white/5 relative overflow-hidden flex flex-col">
            <!-- Decorative Glow -->
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-cyan-500/10 blur-[80px] rounded-full"></div>

            <div class="flex items-center gap-3 mb-8 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-500/20 to-blue-500/20 border border-cyan-500/30 flex items-center justify-center shadow-lg shadow-cyan-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white uppercase tracking-tight">Confusion Matrix</h2>
                    <p class="text-sm text-gray-400 font-medium">Perbandingan label aktual vs prediksi model</p>
                </div>
            </div>

            @php
                $cm = $metrics['confusion_matrix'];
                $total = $cm['tp'] + $cm['tn'] + $cm['fp'] + $cm['fn'];
            @endphp

            <!-- Matrix Grid -->
            <div class="w-full relative z-10 flex-1 flex flex-col justify-center pb-4">
                <!-- Column Header: Prediksi -->
                <div class="flex justify-center mb-6">
                    <div class="flex items-center gap-4">
                        <div class="h-[1px] w-12 bg-gradient-to-r from-transparent to-gray-600"></div>
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">Prediksi Model</span>
                        <div class="h-[1px] w-12 bg-gradient-to-l from-transparent to-gray-600"></div>
                    </div>
                </div>

                <div class="flex gap-6 items-stretch">
                    <!-- Row Header: Aktual (Vertical) -->
                    <div class="flex flex-col justify-center items-center">
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] -rotate-180 [writing-mode:vertical-lr] py-4">
                            Aktual Data
                        </span>
                    </div>

                    <div class="flex-1">
                        <!-- Class Headers Top -->
                        <div class="grid grid-cols-2 gap-4 mb-4 ml-20">
                            <div class="text-center">
                                <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-xs font-black text-emerald-400 uppercase tracking-widest">Positif</span>
                            </div>
                            <div class="text-center">
                                <span class="px-4 py-1.5 rounded-full bg-red-500/10 border border-red-500/20 text-xs font-black text-red-400 uppercase tracking-widest">Negatif</span>
                            </div>
                        </div>

                        <!-- Matrix Content -->
                        <div class="space-y-4">
                            <!-- Actual Positive Row -->
                            <div class="flex items-center gap-4">
                                <div class="w-16 text-right">
                                    <span class="text-xs font-black text-emerald-400 uppercase tracking-widest">Positif</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 flex-1">
                                    <!-- TP -->
                                    <div class="cm-cell relative group rounded-2xl overflow-hidden bg-gradient-to-br from-emerald-500/20 to-emerald-900/20 border border-emerald-500/30 p-8 text-center transition-all hover:border-emerald-400 hover:scale-[1.02]">
                                        <div class="absolute top-0 right-0 p-2 opacity-20 group-hover:opacity-40 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-5xl font-black text-emerald-400 tracking-tighter drop-shadow-lg">{{ $cm['tp'] }}</p>
                                        <p class="text-[10px] font-black text-emerald-400/60 uppercase tracking-widest mt-2">True Positive</p>
                                    </div>
                                    <!-- FN -->
                                    <div class="cm-cell relative group rounded-2xl overflow-hidden bg-gradient-to-br from-red-500/10 to-red-900/10 border border-red-500/20 p-8 text-center transition-all hover:border-red-400 hover:scale-[1.02]">
                                        <div class="absolute top-0 right-0 p-2 opacity-20 group-hover:opacity-40 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-5xl font-black text-red-500 tracking-tighter">{{ $cm['fn'] }}</p>
                                        <p class="text-[10px] font-black text-red-500/60 uppercase tracking-widest mt-2">False Negative</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actual Negative Row -->
                            <div class="flex items-center gap-4">
                                <div class="w-16 text-right">
                                    <span class="text-xs font-black text-red-400 uppercase tracking-widest">Negatif</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 flex-1">
                                    <!-- FP -->
                                    <div class="cm-cell relative group rounded-2xl overflow-hidden bg-gradient-to-br from-red-500/10 to-red-900/10 border border-red-500/20 p-8 text-center transition-all hover:border-red-400 hover:scale-[1.02]">
                                        <div class="absolute top-0 right-0 p-2 opacity-20 group-hover:opacity-40 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <p class="text-5xl font-black text-red-500 tracking-tighter">{{ $cm['fp'] }}</p>
                                        <p class="text-[10px] font-black text-red-500/60 uppercase tracking-widest mt-2">False Positive</p>
                                    </div>
                                    <!-- TN -->
                                    <div class="cm-cell relative group rounded-2xl overflow-hidden bg-gradient-to-br from-emerald-500/20 to-emerald-900/20 border border-emerald-500/30 p-8 text-center transition-all hover:border-emerald-400 hover:scale-[1.02]">
                                        <div class="absolute top-0 right-0 p-2 opacity-20 group-hover:opacity-40 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <p class="text-5xl font-black text-emerald-400 tracking-tighter drop-shadow-lg">{{ $cm['tn'] }}</p>
                                        <p class="text-[10px] font-black text-emerald-400/60 uppercase tracking-widest mt-2">True Negative</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Detail Table -->
        <div class="glass rounded-2xl p-6 border border-white/5 relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-500/10 blur-[80px] rounded-full"></div>

            <div class="flex items-center gap-3 mb-8 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/30 flex items-center justify-center shadow-lg shadow-purple-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white uppercase tracking-tight">Detail Evaluasi</h2>
                    <p class="text-sm text-gray-400 font-medium">Ringkasan lengkap metrik performa model</p>
                </div>
            </div>
            
            <div class="overflow-hidden rounded-2xl border border-white/10 bg-slate-900/40 relative z-10">
                <table class="w-full text-left">
                    <thead class="bg-slate-900/80 border-b border-white/10">
                        <tr>
                            <th class="p-5 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Metrik</th>
                            <th class="p-5 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em]">Rumus Komputasi</th>
                            <th class="p-5 text-[12px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-cyan-400 shadow-glow-cyan"></div>
                                    <span class="text-base font-black text-cyan-400 uppercase tracking-tighter">Accuracy</span>
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="text-xs text-gray-300 font-mono font-bold bg-slate-950 px-3 py-1.5 rounded-lg border border-white/5">(TP + TN) / Total Data</span>
                            </td>
                            <td class="p-5 text-right">
                                <span class="text-xl font-black text-white tracking-tight">{{ $metrics['accuracy'] }}%</span>
                            </td>
                        </tr>
                        <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-purple-400 shadow-glow-purple"></div>
                                    <span class="text-base font-black text-purple-400 uppercase tracking-tighter">Precision</span>
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="text-xs text-gray-300 font-mono font-bold bg-slate-950 px-3 py-1.5 rounded-lg border border-white/5">TP / (TP + FP)</span>
                            </td>
                            <td class="p-5 text-right">
                                <span class="text-xl font-black text-white tracking-tight">{{ $metrics['precision'] }}%</span>
                            </td>
                        </tr>
                        <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-glow-emerald"></div>
                                    <span class="text-base font-black text-emerald-400 uppercase tracking-tighter">Recall</span>
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="text-xs text-gray-300 font-mono font-bold bg-slate-950 px-3 py-1.5 rounded-lg border border-white/5">TP / (TP + FN)</span>
                            </td>
                            <td class="p-5 text-right">
                                <span class="text-xl font-black text-white tracking-tight">{{ $metrics['recall'] }}%</span>
                            </td>
                        </tr>
                        <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-orange-400 shadow-glow-orange"></div>
                                    <span class="text-base font-black text-orange-400 uppercase tracking-tighter">F1-Score</span>
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="text-xs text-gray-300 font-mono font-bold bg-slate-950 px-3 py-1.5 rounded-lg border border-white/5">2 × (P × R) / (P + R)</span>
                            </td>
                            <td class="p-5 text-right">
                                <span class="text-xl font-black text-white tracking-tight">{{ $metrics['f1'] }}%</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Confusion Matrix Numbers Summary -->
            <div class="mt-8 grid grid-cols-2 gap-4 relative z-10">
                <div class="bg-slate-900/60 rounded-2xl p-4 border border-emerald-500/20 group hover:border-emerald-500/40 transition-all">
                    <p class="text-[11px] text-emerald-400 font-black mb-1 uppercase tracking-[0.2em]">True Positive (TP)</p>
                    <p class="text-2xl font-black text-emerald-400">{{ $cm['tp'] }}</p>
                </div>
                <div class="bg-slate-900/60 rounded-2xl p-4 border border-emerald-500/20 group hover:border-emerald-500/40 transition-all">
                    <p class="text-[11px] text-emerald-400 font-black mb-1 uppercase tracking-[0.2em]">True Negative (TN)</p>
                    <p class="text-2xl font-black text-emerald-400">{{ $cm['tn'] }}</p>
                </div>
                <div class="bg-slate-900/60 rounded-2xl p-4 border border-red-500/20 group hover:border-red-500/40 transition-all">
                    <p class="text-[11px] text-red-400 font-black mb-1 uppercase tracking-[0.2em]">False Positive (FP)</p>
                    <p class="text-2xl font-black text-red-500">{{ $cm['fp'] }}</p>
                </div>
                <div class="bg-slate-900/60 rounded-2xl p-4 border border-red-500/20 group hover:border-red-500/40 transition-all">
                    <p class="text-[11px] text-red-400 font-black mb-1 uppercase tracking-[0.2em]">False Negative (FN)</p>
                    <p class="text-2xl font-black text-red-500">{{ $cm['fn'] }}</p>
                </div>
            </div>

            <div class="mt-4 bg-cyan-500/5 rounded-2xl p-5 border border-cyan-500/20 flex items-center justify-between group hover:bg-cyan-500/10 transition-all relative z-10">
                <div class="flex flex-col">
                    <p class="text-[11px] text-cyan-400 font-black uppercase tracking-[0.2em]">Data Volume</p>
                    <p class="text-sm font-bold text-gray-300">Total Testing Records</p>
                </div>
                <p class="text-3xl font-black text-cyan-400 tracking-tighter">{{ number_format($total) }}</p>
            </div>
        </div>
    </div>

    @else

    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center text-center py-20 opacity-60">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-gray-600 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-400 text-lg font-semibold mb-2">Belum ada hasil evaluasi</p>
        <p class="text-gray-500 text-sm max-w-md">
            Silakan lakukan proses <strong>Testing</strong> terlebih dahulu agar metrik evaluasi model dapat ditampilkan di halaman ini.
        </p>
        <a href="/testing" class="mt-6 futuristic-btn-process px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
            Ke Halaman Testing
        </a>
    </div>

    @endif
</div>
@endsection
