{{-- resources/views/pages/web/notices/show.blade.php --}}

@extends('layouts.app')

@php
    // Check if $notice exists
    if (!isset($notice)) {
        abort(404, 'Notice not found.');
    }
@endphp

@section('title', $notice->title ?? 'Notice Details')
@section('meta_description', isset($notice) ? strip_tags(\Illuminate\Support\Str::limit($notice->content, 160)) : '')

@section('content')

{{-- Breadcrumb --}}
<section class="bg-gray-50 border-b border-gray-200 py-4">
    <div class="container mx-auto px-4">
        <nav class="text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('frontend.notices.index') }}" class="hover:text-primary transition">Notices</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700 font-medium">{{ isset($notice) ? \Illuminate\Support\Str::limit($notice->title, 50) : 'Notice' }}</span>
        </nav>
    </div>
</section>

{{-- Main Content --}}
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">

            {{-- Notice Card --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-primary to-primary/80 text-white p-6 md:p-8">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex-1">
                            <h1 class="text-2xl md:text-3xl font-bold mb-3">{{ $notice->title ?? 'Notice' }}</h1>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-3 py-1 rounded-full bg-white/20 text-white text-xs font-bold">
                                    <i class="fa-regular fa-calendar mr-1"></i>
                                    {{ isset($notice->published_at) ? $notice->published_at->format('d M Y, h:i A') : 'N/A' }}
                                </span>
                                @if(isset($notice) && $notice->is_pinned)
                                    <span class="px-3 py-1 rounded-full bg-red-500/30 text-white text-xs font-bold">
                                        <i class="fa-solid fa-thumbtack mr-1"></i> Pinned
                                    </span>
                                @endif
                                @if(isset($notice))
                                    <span class="px-3 py-1 rounded-full bg-{{ $notice->priority_color }}-500/30 text-white text-xs font-bold">
                                        <i class="fa-solid fa-flag mr-1"></i> {{ $notice->priority_label }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-white/70">
                            <span><i class="fa-regular fa-eye mr-1"></i> {{ $notice->views ?? 0 }}</span>
                            @php
                                $filePaths = isset($notice) ? $notice->file_paths : null;
                                if (is_string($filePaths)) {
                                    $filePaths = json_decode($filePaths, true) ?? [];
                                }
                                $fileCount = is_array($filePaths) ? count($filePaths) : 0;
                            @endphp
                            @if($fileCount > 0)
                                <span><i class="fa-solid fa-paperclip mr-1"></i> {{ $fileCount }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-6 md:p-8">
                    {{-- Content --}}
                    <div class="prose max-w-none prose-headings:text-gray-800 prose-p:text-gray-600 prose-a:text-primary">
                        <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                            {{ $notice->content ?? 'No content available.' }}
                        </div>
                    </div>

                    {{-- Files Section --}}
                    @php
                        $filePaths = isset($notice) ? $notice->file_paths : null;
                        if (is_string($filePaths)) {
                            $filePaths = json_decode($filePaths, true) ?? [];
                        }
                        $fileNames = isset($notice) ? $notice->file_names : null;
                        if (is_string($fileNames)) {
                            $fileNames = json_decode($fileNames, true) ?? [];
                        }
                        $fileCount = is_array($filePaths) ? count($filePaths) : 0;
                    @endphp

                    @if($fileCount > 0)
                    <div class="mt-8">
                        <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-paperclip text-primary"></i>
                            Attached Files
                            <span class="text-xs text-gray-400 font-normal">({{ $fileCount }} files)</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($filePaths as $index => $path)
                            <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between hover:shadow-md transition border border-gray-200">
                                <div class="flex items-center gap-3 min-w-0">
                                    @php
                                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                                        $iconMap = [
                                            'pdf' => 'fa-regular fa-file-pdf text-red-600',
                                            'doc' => 'fa-regular fa-file-word text-blue-600',
                                            'docx' => 'fa-regular fa-file-word text-blue-600',
                                            'xls' => 'fa-regular fa-file-excel text-green-600',
                                            'xlsx' => 'fa-regular fa-file-excel text-green-600',
                                            'jpg' => 'fa-regular fa-file-image text-purple-600',
                                            'jpeg' => 'fa-regular fa-file-image text-purple-600',
                                            'png' => 'fa-regular fa-file-image text-purple-600',
                                            'gif' => 'fa-regular fa-file-image text-purple-600',
                                            'webp' => 'fa-regular fa-file-image text-purple-600',
                                            'svg' => 'fa-regular fa-file-image text-purple-600',
                                            'zip' => 'fa-regular fa-file-archive text-amber-600',
                                            'rar' => 'fa-regular fa-file-archive text-amber-600',
                                            'txt' => 'fa-regular fa-file-lines text-gray-600',
                                            'csv' => 'fa-regular fa-file-csv text-green-600',
                                        ];
                                        $iconClass = $iconMap[$ext] ?? 'fa-regular fa-file text-gray-600';
                                        $fileName = is_array($fileNames) && isset($fileNames[$index]) ? $fileNames[$index] : basename($path);
                                        $fileExists = Storage::disk('public')->exists($path);
                                    @endphp
                                    <i class="{{ $iconClass }} text-2xl flex-shrink-0"></i>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $fileName }}</p>
                                        <p class="text-xs text-gray-400">
                                            @if($fileExists)
                                                {{ number_format(Storage::disk('public')->size($path) / 1024, 2) }} KB
                                            @else
                                                File not found
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if($fileExists)
                                    <a href="{{ route('frontend.notices.download', [$notice, $index]) }}"
                                       class="flex-shrink-0 inline-flex items-center gap-1.5 bg-primary text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-primary/80 transition">
                                        <i class="fa-solid fa-download"></i> Download
                                    </a>
                                @else
                                    <span class="flex-shrink-0 inline-flex items-center gap-1.5 bg-gray-300 text-gray-500 px-3 py-1.5 rounded-lg text-xs font-semibold cursor-not-allowed">
                                        <i class="fa-solid fa-xmark"></i> Unavailable
                                    </span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Meta Info Grid --}}
                    <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-gray-50 rounded-xl p-4 text-center hover:bg-gray-100 transition">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Type</p>
                            <p class="text-sm font-semibold text-gray-700 mt-1">{{ isset($notice) ? ucfirst($notice->type) : 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center hover:bg-gray-100 transition">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Priority</p>
                            <p class="text-sm font-semibold text-{{ isset($notice) ? $notice->priority_color : 'gray' }}-600 mt-1">
                                {{ isset($notice) ? $notice->priority_label : 'N/A' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center hover:bg-gray-100 transition">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Published</p>
                            <p class="text-sm font-semibold text-gray-700 mt-1">
                                {{ isset($notice->published_at) ? $notice->published_at->format('d M Y') : 'N/A' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center hover:bg-gray-100 transition">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Views</p>
                            <p class="text-sm font-semibold text-gray-700 mt-1">{{ $notice->views ?? 0 }}</p>
                        </div>
                    </div>

                    {{-- Share & Actions --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Share this notice:</p>
                            <div class="flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                   target="_blank" class="w-9 h-9 rounded-full bg-[#1877f2] text-white flex items-center justify-center hover:scale-110 transition">
                                    <i class="fa-brands fa-facebook-f text-sm"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($notice->title ?? 'Notice') }}"
                                   target="_blank" class="w-9 h-9 rounded-full bg-[#000000] text-white flex items-center justify-center hover:scale-110 transition">
                                    <i class="fa-brands fa-x-twitter text-sm"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                                   target="_blank" class="w-9 h-9 rounded-full bg-[#0a66c2] text-white flex items-center justify-center hover:scale-110 transition">
                                    <i class="fa-brands fa-linkedin-in text-sm"></i>
                                </a>
                                <a href="mailto:?subject={{ urlencode($notice->title ?? 'Notice') }}&body={{ urlencode($notice->content ?? '') }}"
                                   class="w-9 h-9 rounded-full bg-gray-600 text-white flex items-center justify-center hover:scale-110 transition">
                                    <i class="fa-regular fa-envelope text-sm"></i>
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('frontend.notices.index') }}"
                           class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                            <i class="fa-solid fa-arrow-left"></i> Back to Notices
                        </a>
                    </div>
                </div>
            </div>

            {{-- Related Notices --}}
            @if(isset($relatedNotices) && $relatedNotices->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-link text-primary"></i>
                    Related Notices
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($relatedNotices as $related)
                    <a href="{{ route('frontend.notices.show', $related) }}"
                       class="bg-white rounded-xl p-4 shadow-sm hover:shadow-lg transition border-l-4 border-primary hover:border-primary/50">
                        <h4 class="font-semibold text-gray-800 hover:text-primary transition">{{ $related->title }}</h4>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fa-regular fa-calendar mr-1"></i>
                            {{ $related->published_at ? $related->published_at->format('d M Y') : 'N/A' }}
                            <span class="mx-2">|</span>
                            <span class="px-2 py-0.5 bg-{{ $related->priority_color }}-50 text-{{ $related->priority_color }}-700 text-[10px] font-bold rounded-full">
                                {{ $related->priority_label }}
                            </span>
                        </p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</section>

@endsection