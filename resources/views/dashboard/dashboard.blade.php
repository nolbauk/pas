@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400">
            Kesimpulan Analisis
        </h1>
        <p class="text-gray-300 mt-2 font-medium leading-relaxed">
            Ringkasan akhir dari seluruh proses pengolahan data dan evaluasi model SVM.
        </p>
    </div>

    <!-- Conclusion Hero Card -->
    <div class="glass rounded-3xl p-8 mb-8 border border-cyan-500/20 relative overflow-hidden group hover:shadow-[0_0_50px_rgba(34,211,238,0.1)] transition-all duration-500">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/20 transition-all duration-500"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white uppercase tracking-tight">Status Analisis Selesai</h2>
                    <p class="text-cyan-400 font-bold text-sm">KESIMPULAN UTAMA:</p>
                </div>
            </div>

            @php
                $conclusionText = "Data seimbang.";
                $conclusionColor = "text-gray-300";
                if ($posPercent > $negPercent + 5) {
                    $conclusionText = "Masyarakat cenderung memberikan respon POSITIF terhadap topik ini.";
                    $conclusionColor = "text-emerald-400";
                } elseif ($negPercent > $posPercent + 5) {
                    $conclusionText = "Masyarakat cenderung memberikan respon NEGATIF terhadap topik ini.";
                    $conclusionColor = "text-red-400";
                }
            @endphp

            <p class="text-3xl font-bold {{ $conclusionColor }} leading-tight mb-6">
                "{{ $conclusionText }}"
            </p>

            <!-- Ratio Bar -->
            <div class="space-y-3">
                <div class="flex justify-between items-end">
                    <span class="text-emerald-400 font-bold text-sm uppercase tracking-widest">Positif ({{ $posPercent }}%)</span>
                    <span class="text-red-400 font-bold text-sm uppercase tracking-widest">Negatif ({{ $negPercent }}%)</span>
                </div>
                <div class="h-4 w-full bg-slate-900/50 rounded-full overflow-hidden border border-white/5 flex">
                    <div class="h-full bg-gradient-to-r from-emerald-600 to-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.3)] transition-all duration-1000" style="width: {{ $posPercent }}%"></div>
                    <div class="h-full bg-gradient-to-r from-red-600 to-red-400 shadow-[0_0_15px_rgba(239,68,68,0.3)] transition-all duration-1000" style="width: {{ $negPercent }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Data -->
        <div class="glass rounded-2xl p-6 hover-glow relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <p class="text-gray-300 font-bold mb-2 uppercase tracking-tight text-xs">Total Data Terproses</p>
            <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">{{ number_format($totalData) }}</h2>
            <p class="text-xs text-gray-300 mt-2 font-medium tracking-wide">Dataset siap dianalisis</p>
        </div>

        <!-- Positif -->
        <div class="glass rounded-2xl p-6 hover-glow relative overflow-hidden group border-b-2 border-emerald-500/30">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <p class="text-emerald-300 font-bold mb-2 uppercase tracking-tight text-xs">Total Sentimen Positif</p>
            <h2 class="text-4xl font-bold text-emerald-400">{{ number_format($positiveCount) }}</h2>
            <p class="text-xs text-gray-300 mt-2 font-medium tracking-wide">Komentar dengan label positif</p>
        </div>

        <!-- Negatif -->
        <div class="glass rounded-2xl p-6 hover-glow relative overflow-hidden group border-b-2 border-red-500/30">
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <p class="text-red-300 font-bold mb-2 uppercase tracking-tight text-xs">Total Sentimen Negatif</p>
            <h2 class="text-4xl font-bold text-red-400">{{ number_format($negativeCount) }}</h2>
            <p class="text-xs text-gray-300 mt-2 font-medium tracking-wide">Komentar dengan label negatif</p>
        </div>
    </div>

    <!-- Model Performance Summary -->
    <div class="glass rounded-2xl p-6 border border-purple-500/20">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-500/30 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-white">Performa Model SVM</h2>
        </div>

        @if($metrics)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Accuracy -->
                <div class="metric-card accuracy glass rounded-2xl p-6 hover-glow animate-in">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-gray-300 text-[10px] font-bold uppercase tracking-widest mb-1">Accuracy</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
                                {{ $metrics['accuracy'] }}%
                            </p>
                        </div>
                        <div class="relative w-12 h-12">
                            <svg class="w-12 h-12 -rotate-90" viewBox="0 0 88 88">
                                <defs><linearGradient id="gradAccuracyDash" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#22d3ee"/><stop offset="1" stop-color="#3b82f6"/></linearGradient></defs>
                                <circle cx="44" cy="44" r="40" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="6"/>
                                <circle cx="44" cy="44" r="40" fill="none" stroke="url(#gradAccuracyDash)" stroke-width="6"
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
                            <p class="text-gray-300 text-[10px] font-bold uppercase tracking-widest mb-1">Precision</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500">
                                {{ $metrics['precision'] }}%
                            </p>
                        </div>
                        <div class="relative w-12 h-12">
                            <svg class="w-12 h-12 -rotate-90" viewBox="0 0 88 88">
                                <defs><linearGradient id="gradPrecisionDash" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#a855f7"/><stop offset="1" stop-color="#ec4899"/></linearGradient></defs>
                                <circle cx="44" cy="44" r="40" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="6"/>
                                <circle cx="44" cy="44" r="40" fill="none" stroke="url(#gradPrecisionDash)" stroke-width="6"
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
                            <p class="text-gray-300 text-[10px] font-bold uppercase tracking-widest mb-1">Recall</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-500">
                                {{ $metrics['recall'] }}%
                            </p>
                        </div>
                        <div class="relative w-12 h-12">
                            <svg class="w-12 h-12 -rotate-90" viewBox="0 0 88 88">
                                <defs><linearGradient id="gradRecallDash" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#10b981"/><stop offset="1" stop-color="#14b8a6"/></linearGradient></defs>
                                <circle cx="44" cy="44" r="40" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="6"/>
                                <circle cx="44" cy="44" r="40" fill="none" stroke="url(#gradRecallDash)" stroke-width="6"
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
                            <p class="text-gray-300 text-[10px] font-bold uppercase tracking-widest mb-1">F1-Score</p>
                            <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-500">
                                {{ $metrics['f1'] }}%
                            </p>
                        </div>
                        <div class="relative w-12 h-12">
                            <svg class="w-12 h-12 -rotate-90" viewBox="0 0 88 88">
                                <defs><linearGradient id="gradF1Dash" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#f97316"/><stop offset="1" stop-color="#eab308"/></linearGradient></defs>
                                <circle cx="44" cy="44" r="40" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="6"/>
                                <circle cx="44" cy="44" r="40" fill="none" stroke="url(#gradF1Dash)" stroke-width="6"
                                    stroke-linecap="round" class="progress-ring-circle"
                                    stroke-dasharray="251.2"
                                    stroke-dashoffset="{{ 251.2 - (251.2 * $metrics['f1'] / 100) }}"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-300 mt-4 font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                * Berdasarkan hasil testing terakhir yang disimpan dalam sistem.
            </p>
        @else
            <div class="py-10 text-center bg-slate-900/30 rounded-xl border border-dashed border-gray-700">
                <p class="text-gray-500 text-sm">Belum ada data evaluasi model. Silakan lakukan proses <a href="/testing" class="text-cyan-400 hover:underline">Testing</a> terlebih dahulu.</p>
            </div>
        @endif
    </div>
</div>
@endsection