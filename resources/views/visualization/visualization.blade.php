@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Data Visualization</h1>

@if($totalData > 0)
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Total Data Card -->
    <div class="glass rounded-2xl p-6 hover-glow relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <p class="text-gray-300 font-bold mb-2 uppercase tracking-tight text-xs">Total Data Testing</p>
        <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">{{ number_format($totalData) }}</h2>
    </div>

    <!-- Positif Card -->
    <div class="glass rounded-2xl p-6 hover-glow relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <p class="text-gray-300 font-bold mb-2 uppercase tracking-tight text-xs">Sentimen Positif</p>
        <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-500">{{ number_format($positiveCount) }}</h2>
    </div>

    <!-- Negatif Card -->
    <div class="glass rounded-2xl p-6 hover-glow relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <p class="text-gray-300 font-bold mb-2 uppercase tracking-tight text-xs">Sentimen Negatif</p>
        <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-rose-500">{{ number_format($negativeCount) }}</h2>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Chart Container -->
    <div class="glass rounded-2xl p-6 lg:col-span-1 flex flex-col items-center justify-center relative overflow-hidden group">
        <!-- Background Glow -->
        <div class="absolute -right-20 -top-20 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/20 transition-all duration-500"></div>
        <div class="absolute -left-20 -bottom-20 w-48 h-48 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-all duration-500"></div>

        <h2 class="text-xl font-bold mb-6 w-full text-left flex items-center gap-2 relative z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            Distribusi Sentimen
        </h2>
        
        <div class="relative w-full max-w-[280px] aspect-square relative z-10">
            <!-- Center Summary Text -->
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total</span>
                <span class="text-3xl font-black text-white">{{ number_format($totalData) }}</span>
                <span class="text-[10px] font-bold text-cyan-400 bg-cyan-400/10 px-2 py-0.5 rounded-full mt-1 border border-cyan-400/20">DATA</span>
            </div>
            <canvas id="sentimentChart"></canvas>
        </div>
    </div>

    <!-- Positive Comments Table -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-emerald-500/20 flex items-center gap-3 bg-emerald-500/5">
            <div class="w-2 h-2 rounded-full bg-emerald-400 shadow-lg shadow-emerald-400/50"></div>
            <h2 class="text-lg font-bold text-emerald-400">Komentar Positif</h2>
            <span class="ml-auto text-xs font-bold text-emerald-300/80">{{ $positiveData->count() }} DATA</span>
        </div>

        <div class="overflow-x-auto futuristic-scroll" style="max-height: 400px; overflow-y: auto;">
            <table class="w-full text-left">
                <thead class="bg-slate-800 sticky top-0 border-b border-emerald-500/20">
                    <tr>
                        <th class="p-3 text-sm font-bold text-gray-200 w-12">No</th>
                        <th class="p-3 text-sm font-bold text-gray-200">Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($positiveData as $index => $data)
                    <tr class="border-t border-gray-700/30 hover:bg-emerald-500/5 transition-colors">
                        <td class="p-3 align-top text-gray-400 text-sm font-medium">{{ $index + 1 }}</td>
                        <td class="p-3 align-top">
                            <p class="text-sm text-gray-100 leading-relaxed" title="{{ $data->full_text }}">{{ $data->full_text }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="p-4 text-center text-gray-500 text-sm">Belum ada data positif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Negative Comments Table -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-red-500/20 flex items-center gap-3 bg-red-500/5">
            <div class="w-2 h-2 rounded-full bg-red-400 shadow-lg shadow-red-400/50"></div>
            <h2 class="text-lg font-bold text-red-400">Komentar Negatif</h2>
            <span class="ml-auto text-xs font-bold text-red-300/80">{{ $negativeData->count() }} DATA</span>
        </div>

        <div class="overflow-x-auto futuristic-scroll" style="max-height: 400px; overflow-y: auto;">
            <table class="w-full text-left">
                <thead class="bg-slate-800 sticky top-0 border-b border-red-500/20">
                    <tr>
                        <th class="p-3 text-sm font-bold text-gray-200 w-12">No</th>
                        <th class="p-3 text-sm font-bold text-gray-200">Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($negativeData as $index => $data)
                    <tr class="border-t border-gray-700/30 hover:bg-red-500/5 transition-colors">
                        <td class="p-3 align-top text-gray-400 text-sm font-medium">{{ $index + 1 }}</td>
                        <td class="p-3 align-top">
                            <p class="text-sm text-gray-100 leading-relaxed" title="{{ $data->full_text }}">{{ $data->full_text }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="p-4 text-center text-gray-500 text-sm">Belum ada data negatif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('sentimentChart').getContext('2d');
        
        // Create Gradients for a cooler look
        const gradientPos = ctx.createLinearGradient(0, 0, 0, 400);
        gradientPos.addColorStop(0, '#10b981'); // emerald-500
        gradientPos.addColorStop(1, '#059669'); // emerald-600

        const gradientNeg = ctx.createLinearGradient(0, 0, 0, 400);
        gradientNeg.addColorStop(0, '#f43f5e'); // rose-500
        gradientNeg.addColorStop(1, '#e11d48'); // rose-600

        const positiveCount = {{ $positiveCount }};
        const negativeCount = {{ $negativeCount }};
        const total = positiveCount + negativeCount;
        const posPercent = total > 0 ? ((positiveCount / total) * 100).toFixed(1) : 0;
        const negPercent = total > 0 ? ((negativeCount / total) * 100).toFixed(1) : 0;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [`Positif (${posPercent}%)`, `Negatif (${negPercent}%)`],
                datasets: [{
                    data: [positiveCount, negativeCount],
                    backgroundColor: [gradientPos, gradientNeg],
                    borderColor: [
                        'rgba(16, 185, 129, 0.2)',
                        'rgba(244, 63, 94, 0.2)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 15,
                    borderRadius: 10,
                    spacing: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#e5e7eb', // gray-200
                            padding: 25,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: true,
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1
                    }
                },
                cutout: '82%', // Elegant thin ring
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });
    });
</script>
@else
<!-- Empty State -->
<div class="flex flex-col items-center justify-center text-center py-20 opacity-60">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-gray-600 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
    </svg>
    <p class="text-gray-400 text-lg font-semibold mb-2">Belum ada data untuk divisualisasikan</p>
    <p class="text-gray-500 text-sm max-w-md">
        Silakan lakukan <strong>Upload Dataset</strong> terlebih dahulu agar distribusi sentimen dapat ditampilkan di halaman ini.
    </p>
    <a href="/upload-dataset" class="mt-6 futuristic-btn-blue px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        Ke Halaman Upload Dataset
    </a>
</div>
@endif
@endsection
