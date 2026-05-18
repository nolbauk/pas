<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SVM Sentiment Analysis</title>
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
<body class="min-h-screen flex items-center justify-center text-white px-4 py-10 relative">

    <!-- Back to Landing Button -->
    <a href="{{ url('/') }}" class="fixed top-8 left-8 flex items-center gap-2 px-4 py-2 rounded-xl glass border border-white/10 text-gray-400 hover:text-white hover:border-cyan-500/50 transition-all group z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span class="text-sm font-bold uppercase tracking-widest">Back to Home</span>
    </a>

    <div class="glass rounded-3xl shadow-2xl p-10 w-full max-w-md">

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="text-3xl font-black tracking-tight">CREATE <span class="text-purple-400">ACCOUNT</span></h1>
            <p class="text-gray-400 mt-2 font-medium">Daftar untuk memulai analisis sentimen</p>
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

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="Masukkan nama lengkap"
                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl p-3 text-white focus:border-purple-400 transition-all outline-none">
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                    placeholder="Masukkan username unik"
                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl p-3 text-white focus:border-purple-400 transition-all outline-none">
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    placeholder="admin@example.com"
                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl p-3 text-white focus:border-purple-400 transition-all outline-none">
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Password</label>
                <input type="password" name="password" required
                    placeholder="Minimal 8 karakter"
                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl p-3 text-white focus:border-purple-400 transition-all outline-none">
            </div>

            <div class="mb-8">
                <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    placeholder="Ulangi password"
                    class="w-full bg-slate-900/50 border border-white/10 rounded-xl p-3 text-white focus:border-purple-400 transition-all outline-none">
            </div>

            <button type="submit" class="futuristic-btn-blue !bg-purple-500/15 !border-purple-500/35 !text-purple-300 hover:!bg-purple-500/25 shadow-purple-500/20">
                Daftar Akun Baru
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-400 font-medium">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-bold transition-colors underline underline-offset-4">Masuk ke Sistem</a>
            </p>
        </div>

    </div>

</body>
</html>