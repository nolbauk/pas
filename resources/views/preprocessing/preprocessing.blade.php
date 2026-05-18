@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Text Preprocessing</h1>

<div class="glass rounded-2xl p-6 mb-6">
    <form id="preprocessForm" action="{{ route('preprocessing.process') }}" method="POST">
        @csrf

        <div class="flex items-center gap-4 w-full">
            <button
                type="button"
                onclick="processData()"
                id="processBtn"
                class="futuristic-btn-process whitespace-nowrap">
                Proses Data
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
    </form>
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
function processData() {
    const form = document.getElementById('preprocessForm');
    const progressContainer = document.getElementById('processProgressContainer');
    const progressBar = document.getElementById('processProgressBar');
    const progressText = document.getElementById('processProgressText');
    const processBtn = document.getElementById('processBtn');

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

            progressText.innerText = 'Preprocessing berhasil dilakukan';

            setTimeout(() => {
                form.submit();
            }, 1000);
        }
    }, 150);
}
</script>
@endsection