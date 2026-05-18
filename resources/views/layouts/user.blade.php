<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Sentiment AI</title>

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
            box-shadow: 0 0 20px rgba(59,130,246,0.5);
            transform: translateY(-2px);
            transition: 0.3s;
        }

        table tbody tr:hover {
            background: rgba(255,255,255,0.05);
            transition: 0.3s;
        }

        input, textarea {
            outline: none;
        }

        button {
            transition: 0.3s;
        }
    </style>
</head>
<body class="text-white min-h-screen">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 glass shadow-2xl p-6 flex flex-col">

        <div class="mb-10">
            <h1 class="text-2xl font-bold tracking-wide">
                Analisis Sentimen
            </h1>
            <p class="text-sm text-gray-300 mt-1">
                SUPPORT VECTOR MACHINE
            </p>
        </div>

        <!-- Menu User -->
        <nav class="space-y-3">

            <a href="/user/dashboard"
               class="flex items-center gap-3 p-3 rounded-xl
               {{ request()->is('user/dashboard') ? 'bg-cyan-500/20 border border-cyan-400 shadow-lg' : 'hover:bg-blue-500/20' }}
               hover-glow">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-cyan-400"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7"/>
                </svg>

                <span>Hasil Analisis</span>
            </a>

        </nav>

        <!-- Logout -->
        <div class="mt-auto pt-6">
            <a href="/login"
               class="flex items-center gap-3 p-3 rounded-xl border border-red-400/40 text-red-300 hover:bg-red-500/10 transition-all duration-300">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>

                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">

        <!-- Top Navbar -->
        <header class="glass shadow-lg mx-6 mt-4 rounded-2xl px-6 py-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">Sentiment Analysis Result</h2>
                <p class="text-sm text-gray-300">
                    User View Dashboard
                </p>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm">User</p>
                    <p class="text-xs text-gray-400">Viewer Access</p>
                </div>

                <div class="w-10 h-10 rounded-full bg-cyan-500 flex items-center justify-center font-bold">
                    U
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="p-6">
            <div class="glass rounded-3xl p-6 shadow-2xl min-h-[85vh]">
                @yield('content')
            </div>
        </main>

    </div>
</div>

</body>
</html>