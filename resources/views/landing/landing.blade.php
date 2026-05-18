<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAS v1.0 - Sentiment Analysis Engine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #22d3ee;
            --secondary: #3b82f6;
            --bg-dark: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: white;
            overflow-x: hidden;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .text-gradient {
            background: linear-gradient(135deg, #22d3ee 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(34, 211, 238, 0.15) 0%, transparent 70%);
            filter: blur(60px);
            z-index: -1;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            border-color: rgba(34, 211, 238, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(34, 211, 238, 0.1);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .btn-futuristic {
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .btn-futuristic::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-futuristic:hover::before {
            left: 100%;
        }

        .grid-bg {
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="grid-bg">

    <!-- Hero Glows -->
    <div class="hero-glow top-[-10%] left-[-10%]"></div>
    <div class="hero-glow bottom-[10%] right-[-10%] animate-pulse"></div>

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 px-8 py-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center glass rounded-2xl px-6 py-4 border border-white/5 shadow-2xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <span class="text-xl font-black tracking-tight">PAS <span class="text-cyan-400">v1.0</span></span>
            </div>
            

            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm font-bold text-gray-300 hover:text-white px-4">Login</a>
                <a href="{{ route('register') }}" class="btn-futuristic bg-cyan-500 hover:bg-cyan-400 px-6 py-2.5 rounded-xl text-sm font-black text-slate-950 uppercase tracking-tight shadow-lg shadow-cyan-500/20">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-48 pb-20 px-8">
        <div class="max-w-7xl mx-auto flex flex-col items-center text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-black uppercase tracking-[0.2em] mb-8 animate-bounce">
                🚀 Powered by Support Vector Machine
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black leading-tight mb-8">
                Uncover the Public <br>
                <span class="text-gradient">Sentiment Voice</span>
            </h1>

            <p class="text-gray-400 text-lg md:text-xl max-w-3xl leading-relaxed mb-12">
                Sistem cerdas untuk menganalisis sentimen masyarakat terhadap kebijakan 
                <span class="text-white font-bold italic">Penggabungan Biaya Parkir STNK</span> di media sosial. 
                Dapatkan insight akurat menggunakan algoritma SVM tercanggih.
            </p>

            <div class="flex flex-col sm:flex-row gap-6">
                <a href="{{ route('login') }}" class="btn-futuristic bg-white text-slate-950 px-10 py-4 rounded-2xl font-black uppercase tracking-widest flex items-center gap-3">
                    Start Analysis
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#about" class="glass px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-gray-300 hover:text-white transition-all border border-white/10">
                    Learn More
                </a>
            </div>

            <!-- Dashboard Mockup/Preview -->
            <div class="mt-24 relative w-full max-w-5xl animate-float">
                <div class="absolute -inset-4 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-[2.5rem] blur-2xl opacity-10"></div>
                <div class="glass rounded-[2rem] border border-white/10 p-2 shadow-3xl overflow-hidden">
                    <!-- Internal Dashboard UI Mockup -->
                    <div class="bg-[#0f172a] rounded-[1.5rem] overflow-hidden border border-white/5 h-[450px] flex flex-col">
                        <!-- Mock Navbar -->
                        <div class="h-14 border-b border-white/5 flex items-center px-6 gap-4 bg-white/[0.02]">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500/30"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/30"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/30"></div>
                            </div>
                            <div class="h-4 w-32 bg-white/10 rounded-full ml-4"></div>
                            <div class="ml-auto flex gap-4">
                                <div class="w-8 h-8 rounded-lg bg-white/5"></div>
                                <div class="w-8 h-8 rounded-full bg-cyan-500/20"></div>
                            </div>
                        </div>
                        
                        <!-- Mock Content -->
                        <div class="flex-1 p-8 flex gap-8">
                            <!-- Sidebar Mock -->
                            <div class="w-48 space-y-4 hidden sm:block">
                                <div class="h-10 w-full bg-cyan-500/10 rounded-xl border border-cyan-500/20 flex items-center px-3 gap-2">
                                    <div class="w-4 h-4 rounded bg-cyan-400/40"></div>
                                    <div class="h-2 w-16 bg-cyan-400/40 rounded"></div>
                                </div>
                                <div class="h-10 w-full bg-white/[0.03] rounded-xl flex items-center px-3 gap-2">
                                    <div class="w-4 h-4 rounded bg-white/10"></div>
                                    <div class="h-2 w-20 bg-white/10 rounded"></div>
                                </div>
                                <div class="h-10 w-full bg-white/[0.03] rounded-xl flex items-center px-3 gap-2">
                                    <div class="w-4 h-4 rounded bg-white/10"></div>
                                    <div class="h-2 w-12 bg-white/10 rounded"></div>
                                </div>
                                <div class="h-10 w-full bg-white/[0.03] rounded-xl flex items-center px-3 gap-2">
                                    <div class="w-4 h-4 rounded bg-white/10"></div>
                                    <div class="h-2 w-24 bg-white/10 rounded"></div>
                                </div>
                            </div>
                            
                            <!-- Main Panel Mock -->
                            <div class="flex-1 flex flex-col gap-8">
                                <!-- Top Stats -->
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="h-24 bg-white/[0.03] rounded-2xl border border-white/5 p-5 relative overflow-hidden">
                                        <div class="h-2 w-1/3 bg-gray-500/50 rounded-full mb-3"></div>
                                        <div class="h-8 w-2/3 bg-cyan-400/20 rounded-lg"></div>
                                        <div class="absolute bottom-0 left-0 w-full h-1 bg-cyan-500/30"></div>
                                    </div>
                                    <div class="h-24 bg-white/[0.03] rounded-2xl border border-white/5 p-5 relative overflow-hidden">
                                        <div class="h-2 w-1/3 bg-gray-500/50 rounded-full mb-3"></div>
                                        <div class="h-8 w-2/3 bg-emerald-400/20 rounded-lg"></div>
                                        <div class="absolute bottom-0 left-0 w-full h-1 bg-emerald-500/30"></div>
                                    </div>
                                    <div class="h-24 bg-white/[0.03] rounded-2xl border border-white/5 p-5 relative overflow-hidden">
                                        <div class="h-2 w-1/3 bg-gray-500/50 rounded-full mb-3"></div>
                                        <div class="h-8 w-2/3 bg-red-400/20 rounded-lg"></div>
                                        <div class="absolute bottom-0 left-0 w-full h-1 bg-red-500/30"></div>
                                    </div>
                                </div>
                                
                                <!-- Chart & List -->
                                <div class="flex-1 flex gap-8">
                                    <div class="flex-1 bg-white/[0.03] rounded-3xl border border-white/5 p-8 flex items-center justify-center gap-12">
                                        <!-- Animated Doughnut Mock -->
                                        <div class="w-40 h-40 rounded-full border-[16px] border-emerald-500/10 border-t-emerald-400 border-r-emerald-400 border-l-red-400/40 relative flex items-center justify-center shadow-[0_0_30px_rgba(16,185,129,0.1)]">
                                            <div class="flex flex-col items-center">
                                                <span class="text-2xl font-black text-white leading-none">84.2</span>
                                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Accuracy</span>
                                            </div>
                                        </div>
                                        <div class="space-y-4 flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                                <div class="h-2 w-full bg-white/10 rounded-full"></div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                                <div class="h-2 w-4/5 bg-white/10 rounded-full"></div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="w-3 h-3 rounded-full bg-gray-500"></div>
                                                <div class="h-2 w-3/4 bg-white/10 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-24 px-8 relative">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col items-center text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black mb-4">Core <span class="text-cyan-400">Capabilities</span></h2>
                <p class="text-gray-400 max-w-2xl">Fitur unggulan yang dirancang untuk memberikan hasil analisis yang komprehensif dan mudah dipahami.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="glass p-8 rounded-3xl border border-white/5 card-hover transition-all duration-500">
                    <div class="w-14 h-14 bg-cyan-500/10 rounded-2xl flex items-center justify-center mb-6 border border-cyan-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black mb-4 tracking-tight">Advanced SVM Model</h3>
                    <p class="text-gray-400 leading-relaxed text-sm font-medium">Klasifikasi sentimen otomatis menjadi Positif atau Negatif dengan akurasi tinggi menggunakan Support Vector Machine.</p>
                </div>

                <!-- Card 2 -->
                <div class="glass p-8 rounded-3xl border border-white/5 card-hover transition-all duration-500">
                    <div class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6 border border-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black mb-4 tracking-tight">Interactive Analytics</h3>
                    <p class="text-gray-400 leading-relaxed text-sm font-medium">Visualisasi data interaktif untuk memahami proporsi sentimen dan tren opini publik secara real-time.</p>
                </div>

                <!-- Card 3 -->
                <div class="glass p-8 rounded-3xl border border-white/5 card-hover transition-all duration-500">
                    <div class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-6 border border-purple-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black mb-4 tracking-tight">System Evaluation</h3>
                    <p class="text-gray-400 leading-relaxed text-sm font-medium">Laporan performa model yang detail mulai dari Accuracy, Precision, hingga Confusion Matrix yang komprehensif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Case Study / About Section -->
    <section id="about" class="py-24 px-8 bg-slate-900/30">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative">
                <div class="absolute -inset-4 bg-cyan-400 rounded-3xl blur-3xl opacity-10"></div>
                <div class="glass rounded-3xl border border-white/5 overflow-hidden">
                    <div class="p-4 bg-white/5 border-b border-white/10 flex gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            <div class="p-4 rounded-xl bg-cyan-500/5 border border-cyan-500/10">
                                <p class="text-cyan-400 text-xs font-black uppercase mb-1">Public Opinion #1</p>
                                <p class="text-gray-300 italic">"Kebijakan parkir di STNK ini memberatkan masyarakat yang sudah bayar pajak tepat waktu."</p>
                                <div class="mt-3 flex justify-end">
                                    <span class="px-2 py-0.5 rounded bg-red-500/20 text-red-400 text-[10px] font-bold uppercase">Negative Sentimen</span>
                                </div>
                            </div>
                            <div class="p-4 rounded-xl bg-blue-500/5 border border-blue-500/10 ml-8">
                                <p class="text-blue-400 text-xs font-black uppercase mb-1">Public Opinion #2</p>
                                <p class="text-gray-300 italic">"Kalau tujuannya untuk penataan dan efisiensi, saya setuju saja asal transparan."</p>
                                <div class="mt-3 flex justify-end">
                                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase">Positive Sentimen</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-3xl md:text-5xl font-black mb-8 leading-tight">Menganalisis Isu <br> <span class="text-gradient">Parkir STNK</span></h2>
                <p class="text-gray-400 text-lg leading-relaxed mb-8">
                    Studi kasus utama platform ini difokuskan pada dinamika opini masyarakat terkait wacana penggabungan biaya parkir ke dalam pembayaran STNK tahunan. 
                </p>
                <ul class="space-y-4 mb-10">
                    <li class="flex items-center gap-3 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Data bersumber dari Media Sosial X (Twitter)
                    </li>
                    <li class="flex items-center gap-3 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Pemrosesan Teks (Case Folding, Tokenizing, Stopwords)
                    </li>
                    <li class="flex items-center gap-3 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Pembagian Data 80% Training & 20% Testing
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="text-cyan-400 font-black uppercase tracking-widest text-sm flex items-center gap-2 group">
                    Explore Case Study
                    <span class="group-hover:translate-x-2 transition-transform">→</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-8 border-t border-white/5">
        <div class="max-w-7xl mx-auto flex flex-col md:row justify-between items-center gap-8">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <span class="font-bold">PAS v1.0 <span class="text-gray-500 font-medium ml-2">© 2026</span></span>
            </div>
        </div>
    </footer>

</body>
</html>