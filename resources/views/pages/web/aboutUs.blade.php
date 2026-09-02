@extends('layouts.app')

@section('title', $about->title ?? 'About Us')

@section('content')

{{-- HERO - DYNAMIC --}}
@php
    $heroImg       = $about->hero_image ? asset('storage/'.$about->hero_image) : asset('storage/aboutus-hero.png');
    $heroBadge     = $about->hero_badge            ?? 'About REIAC';
    $heroLine1     = $about->hero_heading_line1     ?? 'Empowering Futures';
    $heroHighlight = $about->hero_heading_highlight ?? 'Worldwide.';
    $heroSubtext   = $about->hero_subtext           ?? 'REIAC is your trusted global education partner, committed to guiding students towards world-class opportunities and brighter tomorrows.';
@endphp

<section class="relative h-[620px] overflow-hidden">
    <img src="{{ $heroImg }}" class="absolute inset-0 w-full h-full object-cover object-bottom-right" alt="About REIAC">
    <div class="absolute inset-0 bg-gradient-to-r from-[#061d43]/95 via-[#061d43]/80 to-[#061d43]/10"></div>

    <div class="relative z-10 max-w-7xl mx-auto h-full px-6 flex items-center">
        <div class="max-w-xl text-white">
            <div class="text-xs text-white/80 mb-8">
                <a href="/" class="hover:text-[#dca737]">Home</a>
                <span class="mx-2">›</span>
                <span>About Us</span>
            </div>

            <p class="text-[#dca737] text-xs font-extrabold uppercase tracking-[0.28em] mb-4">
                {{ $heroBadge }}
            </p>

            <h1 class="text-4xl md:text-5xl font-black leading-tight mb-5">
                {{ $heroLine1 }} <br>
                <span class="text-[#dca737]">{{ $heroHighlight }}</span>
            </h1>

            <p class="text-white/90 text-sm md:text-base leading-7 max-w-lg">
                {{ $heroSubtext }}
            </p>

            <a href="#who-we-are"
                class="inline-flex items-center gap-2 mt-8 bg-[#dca737] text-white px-6 py-3 rounded font-bold text-sm hover:bg-[#c49322] transition">
                Get to Know Us
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</section>

{{-- WHO WE ARE / ABOUT REIAC - DYNAMIC --}}
<section id="who-we-are" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-14 items-center">

            <div>
                <p class="text-[#dca737] text-xs font-extrabold uppercase tracking-[0.25em] mb-4">
                    Who We Are
                </p>

                <h2 class="text-3xl md:text-4xl font-black text-[#102b5c] leading-tight mb-5">
                    {!! $about->who_we_are_title ?? 'About <span class="text-[#dca737]">REIAC</span>' !!}
                </h2>

                <div class="w-14 h-1 bg-[#dca737] mb-6"></div>

                <div class="text-gray-600 text-sm leading-7 max-w-xl mb-8 space-y-4">
                    {!! $about->description !!}
                </div>

                <!-- @if($about->who_we_are_points && count($about->who_we_are_points) > 0)
                    <div class="space-y-4">
                        @foreach($about->who_we_are_points as $point)
                            @if(!empty($point))
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full border border-[#dca737] text-[#dca737] flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                    <span class="text-sm font-semibold text-gray-700">{{ $point }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach(['Personalised Guidance', 'Expert Counselors', 'Global University Network', 'End-to-End Support'] as $item)
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full border border-[#dca737] text-[#dca737] flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                                <span class="text-sm font-semibold text-gray-700">{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif -->
            </div>

            <div class="relative">
                    <img src="{{ asset('storage/' . $about->image) }}"
                        class="w-full h-[420px] object-cover rounded-xl shadow-lg" alt="Students">

                <div class="absolute -bottom-10 -left-10 bg-white rounded-xl shadow-2xl p-7 w-72 hidden md:block">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-full bg-[#102b5c] text-white flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <h3 class="text-3xl font-black text-[#dca737]">10+</h3>
                            <p class="text-sm font-bold text-[#102b5c] leading-tight">
                                Years of Excellence <br> in Education Services
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- PARTNERS --}}
@php
    $partnerChunks      = $partners->chunk(6);
    $totalPartnerChunks = $partnerChunks->count();
@endphp
@if($partners->count() > 0)
<section class="py-10 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6">

        <div class="flex items-center justify-center gap-4 mb-8">
            <div class="h-px flex-1 bg-gray-200"></div>
            <p class="text-gold text-xs font-extrabold uppercase tracking-[0.2em] whitespace-nowrap">OUR PARTNERS</p>
            <div class="h-px flex-1 bg-gray-200"></div>
        </div>

        <div class="relative">

            @if($totalPartnerChunks > 1)
            <button id="partnerPrev"
                class="hidden lg:flex absolute -left-6 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-lg border border-gray-200 shadow items-center justify-center hover:bg-gray-50 transition z-10">
                <i class="fa-solid fa-chevron-left text-gray-500 text-xs"></i>
            </button>
            <button id="partnerNext"
                class="hidden lg:flex absolute -right-6 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-lg border border-gray-200 shadow items-center justify-center hover:bg-gray-50 transition z-10">
                <i class="fa-solid fa-chevron-right text-gray-500 text-xs"></i>
            </button>
            @endif

            <div class="overflow-hidden">
                <div id="partnerTrack"
                     class="flex transition-transform duration-500 ease-in-out"
                     style="width: {{ $totalPartnerChunks * 100 }}%">
                    @foreach($partnerChunks as $chunk)
                    <div class="partner-page flex-shrink-0" style="width: {{ 100 / $totalPartnerChunks }}%">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 items-center">
                            @foreach($chunk as $partner)
                            <div class="flex justify-center opacity-80 hover:opacity-100 transition">
                                <img src="{{ asset('storage/' . $partner->image) }}" alt="REIAC Partner"
                                     class="h-14 max-w-[160px] object-contain">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($totalPartnerChunks > 1)
            <div class="flex justify-center gap-3 mt-6">
                @for($d = 0; $d < $totalPartnerChunks; $d++)
                <button class="partner-dot w-2.5 h-2.5 rounded-full transition-all duration-300 {{ $d === 0 ? 'bg-primary' : 'bg-gray-300' }}"
                        data-page="{{ $d }}"></button>
                @endfor
            </div>
            @endif

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('partnerTrack');
    if (!track) return;
    const dots  = document.querySelectorAll('.partner-dot');
    let current = 0;
    const total = dots.length;
    if (total <= 1) return;
    const stepPct = 100 / total;
    function goTo(idx) {
        current = (idx + total) % total;
        track.style.transform = 'translateX(-' + (current * stepPct) + '%)';
        dots.forEach(function(d, i) {
            d.classList.toggle('bg-primary',  i === current);
            d.classList.toggle('bg-gray-300', i !== current);
        });
    }
    document.getElementById('partnerNext')?.addEventListener('click', function() { goTo(current + 1); });
    document.getElementById('partnerPrev')?.addEventListener('click', function() { goTo(current - 1); });
    dots.forEach(function(d) { d.addEventListener('click', function() { goTo(+d.dataset.page); }); });
});
</script>
@endif

@endsection
