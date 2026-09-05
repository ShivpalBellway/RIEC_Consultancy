@extends('layouts.app')

@section('title', 'REIAC - Your Global Education Partner')

@section('content')

{{-- ===== HERO SECTION ===== --}}
@php
    $heroImage   = $siteSetting && $siteSetting->hero_image
                    ? asset('storage/' . $siteSetting->hero_image)
                    : asset('storage/hero.png');
    $heroBadge      = $siteSetting->hero_badge            ?? 'REIAC CONSULTING';
    $heroLine1      = $siteSetting->hero_heading_line1    ?? 'Your Global Education';
    $heroLine2      = $siteSetting->hero_heading_line2    ?? 'Partner for a';
    $heroHighlight  = $siteSetting->hero_heading_highlight ?? 'Better Tomorrow';
    $heroSubtext    = $siteSetting->hero_subtext          ?? 'We guide students to world-class universities and help institutions build stronger global partnerships.';
    $heroBtn1Text   = 'For Students';
    $heroBtn2Text   = 'For Institutions';
    $heroBtn2Url    = route('institution');
@endphp

<section class="relative min-h-[620px] lg:h-[620px] overflow-visible bg-[#061d43]">
    <img src="{{ $heroImage }}" alt="Hero Banner"
        class="absolute inset-0 w-full h-full object-cover object-center lg:object-right">

    <div class="absolute inset-0 bg-gradient-to-r from-[#061d43]/95 via-[#061d43]/65 to-[#061d43]/20"></div>

    <div class="relative z-10 max-w-7xl mx-auto min-h-[620px] px-4 sm:px-6 md:px-10 flex items-center py-20">
        <div class="max-w-[650px]">

            <p class="text-[#dca737] text-xs sm:text-sm font-bold uppercase tracking-[0.2em] sm:tracking-[0.25em] mb-4 sm:mb-5">
                {{ $heroBadge }}
            </p>

            <h1 class="text-white text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-[1.12] mb-5 sm:mb-6">
                {{ $heroLine1 }} <br>
                {{ $heroLine2 }} <br>
                <span class="text-[#dca737]">{{ $heroHighlight }}</span>
            </h1>

            <p class="text-white/90 text-sm sm:text-base md:text-lg max-w-xl leading-7 sm:leading-8 mb-8 sm:mb-9">
                {{ $heroSubtext }}
            </p>

            <div class="flex flex-col sm:flex-row flex-wrap gap-4">
                <a href="{{ route('apply.index') }}"
                    class="text-center bg-[#dca737] text-[#061d43] hover:bg-[#c8942d] px-7 sm:px-8 py-4 rounded-md font-bold transition shadow-lg">
                    {{ $heroBtn1Text }} →
                </a>
                <a href="{{ $heroBtn2Url }}"
                    class="text-center border border-[#dca737] text-white px-7 sm:px-8 py-4 rounded-md font-bold hover:bg-white hover:text-[#061d43] transition">
                    {{ $heroBtn2Text }} →
                </a>
                <a href="{{ route('agent.login') }}"
                    class="text-center bg-white text-[#061d43] hover:bg-[#dca737] px-7 sm:px-8 py-4 rounded-md font-bold transition shadow-lg inline-flex items-center justify-center gap-2">
                    <i class="fa-solid fa-handshake-angle"></i>
                    Become an Agent
                </a>
            </div>

        </div>
    </div>

    {{-- Floating Feature Card --}}
    <div class="absolute left-1/2 bottom-0 translate-y-[55%] lg:translate-y-[45%] -translate-x-1/2 w-full px-4 sm:px-6 z-20">
        <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-2xl px-4 sm:px-6 py-6 sm:py-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">

            @foreach($features as $feature)
            <div class="flex items-center gap-4 lg:border-r last:border-r-0 border-gray-200 px-2 sm:px-3">
                <div class="w-11 h-11 sm:w-12 sm:h-12 flex shrink-0 items-center justify-center text-2xl sm:text-3xl text-[#061d43]">
                    <i class="{{ $feature->icon }}"></i>
                </div>

                <div class="flex flex-col">
                    <h3 class="font-bold text-[#061d43] leading-6 text-sm sm:text-base">
                        {{ $feature->title }}
                    </h3>

                    <span class="text-gray-500 text-xs sm:text-sm mt-1 leading-5">
                        {{ $feature->subtitle }}
                    </span>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>

{{-- Gap for floating feature card --}}
<div class="mt-72 sm:mt-40 lg:mt-28"></div>


{{-- ===== STATS SECTION ===== --}}
@php
    $statsBadge     = $siteSetting->stats_badge             ?? 'WHY CHOOSE REIAC?';
    $statsLine1     = $siteSetting->stats_heading_line1     ?? 'Trusted Guidance.';
    $statsLine2     = $siteSetting->stats_heading_line2     ?? 'Proven';
    $statsHighlight = $siteSetting->stats_heading_highlight ?? 'Results.';
    $statsSubtext   = $siteSetting->stats_subtext           ?? 'We are committed to providing transparent, reliable and result-oriented services to help students and institutions achieve their goals.';
    $statCountries  = $siteSetting->stat_countries          ?? '15+';
    $statSatisfaction = $siteSetting->stat_satisfaction     ?? '98%';

    $statsCards = [
        ['icon' => 'fa-solid fa-shield-halved',      'number' => ($statAdmissions > 0 ? $statAdmissions . '+' : '500+'), 'title' => 'Successful',  'subtitle' => 'Admissions'],
        ['icon' => 'fa-solid fa-building-columns',   'number' => ($statPartners > 0   ? $statPartners . '+'   : '100+'), 'title' => 'Partner',     'subtitle' => 'Institutions'],
        ['icon' => 'fa-solid fa-globe',              'number' => $statCountries,                                         'title' => 'Countries',   'subtitle' => 'Covered'],
        ['icon' => 'fa-solid fa-users',              'number' => $statSatisfaction,                                      'title' => 'Student',     'subtitle' => 'Satisfaction'],
    ];
@endphp

<section class="bg-white py-12 sm:py-16 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-[1fr_1.8fr] gap-10 lg:gap-14 items-center">

        <div>
            <p class="text-gold text-xs font-extrabold uppercase tracking-[0.18em] mb-4">
                {{ $statsBadge }}
            </p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary leading-[1.15] mb-5 sm:mb-6">
                {{ $statsLine1 }}<br>
                {{ $statsLine2 }} <span class="text-gold">{{ $statsHighlight }}</span>
            </h2>
            <p class="text-gray-600 text-sm sm:text-base leading-7 max-w-md mb-7 sm:mb-8">
                {{ $statsSubtext }}
            </p>
            <a href="{{ route('aboutUs') }}"
                class="inline-flex items-center gap-3 bg-primary text-white px-7 py-4 rounded-md text-sm font-bold hover:bg-blue-900 transition shadow">
                Learn More About Us
                <span class="text-lg leading-none">→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-4 gap-5 sm:gap-7">
            @foreach($statsCards as $stat)
            <div class="bg-white border border-gray-200 rounded-xl px-5 sm:px-6 py-7 sm:py-8 text-center shadow-sm hover:shadow-md transition">
                <div class="text-primary text-4xl sm:text-5xl mb-5 sm:mb-6">
                    <i class="{{ $stat['icon'] }}"></i>
                </div>
                <div class="text-3xl sm:text-4xl font-bold text-gold mb-3 sm:mb-4">
                    {{ $stat['number'] }}
                </div>
                <div class="text-primary text-sm sm:text-base font-extrabold leading-6">
                    {{ $stat['title'] }}<br>{{ $stat['subtitle'] }}
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ===== SERVICES ===== --}}
<section class="py-12 sm:py-16 px-4 sm:px-6 bg-white">
    <div class="max-w-7xl mx-auto">

        <p class="text-gold text-xs font-extrabold uppercase tracking-[0.18em] text-center mb-3">
            OUR SERVICES
        </p>

        <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-center text-primary mb-10 sm:mb-12 leading-tight">
            Comprehensive <span class="text-gold">Solutions</span> for Students & Institutions
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-7">
            @foreach($services as $service)

            <div class="bg-white rounded-xl overflow-hidden shadow-md border border-gray-100 hover:shadow-xl transition duration-300 group">

                <div class="h-44 overflow-hidden bg-gray-100">
                    @if($service->image)
                    <img src="{{ Str::startsWith($service->image, 'http') ? $service->image : asset('storage/' . $service->image) }}"
                        alt="{{ $service->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#1a2f5e]/10 to-[#dca737]/10">
                        <i class="{{ $service->icon ?: 'fa-solid fa-concierge-bell' }} text-[#1a2f5e]/30 text-5xl"></i>
                    </div>
                    @endif
                </div>

                <div class="relative px-6 sm:px-7 pt-12 pb-8">

                    <div class="absolute -top-9 left-6 sm:left-7 w-[72px] h-[72px] sm:w-[76px] sm:h-[76px] rounded-full bg-primary flex items-center justify-center shadow-lg border-4 border-white">
                        <i class="{{ $service->icon }} text-white text-2xl sm:text-3xl"></i>
                    </div>

                    <h3 class="text-lg sm:text-xl font-extrabold text-primary mb-4 leading-tight">
                        {{ $service->title }}
                    </h3>

                    <p class="text-gray-600 text-sm sm:text-base leading-7 mb-6">
                        {{ $service->excerpt }}
                    </p>

                    <a href="{{ route('services.show', $service->slug) }}" class="inline-flex items-center gap-2 text-primary text-sm font-extrabold hover:text-gold transition">
                        Learn More
                        <span class="text-gold text-lg">→</span>
                    </a>

                </div>
            </div>

            @endforeach
        </div>
    </div>
</section>


{{-- ===== PROCESS SECTION ===== --}}
<section class="relative py-12 sm:py-16 px-4 sm:px-6 bg-primary overflow-hidden">

    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1800&q=80"
            class="w-full h-full object-cover" alt="">
    </div>

    <div class="relative max-w-7xl mx-auto">

        <div class="text-center mb-10 sm:mb-8">
            <p class="text-gold text-xs font-extrabold uppercase tracking-[0.2em] mb-3">
                OUR PROCESS
            </p>

            <h2 class="text-3xl sm:text-4xl md:text-4xl font-bold text-white">
                Simple Steps to Your
                <span class="text-gold">Success</span>
            </h2>
        </div>

        <div class="relative">

            <div class="hidden lg:block absolute top-10 left-[12%] right-[12%] border-t-2 border-dashed border-gray-300/60"></div>

            <div class="hidden lg:block absolute top-[34px] left-1/4 w-4 h-4 rounded-full border-4 border-primary bg-white"></div>
            <div class="hidden lg:block absolute top-[34px] left-1/2 w-4 h-4 rounded-full border-4 border-primary bg-white -translate-x-1/2"></div>
            <div class="hidden lg:block absolute top-[34px] right-1/4 w-4 h-4 rounded-full border-4 border-primary bg-white"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $processSteps->count() }} gap-8">
                @foreach($processSteps as $step)
                <div class="text-center relative z-10 bg-white/5 lg:bg-transparent border border-white/10 lg:border-0 rounded-2xl p-6 lg:p-0">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-white border-[5px] border-primary shadow-lg flex items-center justify-center mb-3 sm:mb-2">
                        <i class="{{ $step->icon }} text-2xl sm:text-3xl text-gray-500"></i>
                    </div>
                    <h3 class="text-gold text-lg sm:text-xl font-bold mb-1">{{ $step->title }}</h3>
                    <p class="text-gray-200 text-sm leading-7 max-w-[240px] mx-auto">{{ $step->description }}</p>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>


{{-- ===== TESTIMONIALS SECTION ===== --}}
@php
    $allStories = $successStories->values();
    $totalStories = $allStories->count();
@endphp

<section class="py-14 sm:py-16 px-4 sm:px-6 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-10 sm:mb-12">
            <p class="text-gold text-xs font-extrabold uppercase tracking-[0.18em] mb-3">
                WHAT OUR STUDENTS SAY
            </p>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary">
                Success <span class="text-gold">Stories</span>
            </h2>
        </div>

        @if($totalStories > 0)
        <div class="relative">

            <button id="storyPrev"
                class="hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 lg:-translate-x-8 w-11 h-11 bg-white rounded-full border border-gray-200 shadow-md items-center justify-center hover:bg-gray-50 transition z-10">
                <i class="fa-solid fa-chevron-left text-gray-500 text-sm"></i>
            </button>

            <button id="storyNext"
                class="hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 lg:translate-x-8 w-11 h-11 bg-white rounded-full border border-gray-200 shadow-md items-center justify-center hover:bg-gray-50 transition z-10">
                <i class="fa-solid fa-chevron-right text-gray-500 text-sm"></i>
            </button>

            <div id="storyScroller"
                 class="reiac-horizontal-scroll flex gap-5 sm:gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-6 px-1">
                @foreach($allStories as $item)
                    <div class="snap-start shrink-0 w-[88%] sm:w-[48%] lg:w-[31.8%]">
                        @include('partials.web._story-card', ['item' => $item])
                    </div>
                @endforeach
            </div>

            <p class="mt-1 text-center text-xs text-gray-400 sm:hidden">
                Swipe to view more reviews
            </p>

        </div>
        @endif

    </div>
</section>


{{-- ===== OUR PARTNERS ===== --}}
@if($partners->count() > 0)
<section class="py-10 bg-white border-b border-gray-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <div class="flex items-center justify-center gap-4 mb-8">
            <div class="h-px flex-1 bg-gray-200"></div>
            <p class="text-gold text-xs font-extrabold uppercase tracking-[0.2em] whitespace-nowrap">
                OUR PARTNERS
            </p>
            <div class="h-px flex-1 bg-gray-200"></div>
        </div>

        <div class="relative">

            <button id="partnerPrev"
                class="hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 lg:-translate-x-8 w-10 h-10 bg-white rounded-full border border-gray-200 shadow items-center justify-center hover:bg-gray-50 transition z-10">
                <i class="fa-solid fa-chevron-left text-gray-500 text-xs"></i>
            </button>

            <button id="partnerNext"
                class="hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 lg:translate-x-8 w-10 h-10 bg-white rounded-full border border-gray-200 shadow items-center justify-center hover:bg-gray-50 transition z-10">
                <i class="fa-solid fa-chevron-right text-gray-500 text-xs"></i>
            </button>

            <div id="partnerScroller"
     class="reiac-horizontal-scroll flex gap-5 sm:gap-8 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-5 px-1">

    @foreach($partners as $partner)
        <div class="snap-start shrink-0 w-[52%] sm:w-[30%] md:w-[22%] lg:w-[15%]">

            @if(!empty($partner->link))
                <a href="{{ $partner->link }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="block">
                    <div class="h-24 sm:h-28 flex items-center justify-center rounded-2xl border border-gray-100 bg-white shadow-sm px-4 opacity-80 hover:opacity-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                        <img src="{{ asset('storage/' . $partner->image) }}"
                             alt="REIAC Partner"
                             class="h-12 sm:h-14 max-w-full object-contain">
                    </div>
                </a>
            @else
                <div class="h-24 sm:h-28 flex items-center justify-center rounded-2xl border border-gray-100 bg-white shadow-sm px-4 opacity-80 hover:opacity-100 hover:shadow-md transition">
                    <img src="{{ asset('storage/' . $partner->image) }}"
                         alt="REIAC Partner"
                         class="h-12 sm:h-14 max-w-full object-contain">
                </div>
            @endif

        </div>
    @endforeach

</div>

            <p class="mt-1 text-center text-xs text-gray-400 sm:hidden">
                Swipe to view more partners
            </p>

        </div>
    </div>
</section>
@endif


@push('scripts')
<style>
    .reiac-horizontal-scroll {
        scrollbar-width: thin;
        scrollbar-color: #dca737 #f1f5f9;
    }

    .reiac-horizontal-scroll::-webkit-scrollbar {
        height: 7px;
    }

    .reiac-horizontal-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 999px;
    }

    .reiac-horizontal-scroll::-webkit-scrollbar-thumb {
        background: #dca737;
        border-radius: 999px;
    }

    @media (max-width: 640px) {
        .reiac-horizontal-scroll::-webkit-scrollbar {
            height: 4px;
        }
    }

    /* Hide scrollbar on large screens (desktops/laptops) */
    @media (min-width: 1024px) {
        .reiac-horizontal-scroll {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .reiac-horizontal-scroll::-webkit-scrollbar {
            display: none;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function initHorizontalSlider(scrollerId, prevId, nextId) {
        const scroller = document.getElementById(scrollerId);
        const prevBtn = document.getElementById(prevId);
        const nextBtn = document.getElementById(nextId);

        if (!scroller) return;

        function getScrollAmount() {
            const firstCard = scroller.querySelector('.shrink-0');
            if (!firstCard) return 280;

            const styles = window.getComputedStyle(scroller);
            const gap = parseInt(styles.columnGap || styles.gap || 24, 10);

            return firstCard.offsetWidth + gap;
        }

        nextBtn?.addEventListener('click', function () {
            scroller.scrollBy({
                left: getScrollAmount(),
                behavior: 'smooth'
            });
        });

        prevBtn?.addEventListener('click', function () {
            scroller.scrollBy({
                left: -getScrollAmount(),
                behavior: 'smooth'
            });
        });
    }

    initHorizontalSlider('storyScroller', 'storyPrev', 'storyNext');
    initHorizontalSlider('partnerScroller', 'partnerPrev', 'partnerNext');
});
</script>
@endpush

@endsection
