@extends('layouts.admin')

@section('title', 'About Us')
@section('page-title', 'About Us')

@section('header-actions')
<a href="{{ route('admin.about.edit') }}"
   class="inline-flex items-center gap-2 bg-[#1a2f5e] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#132247] transition">
    <i class="fa-solid fa-pen text-xs"></i>
    Edit About Us
</a>
@endsection

@section('content')
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6">
        @if($about->exists)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <h3 class="font-bold text-gray-900 mb-2">Title</h3>
                    <p class="text-sm text-gray-600">{{ $about->title ?? 'Not set' }}</p>

                    @if($about->image)
                        <img src="{{ asset('storage/'.$about->image) }}" class="mt-4 w-full h-40 object-cover rounded" alt="About Image">
                    @endif
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <h3 class="font-bold text-gray-900 mb-2">Description</h3>
                    <div class="text-sm text-gray-600 prose max-w-none">
                        {!! $about->description ?? 'Not set' !!}
                    </div>
                </div>
            </div>

            {{-- Hero Section Preview --}}
            <div class="mt-6">
                <h3 class="font-bold text-gray-900 mb-4 px-1">Hero Section</h3>
                <div class="relative rounded-2xl overflow-hidden h-52">
                    @if($about->hero_image)
                        <img src="{{ asset('storage/'.$about->hero_image) }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 bg-[#061d43]"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-r from-[#061d43]/90 to-[#061d43]/20"></div>
                    <div class="relative z-10 p-6 h-full flex flex-col justify-center">
                        <p class="text-[#dca737] text-xs font-extrabold uppercase tracking-widest mb-2">
                            {{ $about->hero_badge ?? 'About REIAC' }}
                        </p>
                        <h2 class="text-white text-2xl font-black leading-tight mb-2">
                            {{ $about->hero_heading_line1 ?? 'Empowering Futures' }}<br>
                            <span class="text-[#dca737]">{{ $about->hero_heading_highlight ?? 'Worldwide.' }}</span>
                        </h2>
                        <p class="text-white/80 text-xs max-w-md leading-5">
                            {{ $about->hero_subtext ?? 'REIAC is your trusted global education partner...' }}
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-address-card text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No About Us content found.</p>
                <a href="{{ route('admin.about.edit') }}" class="inline-block mt-4 px-6 py-2 bg-[#1a2f5e] text-white rounded-xl hover:bg-[#132247] transition">
                    Create About Us Page
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
