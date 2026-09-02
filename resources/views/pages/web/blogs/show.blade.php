@extends('layouts.app')

@section('title', $blog->title . ' - REIAC')

@section('content')
<article class="bg-white">
    {{-- ===== HEADER HEADER ===== --}}
    <header class="bg-[#061d43] py-20 px-6 overflow-hidden relative">
        <div class="absolute inset-0 opacity-15">
            <img src="https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&w=1800&q=80"
                 alt="Header Background"
                 class="w-full h-full object-cover">
        </div>

        <div class="relative max-w-6xl mx-auto text-center">
            <div class="flex items-center justify-center gap-3 mb-6">
                <span class="text-xs uppercase tracking-wider font-extrabold bg-[#dca737] text-white px-3 py-1 rounded-md">
                    Education
                </span>
                <span class="text-gray-300 text-sm flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar text-[#dca737]"></i>
                    {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                </span>
            </div>

            <h1 class="text-white text-3xl md:text-5xl font-black mb-6 leading-tight max-w-3xl mx-auto">
                {{ $blog->title }}
            </h1>

            <div class="flex items-center justify-center gap-3 text-gray-300 text-sm">
                <span>By <strong class="text-white">REIAC Consulting</strong></span>
                <span>•</span>
                <span>{{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read</span>
            </div>
        </div>
    </header>

    {{-- ===== ARTICLE BODY ===== --}}
    <div class="py-16 px-6 bg-gray-50">
        <div class="max-w-7xl mx-auto">

            {{-- Cover Image Card --}}
            @if($blog->image)
                <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-lg -mt-28 mb-12 relative z-10">
                    <img src="{{ asset('storage/' . $blog->image) }}"
                         alt="{{ $blog->title }}"
                         class="w-full max-h-[480px] object-cover">
                </div>
            @else
                <div class="h-6 -mt-22 mb-12"></div>
            @endif

            {{-- Breadcrumb & Back --}}
            <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-200">
                <a href="{{ route('blogs.index') }}"
                   class="inline-flex items-center gap-2 text-primary font-extrabold text-sm hover:text-gold transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Blogs
                </a>

                <div class="flex gap-2">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 text-gray-500 hover:text-gold transition">
                        <i class="fa-brands fa-x-twitter text-xs"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 text-gray-500 hover:text-gold transition">
                        <i class="fa-brands fa-facebook-f text-xs"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 text-gray-500 hover:text-gold transition">
                        <i class="fa-brands fa-linkedin-in text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="prose max-w-none text-gray-700 leading-relaxed text-[16px] md:text-[17px] space-y-6">
                {!! $blog->content !!}
            </div>

            {{-- CTA Newsletter --}}
            <div class="mt-16 bg-gradient-to-br from-[#061d43] to-[#0d234a] rounded-3xl p-8 md:p-12 text-center text-white relative overflow-hidden shadow-xl">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#dca737] via-transparent to-transparent"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-3">Begin Your Study Journey Today</h3>
                    <p class="text-gray-300 text-sm max-w-md mx-auto mb-6 leading-relaxed">
                        Need guidance on foreign applications, visas, or career counseling? Our specialists are here to assist.
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('contact') }}"
                           class="bg-gold text-white hover:bg-yellow-600 px-6 py-3 rounded-xl font-bold text-sm transition">
                            Schedule Free Consultation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
@endsection
