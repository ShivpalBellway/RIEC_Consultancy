@extends('layouts.admin')

@section('title', 'Contact Message')
@section('page-title', 'Contact Message Details')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="grid lg:grid-cols-12 gap-6">

        {{-- LEFT PROFILE CARD --}}
        <div class="lg:col-span-4">
            <div class="bg-gradient-to-br from-[#061d43] via-[#102b5c] to-[#1a2f5e] rounded-3xl shadow-xl overflow-hidden sticky top-6">
                <div class="p-7 text-white relative">
                    <div class="absolute -top-12 -right-12 w-36 h-36 bg-[#dca737]/20 rounded-full blur-2xl"></div>

                    <div class="w-16 h-16 rounded-2xl bg-[#dca737] flex items-center justify-center shadow-lg mb-5">
                        <i class="fa-solid fa-envelope-open-text text-white text-2xl"></i>
                    </div>

                    <p class="text-xs uppercase tracking-[0.25em] text-[#dca737] font-bold mb-2">
                        Contact Inquiry
                    </p>

                    <h2 class="text-2xl font-black leading-tight">
                        {{ $contactMessage->name }}
                    </h2>

                    <p class="text-sm text-white/65 mt-2">
                        Message #{{ $contactMessage->id }}
                    </p>

                    <div class="mt-6">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-500/15 text-green-300 border border-green-400/20 text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            Read Message
                        </span>
                    </div>

                    <div class="mt-7 space-y-4">
                        <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                            <p class="text-xs text-white/45 mb-1">Email Address</p>
                            <a href="mailto:{{ $contactMessage->email }}" class="text-sm font-bold text-white hover:text-[#dca737] break-all">
                                {{ $contactMessage->email }}
                            </a>
                        </div>

                        <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                            <p class="text-xs text-white/45 mb-1">Phone Number</p>
                            @if($contactMessage->phone)
                                <a href="tel:{{ $contactMessage->phone }}" class="text-sm font-bold text-white hover:text-[#dca737]">
                                    {{ $contactMessage->phone }}
                                </a>
                            @else
                                <span class="text-sm font-bold text-white/60">—</span>
                            @endif
                        </div>

                        <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                            <p class="text-xs text-white/45 mb-1">Received On</p>
                            <p class="text-sm font-bold text-white">
                                {{ $contactMessage->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-xs text-white/50 mt-1">
                                {{ $contactMessage->created_at->format('h:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-7 border-t border-white/10 pt-5">
                        <a href="{{ route('admin.contact-messages.index') }}"
                           class="inline-flex items-center gap-2 text-sm text-white/70 hover:text-[#dca737] font-bold transition">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back to Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT DETAILS --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Header --}}
                <div class="px-7 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.18em] mb-2">
                                Message Subject
                            </p>

                            <h2 class="font-black text-gray-900 text-xl leading-snug">
                                {{ $contactMessage->subject ?: 'No Subject' }}
                            </h2>

                            <p class="text-xs text-gray-400 mt-2">
                                Received on {{ $contactMessage->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>

                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 text-green-600 text-xs font-black border border-green-100">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            Read
                        </span>
                    </div>
                </div>

                {{-- Quick Info --}}
                <div class="p-7">
                    <div class="grid md:grid-cols-3 gap-4 mb-6">

                        <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5">
                            <div class="w-10 h-10 rounded-xl bg-[#1a2f5e]/10 text-[#1a2f5e] flex items-center justify-center mb-3">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <p class="text-[11px] text-gray-400 font-black uppercase tracking-wider mb-1">Name</p>
                            <p class="font-black text-gray-900 text-sm">{{ $contactMessage->name }}</p>
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5">
                            <div class="w-10 h-10 rounded-xl bg-[#dca737]/10 text-[#dca737] flex items-center justify-center mb-3">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <p class="text-[11px] text-gray-400 font-black uppercase tracking-wider mb-1">Email</p>
                            <a href="mailto:{{ $contactMessage->email }}"
                               class="font-black text-[#1a2f5e] text-sm hover:underline break-all">
                                {{ $contactMessage->email }}
                            </a>
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <p class="text-[11px] text-gray-400 font-black uppercase tracking-wider mb-1">Phone</p>
                            @if($contactMessage->phone)
                                <a href="tel:{{ $contactMessage->phone }}"
                                   class="font-black text-gray-900 text-sm hover:text-[#1a2f5e]">
                                    {{ $contactMessage->phone }}
                                </a>
                            @else
                                <p class="font-black text-gray-400 text-sm">—</p>
                            @endif
                        </div>

                    </div>

                    {{-- Message Box --}}
                    <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-gray-50 to-white p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-11 h-11 rounded-2xl bg-[#1a2f5e] text-[#dca737] flex items-center justify-center shadow-lg shadow-[#1a2f5e]/15">
                                <i class="fa-solid fa-message"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 text-sm">Message Content</h3>
                                <p class="text-xs text-gray-400">Full inquiry message submitted from website contact form.</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <p class="text-sm text-gray-700 leading-8 whitespace-pre-line">
                                {{ $contactMessage->message }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="px-7 py-5 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.contact-messages.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 hover:text-gray-900 hover:bg-gray-50 text-sm font-bold transition">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back
                        </a>

                        <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ $contactMessage->subject ?: 'Your Inquiry' }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#1a2f5e] text-white hover:bg-[#142447] text-sm font-bold transition shadow-lg shadow-[#1a2f5e]/15">
                            <i class="fa-solid fa-reply"></i>
                            Reply
                        </a>
                    </div>

                    <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST"
                          onsubmit="return confirm('Delete this message?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white text-sm font-bold transition">
                            <i class="fa-solid fa-trash"></i>
                            Delete
                        </button>
                    </form>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection
