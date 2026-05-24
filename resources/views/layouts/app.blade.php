<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analysis Dashboard</title>

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
        
        .futuristic-scroll::-webkit-scrollbar {
            width: 10px;
        }

        .futuristic-scroll::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.06);
            border-radius: 999px;
        }

        .futuristic-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(
                180deg,
                rgba(34,211,238,0.9),
                rgba(59,130,246,0.85)
            );
            border-radius: 999px;
            box-shadow: 0 0 12px rgba(34,211,238,0.35);
        }

        /* Upload page reusable styles */
        .futuristic-upload-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(34, 211, 238, 0.25);
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.12);
            transition: all 0.3s ease;
        }

        .futuristic-upload-box:hover {
            border-color: rgba(34, 211, 238, 0.5);
            box-shadow: 0 0 30px rgba(34, 211, 238, 0.25);
        }

        .futuristic-btn-blue {
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            background: rgba(6, 182, 212, 0.15);
            border: 1px solid rgba(34, 211, 238, 0.35);
            color: #67e8f9;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .futuristic-btn-blue:hover {
            background: rgba(6, 182, 212, 0.25);
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.35);
        }

        .futuristic-btn-red {
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(248, 113, 113, 0.35);
            color: #fca5a5;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .futuristic-btn-red:hover {
            background: rgba(239, 68, 68, 0.25);
            box-shadow: 0 0 20px rgba(248, 113, 113, 0.35);
        }

        .futuristic-progress-track {
            width: 100%;
            height: 2.5rem;
            border-radius: 999px;
            overflow: hidden;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(34, 211, 238, 0.2);
            backdrop-filter: blur(10px);
            position: relative;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .futuristic-progress-bar {
            height: 100%;
            width: 0%;
            border-radius: 999px;
            background: linear-gradient(90deg, 
                #0891b2 0%, 
                #22d3ee 50%, 
                #0891b2 100%
            );
            background-size: 200% 100%;
            animation: move-bg 2s linear infinite;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.4),
                        inset 0 0 10px rgba(255, 255, 255, 0.3);
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Scanning light effect */
        .futuristic-progress-bar::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent
            );
            width: 50%;
            animation: scan 1.5s infinite;
        }

        /* Segmented pattern overlay */
        .futuristic-progress-track::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0.05) 1px,
                transparent 1px
            );
            background-size: 20px 100%;
            z-index: 10;
            pointer-events: none;
        }

        @keyframes move-bg {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        @keyframes scan {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(250%); }
        }

        #progressText, #processProgressText {
            font-family: 'Monaco', 'Consolas', monospace;
            font-weight: 900;
            font-size: 0.85rem;
            color: white;
            text-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
            letter-spacing: 0.1em;
            z-index: 20;
        }
        .futuristic-btn-process {
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            background: rgba(34, 211, 238, 0.15);
            border: 1px solid rgba(34, 211, 238, 0.35);
            color: #a5f3fc;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.15);
        }

        .futuristic-btn-process:hover {
            background: rgba(34, 211, 238, 0.25);
            border-color: rgba(34, 211, 238, 0.6);
            box-shadow: 0 0 25px rgba(34, 211, 238, 0.35);
            transform: translateY(-1px);
        }

        .futuristic-btn-process:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Metric Cards Global Styles */
        @keyframes borderGlow {
            0%, 100% { border-color: rgba(34, 211, 238, 0.3); }
            50% { border-color: rgba(34, 211, 238, 0.6); }
        }

        .metric-card {
            position: relative;
            overflow: hidden;
        }

        .metric-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 0 0 1rem 1rem;
        }

        .metric-card.accuracy::after  { background: linear-gradient(90deg, #22d3ee, #3b82f6); }
        .metric-card.precision::after { background: linear-gradient(90deg, #a855f7, #ec4899); }
        .metric-card.recall::after    { background: linear-gradient(90deg, #10b981, #14b8a6); }
        .metric-card.f1::after        { background: linear-gradient(90deg, #f97316, #eab308); }

        @keyframes fillRing {
            from { stroke-dashoffset: 251.2; }
        }

        .progress-ring-circle {
            animation: fillRing 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeSlideIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-in:nth-child(1) { animation-delay: 0s; }
        .animate-in:nth-child(2) { animation-delay: 0.1s; }
        .animate-in:nth-child(3) { animation-delay: 0.2s; }
        .animate-in:nth-child(4) { animation-delay: 0.3s; }
    </style>
</head>

<body class="text-white min-h-screen">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-slate-900/60 backdrop-blur-xl border-r border-white/5 flex flex-col p-6 sticky top-0 h-screen">
        
        <!-- Brand Section -->
        <div class="mb-10 group cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/20 group-hover:scale-110 transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tight text-white leading-none">
                        PAS <span class="text-cyan-400">v1.0</span>
                    </h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">
                        SVM Sentiment
                    </p>
                </div>
            </div>
            <div class="h-px w-full bg-gradient-to-r from-transparent via-cyan-500/20 to-transparent mt-6"></div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-8 overflow-y-auto futuristic-scroll pr-2">
            
            <!-- Main Group -->
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 pl-3">Main Console</p>
                <div class="space-y-1">
                    <a href="/dashboard" 
                       class="group flex items-center gap-3 p-3 rounded-xl transition-all duration-300 relative
                       {{ request()->is('dashboard') ? 'bg-cyan-500/10 text-cyan-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        @if(request()->is('dashboard'))
                            <div class="absolute left-0 w-1 h-6 bg-cyan-400 rounded-r-full shadow-[0_0_10px_rgba(34,211,238,0.8)]"></div>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->is('dashboard') ? 'text-cyan-400' : 'group-hover:text-cyan-400 transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/>
                        </svg>
                        <span class="font-bold text-sm">Dashboard</span>
                    </a>

                    <a href="/visualization" 
                       class="group flex items-center gap-3 p-3 rounded-xl transition-all duration-300 relative
                       {{ request()->is('visualization') ? 'bg-cyan-500/10 text-cyan-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        @if(request()->is('visualization'))
                            <div class="absolute left-0 w-1 h-6 bg-cyan-400 rounded-r-full shadow-[0_0_10px_rgba(34,211,238,0.8)]"></div>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->is('visualization') ? 'text-cyan-400' : 'group-hover:text-cyan-400 transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        <span class="font-bold text-sm">Data Visualization</span>
                    </a>
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
            <!-- Model Pipeline Group -->
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 pl-3">Model Pipeline</p>
                <div class="space-y-1">
                    <a href="/upload-dataset" 
                       class="group flex items-center gap-3 p-3 rounded-xl transition-all duration-300 relative
                       {{ request()->is('upload-dataset') ? 'bg-cyan-500/10 text-cyan-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        @if(request()->is('upload-dataset'))
                            <div class="absolute left-0 w-1 h-6 bg-cyan-400 rounded-r-full shadow-[0_0_10px_rgba(34,211,238,0.8)]"></div>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->is('upload-dataset') ? 'text-cyan-400' : 'group-hover:text-cyan-400 transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 8v4m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        <span class="font-bold text-sm">Upload Dataset</span>
                    </a>

                    <a href="/train-model" 
                       class="group flex items-center gap-3 p-3 rounded-xl transition-all duration-300 relative
                       {{ request()->is('train-model') ? 'bg-cyan-500/10 text-cyan-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        @if(request()->is('train-model'))
                            <div class="absolute left-0 w-1 h-6 bg-cyan-400 rounded-r-full shadow-[0_0_10px_rgba(34,211,238,0.8)]"></div>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->is('train-model') ? 'text-cyan-400' : 'group-hover:text-cyan-400 transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-bold text-sm">Train Model</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Analysis Group -->
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 pl-3">Analytics & Test</p>
                <div class="space-y-1">
                    <a href="/testing" 
                       class="group flex items-center gap-3 p-3 rounded-xl transition-all duration-300 relative
                       {{ request()->is('testing') ? 'bg-cyan-500/10 text-cyan-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        @if(request()->is('testing'))
                            <div class="absolute left-0 w-1 h-6 bg-cyan-400 rounded-r-full shadow-[0_0_10px_rgba(34,211,238,0.8)]"></div>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->is('testing') ? 'text-cyan-400' : 'group-hover:text-cyan-400 transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6"/>
                        </svg>
                        <span class="font-bold text-sm">Testing</span>
                    </a>

                    <a href="/evaluation" 
                       class="group flex items-center gap-3 p-3 rounded-xl transition-all duration-300 relative
                       {{ request()->is('evaluation') ? 'bg-cyan-500/10 text-cyan-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        @if(request()->is('evaluation'))
                            <div class="absolute left-0 w-1 h-6 bg-cyan-400 rounded-r-full shadow-[0_0_10px_rgba(34,211,238,0.8)]"></div>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->is('evaluation') ? 'text-cyan-400' : 'group-hover:text-cyan-400 transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-bold text-sm">Evaluation Model</span>
                    </a>

                    <a href="/real-time-prediction" 
                       class="group flex items-center gap-3 p-3 rounded-xl transition-all duration-300 relative
                       {{ request()->is('real-time-prediction') ? 'bg-cyan-500/10 text-cyan-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        @if(request()->is('real-time-prediction'))
                            <div class="absolute left-0 w-1 h-6 bg-cyan-400 rounded-r-full shadow-[0_0_10px_rgba(34,211,238,0.8)]"></div>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->is('real-time-prediction') ? 'text-cyan-400' : 'group-hover:text-cyan-400 transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <span class="font-bold text-sm">Real-Time Prediction</span>
                    </a>
                </div>
            </div>

        </nav>

        <!-- Logout Section -->
        <div class="mt-auto pt-10">
            <a href="{{ route('logout') }}"
               class="flex items-center gap-3 p-3 rounded-xl border border-red-500/30 bg-red-500/5 text-red-400 hover:bg-red-500/10 hover:border-red-500/60 transition-all duration-300 group">
                <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center group-hover:bg-red-500/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/>
                    </svg>
                </div>
                <span class="font-bold text-sm">System Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col max-h-screen overflow-hidden">

        <!-- Top Navbar Container -->
        <header class="h-20 px-8 flex items-center justify-between bg-slate-900/60 backdrop-blur-2xl border-b border-white/[0.05] relative z-50 overflow-hidden">
            <!-- Background Glow Decoration -->
            <div class="absolute top-0 left-1/4 w-64 h-full bg-cyan-500/5 blur-[80px]"></div>
            
            <!-- Left: System Status & Clock -->
            <div class="flex items-center gap-10 relative z-10">
                <div class="flex items-center gap-3 bg-white/[0.03] border border-white/5 px-4 py-2 rounded-2xl">
                    <div class="relative">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)]"></div>
                        <div class="absolute inset-0 w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping opacity-75"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest leading-none mb-1">System Online</span>
                        <span class="text-[9px] text-gray-500 font-bold uppercase tracking-tighter">Lat: 0.00ms / Proc: SVM_v1</span>
                    </div>
                </div>

                <div class="hidden xl:flex flex-col border-l border-white/10 pl-10">
                    <p id="systemTime" class="text-sm font-black text-cyan-100 tracking-wider">00:00:00</p>
                    <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.2em]">Local Station Time</p>
                </div>
            </div>

            <!-- Center: SVM Engine Status -->
            <div class="absolute left-1/2 -translate-x-1/2 flex items-center">
                <div class="px-6 py-1.5 rounded-full bg-gradient-to-r from-cyan-500/10 via-blue-500/10 to-cyan-500/10 border border-white/10 flex items-center gap-3 backdrop-blur-md shadow-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span class="text-[11px] font-black text-white uppercase tracking-[0.2em]">
                        SVM ENGINE: <span class="text-cyan-400">{{ Auth::user()->role === 'admin' ? 'ROOT_ACCESS' : 'RESTRICTED_VIEW' }}</span>
                    </span>
                    <div class="flex gap-1 ml-2">
                        <div class="w-1 h-1 rounded-full bg-cyan-400 animate-pulse"></div>
                        <div class="w-1 h-1 rounded-full bg-cyan-400 animate-pulse delay-75"></div>
                        <div class="w-1 h-1 rounded-full bg-cyan-400 animate-pulse delay-150"></div>
                    </div>
                </div>
            </div>

            <!-- Right: User Profile -->
            <div class="flex items-center gap-6 relative z-10">
                <div class="flex flex-col items-end">
                    <p class="text-sm font-black text-white tracking-tight">{{ Auth::user()->name }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'General User' }}</span>
                        <div class="w-1.5 h-1.5 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-cyan-500' : 'bg-blue-500' }}"></div>
                    </div>
                </div>
                
                <div class="relative group cursor-pointer">
                    <div class="absolute -inset-1.5 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-full blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                    <div class="relative w-12 h-12 rounded-full p-[2px] bg-gradient-to-br from-white/20 to-transparent">
                        <div class="w-full h-full rounded-full bg-slate-900 flex items-center justify-center overflow-hidden border border-white/10">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background={{ Auth::user()->role === 'admin' ? '0284c7' : '6366f1' }}&color=fff&bold=true" alt="User">
                        </div>
                    </div>
                    <!-- Active status indicator -->
                    <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-slate-900 rounded-full flex items-center justify-center border border-white/5">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.8)]"></div>
                    </div>
                </div>
            </div>

            <!-- Scanner Line Effect -->
            <div class="absolute bottom-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-cyan-400/30 to-transparent"></div>
        </header>

        <script>
            function updateClock() {
                const now = new Date();
                const clock = document.getElementById('systemTime');
                if (clock) {
                    clock.textContent = now.toLocaleTimeString('en-US', { hour12: false });
                }
            }
            setInterval(updateClock, 1000);
            updateClock();
        </script>

        <!-- Dynamic Content -->
        <main class="flex-1 overflow-y-auto p-8 futuristic-scroll">
            <div class="glass rounded-[2rem] p-8 shadow-2xl min-h-full relative overflow-hidden">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl -mr-48 -mt-48"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl -ml-48 -mb-48"></div>
                
                <div class="relative z-10">
                    @yield('content')
                </div>
            </div>
        </main>

    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
    
    <!-- Modal Content -->
    <div class="glass relative w-full max-w-md rounded-2xl border border-white/10 p-8 shadow-2xl">
        <!-- Decoration -->
        <div class="absolute -top-12 -left-12 w-24 h-24 bg-red-500/10 rounded-full blur-2xl"></div>
        
        <!-- Icon -->
        <div class="w-16 h-16 rounded-2xl bg-red-500/10 border border-red-500/30 flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        
        <h3 class="text-xl font-black text-center text-white uppercase tracking-tight mb-2" id="modalTitle">Konfirmasi Hapus</h3>
        <p class="text-gray-400 text-center text-sm mb-8" id="modalMessage">Apakah Anda yakin ingin menghapus semua data? Tindakan ini tidak dapat dibatalkan.</p>
        
        <div class="flex gap-4">
            <button onclick="closeConfirmModal()" class="flex-1 px-6 py-3 rounded-xl bg-slate-800 border border-white/5 font-bold text-gray-300 hover:bg-slate-700 transition-all">
                Batal
            </button>
            <button id="confirmBtn" class="flex-1 futuristic-btn-red !py-3">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
    let currentFormToSubmit = null;

    function showConfirmModal(formId, title, message) {
        currentFormToSubmit = document.getElementById(formId);
        if (title) document.getElementById('modalTitle').innerText = title;
        if (message) document.getElementById('modalMessage').innerText = message;
        
        const modal = document.getElementById('confirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Add animation class
        modal.querySelector('.glass').classList.add('animate-in');
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.querySelector('.glass').classList.remove('animate-in');
        currentFormToSubmit = null;
    }

    document.getElementById('confirmBtn').addEventListener('click', () => {
        if (currentFormToSubmit) {
            currentFormToSubmit.submit();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeConfirmModal();
    });
</script>

</body>
</html>