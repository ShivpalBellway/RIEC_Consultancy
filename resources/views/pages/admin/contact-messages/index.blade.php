@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-[#061d43] via-[#102b5c] to-[#1a2f5e] rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#dca737]/20 rounded-full blur-2xl"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-xs text-white/60 font-bold uppercase tracking-wider">Total Messages</p>
                    <h3 class="text-3xl font-black mt-2">{{ $messages->total() }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-white/10 text-[#dca737] flex items-center justify-center">
                    <i class="fa-solid fa-envelope-open-text text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Unread</p>
                    <h3 class="text-3xl font-black text-amber-500 mt-2">
                        {{ $messages->where('is_read', false)->count() }}
                    </h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
                    <i class="fa-solid fa-envelope text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Read</p>
                    <h3 class="text-3xl font-black text-green-600 mt-2">
                        {{ $messages->where('is_read', true)->count() }}
                    </h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#1a2f5e] text-[#dca737] flex items-center justify-center shadow-lg shadow-[#1a2f5e]/15">
                        <i class="fa-solid fa-inbox text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-black text-gray-900 text-lg">Website Contact Messages</h2>
                        <p class="text-xs text-gray-400 mt-1">All website contact form inquiries and user messages.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-[#1a2f5e]/5 text-[#1a2f5e] text-xs font-bold">
                        <i class="fa-regular fa-clock"></i>
                        Latest First
                    </span>
                </div>
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/80 text-[11px] text-gray-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left font-black">User</th>
                        <th class="px-6 py-4 text-left font-black">Contact</th>
                        <th class="px-6 py-4 text-left font-black">Subject</th>
                        <th class="px-6 py-4 text-left font-black">Status</th>
                        <th class="px-6 py-4 text-left font-black">Date</th>
                        <th class="px-6 py-4 text-right font-black">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($messages as $msg)
                        <tr class="group hover:bg-gray-50/80 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-2xl bg-[#1a2f5e]/10 text-[#1a2f5e] flex items-center justify-center font-black">
                                        {{ strtoupper(substr($msg->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900">{{ $msg->name }}</div>
                                        <div class="text-xs text-gray-400">Message #{{ $msg->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <a href="mailto:{{ $msg->email }}" class="text-xs font-bold text-[#1a2f5e] hover:underline block break-all">
                                    {{ $msg->email }}
                                </a>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $msg->phone ?: 'No phone' }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800 max-w-[260px] truncate">
                                    {{ $msg->subject ?: 'No Subject' }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1 max-w-[260px] truncate">
                                    {{ Str::limit($msg->message, 55) }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if($msg->is_read)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 text-green-600 text-xs font-black border border-green-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Read
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 text-amber-600 text-xs font-black border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Unread
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-gray-700">
                                    {{ $msg->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $msg->created_at->format('h:i A') }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.contact-messages.show', $msg) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1a2f5e] text-white text-xs font-black hover:bg-[#142447] hover:-translate-y-0.5 transition-all shadow-lg shadow-[#1a2f5e]/15">
                                    <i class="fa-solid fa-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-50 text-gray-300 flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-inbox text-3xl"></i>
                                </div>
                                <h3 class="font-black text-gray-700">No contact messages found</h3>
                                <p class="text-xs text-gray-400 mt-1">Website inquiries will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="lg:hidden divide-y divide-gray-100">
            @forelse($messages as $msg)
                <div class="p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-[#1a2f5e]/10 text-[#1a2f5e] flex items-center justify-center font-black">
                                {{ strtoupper(substr($msg->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900">{{ $msg->name }}</h3>
                                <p class="text-xs text-gray-400">#{{ $msg->id }}</p>
                            </div>
                        </div>

                        @if($msg->is_read)
                            <span class="px-2.5 py-1 rounded-full bg-green-50 text-green-600 text-[11px] font-black">Read</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[11px] font-black">Unread</span>
                        @endif
                    </div>

                    <div class="mt-4 rounded-2xl bg-gray-50 p-4 space-y-2">
                        <p class="text-xs text-gray-500">
                            <i class="fa-solid fa-envelope text-[#dca737] w-4"></i>
                            {{ $msg->email }}
                        </p>
                        <p class="text-xs text-gray-500">
                            <i class="fa-solid fa-phone text-[#dca737] w-4"></i>
                            {{ $msg->phone ?: 'No phone' }}
                        </p>
                        <p class="text-sm font-bold text-gray-800 pt-2">
                            {{ $msg->subject ?: 'No Subject' }}
                        </p>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <p class="text-xs text-gray-400">
                            {{ $msg->created_at->format('M d, Y h:i A') }}
                        </p>

                        <a href="{{ route('admin.contact-messages.show', $msg) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#1a2f5e] text-white text-xs font-black">
                            <i class="fa-solid fa-eye"></i>
                            View
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center">
                    <i class="fa-solid fa-inbox text-4xl text-gray-200 mb-3"></i>
                    <p class="text-gray-400 text-sm">No contact messages found.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-5 border-t border-gray-100 bg-gray-50">
            {{ $messages->links() }}
        </div>

    </div>
</div>
@endsection
