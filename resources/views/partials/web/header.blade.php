{{-- ===================== TOP BAR ===================== --}}
<div class="bg-primary text-white text-xs py-2 px-6 hidden md:flex justify-between items-center">
    <div class="flex gap-6">
        <span class="flex items-center gap-1.5">
            <i class="fa-solid fa-phone text-gold"></i>
            {{ $siteSetting->contact_phone ?? '+82 10-6552-8264' }}
        </span>
        <span class="flex items-center gap-1.5">
            <i class="fa-solid fa-envelope text-gold"></i>
            {{ $siteSetting->contact_email ?? 'info@reiac.com' }}
        </span>
        <span class="flex items-center gap-1.5">
            <i class="fa-regular fa-clock text-gold"></i>
            {{ $siteSetting->contact_hours ?? 'Mon - Sun: 9:00 AM - 8:00 PM (KST)' }}
        </span>
    </div>

    <!-- <div class="flex gap-3 text-sm">
        <a href="{{ $siteSetting->social_instagram ?? '#' }}" {{ $siteSetting && $siteSetting->social_instagram ? 'target=_blank' : '' }} class="hover:text-gold transition">
            <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="{{ $siteSetting->social_facebook ?? '#' }}" {{ $siteSetting && $siteSetting->social_facebook ? 'target=_blank' : '' }} class="hover:text-gold transition">
            <i class="fa-brands fa-facebook-f"></i>
        </a>
        <a href="{{ $siteSetting->social_linkedin ?? '#' }}" {{ $siteSetting && $siteSetting->social_linkedin ? 'target=_blank' : '' }} class="hover:text-gold transition">
            <i class="fa-brands fa-linkedin-in"></i>
        </a>

    </div>
    <div class="language-switcher">
        <button type="button" class="language-btn" onclick="changeLanguage('en', this)">
            EN
        </button>

        <button type="button" class="language-btn" onclick="changeLanguage('ko', this)">
            한국어
        </button>
    </div> -->
<div class="flex items-center justify-between gap-4">

    <!-- Social Icons -->
    <div class="flex items-center gap-3 text-sm">
        <a href="{{ $siteSetting->social_instagram ?? '#' }}"
            {{ $siteSetting && $siteSetting->social_instagram ? 'target=_blank' : '' }}
            class="hover:text-gold transition">
            <i class="fa-brands fa-instagram"></i>
        </a>

        <a href="{{ $siteSetting->social_facebook ?? '#' }}"
            {{ $siteSetting && $siteSetting->social_facebook ? 'target=_blank' : '' }}
            class="hover:text-gold transition">
            <i class="fa-brands fa-facebook-f"></i>
        </a>

        <a href="{{ $siteSetting->social_linkedin ?? '#' }}"
            {{ $siteSetting && $siteSetting->social_linkedin ? 'target=_blank' : '' }}
            class="hover:text-gold transition">
            <i class="fa-brands fa-linkedin-in"></i>
        </a>
    </div>

    <!-- Language Switcher -->
    <div class="language-switcher flex items-center gap-2">
        <button type="button" class="language-btn" onclick="changeLanguage('en', this)">
            EN
        </button>

        <button type="button" class="language-btn" onclick="changeLanguage('ko', this)">
            한국어
        </button>
    </div>

</div>


    <style>
        #google_translate_element {
            position: absolute;
            left: -9999px;
            top: -9999px;
            height: 0;
            overflow: hidden;
        }

        .goog-te-banner-frame.skiptranslate,
        iframe.goog-te-banner-frame,
        iframe.skiptranslate {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
        }

        body {
            top: 0 !important;
        }

        .skiptranslate,
        .goog-te-gadget,
        .goog-te-gadget span,
        .goog-logo-link,
        #goog-gt-tt,
        .goog-tooltip,
        .goog-tooltip:hover {
            display: none !important;
        }

        .goog-text-highlight {
            background: transparent !important;
            box-shadow: none !important;
        }

        .language-switcher {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .language-btn {
            border: 1px solid #c89b2a;
            background: transparent;
            color: #c89b2a;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .language-btn:hover,
        .language-btn.active {
            background: #c89b2a;
            color: #fff;
        }
    </style>

    <div id="google_translate_element"></div>


    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,ko',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function triggerTranslate(lang) {
            const combo = document.querySelector('.goog-te-combo');

            if (!combo) {
                return false;
            }

            combo.value = lang;

            combo.dispatchEvent(new Event('change', {
                bubbles: true
            }));
            combo.dispatchEvent(new Event('input', {
                bubbles: true
            }));

            if (typeof combo.onchange === 'function') {
                combo.onchange();
            }

            return true;
        }

        function changeLanguage(lang, btn) {
            document.querySelectorAll('.language-btn').forEach(function(button) {
                button.classList.remove('active');
            });

            if (btn) {
                btn.classList.add('active');
            }

            let count = 0;

            const timer = setInterval(function() {
                const done = triggerTranslate(lang);
                hideGoogleBar();

                count++;

                if (done || count > 20) {
                    clearInterval(timer);

                    setTimeout(function() {
                        triggerTranslate(lang);
                        hideGoogleBar();
                    }, 500);

                    setTimeout(function() {
                        triggerTranslate(lang);
                        hideGoogleBar();
                    }, 1000);
                }
            }, 200);
        }

        function hideGoogleBar() {
            document.body.style.top = '0px';

            document.querySelectorAll(
                '.goog-te-banner-frame, iframe.goog-te-banner-frame, iframe.skiptranslate'
            ).forEach(function(el) {
                el.style.display = 'none';
                el.style.visibility = 'hidden';
                el.style.height = '0';
            });
        }

        setInterval(hideGoogleBar, 300);
    </script>

    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</div>



@php
$activeClass = 'text-gold border-b-2 border-gold pb-1.5 font-semibold';
$normalClass = 'hover:text-gold border-b-2 border-transparent pb-1.5 transition-all duration-200';
@endphp

{{-- ===================== MAIN HEADER ===================== --}}
<header class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">

    @php
    $siteSetting = \App\Models\SiteSetting::first();
    @endphp

    <div class="max-w-7xl mx-auto px-4 lg:px-6">
        <div class="flex justify-between items-center h-[82px]">

            <a href="/" class="flex items-center">
                @if($siteSetting && $siteSetting->header_logo)
                <img src="{{ asset('storage/' . $siteSetting->header_logo) }}"
                    alt="REIAC Logo"
                    class="h-14 w-[150px] object-cover">
                @else
                <img src="{{ asset('storage/logo/logo-reiac-nobg.png') }}"
                    alt="REIAC Logo"
                    class="h-14 w-[150px] object-contain">
                @endif
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center gap-7 text-[15px] font-medium text-gray-700">

                <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? $activeClass : $normalClass }}">
                    Home
                </a>

                <a href="{{ route('aboutUs') }}"
                    class="{{ request()->routeIs('aboutUs') ? $activeClass : $normalClass }}">
                    About Us
                </a>

                {{-- Services Dropdown --}}
                <div class="relative group">
                    <button type="button"
                        class="{{ request()->routeIs('services.*') ? $activeClass : $normalClass }} inline-flex items-center gap-1">
                        Services
                        <i class="fa-solid fa-chevron-down text-[10px] mt-[2px] group-hover:rotate-180 transition"></i>
                    </button>

                    <div class="absolute left-0 top-full mt-4 w-56 rounded-xl bg-white border border-gray-100 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 overflow-hidden">
                        @foreach($headerServices as $service)
                        <a href="{{ route('services.show', $service->slug) }}"
                            class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gold transition">
                            {{ $service->title }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('apply.index') }}"
                    class="{{ request()->routeIs('apply.*') ? $activeClass : $normalClass }}">
                    For Students
                </a>

                {{-- ===================== NOTICE TAB ===================== --}}
                <a href="{{ route('frontend.notices.index') }}"
                    class="{{ request()->routeIs('frontend.notices.*') ? $activeClass : $normalClass }} relative inline-flex items-center gap-1">
                    <i class="fa-solid fa-bullhorn text-sm"></i>
                    Notices
                    @php
                    $noticeCount = \App\Models\Notice::where('status', 'published')
                    ->whereDate('published_at', '<=', today())
                        ->count();
                        @endphp
                        @if($noticeCount > 0)
                        <span class="absolute -top-2 -right-5 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                            {{ $noticeCount }}
                        </span>
                        @endif
                </a>
               <a href="{{ route('visa.calculator') }}" class="{{ request()->routeIs('visa.calculator') ? $activeClass : $normalClass }}">
                    F-2-7 Points Calculator
                </a>

                <a href="{{ route('contact') }}"
                    class="{{ request()->routeIs('contact') ? $activeClass : $normalClass }}">
                    Contact Us
                </a>

            </nav>

            {{-- Right Side CTA --}}
            <div class="hidden lg:flex items-center gap-4">

                @auth
                <a href="{{ route('student.dashboard') }}"
                    class="inline-flex items-center gap-2 border border-primary/20 text-primary px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-gauge-high text-xs"></i>
                    Dashboard
                </a>
                <form action="{{ route('student.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gold text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow hover:bg-yellow-600 hover:-translate-y-0.5 transition-all duration-300">
                        Logout
                    </button>
                </form>
                @else
                <a href="{{ route('student.login') }}"
                    class="inline-flex items-center gap-2 border border-primary/20 text-primary px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary hover:text-white transition-all duration-300">
                    Login
                </a>
                <a href="{{ route('student.register') }}"
                    class="inline-flex items-center gap-2 bg-gold text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow hover:bg-yellow-600 hover:-translate-y-0.5 transition-all duration-300">
                    Register
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
                @endauth

            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobileMenuBtn"
                class="lg:hidden text-primary text-2xl p-2 rounded-lg hover:bg-gray-100 transition">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>
    </div>

    {{-- ===================== MOBILE MENU ===================== --}}
    <div id="mobileMenu"
        class="hidden lg:hidden bg-white border-t border-gray-100 shadow-lg">

        <div class="px-5 py-4 space-y-1 text-sm font-medium">

            <a href="{{ route('home') }}"
                class="block rounded-lg px-3 py-3 {{ request()->routeIs('home') ? 'bg-gold/10 text-gold font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gold' }}">
                Home
            </a>

            <a href="{{ route('aboutUs') }}"
                class="block rounded-lg px-3 py-3 {{ request()->routeIs('aboutUs') ? 'bg-gold/10 text-gold font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gold' }}">
                About Us
            </a>

            {{-- Mobile Services --}}
            <div class="block rounded-lg px-3 py-2 text-gray-700 font-medium">
                <div class="flex items-center justify-between cursor-pointer" onclick="toggleMobileServices()">
                    <span>Services</span>
                    <i class="fa-solid fa-chevron-down text-xs transition" id="mobileServicesIcon"></i>
                </div>
                <div id="mobileServicesMenu" class="hidden mt-1 space-y-1 pl-4">
                    @foreach($headerServices as $service)
                    <a href="{{ route('services.show', $service->slug) }}"
                        class="block rounded-lg px-3 py-2 {{ request()->is('services/'.$service->slug) ? 'bg-gold/10 text-gold font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gold' }}">
                        {{ $service->title }}
                    </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('apply.index') }}"
                class="block rounded-lg px-3 py-3 {{ request()->routeIs('apply.*') ? 'bg-gold/10 text-gold font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gold' }}">
                For Students
            </a>

            {{-- ===================== MOBILE NOTICE TAB ===================== --}}
            <a href="{{ route('frontend.notices.index') }}"
                class="block rounded-lg px-3 py-3 {{ request()->routeIs('frontend.notices.*') ? 'bg-gold/10 text-gold font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gold' }} inline-flex items-center gap-2">
                <i class="fa-solid fa-bullhorn"></i>
                Notices
                @php
                $noticeCount = \App\Models\Notice::where('status', 'published')
                ->whereDate('published_at', '<=', today())
                    ->count();
                    @endphp
                    @if($noticeCount > 0)
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                        {{ $noticeCount }}
                    </span>
                    @endif
            </a>

            <a href="{{ route('visa.calculator') }}"
                class="block rounded-lg px-3 py-3 {{ request()->routeIs('visa.calculator') ? 'bg-gold/10 text-gold font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gold' }}">
                  F-2-7 Points Calculator
            </a>

            <a href="{{ route('contact') }}"
                class="block rounded-lg px-3 py-3 {{ request()->routeIs('contact') ? 'bg-gold/10 text-gold font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gold' }}">
                Contact Us
            </a>

            <div class="pt-3">
                @auth
                <a href="{{ route('student.dashboard') }}"
                    class="block w-full text-center border border-primary/20 text-primary px-4 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition">
                    Dashboard
                </a>
                <form action="{{ route('student.logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="block w-full text-center bg-gold text-white px-4 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition">
                        Logout
                    </button>
                </form>
                @else
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('student.login') }}"
                        class="block w-full text-center border border-primary/20 text-primary px-4 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition">
                        Login
                    </a>
                    <a href="{{ route('student.register') }}"
                        class="block w-full text-center bg-gold text-white px-4 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition">
                        Register
                    </a>
                </div>
                @endauth
            </div>

        </div>
    </div>

</header>

{{-- ===================== MOBILE MENU SCRIPT ===================== --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mobile Menu Toggle
        const btn = document.getElementById("mobileMenuBtn");
        const menu = document.getElementById("mobileMenu");

        btn.addEventListener("click", function() {
            menu.classList.toggle("hidden");

            const icon = btn.querySelector("i");
            if (menu.classList.contains("hidden")) {
                icon.classList.remove("fa-xmark");
                icon.classList.add("fa-bars");
            } else {
                icon.classList.remove("fa-bars");
                icon.classList.add("fa-xmark");
            }
        });

        // Mobile Services Toggle
        window.toggleMobileServices = function() {
            const menu = document.getElementById("mobileServicesMenu");
            const icon = document.getElementById("mobileServicesIcon");
            menu.classList.toggle("hidden");
            icon.classList.toggle("rotate-180");
        };
    });
</script>
