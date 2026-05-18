<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SVM Sentiment Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
        }
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .hover-glow:hover {
            box-shadow: 0 0 20px rgba(34,211,238,0.5);
            transition: 0.3s;
        }
        .futuristic-btn-blue {
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            background: rgba(6, 182, 212, 0.15);
            border: 1px solid rgba(34, 211, 238, 0.35);
            color: #67e8f9;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
            display: block;
            width: 100%;
        }
        .futuristic-btn-blue:hover {
            background: rgba(6, 182, 212, 0.25);
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.35);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center text-white px-4 relative">

    <!-- Back to Landing Button -->
    <a href="{{ url('/') }}" class="fixed top-8 left-8 flex items-center gap-2 px-4 py-2 rounded-xl glass border border-white/10 text-gray-400 hover:text-white hover:border-cyan-500/50 transition-all group z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span class="text-sm font-bold uppercase tracking-widest">Back to Home</span>
    </a>

    <div class="glass rounded-3xl shadow-2xl p-10 w-full max-w-md">

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-cyan-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-3xl font-black tracking-tight">SECURE <span class="text-cyan-400">LOGIN</span></h1>
            <p class="text-gray-400 mt-2 font-medium">Masuk untuk melanjutkan analisis</p>
        </div>

        @if($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-red-500/20 border border-red-500/50 text-red-300 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        placeholder="Masukkan username"
                        class="w-full bg-slate-900/50 border border-white/10 rounded-xl p-3 pl-10 text-white focus:border-cyan-400 transition-all outline-none">
                </div>
            </div>

            <div class="mb-8">
                <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input type="password" name="password" required
                        placeholder="Masukkan password"
                        class="w-full bg-slate-900/50 border border-white/10 rounded-xl p-3 pl-10 text-white focus:border-cyan-400 transition-all outline-none">
                </div>
            </div>

            <button type="submit" class="futuristic-btn-blue">
                Masuk ke Sistem
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-400 font-medium">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-cyan-400 hover:text-cyan-300 font-bold transition-colors underline underline-offset-4">Daftar Sekarang</a>
            </p>
        </div>

    </div>

</body>
</html>