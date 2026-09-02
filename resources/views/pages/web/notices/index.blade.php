{{-- resources/views/pages/web/notices/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Notices & Announcements')
@section('meta_description', 'Stay updated with the latest notices and announcements from REIAC')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-r from-[#1a2f5e] to-[#2a4f8e] text-white py-16">
    <div class="absolute inset-0 opacity-10" style="background-image: url('{{ asset('images/pattern.png') }}'); background-size: cover;"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl">
            <div class="flex items-center gap-3 mb-4">
                <i class="fa-solid fa-bullhorn text-3xl text-gold"></i>
                <span class="bg-gold/20 text-gold px-4 py-1 rounded-full text-sm font-semibold">Latest Updates</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Notices & <span class="text-gold">Announcements</span></h1>
            <p class="text-lg text-white/80">Stay updated with the latest announcements, updates, and important information from REIAC.</p>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">

        {{-- Filter/Search Bar --}}
        <form method="GET" action="{{ route('frontend.notices.index') }}" class="bg-white rounded-2xl shadow-sm p-4 mb-8 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-600">Filter:</span>
                <select id="filterType" class="rounded-xl border border-gray-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Types</option>
                    <option value="general" @selected(request('type') === 'general')>General</option>
                    <option value="important" @selected(request('type') === 'important')>Important</option>
                    <option value="urgent" @selected(request('type') === 'urgent')>Urgent</option>
                    <option value="event" @selected(request('type') === 'event')>Event</option>
                    <option value="update" @selected(request('type') === 'update')>Update</option>
                </select>
                <select id="filterPriority" class="rounded-xl border border-gray-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Priority</option>
                    <option value="low" @selected(request('priority') === 'low')>Low</option>
                    <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                    <option value="high" @selected(request('priority') === 'high')>High</option>
                    <option value="urgent" @selected(request('priority') === 'urgent')>Urgent</option>
                </select>
            </div>
            <div class="relative">
                <input type="search" id="searchNotices" name="search" value="{{ request('search') }}" placeholder="Search notices..."
                       class="w-full md:w-64 rounded-xl border border-gray-200 px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <button type="submit" aria-label="Search notices" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition">
                    <i class="fa-solid fa-search text-sm"></i>
                </button>
            </div>
        </form>

        {{-- Pinned Notices Section --}}
        @if($pinnedNotices->count() > 0)
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1 h-6 bg-red-500 rounded-full"></div>
                <h2 class="text-xl font-bold text-gray-800">📌 Pinned Notices</h2>
                <span class="text-xs text-white bg-red-500 px-3 py-0.5 rounded-full">{{ $pinnedNotices->count() }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pinnedNotices as $notice)
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border-2 border-red-200 hover:border-red-400">
                    <div class="relative">
                        @if($notice->priority == 'urgent')
                            <div class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg">
                                ⚡ URGENT
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fa-solid fa-thumbtack text-red-500 text-sm"></i>
                                <span class="text-xs text-red-500 font-semibold">PINNED</span>
                            </div>
                            <a href="{{ route('frontend.notices.show', $notice) }}" class="block">
                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-primary transition line-clamp-2">
                                    {{ $notice->title }}
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-3 mt-2">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($notice->content), 120) }}
                                </p>
                            </a>
                            <div class="flex items-center justify-between text-xs text-gray-400 mt-4 pt-3 border-t border-gray-100">
                                <div class="flex items-center gap-3">
                                    <span>
                                        <i class="fa-regular fa-calendar mr-1"></i>
                                        {{ $notice->published_at ? $notice->published_at->format('d M Y') : 'N/A' }}
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-eye mr-1"></i>
                                        {{ $notice->views }}
                                    </span>
                                </div>
                                @php
                                    $filePaths = $notice->file_paths;
                                    if (is_string($filePaths)) {
                                        $filePaths = json_decode($filePaths, true) ?? [];
                                    }
                                    $fileCount = is_array($filePaths) ? count($filePaths) : 0;
                                @endphp
                                @if($fileCount > 0)
                                    <span class="text-blue-600">
                                        <i class="fa-solid fa-paperclip mr-1"></i>
                                        {{ $fileCount }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- All Notices Grid --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1 h-6 bg-primary rounded-full"></div>
                <h2 class="text-xl font-bold text-gray-800">All Notices</h2>
                <span class="text-xs text-white bg-primary px-3 py-0.5 rounded-full">{{ $notices->total() }}</span>
            </div>

            @if($notices->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($notices as $notice)
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden hover:-translate-y-1">
                        {{-- Priority Banner --}}
                        @if($notice->priority == 'urgent')
                            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-4 py-1.5 text-center">
                                ⚡ URGENT NOTICE
                            </div>
                        @elseif($notice->priority == 'high')
                            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold px-4 py-1.5 text-center">
                                🔥 HIGH PRIORITY
                            </div>
                        @elseif($notice->priority == 'medium')
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-bold px-4 py-1.5 text-center">
                                📌 MEDIUM PRIORITY
                            </div>
                        @endif

                        <div class="p-6">
                            {{-- Pinned Badge --}}
                            @if($notice->is_pinned)
                                <div class="flex items-center gap-1 text-red-500 text-xs font-semibold mb-2">
                                    <i class="fa-solid fa-thumbtack"></i>
                                    <span>Pinned</span>
                                </div>
                            @endif

                            <a href="{{ route('frontend.notices.show', $notice) }}" class="block">
                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-primary transition line-clamp-2">
                                    {{ $notice->title }}
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-3 mt-2">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($notice->content), 100) }}
                                </p>
                            </a>

                            <div class="flex items-center justify-between text-xs text-gray-400 mt-4 pt-3 border-t border-gray-100">
                                <div class="flex items-center gap-3">
                                    <span>
                                        <i class="fa-regular fa-calendar mr-1"></i>
                                        {{ $notice->published_at ? $notice->published_at->format('d M Y') : 'N/A' }}
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-eye mr-1"></i>
                                        {{ $notice->views }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php
                                        $filePaths = $notice->file_paths;
                                        if (is_string($filePaths)) {
                                            $filePaths = json_decode($filePaths, true) ?? [];
                                        }
                                        $fileCount = is_array($filePaths) ? count($filePaths) : 0;
                                    @endphp
                                    @if($fileCount > 0)
                                        <span class="text-blue-600">
                                            <i class="fa-solid fa-paperclip mr-1"></i>
                                            {{ $fileCount }}
                                        </span>
                                    @endif
                                    <span class="px-2 py-0.5 bg-{{ $notice->priority_color }}-50 text-{{ $notice->priority_color }}-700 text-[10px] font-bold rounded-full">
                                        {{ $notice->priority_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $notices->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-inbox text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Notices Found</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">There are no notices available at the moment. Please check back later.</p>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-12 bg-primary">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-white mb-2">📬 Stay Updated</h3>
            <p class="text-white/80 mb-6">Subscribe to get notified about new notices and announcements.</p>
            <div class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                <input type="email" placeholder="Enter your email"
                       class="flex-1 rounded-xl border-0 px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-gold">
                <button class="bg-gold text-white px-6 py-3 rounded-xl font-semibold hover:bg-yellow-600 transition whitespace-nowrap">
                    Subscribe Now
                </button>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchNotices');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const searchValue = this.value.trim();
                if (searchValue) {
                    window.location.href = "{{ route('frontend.notices.index') }}?search=" + encodeURIComponent(searchValue);
                } else {
                    window.location.href = "{{ route('frontend.notices.index') }}";
                }
            }
        });
    }

    // Filter change
    document.getElementById('filterType')?.addEventListener('change', function() {
        applyFilters();
    });
    
    document.getElementById('filterPriority')?.addEventListener('change', function() {
        applyFilters();
    });

    function applyFilters() {
        const type = document.getElementById('filterType').value;
        const priority = document.getElementById('filterPriority').value;
        let url = "{{ route('frontend.notices.index') }}?";
        if (type) url += 'type=' + type + '&';
        if (priority) url += 'priority=' + priority;
        if (url.endsWith('&') || url.endsWith('?')) {
            url = url.slice(0, -1);
        }
        if (url !== "{{ route('frontend.notices.index') }}?") {
            window.location.href = url;
        }
    }
});
</script>
@endpush
@endsection