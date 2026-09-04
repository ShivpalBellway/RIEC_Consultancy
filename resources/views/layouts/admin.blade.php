<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — REIAC</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a2f5e',
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

        .sidebar-link {
            transition: all .2s;
        }

        .sidebar-link.active {
            background: #dca737;
            color: #fff;
        }

        .sidebar-link:not(.active):hover {
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        .admin-toast {
            animation: adminToastIn .24s ease-out;
        }

        .admin-toast.is-leaving {
            animation: adminToastOut .2s ease-in forwards;
        }

        @keyframes adminToastIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes adminToastOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateY(-8px) scale(.98);
            }
        }

        /* Sidebar Styles */
        .sidebar-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 16px;
            border-radius: 14px;
            color: rgba(255, 255, 255, .68);
            font-size: 14px;
            font-weight: 600;
            transition: all .25s ease;
            overflow: hidden;
            text-decoration: none;
            cursor: pointer;
            width: 100%;
            background: transparent;
            border: none;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            transition: .25s;
            color: rgba(255, 255, 255, .5);
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .06);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-link:hover i {
            color: #dca737;
        }

        .sidebar-link.active {
            color: #fff;
            background: linear-gradient(80deg, #dca737 0%, #dca737 22%, #0c0c0c 100%);
            box-shadow: 0 10px 25px rgba(220, 167, 55, .22);
        }

        .sidebar-link.active i {
            color: #fff !important;
        }

        .sidebar-link.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 10%;
            width: 4px;
            height: 80%;
            background: #fff;
            border-radius: 10px;
        }

        .sidebar-link.active::after {
            content: "";
            position: absolute;
            right: -25px;
            top: 50%;
            transform: translateY(-50%);
            width: 70px;
            height: 70px;
            background: radial-gradient(circle, rgba(255, 255, 255, .14), transparent 70%);
        }

        /* Submenu items indentation */
        #website-manage-sub .sidebar-link {
            padding-left: 32px;
            font-size: 13px;
        }

        #website-manage-sub .sidebar-link i {
            width: 18px;
            font-size: 14px;
        }

        /* Scrollbar styling */
        nav::-webkit-scrollbar {
            width: 4px;
        }

        nav::-webkit-scrollbar-track {
            background: transparent;
        }

        nav::-webkit-scrollbar-thumb {
            background: rgba(220, 167, 55, 0.3);
            border-radius: 10px;
        }

        nav::-webkit-scrollbar-thumb:hover {
            background: rgba(220, 167, 55, 0.5);
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans bg-[#f5f7fb] antialiased">

    @hasSection('dashboard-shell')
    <div class="min-h-screen">
        <header class="bg-white border-b border-gray-100 px-5 lg:px-8 py-4 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex items-center gap-5 min-w-[240px]">
                    <button type="button" class="w-10 h-10 rounded-xl hover:bg-gray-50 text-[#15213d] flex items-center justify-center">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-black text-[#15213d] leading-none">Dashboard</h1>
                        <p class="text-xs text-gray-500 font-semibold mt-1">Welcome back, {{ session('admin_name', 'Admin') }}</p>
                    </div>
                </div>

                <div class="flex-1 max-w-2xl lg:mx-auto">
                    <label class="relative block">
                        <input type="search"
                            placeholder="Search applications, users, programs..."
                            class="w-full h-12 rounded-xl border border-gray-200 bg-gray-50 px-5 pr-12 text-sm font-semibold text-gray-600 outline-none focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-50">
                        <i class="fa-solid fa-magnifying-glass absolute right-5 top-1/2 -translate-y-1/2 text-[#15213d]"></i>
                    </label>
                </div>

                <div class="flex items-center gap-3 lg:justify-end">
                    <button class="relative w-11 h-11 rounded-full bg-gray-50 text-[#15213d] hover:bg-gray-100">
                        <i class="fa-regular fa-bell"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black rounded-full px-1.5">8</span>
                    </button>
                    <button class="relative w-11 h-11 rounded-full bg-gray-50 text-[#15213d] hover:bg-gray-100">
                        <i class="fa-regular fa-envelope"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black rounded-full px-1.5">12</span>
                    </button>
                    <button class="w-11 h-11 rounded-full bg-gray-50 text-[#15213d] hover:bg-gray-100">
                        <i class="fa-regular fa-moon"></i>
                    </button>
                    <div class="flex items-center gap-3 pl-3">
                        <div class="w-12 h-12 rounded-full bg-amber-100 border-4 border-amber-200 flex items-center justify-center text-[#15213d] font-black">
                            {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
                        </div>
                        <div class="hidden sm:block">
                            <div class="text-sm font-black text-[#15213d]">{{ session('admin_name', 'Admin') }}</div>
                            <div class="text-xs text-gray-500 font-semibold">Super Admin</div>
                        </div>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-8 h-8 rounded-lg text-[#15213d] hover:bg-gray-50" title="Logout">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-5 lg:p-6">
            @yield('content')
        </main>
    </div>
    @else
    <div class="flex h-screen overflow-hidden">

        {{-- ═══ SIDEBAR ═══ --}}
        <aside
            class="w-64 flex flex-col flex-shrink-0 shadow-2xl border-r border-white/5
             bg-gradient-to-b from-[#142447] via-[#182d59] to-[#0d1d3b]"
            id="sidebar">

            {{-- Logo --}}
            <div class="px-5 py-5 border-b border-white/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-[#dca737]/10 to-transparent"></div>
                <div class="relative flex justify-center items-center">
                    <img
                        src="{{ asset('storage/logo/adminlogo.png') }}"
                        alt="REIAC Logo"
                        class="h-16 w-[200px] object-cover transition duration-300 hover:scale-105">
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-1">

                {{-- MAIN MENU --}}
                <p class="text-[#dca737]/70 text-[10px] uppercase tracking-[0.22em] px-4 mb-3 font-bold">
                    Main Menu
                </p>

                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.applications.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice"></i>
                    <span>Student Applications</span>
                </a>

                <a href="{{ route('admin.students.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Registered Students</span>
                </a>

                <div class="my-4 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>

                {{-- AGENCY PARTNER NETWORK --}}
                <p class="text-[#dca737]/70 text-[10px] uppercase tracking-[0.22em] px-4 mb-3 font-bold">
                    Agency Partner Network
                </p>

                <a href="{{ route('admin.agents.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.agents.*') ? 'active' : '' }} flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-handshake-angle"></i>
                        <span>Agent Partners</span>
                    </div>
                    @php $pendingAgentCount = \App\Models\Agent::where('status', 'pending')->count(); @endphp
                    @if($pendingAgentCount > 0)
                    <span class="bg-amber-400 text-slate-900 text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm">{{ $pendingAgentCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.students.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }} flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-user-graduate"></i>
                        <span>Agent Students</span>
                    </div>
                    @php $totalStudentCount = \App\Models\Student::count(); @endphp
                    @if($totalStudentCount > 0)
                    <span class="bg-primary/40 text-white text-[10px] font-black px-2 py-0.5 rounded-full border border-white/20">{{ $totalStudentCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.document-removals.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.document-removals.*') ? 'active' : '' }} flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-trash-can-arrow-up"></i>
                        <span>Removal Requests</span>
                    </div>
                    @php $pendingRemovalCount = \App\Models\StudentDocument::where('removal_request_status', 'requested')->count(); @endphp
                    @if($pendingRemovalCount > 0)
                    <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm">{{ $pendingRemovalCount }}</span>
                    @endif
                </a>

                <div class="my-4 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>

                {{-- APPLICATION SETUP --}}
                <p class="text-[#dca737]/70 text-[10px] uppercase tracking-[0.22em] px-4 mb-3 font-bold">
                    Application Setup
                </p>

                <a href="{{ route('admin.programs.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.programs.index') || request()->routeIs('admin.programs.create') || request()->routeIs('admin.programs.edit') ? 'active' : '' }}">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Program Information</span>
                </a>

                <a href="{{ route('admin.eligibility-setup') }}"
                    class="sidebar-link {{ request()->routeIs('admin.eligibility-setup') || request()->routeIs('admin.programs.eligibility.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Eligibility Setup</span>
                </a>

                <a href="{{ route('admin.form-builder-setup') }}"
                    class="sidebar-link {{ request()->routeIs('admin.form-builder-setup') || request()->routeIs('admin.programs.form-builder.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>Information Form Builder</span>
                </a>

                <div class="my-4 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>

                {{-- WEBSITE MANAGE --}}
                <p class="text-[#dca737]/70 text-[10px] uppercase tracking-[0.22em] px-4 mb-3 font-bold">
                    Website Manage
                </p>

                @php
                $websiteOpen = request()->routeIs('admin.services.*') ||
                request()->routeIs('admin.features.*') ||
                request()->routeIs('admin.process-steps.*') ||
                request()->routeIs('admin.contact-settings.*') ||
                request()->routeIs('admin.stats.*') ||
                request()->routeIs('admin.success-stories.*') ||
                request()->routeIs('admin.partners.*') ||

                request()->routeIs('admin.site.manage') ||
                request()->routeIs('admin.faqs.*') ||
                request()->routeIs('admin.blogs.*')||
                request()->routeIs('admin.about.*') ||
                request()->routeIs('admin.legal-pages.*');
                @endphp

                <button id="website-manage-toggle" type="button" class="sidebar-link {{ $websiteOpen ? 'active' : '' }}" onclick="toggleWebsiteManage()">
                    <i class="fa-solid fa-sitemap"></i>
                    <span>Website Manage</span>
                    <i id="website-manage-caret" class="fa-solid fa-chevron-down ml-auto transition-transform duration-300" style="transform: {{ $websiteOpen ? 'rotate(180deg)' : 'rotate(0deg)' }};"></i>
                </button>

                <div id="website-manage-sub" class="pl-2 mt-2 space-y-1 {{ $websiteOpen ? '' : 'hidden' }}">
                    <a href="{{ route('admin.site.manage') }}" class="sidebar-link {{ request()->routeIs('admin.site.manage') ? 'active' : '' }}">
                        <i class="fa-solid fa-table-cells-large"></i>
                        <span>Overview</span>
                    </a>

                    <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-list-check"></i>
                        <span>Services</span>
                    </a>

                    <a href="{{ route('admin.features.index') }}" class="sidebar-link {{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-star"></i>
                        <span>Features</span>
                    </a>

                    <a href="{{ route('admin.process-steps.index') }}" class="sidebar-link {{ request()->routeIs('admin.process-steps.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-list-ol"></i>
                        <span>Process Steps</span>
                    </a>

                    <a href="{{ route('admin.stats.edit') }}" class="sidebar-link {{ request()->routeIs('admin.stats.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-bar"></i>
                        <span>Stats Section</span>
                    </a>

                    <a href="{{ route('admin.contact-settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.contact-settings.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-address-book"></i>
                        <span>Contact Settings</span>
                    </a>

                    <a href="{{ route('admin.legal-pages.edit') }}" class="sidebar-link {{ request()->routeIs('admin.legal-pages.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-scale-balanced"></i>
                        <span>Privacy & Terms</span>
                    </a>



                    <a href="{{ route('admin.success-stories.index') }}" class="sidebar-link {{ request()->routeIs('admin.success-stories.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-quote-left"></i>
                        <span>Success Stories</span>
                    </a>

                    <a href="{{ route('admin.partners.index') }}" class="sidebar-link {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-handshake"></i>
                        <span>Our Partners</span>
                    </a>

                    <a href="{{ route('admin.faqs.index') }}" class="sidebar-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-question"></i>
                        <span>Manage FAQs</span>
                    </a>
                    <a href="{{ route('admin.about.index') }}" class="sidebar-link {{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>About Us</span>
                    </a>

                    <a href="{{ route('admin.blogs.index') }}" class="sidebar-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>Manage Blogs</span>
                    </a>

                    <a href="{{ route('admin.site.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.site.settings.*') || request()->routeIs('admin.site.settings.edit') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Site Settings</span>
                    </a>
                </div>

                <div class="my-4 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>

                {{-- SYSTEM --}}
                <p class="text-[#dca737]/70 text-[10px] uppercase tracking-[0.22em] px-4 mb-3 font-bold">
                    System
                </p>

                <a href="{{ route('admin.contact-messages.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-envelope-open-text"></i>
                    <span>Contact Messages</span>
                </a>

                <a href="{{ url('/') }}" target="_blank" class="sidebar-link">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>View Website</span>
                </a>
                <a href="{{ route('admin.activity-logs.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Activity Logs</span>

                </a>
                {{-- Notice Sidebar Link - Same Style as Activity Logs --}}
                <a href="{{ route('admin.notices.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>Notices</span>
                    {{-- @php
        $noticeCount = \App\Models\Notice::where('status', 'published')
                                        ->whereDate('published_at', '<=', today())
                                        ->count();
    @endphp
    @if($noticeCount > 0)
        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $noticeCount }}</span>
                    @endif --}}
                </a>

            </nav>

            {{-- Admin Info --}}
            <div class="p-4 border-t border-white/10 bg-black/10 backdrop-blur-sm">
                <div class="rounded-2xl bg-white/5 border border-white/10 p-3 flex items-center gap-3">

                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#dca737] to-[#b88719]
                flex items-center justify-center text-[#142447] font-black shadow-lg">
                        {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-bold truncate">
                            {{ session('admin_name', 'Admin') }}
                        </div>
                        <div class="text-white/40 text-xs">
                            Administrator
                        </div>
                    </div>

                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-9 h-9 rounded-xl bg-white/5 hover:bg-red-500/20
                    text-white/50 hover:text-red-400 transition">
                            <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                        </button>
                    </form>

                </div>
            </div>
        </aside>

        {{-- ═══ MAIN AREA ═══ --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- Top Header --}}
            <header class="bg-white/90 backdrop-blur-xl border-b border-gray-200/80 px-6 py-3 flex items-center justify-between flex-shrink-0 shadow-sm relative overflow-hidden">

                {{-- Soft Premium Glow --}}
                <div class="absolute inset-y-0 left-0 w-72 bg-gradient-to-r from-primary/5 to-transparent pointer-events-none"></div>

                {{-- Left Title --}}
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary to-[#0d2148] flex items-center justify-center shadow-lg shadow-primary/20">
                            <i class="fa-solid fa-layer-group text-gold text-sm"></i>
                        </div>

                        <div>
                            <h1 class="text-[15px] font-extrabold text-gray-900 leading-none tracking-tight">
                                @yield('page-title', 'Dashboard')
                            </h1>

                            <div class="flex items-center gap-1 text-[11px] text-gray-400 mt-1.5 font-semibold">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition">Admin</a>
                                @yield('breadcrumb')
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side --}}
                <div class="relative z-10 flex items-center gap-3">

                    @yield('header-actions')

                    {{-- Premium Date Time --}}
                    <div class="hidden md:flex items-center gap-3 h-11 px-4 rounded-2xl bg-gradient-to-r from-gray-50 to-white border border-gray-200 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary to-[#0d2148] flex items-center justify-center shadow-md shadow-primary/15">
                            <i class="fa-regular fa-calendar-days text-gold text-xs"></i>
                        </div>

                        <div class="leading-none">
                            <div id="currentDate" class="text-[10px] uppercase tracking-[0.16em] font-extrabold text-gray-400 mb-1">
                                Loading Date
                            </div>

                            <div class="flex items-center gap-2">
                                <span id="currentTime" class="text-sm font-black text-primary tracking-wide">
                                    --:--:--
                                </span>

                                <span class="flex items-center gap-1 text-[9px] font-black text-emerald-600 uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Live
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <script>
                function updateAdminDateTime() {
                    const now = new Date();

                    const dateEl = document.getElementById('currentDate');
                    const timeEl = document.getElementById('currentTime');

                    if (!dateEl || !timeEl) return;

                    dateEl.innerText = now.toLocaleDateString('en-US', {
                        weekday: 'short',
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    timeEl.innerText = now.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    });
                }

                updateAdminDateTime();
                setInterval(updateAdminDateTime, 1000);
            </script>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @endif

    <div id="adminToastStack"
        class="fixed top-5 right-5 z-[9999] w-[calc(100%-2.5rem)] max-w-sm space-y-3 pointer-events-none"
        data-success="{{ session('success') }}"
        data-error="{{ session('error') }}"
        data-validation="{{ $errors->any() ? $errors->first() : '' }}">
    </div>

    @stack('scripts')
    <script>
        function toggleWebsiteManage() {
            var sub = document.getElementById('website-manage-sub');
            var btn = document.getElementById('website-manage-toggle');
            var caret = document.getElementById('website-manage-caret');
            if (!sub || !btn) return;

            if (sub.classList.contains('hidden')) {
                sub.classList.remove('hidden');
                btn.classList.add('active');
                if (caret) caret.style.transform = 'rotate(180deg)';
            } else {
                sub.classList.add('hidden');
                btn.classList.remove('active');
                if (caret) caret.style.transform = 'rotate(0deg)';
            }
        }

        window.adminToast = function(type, message) {
            var stack = document.getElementById('adminToastStack');
            if (!stack || !message) {
                return;
            }

            var isSuccess = type === 'success';
            var toast = document.createElement('div');
            toast.className = 'admin-toast pointer-events-auto rounded-2xl border bg-white p-4 shadow-2xl flex gap-3 ' +
                (isSuccess ? 'border-emerald-100' : 'border-red-100');

            toast.innerHTML =
                '<div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 ' +
                (isSuccess ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600') +
                '">' +
                '<i class="fa-solid ' + (isSuccess ? 'fa-circle-check' : 'fa-circle-exclamation') + '"></i>' +
                '</div>' +
                '<div class="min-w-0 flex-1">' +
                '<div class="text-sm font-extrabold text-gray-900">' + (isSuccess ? 'Success' : 'Error') + '</div>' +
                '<div class="admin-toast-message mt-1 text-sm text-gray-600 leading-5"></div>' +
                '</div>' +
                '<button type="button" class="w-8 h-8 rounded-lg text-gray-400 hover:bg-gray-50 hover:text-gray-700 shrink-0">' +
                '<i class="fa-solid fa-xmark"></i>' +
                '</button>';

            toast.querySelector('.admin-toast-message').textContent = message;
            stack.appendChild(toast);

            var closeToast = function() {
                toast.classList.add('is-leaving');
                setTimeout(function() {
                    toast.remove();
                }, 220);
            };

            toast.querySelector('button').addEventListener('click', closeToast);
            setTimeout(closeToast, isSuccess ? 4200 : 6500);
        };

        var stackEl = document.getElementById('adminToastStack');
        if (stackEl) {
            var successMsg = stackEl.dataset.success;
            var errorMsg = stackEl.dataset.error;
            var validationMsg = stackEl.dataset.validation;

            if (successMsg) window.adminToast('success', successMsg);
            if (errorMsg) window.adminToast('error', errorMsg);
            if (validationMsg) window.adminToast('error', validationMsg);
        }
    </script>
</body>

</html>
