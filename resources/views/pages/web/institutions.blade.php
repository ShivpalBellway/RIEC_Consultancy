@extends('layouts.app')

@section('title', 'For Institutions')

@section('content')

<section class="relative overflow-hidden bg-[#f7f9fc]">
    <div class="absolute inset-0">
        <img src="{{ asset('storage/institute-banner.png') }}"
             alt=""
             class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-white/60 lg:bg-white/20"></div>
    </div>

    <div class="relative z-20 max-w-8xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="min-h-[620px] lg:min-h-[780px] flex items-center py-20 lg:py-0">
            <div class="w-full lg:w-[48%] bg-white/75 lg:bg-transparent backdrop-blur-sm lg:backdrop-blur-0 rounded-3xl lg:rounded-none p-5 sm:p-8 lg:p-0">
                <span class="uppercase tracking-[3px] sm:tracking-[5px] text-[#c89b2a] font-bold text-sm sm:text-lg lg:text-xl">
                    FOR INSTITUTIONS
                </span>

                <h1 class="mt-4 sm:mt-5 text-3xl sm:text-4xl xl:text-5xl font-extrabold leading-tight text-[#142c61]">
                    Empowering Institutions.
                    <span class="block text-[#d7a22a]">
                        Transforming Futures.
                    </span>
                </h1>

                <p class="mt-5 sm:mt-8 text-gray-700 text-base sm:text-lg leading-7 sm:leading-9 max-w-xl">
                    REIAC partners with universities, colleges and organizations
                    worldwide to create meaningful academic collaborations and
                    life-changing opportunities for students.
                </p>

                <div class="flex flex-col sm:flex-row flex-wrap gap-4 sm:gap-5 mt-8 sm:mt-10">
                    <a href="{{ url('/contact-us') }}"
                       class="text-center bg-[#132b5d] text-white px-6 sm:px-8 py-4 rounded-xl font-semibold hover:bg-[#0e2148] duration-300 shadow-lg">
                        Partner With REIAC →
                    </a>

                    <a href="#partnership"
                       class="text-center bg-white border-2 border-gray-200 px-6 sm:px-8 py-4 rounded-xl font-semibold text-[#132b5d] hover:border-[#d7a22a] duration-300 shadow">
                        Explore Partnership Options
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5 mt-10 sm:mt-14 max-w-2xl">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 sm:p-6 text-center">
                        <i class="fa-solid fa-building-columns text-2xl sm:text-3xl text-[#132b5d]"></i>
                        <h3 class="mt-3 sm:mt-4 text-3xl sm:text-4xl font-bold text-[#d7a22a]">100+</h3>
                        <p class="mt-2 text-sm text-gray-600">Partner Institutions</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 sm:p-6 text-center">
                        <i class="fa-solid fa-globe text-2xl sm:text-3xl text-[#132b5d]"></i>
                        <h3 class="mt-3 sm:mt-4 text-3xl sm:text-4xl font-bold text-[#d7a22a]">15+</h3>
                        <p class="mt-2 text-sm text-gray-600">Countries</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 sm:p-6 text-center">
                        <i class="fa-solid fa-users text-2xl sm:text-3xl text-[#132b5d]"></i>
                        <h3 class="mt-3 sm:mt-4 text-3xl sm:text-4xl font-bold text-[#d7a22a]">10,000+</h3>
                        <p class="mt-2 text-sm text-gray-600">Students Empowered</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="partnership" class="py-14 sm:py-20 bg-[#f8fafc]">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 text-center">
        <p class="text-[#c89b2a] font-bold tracking-[0.2em] sm:tracking-[0.25em] text-xs sm:text-sm lg:text-lg uppercase mb-3">
            Why Partner With REIAC?
        </p>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#1a2f5e]">
            Stronger Together for Global Impact
        </h2>
        <p class="mt-4 text-gray-600 max-w-2xl mx-auto text-sm sm:text-base">
            Our partnerships are built on trust, collaboration, and a shared vision for academic excellence.
        </p>

        <div class="mt-10 sm:mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 sm:gap-6">
            @php
                $features = [
                    ['icon' => 'fa-handshake', 'title' => 'Strategic Partnerships', 'text' => 'Collaborate on long-term academic programs.'],
                    ['icon' => 'fa-globe', 'title' => 'Global Exposure', 'text' => 'Expand your reach and enhance international enrollment.'],
                    ['icon' => 'fa-graduation-cap', 'title' => 'Student Success', 'text' => 'Provide students with world-class opportunities and support.'],
                    ['icon' => 'fa-chart-line', 'title' => 'Mutual Growth', 'text' => 'Drive innovation, research and institution development together.'],
                    ['icon' => 'fa-shield-halved', 'title' => 'Trusted Support', 'text' => 'Dedicated team to assist you at every step of the partnership.'],
                ];
            @endphp

            @foreach($features as $feature)
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-full bg-[#1a2f5e] text-white flex items-center justify-center text-xl sm:text-2xl">
                        <i class="fa-solid {{ $feature['icon'] }}"></i>
                    </div>
                    <h3 class="mt-5 sm:mt-6 text-base sm:text-lg font-extrabold text-[#1a2f5e]">{{ $feature['title'] }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-6">{{ $feature['text'] }}</p>
                    <div class="w-10 h-1 bg-[#c89b2a] mx-auto mt-6 rounded-full"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-14 sm:py-20 bg-white overflow-hidden">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-20 text-center">
        <p class="text-[#c89b2a] font-bold tracking-[0.25em] text-xs uppercase mb-3">
            Our Partner Institutions
        </p>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#1a2f5e] mb-8 sm:mb-12">
            Proud to Collaborate with Leading Institutions
        </h2>

        @if($partners->count() > 0)
            <div class="relative max-w-7xl mx-auto">
                <button type="button" id="instPartnerPrev"
                    class="hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 lg:-translate-x-12 w-11 h-11 bg-white rounded-full border border-gray-200 shadow-md items-center justify-center hover:bg-gray-50 transition z-10">
                    <i class="fa-solid fa-chevron-left text-gray-500 text-sm"></i>
                </button>

                <button type="button" id="instPartnerNext"
                    class="hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 lg:translate-x-12 w-11 h-11 bg-white rounded-full border border-gray-200 shadow-md items-center justify-center hover:bg-gray-50 transition z-10">
                    <i class="fa-solid fa-chevron-right text-gray-500 text-sm"></i>
                </button>

                <div id="instPartnerScroller"
                     class="partners-scroll flex gap-4 sm:gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-5 px-1">
                    @foreach($partners as $partner)
                        <div class="snap-start shrink-0 w-[72%] xs:w-[65%] sm:w-[260px] md:w-[230px] lg:w-[220px]">
                            <div class="bg-white rounded-2xl border border-gray-100 p-5 sm:p-6 h-28 sm:h-32 flex items-center justify-center shadow-sm hover:shadow-lg transition">
                                <img src="{{ asset('storage/' . $partner->image) }}"
                                     class="max-h-16 sm:max-h-20 max-w-full object-contain"
                                     alt="REIAC Partner">
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="mt-2 text-xs text-gray-400 sm:hidden">
                    Swipe to view more partners
                </p>
            </div>
        @else
            <div class="text-gray-500">No partners added yet.</div>
        @endif
    </div>
</section>

<section class="py-14 sm:py-20 bg-[#f8fafc] overflow-hidden">
    <div class="max-w-8xl mx-auto grid lg:grid-cols-2 gap-10 lg:gap-12 items-center px-4 sm:px-6 lg:px-20">
        <div>
            <p class="text-[#c89b2a] font-bold tracking-[0.25em] text-xs uppercase mb-3">
                Why Institutions Choose Us
            </p>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#1a2f5e] leading-tight">
                More Than a Partnership,<br class="hidden sm:block">
                It’s a <span class="text-[#c89b2a]">Shared Mission.</span>
            </h2>
            <p class="mt-5 text-gray-600 leading-7 sm:leading-8 text-sm sm:text-base">
                We help institutions create opportunities that empower students and support international academic growth.
            </p>

            <ul class="mt-8 space-y-4">
                <li class="flex gap-3 text-gray-700 text-sm sm:text-base"><i class="fa-solid fa-circle-check text-[#c89b2a] mt-1"></i> Customized partnership models</li>
                <li class="flex gap-3 text-gray-700 text-sm sm:text-base"><i class="fa-solid fa-circle-check text-[#c89b2a] mt-1"></i> Student recruitment and exchange opportunities</li>
                <li class="flex gap-3 text-gray-700 text-sm sm:text-base"><i class="fa-solid fa-circle-check text-[#c89b2a] mt-1"></i> Transparent communication</li>
                <li class="flex gap-3 text-gray-700 text-sm sm:text-base"><i class="fa-solid fa-circle-check text-[#c89b2a] mt-1"></i> Ethical and quality-driven approach</li>
            </ul>
        </div>

        <div class="relative pb-12 sm:pb-0">
            <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1200&q=80"
                 class="rounded-3xl shadow-2xl w-full h-[280px] sm:h-[360px] lg:h-[430px] object-cover"
                 alt="Academic Collaboration">

            <div class="sm:absolute sm:-bottom-8 sm:right-0 lg:-right-4 bg-[#1a2f5e] text-white rounded-3xl p-5 sm:p-7 sm:max-w-sm shadow-xl mt-5 sm:mt-0">
                <p class="text-3xl sm:text-4xl text-[#c89b2a]">“</p>
                <p class="font-semibold leading-7 text-sm sm:text-base">
                    Together, we can build a better future for students and institutions worldwide.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-14 sm:py-20 bg-white overflow-hidden">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 text-center">
        <p class="text-[#c89b2a] font-bold tracking-[0.25em] text-xs uppercase mb-3">
            Our Collaboration Process
        </p>

        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#1a2f5e]">
            A Simple Path to a Successful Partnership
        </h2>

        @php
            $steps = [
                ['Connect', 'Share your institution details and goals with us.', 'fa-comments'],
                ['Explore', 'We explore opportunities that align with your vision.', 'fa-file-lines'],
                ['Collaborate', 'We design partnership models that work for both.', 'fa-handshake'],
                ['Implement', 'Launch programs and initiatives smoothly.', 'fa-paper-plane'],
                ['Grow Together', 'Achieve mutual growth and long-term impact.', 'fa-trophy'],
            ];
        @endphp

        <div class="relative mt-12 sm:mt-16">
            <div class="hidden lg:block absolute top-10 left-[10%] right-[10%] border-t-2 border-dashed border-[#9aa8bd] z-0"></div>

            <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-10">
                @foreach($steps as $index => $step)
                    <div class="text-center bg-white sm:bg-transparent rounded-3xl border sm:border-0 border-gray-100 p-6 sm:p-0 shadow-sm sm:shadow-none">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full
                            {{ $index % 2 == 0 ? 'bg-[#102a5c]' : 'bg-[#d4a12c]' }}
                            text-white flex items-center justify-center text-2xl sm:text-3xl
                            shadow-[0_8px_22px_rgba(15,42,92,0.25)]
                            border-[5px] border-white relative z-10">
                            <i class="fa-solid {{ $step[2] }}"></i>
                        </div>

                        <span class="block mt-4 sm:mt-5 text-[#c89b2a] font-extrabold text-sm sm:text-lg">
                            Step {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <h3 class="mt-2 text-xl sm:text-2xl font-extrabold text-[#1a2f5e]">
                            {{ $step[0] }}
                        </h3>

                        <p class="mt-3 text-sm sm:text-base text-gray-600 leading-7 max-w-[230px] mx-auto">
                            {{ $step[1] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="relative py-14 sm:py-20 bg-[#1a2f5e] overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1600&q=80"
             class="w-full h-full object-cover"
             alt="">
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 flex flex-col lg:flex-row items-center lg:items-start justify-between gap-8 text-center lg:text-left">
        <div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight">
                Let’s Build the <span class="text-[#f4c247]">Future of Education</span> Together
            </h2>
            <p class="mt-3 text-white/80 text-sm sm:text-base">
                Join hands with REIAC and open doors to global opportunities.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <a href="{{ url('/contact-us') }}" class="text-center bg-[#c89b2a] text-white px-7 sm:px-8 py-4 rounded-xl font-bold hover:bg-[#b78a22] transition">
                Become a Partner →
            </a>
            <a href="{{ url('/contact-us') }}" class="text-center border border-white/40 text-white px-7 sm:px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-[#1a2f5e] transition">
                Contact Us
            </a>
        </div>
    </div>
</section>

@push('scripts')
<style>
    .partners-scroll {
        scrollbar-width: thin;
        scrollbar-color: #c89b2a #f1f5f9;
    }

    .partners-scroll::-webkit-scrollbar {
        height: 8px;
    }

    .partners-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 999px;
    }

    .partners-scroll::-webkit-scrollbar-thumb {
        background: #c89b2a;
        border-radius: 999px;
    }

    @media (max-width: 640px) {
        .partners-scroll::-webkit-scrollbar {
            height: 4px;
        }
    }

    /* Hide scrollbar on large screens (desktops/laptops) */
    @media (min-width: 1024px) {
        .partners-scroll {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .partners-scroll::-webkit-scrollbar {
            display: none;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const scroller = document.getElementById('instPartnerScroller');
    const nextBtn = document.getElementById('instPartnerNext');
    const prevBtn = document.getElementById('instPartnerPrev');

    if (!scroller) return;

    function scrollPartners(direction) {
        const card = scroller.querySelector('.shrink-0');
        const cardWidth = card ? card.offsetWidth : 260;
        const gap = 24;
        scroller.scrollBy({
            left: direction * (cardWidth + gap),
            behavior: 'smooth'
        });
    }

    nextBtn?.addEventListener('click', function () {
        scrollPartners(1);
    });

    prevBtn?.addEventListener('click', function () {
        scrollPartners(-1);
    });
});
</script>
@endpush

@endsection
