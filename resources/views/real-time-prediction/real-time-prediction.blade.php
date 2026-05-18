@extends('layouts.app')

@section('content')
<style>
    /* Typing cursor animation */
    .textarea-glow:focus {
        border-color: rgba(34, 211, 238, 0.6);
        box-shadow: 0 0 25px rgba(34, 211, 238, 0.2), inset 0 0 15px rgba(34, 211, 238, 0.05);
    }

    /* Pulse ring animation for the submit button */
    @keyframes pulseRing {
        0% { transform: scale(1); opacity: 0.6; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    .btn-predict {
        position: relative;
        overflow: hidden;
    }

    .btn-predict::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 1rem;
        background: linear-gradient(135deg, #22d3ee, #3b82f6, #a855f7);
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .btn-predict:hover::before {
        opacity: 1;
    }

    /* Shimmer loading animation */
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .loading-shimmer {
        background: linear-gradient(90deg,
            rgba(34, 211, 238, 0.05) 25%,
            rgba(34, 211, 238, 0.15) 50%,
            rgba(34, 211, 238, 0.05) 75%
        );
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    /* Result card entrance */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .result-enter {
        animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Sentiment badge glow */
    .badge-positive {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(52, 211, 153, 0.1));
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #6ee7b7;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.15);
    }

    .badge-negative {
        background: linear-gradient(135deg, rgba(244, 63, 94, 0.15), rgba(251, 113, 133, 0.1));
        border: 1px solid rgba(244, 63, 94, 0.4);
        color: #fda4af;
        box-shadow: 0 0 20px rgba(244, 63, 94, 0.15);
    }

    /* Spinner */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .spinner {
        animation: spin 0.8s linear infinite;
    }

    /* Character counter fade */
    .char-counter {
        transition: color 0.3s ease;
    }

    /* Floating particles behind the form */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.3; }
        50% { transform: translateY(-20px) rotate(180deg); opacity: 0.6; }
    }

    .particle {
        position: absolute;
        border-radius: 50%;
        background: rgba(34, 211, 238, 0.15);
        pointer-events: none;
    }

    .particle:nth-child(1) { width: 6px; height: 6px; top: 10%; left: 5%;  animation: float 6s ease-in-out infinite; }
    .particle:nth-child(2) { width: 4px; height: 4px; top: 25%; right: 8%; animation: float 8s ease-in-out 1s infinite; }
    .particle:nth-child(3) { width: 8px; height: 8px; bottom: 15%; left: 12%; animation: float 7s ease-in-out 2s infinite; }
    .particle:nth-child(4) { width: 5px; height: 5px; bottom: 30%; right: 15%; animation: float 9s ease-in-out 0.5s infinite; }
    .particle:nth-child(5) { width: 3px; height: 3px; top: 50%; left: 50%; animation: float 5s ease-in-out 3s infinite; }
</style>

<div class="relative">
    <!-- Floating particles -->
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400">
            Real-Time Prediction
        </h1>
        <p class="text-gray-300 mt-2">
            Masukkan teks komentar untuk menganalisis sentimen secara langsung menggunakan model SVM yang telah dilatih.
        </p>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
    <div class="mb-6 p-4 rounded-xl border border-red-500/30 bg-red-500/10 text-red-300 text-sm flex items-center gap-3 result-enter">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Two-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        <!-- LEFT: Input Komentar -->
        <form id="predictionForm" action="{{ route('real-time-prediction.predict') }}" method="POST">
            @csrf
            <div class="futuristic-upload-box p-6 h-full flex flex-col">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500/20 to-blue-500/20 border border-cyan-500/30 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Input Komentar</h2>
                        <p class="text-xs text-gray-300 font-medium">Tulis atau tempel teks yang ingin dianalisis</p>
                    </div>
                </div>

                <textarea
                    id="inputText"
                    name="text"
                    rows="8"
                    maxlength="5000"
                    placeholder="Ketik komentar di sini... contoh: 'Pelayanan rumah sakit ini sangat memuaskan dan ramah'"
                    class="w-full flex-1 bg-slate-900/50 border border-gray-700/50 rounded-xl p-4 text-white placeholder-gray-400 resize-none textarea-glow transition-all duration-300 focus:outline-none text-sm leading-relaxed"
                    required
                >{{ old('text', $inputText ?? '') }}</textarea>

                <div class="flex justify-between items-center mt-3">
                    <p class="char-counter text-xs text-gray-300 font-medium">
                        <span id="charCount">0</span> / 5000 karakter
                    </p>
                    <button type="button" id="clearBtn" class="text-xs text-gray-500 hover:text-red-400 transition-colors duration-200 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center mt-5">
                    <button
                        type="submit"
                        id="predictBtn"
                        class="btn-predict futuristic-btn-process px-8 py-3 rounded-xl font-semibold text-base flex items-center gap-3 group w-full justify-center"
                    >
                        <span id="btnText" class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            Analisis Sentimen
                        </span>
                        <span id="btnLoading" class="hidden flex items-center gap-3">
                            <svg class="w-5 h-5 spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Menganalisis...
                        </span>
                    </button>
                </div>
            </div>
        </form>

        <!-- RIGHT: Hasil Prediksi -->
        <div class="futuristic-upload-box p-6 h-full flex flex-col {{ isset($result) ? 'result-enter' : '' }}">

            <!-- Result Header -->
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/30 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">Hasil Prediksi</h2>
                    <p class="text-xs text-gray-300 font-medium">
                        {{ isset($result) ? 'Analisis sentimen berhasil dilakukan' : 'Menunggu input untuk dianalisis' }}
                    </p>
                </div>
            </div>

            @isset($result)
                <!-- Sentiment Badge -->
                <div class="flex justify-center mb-6">
                    <div class="px-8 py-4 rounded-2xl text-center {{ $result['label'] === 'Positif' ? 'badge-positive' : 'badge-negative' }}">
                        <div class="flex items-center gap-3 justify-center mb-1">
                            @if($result['label'] === 'Positif')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                            <span class="text-2xl font-bold">{{ $result['label'] }}</span>
                        </div>
                        <p class="text-xs text-white/80 font-medium mt-1">Sentimen terdeteksi</p>
                    </div>
                </div>

                <!-- Details -->
                <div class="space-y-4 flex-1">
                    <!-- Original Text -->
                    <div class="bg-slate-900/40 rounded-xl p-4 border border-gray-700/30">
                        <p class="text-xs text-gray-300 font-bold mb-2 flex items-center gap-2 uppercase tracking-tighter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Teks Asli
                        </p>
                        <p class="text-sm text-gray-100 leading-relaxed">{{ $result['original'] }}</p>
                    </div>

                    <!-- Processed Text -->
                    <div class="bg-slate-900/40 rounded-xl p-4 border border-gray-700/30">
                        <p class="text-xs text-gray-300 font-bold mb-2 flex items-center gap-2 uppercase tracking-tighter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Teks Setelah Preprocessing
                        </p>
                        <p class="text-sm text-gray-100 leading-relaxed font-mono">{{ $result['processed'] }}</p>
                    </div>
                </div>
            @else
                <!-- Empty/Waiting State -->
                <div class="flex-1 flex flex-col items-center justify-center text-center py-8 opacity-60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="text-gray-300 text-sm font-bold">Belum ada hasil prediksi</p>
                    <p class="text-gray-400 text-xs mt-1">Masukkan komentar dan klik "Analisis Sentimen"</p>
                </div>
            @endisset
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('inputText');
    const charCount = document.getElementById('charCount');
    const clearBtn = document.getElementById('clearBtn');
    const form = document.getElementById('predictionForm');
    const predictBtn = document.getElementById('predictBtn');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');

    // Character counter
    function updateCharCount() {
        const len = textarea.value.length;
        charCount.textContent = len;
        if (len > 4500) {
            charCount.parentElement.classList.add('text-amber-400');
            charCount.parentElement.classList.remove('text-gray-500');
        } else {
            charCount.parentElement.classList.remove('text-amber-400');
            charCount.parentElement.classList.add('text-gray-500');
        }
    }
    textarea.addEventListener('input', updateCharCount);
    updateCharCount(); // init

    // Clear button
    clearBtn.addEventListener('click', function () {
        textarea.value = '';
        updateCharCount();
        textarea.focus();
    });

    // Form submit loading state
    form.addEventListener('submit', function () {
        if (textarea.value.trim() === '') return;
        predictBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
    });
});
</script>
@endsection
