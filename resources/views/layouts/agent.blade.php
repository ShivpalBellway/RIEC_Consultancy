<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Agent Portal') — REIAC Global</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#3730a3',
                            600: '#1a2f5e',
                            700: '#142347',
                        },
                        gold: '#dca737',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-item { position: relative; transition: all 0.2s ease; }
        .sidebar-item.active { background: linear-gradient(90deg, #dca737 0%, #b8811f 100%); color: #ffffff; font-weight: 700; box-shadow: 0 8px 20px rgba(220, 167, 55, 0.22); }
        .sidebar-item.active::before { content: ''; position: absolute; left: 0; top: 12%; width: 4px; height: 76%; background: #fff; border-radius: 0 5px 5px 0; }
        .sidebar-item:not(.active):hover { background: rgba(255, 255, 255, 0.09); color: #ffffff; transform: translateX(3px); }
        .sidebar-item i { color: rgba(255,255,255,.58); transition: color .2s ease; }
        .sidebar-item:hover i, .sidebar-item.active i { color: #ffffff; }
        .agent-sidebar-scroll { scrollbar-width: thin; scrollbar-color: rgba(220,167,55,.45) transparent; }
        .agent-sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .agent-sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .agent-sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(220,167,55,.45); border-radius: 99px; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased min-h-screen flex flex-col">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-40 w-72 bg-primary-600 text-white transition-transform duration-300 ease-in-out md:static md:translate-x-0 flex flex-col justify-between shadow-xl">
            <div class="min-h-0 flex flex-col">
                <!-- Brand Header -->
                <div class="h-20 flex items-center justify-between px-6 border-b border-primary-500/30 shrink-0">
                    <a href="{{ route('agent.dashboard') }}" class="flex items-center gap-3">
                        @php($agentLogo = \App\Models\SiteSetting::query()->value('header_logo'))
                        <div class="w-11 h-11 rounded-xl bg-white flex items-center justify-center shadow-md overflow-hidden shrink-0">
                            @if($agentLogo)
                                <img src="{{ asset('storage/' . $agentLogo) }}" alt="REIAC logo" class="max-w-full max-h-full object-contain p-1">
                            @else
                                <span class="text-primary-600 font-extrabold text-xl">R</span>
                            @endif
                        </div>
                        <div>
                            <span class="font-extrabold text-xl tracking-tight text-white">REIAC</span>
                            <span class="text-[11px] text-gold block font-bold mt-0.5 tracking-[.16em] uppercase">Agent Portal</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = false" class="md:hidden text-gray-300 hover:text-white">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Agency Info Badge -->
                <div class="px-5 py-4 m-4 rounded-2xl bg-primary-700/60 border border-primary-500/30 backdrop-blur-sm shadow-inner shrink-0">
                    <div class="text-xs text-gray-300 uppercase tracking-wider font-semibold">Agency</div>
                    <div class="font-bold text-white text-sm truncate mt-0.5">{{ auth('agent')->user()->agency_name }}</div>
                    <div class="text-xs text-gold flex items-center gap-1.5 mt-1 font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Approved Agent
                    </div>
                </div>

                <!-- Nav Links -->
                <nav class="agent-sidebar-scroll px-4 py-3 space-y-1.5 overflow-y-auto">
                    <a href="{{ route('agent.dashboard') }}" class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-200 {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('agent.students.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-200 {{ request()->routeIs('agent.students.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-graduate w-5 text-center"></i>
                        <span>Student Management</span>
                    </a>

                    <a href="{{ route('agent.students.create') }}" class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-200 {{ request()->routeIs('agent.students.create') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-plus w-5 text-center"></i>
                        <span>Add New Student</span>
                    </a>
                </nav>
            </div>

            <!-- Footer / Logout -->
            <div class="p-4 border-t border-primary-500/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-full bg-gold/20 border border-gold/40 flex items-center justify-center text-gold font-bold text-sm">
                            {{ strtoupper(substr(auth('agent')->user()->name, 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <div class="text-sm font-semibold text-white truncate">{{ auth('agent')->user()->name }}</div>
                            <div class="text-xs text-gray-300 truncate">{{ auth('agent')->user()->email }}</div>
                        </div>
                    </div>
                    <form action="{{ route('agent.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-red-400 p-2 rounded-lg hover:bg-primary-700/50 transition-colors" title="Logout">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50">
            <!-- Top Navigation Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-8 shadow-sm">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-lg font-bold text-gray-800 tracking-tight">@yield('page_title', 'Agent Dashboard')</h1>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Date badge -->
                    <span class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-100 text-xs font-semibold text-gray-600">
                        <i class="fa-regular fa-calendar text-primary-600"></i>
                        {{ date('D, d M Y') }}
                    </span>

                    <!-- Quick Student Add Button -->
                    <a href="{{ route('agent.students.create') }}" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-xs md:text-sm font-semibold px-3.5 py-2 rounded-lg transition-all shadow-sm">
                        <i class="fa-solid fa-plus"></i>
                        <span>New Student</span>
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                <!-- Alerts & Flashes -->
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500 text-lg"></i>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
