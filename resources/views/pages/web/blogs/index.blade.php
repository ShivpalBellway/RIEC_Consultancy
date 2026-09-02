@extends('layouts.app')

@section('title', 'Blogs - REIAC')

@section('content')
{{-- ===== BLOG HERO HEADER ===== --}}
<section class="relative bg-[#061d43] py-20 px-6 overflow-hidden">
    {{-- Background Overlay --}}
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1501504905252-473c47e087f8?auto=format&fit=crop&w=1800&q=80"
             alt="Blog Header Background"
             class="w-full h-full object-cover">
    </div>

    <div class="relative max-w-4xl mx-auto text-center">
        <p class="text-[#dca737] text-xs font-extrabold uppercase tracking-[0.25em] mb-4">
            REIAC KNOWLEDGE HUB
        </p>
        <h1 class="text-white text-4xl md:text-5xl font-black mb-6 leading-tight">
            Latest News & <span class="text-[#dca737]">Insights</span>
        </h1>
        <p class="text-gray-200 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
            Stay updated with current guidelines, top university guides, visa procedures, and global education opportunities.
        </p>
    </div>
</section>

{{-- ===== BLOG CARDS LISTING ===== --}}
<section class="py-20 px-6 bg-gray-50">
    <div class="max-w-7xl mx-auto">

        @if($blogs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 hover:shadow-xl transition duration-300 group flex flex-col h-full">

                        {{-- Cover Image --}}
                        <div class="h-52 overflow-hidden relative bg-gray-100 flex-shrink-0">
                            @if($blog->image)
                                <img src="{{ asset('storage/' . $blog->image) }}"
                                     alt="{{ $blog->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold bg-[#061d43]/5">
                                    <i class="fa-solid fa-newspaper text-4xl text-[#061d43]/10"></i>
                                </div>
                            @endif
                            {{-- Date Badge --}}
                            <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm text-primary text-xs font-extrabold px-3 py-1.5 rounded-lg shadow-sm border border-gray-100 flex items-center gap-1">
                                <i class="fa-regular fa-calendar text-gold"></i>
                                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-6 md:p-8 flex flex-col flex-1">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-[10px] uppercase tracking-wider font-extrabold bg-gold/10 text-gold px-2.5 py-1 rounded-md">
                                    Education
                                </span>
                                <span class="text-[10px] uppercase tracking-wider font-extrabold bg-[#061d43]/5 text-[#061d43]/60 px-2.5 py-1 rounded-md">
                                    Insights
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-primary mb-3 leading-snug group-hover:text-gold transition line-clamp-2">
                                <a href="{{ route('blogs.show', $blog->slug) }}">
                                    {{ $blog->title }}
                                </a>
                            </h3>

                            <p class="text-gray-600 text-sm leading-relaxed mb-6 line-clamp-3">
                                {{ $blog->excerpt ?? Str::limit(strip_tags($blog->content), 120) }}
                            </p>

                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                                <a href="{{ route('blogs.show', $blog->slug) }}"
                                   class="inline-flex items-center gap-2 text-primary font-bold text-sm hover:text-gold transition">
                                    Read Article
                                    <span class="text-gold text-lg transition duration-300 group-hover:translate-x-1 inline-block">→</span>
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($blogs->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $blogs->links() }}
                </div>
            @endif

            

        @else
            <div class="text-center py-16 bg-white border border-gray-100 rounded-3xl shadow-sm max-w-lg mx-auto">
                <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-feather-pointed text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-[#061d43] mb-2">No Blog Posts Yet</h3>
                <p class="text-gray-500 max-w-sm mx-auto text-sm">
                    We are crafting awesome educational content for you. Check back shortly!
                </p>
            </div>
        @endif

    </div>
</section>
@endsection
