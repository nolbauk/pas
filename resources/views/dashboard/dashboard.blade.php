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

            <!-- Futuristic Sentiment Ratio Bar -->
            <div class="ratio-wrapper relative rounded-2xl p-5 border border-cyan-500/15 overflow-hidden"
                 style="background: linear-gradient(135deg, rgba(6,182,212,0.04) 0%, rgba(15,23,42,0.6) 50%, rgba(239,68,68,0.04) 100%);">

                <!-- Animated shimmer sweep -->
                <div class="absolute inset-0 ratio-shimmer-sweep pointer-events-none"></div>

                <!-- Top Row: Large Percentage Readouts -->
                <div class="relative z-10 flex justify-between items-end mb-4">
                    <!-- Positive Side -->
                    <div class="flex items-baseline gap-3">
                        <div class="flex flex-col">
                            <span class="text-[15px] font-black uppercase tracking-[0.25em] text-emerald-400/100 mb-0.5 font-mono">Positif</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-emerald-400 font-mono leading-none"
                                      style="text-shadow: 0 0 20px rgba(52,211,153,0.4), 0 0 40px rgba(52,211,153,0.15);">
                                    {{ $posPercent }}
                                </span>
                                <span class="text-emerald-400/60 font-black text-sm font-mono">%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Center Label -->
                    <div class="flex flex-col items-center gap-1 absolute left-1/2 -translate-x-1/2 bottom-0">
                        <span class="text-[20px] font-black uppercase tracking-[0.3em] text-cyan-500/100 font-mono">Ratio</span>
                    </div>

                    <!-- Negative Side -->
                    <div class="flex items-baseline gap-3">
                        <div class="flex flex-col items-end">
                            <span class="text-[15px] font-black uppercase tracking-[0.25em] text-red-400/100 mb-0.5 font-mono">Negatif</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-red-400 font-mono leading-none"
                                      style="text-shadow: 0 0 20px rgba(248,113,113,0.4), 0 0 40px rgba(248,113,113,0.15);">
                                    {{ $negPercent }}
                                </span>
                                <span class="text-red-400/60 font-black text-sm font-mono">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- The Bar -->
                <div class="relative z-10 mb-3">
                    <!-- Outer Track -->
                    <div class="h-[20px] w-full rounded-full overflow-hidden flex"
                         style="background: rgba(15,23,42,0.9); box-shadow: inset 0 1px 3px rgba(0,0,0,0.6), 0 0 0 1px rgba(34,211,238,0.08);">

                        <!-- Positive Fill -->
                        <div class="ratio-fill-anim h-full relative rounded-l-full overflow-hidden" style="width: {{ $posPercent }}%;">
                            <div class="absolute inset-0 rounded-l-full"
                                 style="background: linear-gradient(90deg, #059669 0%, #10b981 40%, #34d399 100%);"></div>
                            <div class="absolute inset-0 bg-gradient-to-b from-white/20 via-transparent to-black/10 rounded-l-full"></div>
                            <div class="absolute inset-0 ratio-bar-shimmer rounded-l-full"></div>
                        </div>

                        <!-- Pulsing Center Divider -->
                        <div class="w-[3px] h-full flex-shrink-0 relative">
                            <div class="absolute inset-0 bg-white/60 rounded-full"></div>
                            <div class="absolute -inset-1 bg-cyan-400/30 blur-sm rounded-full animate-pulse"></div>
                        </div>

                        <!-- Negative Fill -->
                        <div class="ratio-fill-anim h-full relative rounded-r-full overflow-hidden" style="width: {{ $negPercent }}%;">
                            <div class="absolute inset-0 rounded-r-full"
                                 style="background: linear-gradient(90deg, #f87171 0%, #ef4444 40%, #dc2626 100%);"></div>
                            <div class="absolute inset-0 bg-gradient-to-b from-white/20 via-transparent to-black/10 rounded-r-full"></div>
                            <div class="absolute inset-0 ratio-bar-shimmer rounded-r-full"></div>
                        </div>
                    </div>

                    <!-- Neon Trail Underglow -->
                    <div class="absolute top-full left-0 h-[6px] rounded-full blur-md mt-px" style="width: {{ $posPercent }}%; background: linear-gradient(90deg, transparent, rgba(52,211,153,0.35));"></div>
                    <div class="absolute top-full right-0 h-[6px] rounded-full blur-md mt-px" style="width: {{ $negPercent }}%; background: linear-gradient(270deg, transparent, rgba(248,113,113,0.35));"></div>
                </div>

                <!-- Bottom HUD Data Readout -->
                <div class="relative z-10 flex justify-between items-center pt-1">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-1 rounded-full bg-emerald-400 shadow-[0_0_4px_rgba(52,211,153,0.8)] animate-pulse"></div>
                        <span class="font-mono text-[20px] text-emerald-400/100 tracking-wider">
                            <span class="text-emerald-400/100 font-bold">{{ number_format($positiveCount) }}</span> komentar positif
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-[20px] text-red-400/100 tracking-wider">
                            komentar negatif <span class="text-red-400/100 font-bold">{{ number_format($negativeCount) }}</span> 
                        </span>
                        <div class="w-1 h-1 rounded-full bg-red-400 shadow-[0_0_4px_rgba(248,113,113,0.8)] animate-pulse"></div>
                    </div>
                </div>
            </div>

            <style>
                .ratio-fill-anim {
                    animation: ratioGrow 1s cubic-bezier(0.22, 1, 0.36, 1) forwards;
                    transform-origin: left center;
                    transform: scaleX(0);
                }
                @keyframes ratioGrow {
                    to { transform: scaleX(1); }
                }

                .ratio-bar-shimmer {
                    background: linear-gradient(
                        110deg,
                        transparent 30%,
                        rgba(255,255,255,0.15) 45%,
                        rgba(255,255,255,0.25) 50%,
                        rgba(255,255,255,0.15) 55%,
                        transparent 70%
                    );
                    background-size: 250% 100%;
                    animation: barShimmer 2.5s ease-in-out infinite;
                }
                @keyframes barShimmer {
                    0% { background-position: 200% 0; }
                    100% { background-position: -200% 0; }
                }

                .ratio-shimmer-sweep {
                    background: linear-gradient(
                        90deg,
                        transparent 0%,
                        rgba(34,211,238,0.03) 45%,
                        rgba(34,211,238,0.06) 50%,
                        rgba(34,211,238,0.03) 55%,
                        transparent 100%
                    );
                    background-size: 200% 100%;
                    animation: sweepShimmer 4s ease-in-out infinite;
                }
                @keyframes sweepShimmer {
                    0% { background-position: 200% 0; }
                    100% { background-position: -200% 0; }
                }
            </style>
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