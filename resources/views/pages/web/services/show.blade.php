@extends('layouts.app')

@section('title', $service->title . ' — REIAC Services')

@push('styles')
<style>
    /* ── CKEditor rendered HTML styles ─────────────────────────── */
    .ck-rendered { line-height: 1.8; color: #374151; }
    .ck-rendered h1 { font-size: 2rem;   font-weight: 800; color: #1a2f5e; margin: 2rem 0 1rem; line-height: 1.2; }
    .ck-rendered h2 { font-size: 1.5rem; font-weight: 800; color: #1a2f5e; margin: 1.75rem 0 .85rem; line-height: 1.25; }
    .ck-rendered h3 { font-size: 1.2rem; font-weight: 700; color: #1a2f5e; margin: 1.5rem 0 .7rem; }
    .ck-rendered h4 { font-size: 1rem;   font-weight: 700; color: #1a2f5e; margin: 1.25rem 0 .5rem; }
    .ck-rendered p  { margin-bottom: 1rem; }
    .ck-rendered ul { list-style: disc;    padding-left: 1.5rem; margin-bottom: 1rem; }
    .ck-rendered ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
    .ck-rendered li { margin-bottom: .4rem; }
    .ck-rendered strong, .ck-rendered b { font-weight: 700; color: #111827; }
    .ck-rendered em, .ck-rendered i     { font-style: italic; }
    .ck-rendered u  { text-decoration: underline; }
    .ck-rendered s  { text-decoration: line-through; }
    .ck-rendered a  { color: #1a2f5e; text-decoration: underline; }
    .ck-rendered a:hover { color: #dca737; }
    .ck-rendered blockquote {
        border-left: 4px solid #dca737;
        padding: .85rem 1.25rem;
        background: #fdf9f0;
        border-radius: 0 .75rem .75rem 0;
        color: #1a2f5e;
        font-style: italic;
        margin: 1.5rem 0;
    }
    .ck-rendered figure.image { margin: 1.5rem 0; }
    .ck-rendered figure.image img { max-width: 100%; border-radius: .75rem; }
    .ck-rendered table {
        width: 100%; border-collapse: collapse;
        margin-bottom: 1.5rem; font-size: .9rem;
    }
    .ck-rendered table td,
    .ck-rendered table th {
        border: 1px solid #e5e7eb;
        padding: .65rem 1rem;
        text-align: left;
    }
    .ck-rendered table th {
        background: #1a2f5e; color: #fff; font-weight: 700;
    }
    .ck-rendered table tr:nth-child(even) td { background: #f9fafb; }
    .ck-rendered hr { border: none; border-top: 2px solid #e5e7eb; margin: 2rem 0; }
    .ck-rendered pre { background: #1e293b; color: #e2e8f0; padding: 1rem 1.25rem;
        border-radius: .75rem; overflow-x: auto; margin-bottom: 1rem; font-size: .875rem; }

    /* Hero parallax */
    .service-hero-img { transform: scale(1.04); transition: transform 8s ease; }
    .service-hero-img:hover { transform: scale(1); }
</style>
@endpush

@section('content')

{{-- ═══ HERO BANNER ═══ --}}
<section class="relative h-[420px] md:h-[500px] overflow-hidden bg-[#061d43]">

    @if($service->image)
    <img src="{{ Str::startsWith($service->image, 'http') ? $service->image : asset('storage/' . $service->image) }}"
         alt="{{ $service->title }}"
         class="service-hero-img absolute inset-0 w-full h-full object-cover">
    @else
    <div class="absolute inset-0 bg-gradient-to-br from-[#061d43] to-[#1a2f5e]"></div>
    @endif

    {{-- Gradient Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-r from-[#061d43]/90 via-[#061d43]/60 to-transparent"></div>

    {{-- Content --}}
    <div class="relative z-10 max-w-7xl mx-auto h-full px-6 md:px-10 flex items-end pb-14">
        <div class="max-w-2xl">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-white/60 text-sm mb-5 font-semibold flex-wrap">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <span class="text-white/30">›</span>
                <span class="text-[#dca737]">Our Services</span>
                <span class="text-white/30">›</span>
                <span class="text-white">{{ $service->title }}</span>
            </nav>

            {{-- Icon Badge --}}
            @if($service->icon)
            <div class="w-16 h-16 rounded-2xl bg-[#dca737] flex items-center justify-center shadow-xl mb-5">
                <i class="{{ $service->icon }} text-[#061d43] text-3xl"></i>
            </div>
            @endif

            <h1 class="text-white text-4xl md:text-5xl font-extrabold leading-[1.1] mb-4">
                {{ $service->title }}
            </h1>

            @if($service->excerpt)
            <p class="text-white/75 text-base md:text-lg leading-7 max-w-xl">
                {{ $service->excerpt }}
            </p>
            @endif

        </div>
    </div>
</section>


{{-- ═══ MAIN CONTENT ═══ --}}
<section class="py-16 px-6 bg-white">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-[1fr_340px] gap-12">

        {{-- ── Left: Full Description ── --}}
        <div>
            @if($service->description)
                {{-- CKEditor HTML rendered properly --}}
                <div class="ck-rendered">
                    {!! $service->description !!}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        @if($service->icon)
                            <i class="{{ $service->icon }} text-gray-300 text-3xl"></i>
                        @else
                            <i class="fa-solid fa-file-circle-question text-gray-300 text-3xl"></i>
                        @endif
                    </div>
                    <p class="text-gray-400 font-semibold">Detailed description coming soon.</p>
                </div>
            @endif

            {{-- CTA Strip --}}
            <div class="mt-12 rounded-2xl bg-gradient-to-r from-[#1a2f5e] to-[#0d1d3b] p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-white text-xl font-extrabold mb-1">Ready to get started?</h3>
                    <p class="text-white/65 text-sm leading-6">Apply now and let our experts guide you every step of the way.</p>
                </div>
                <a href="{{ route('apply.index') }}"
                   class="flex-shrink-0 bg-[#dca737] hover:bg-[#c8942d] text-[#061d43] font-extrabold px-8 py-4 rounded-xl transition shadow-lg whitespace-nowrap">
                    Apply Now →
                </a>
            </div>
        </div>

        {{-- ── Right Sidebar ── --}}
        <aside class="space-y-8">

            {{-- Contact Card --}}
            <div class="bg-[#1a2f5e] rounded-2xl p-7 text-white shadow-xl">
                <h4 class="text-lg font-extrabold mb-1">Need Help?</h4>
                <p class="text-white/65 text-sm mb-5 leading-6">
                    Speak with one of our advisors about this service today.
                </p>
                <a href="{{ route('contact') }}"
                   class="flex items-center justify-center gap-2 w-full bg-[#dca737] hover:bg-[#c8942d] text-[#061d43] font-extrabold py-3 rounded-xl transition text-sm">
                    <i class="fa-solid fa-envelope"></i>
                    Contact Us
                </a>
                <a href="{{ route('apply.index') }}"
                   class="flex items-center justify-center gap-2 w-full bg-white/10 hover:bg-white/20 text-white font-bold py-3 rounded-xl transition text-sm mt-3">
                    <i class="fa-solid fa-file-lines"></i>
                    Start Application
                </a>
            </div>

            {{-- Other Services --}}
            @if($services->count())
            <div class="bg-gray-50 rounded-2xl p-7 border border-gray-100">
                <h4 class="text-base font-extrabold text-[#1a2f5e] mb-4">Other Services</h4>
                <div class="space-y-3">
                    @foreach($services as $other)
                    <a href="{{ route('services.show', $other->slug) }}"
                       class="flex items-center gap-4 p-3 rounded-xl bg-white border border-gray-100 hover:border-[#dca737]/50 hover:shadow-md transition group">

                        <div class="w-11 h-11 rounded-full bg-[#1a2f5e] flex items-center justify-center flex-shrink-0 group-hover:bg-[#dca737] transition">
                            <i class="{{ $other->icon ?: 'fa-solid fa-circle' }} text-white text-base"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-[#1a2f5e] font-bold text-sm leading-tight truncate">{{ $other->title }}</p>
                            @if($other->excerpt)
                            <p class="text-gray-400 text-xs mt-0.5 line-clamp-1">{{ Str::limit($other->excerpt, 50) }}</p>
                            @endif
                        </div>

                        <i class="fa-solid fa-chevron-right text-gray-300 text-xs ml-auto flex-shrink-0 group-hover:text-[#dca737] transition"></i>
                    </a>
                    @endforeach
                </div>

                <a href="{{ route('home') }}"
                   class="flex items-center justify-center gap-2 w-full mt-4 text-[#1a2f5e] font-bold text-sm hover:text-[#dca737] transition">
                    View All Services <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            @endif

        </aside>

    </div>
</section>

@endsection
