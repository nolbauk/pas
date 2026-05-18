@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Dashboard Visualisasi</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Total Data Card -->
    <div class="glass rounded-2xl p-6 hover-glow relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <p class="text-gray-400 font-medium mb-2">Total Data Training</p>
        <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">{{ number_format($totalData) }}</h2>
    </div>

    <!-- Positif Card -->
    <div class="glass rounded-2xl p-6 hover-glow relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <p class="text-gray-400 font-medium mb-2">Sentimen Positif</p>
        <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-500">{{ number_format($positiveCount) }}</h2>
    </div>

    <!-- Negatif Card -->
    <div class="glass rounded-2xl p-6 hover-glow relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <p class="text-gray-400 font-medium mb-2">Sentimen Negatif</p>
        <h2 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-rose-500">{{ number_format($negativeCount) }}</h2>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Chart Container -->
    <div class="glass rounded-2xl p-6 lg:col-span-1 flex flex-col items-center justify-center">
        <h2 class="text-xl font-semibold mb-6 w-full text-left">Distribusi Sentimen</h2>
        <div class="relative w-full max-w-[250px] aspect-square">
            <canvas id="sentimentChart"></canvas>
        </div>
    </div>

    <!-- Recent Data Table -->
    <div class="glass rounded-2xl overflow-hidden lg:col-span-2">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Data Terbaru (5 Teratas)</h2>
            <a href="/dataset" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors">Lihat Semua →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-800/50">
                    <tr>
                        <th class="p-4 text-sm font-medium text-gray-400">ID</th>
                        <th class="p-4 text-sm font-medium text-gray-400">Komentar</th>
                        <th class="p-4 text-sm font-medium text-gray-400">Sentimen</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentData as $index => $data)
                    <tr class="border-t border-gray-700/50 hover:bg-slate-800/30 transition-colors">
                        <td class="p-4 align-top text-gray-400">{{ $index + 1 }}</td>
                        <td class="p-4 align-top">
                            <p class="text-sm line-clamp-2" title="{{ $data->full_text }}">{{ $data->full_text }}</p>
                        </td>
                        <td class="p-4 align-top">
                            @if($data->label == 1)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border border-emerald-500/30 text-emerald-400">Positif</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-500/20 to-rose-500/20 border border-red-500/30 text-red-400">Negatif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-400">Belum ada data. Silakan upload dataset terlebih dahulu.</td>
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
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('sentimentChart').getContext('2d');
        
        // Data from Controller
        const positiveCount = {{ $positiveCount }};
        const negativeCount = {{ $negativeCount }};

        // Create Chart
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Positif', 'Negatif'],
                datasets: [{
                    data: [positiveCount, negativeCount],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)', // Emerald/Green for Positive
                        'rgba(244, 63, 94, 0.8)'   // Rose/Red for Negative
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(244, 63, 94, 1)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#9ca3af', // gray-400
                            padding: 20,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)', // slate-900
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1', // slate-300
                        borderColor: 'rgba(51, 65, 85, 0.5)', // slate-700
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed + ' Data';
                                    
                                    // Calculate percentage
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((context.parsed / total) * 100);
                                    label += ` (${percentage}%)`;
                                }
                                return label;
                            }
                        }
                    }
                },
                cutout: '65%', // Makes it a doughnut
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    });
</script>
@endsection