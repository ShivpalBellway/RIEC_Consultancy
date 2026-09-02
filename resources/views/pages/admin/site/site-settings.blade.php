@extends('layouts.admin')

@section('title', 'Site Settings')
@section('page-title', 'Site Settings')

@section('content')

<div class="max-w-4xl mx-auto">

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.site.settings.update') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

            <!-- Header -->
            <div class="px-8 py-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Website Branding</h2>
                <p class="text-sm text-gray-500 mt-1">Manage website logos and hero section from a single place.</p>
            </div>

            <div class="p-8 space-y-8">

                <!-- Header Logo -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Header Logo</label>
                    <input type="file" name="header_logo"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @if($setting && $setting->header_logo)
                        <div class="mt-4 p-4 border border-gray-100 rounded-2xl bg-gray-50 inline-block">
                            <img src="{{ asset('storage/'.$setting->header_logo) }}" class="h-20 object-contain" alt="Header Logo">
                        </div>
                    @endif
                </div>

                <!-- Footer Logo -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Footer Logo</label>
                    <input type="file" name="footer_logo"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @if($setting && $setting->footer_logo)
                        <div class="mt-4 p-4 border border-gray-100 rounded-2xl bg-gray-50 inline-block">
                            <img src="{{ asset('storage/'.$setting->footer_logo) }}" class="h-20 object-contain" alt="Footer Logo">
                        </div>
                    @endif
                </div>

                <hr class="border-gray-100">

                <!-- Hero Section -->
                <div>
                    <h3 class="text-base font-bold text-gray-800 mb-5">Hero Section</h3>
                    <div class="space-y-5">

                        <!-- Hero Image -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Hero Background Image</label>
                            <input type="file" name="hero_image" accept="image/*"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                            @if($setting && $setting->hero_image)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/'.$setting->hero_image) }}" class="h-28 rounded-xl object-cover" alt="Hero Image">
                                    <p class="text-xs text-gray-400 mt-1">Current image — upload new to replace</p>
                                </div>
                            @endif
                        </div>

                        <!-- Badge Text -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Badge Text</label>
                            <input type="text" name="hero_badge"
                                value="{{ old('hero_badge', $setting->hero_badge ?? 'REIAC CONSULTING') }}"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                        </div>

                        <!-- Heading Lines -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Heading Line 1</label>
                                <input type="text" name="hero_heading_line1"
                                    value="{{ old('hero_heading_line1', $setting->hero_heading_line1 ?? 'Your Global Education') }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Heading Line 2</label>
                                <input type="text" name="hero_heading_line2"
                                    value="{{ old('hero_heading_line2', $setting->hero_heading_line2 ?? 'Partner for a') }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Highlighted Word</label>
                                <input type="text" name="hero_heading_highlight"
                                    value="{{ old('hero_heading_highlight', $setting->hero_heading_highlight ?? 'Better Tomorrow') }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                            </div>
                        </div>

                        <!-- Subtext -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sub Text</label>
                            <textarea name="hero_subtext" rows="3"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('hero_subtext', $setting->hero_subtext ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

                <hr class="border-gray-100">

                <div>
                    <h3 class="text-base font-bold text-gray-800 mb-2">Application Notifications</h3>
                    <p class="text-sm text-gray-500 mb-5">New application emails and their current attachments will be sent to this address.</p>

                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="application_recipient_email">
                        Recipient Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="application_recipient_email"
                        name="application_recipient_email"
                        value="{{ old('application_recipient_email', $setting->application_recipient_email ?? config('mail.application_recipient')) }}"
                        required
                        autocomplete="email"
                        placeholder="applications@example.com"
                        class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] {{ $errors->has('application_recipient_email') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('application_recipient_email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Footer -->
            <div class="px-8 py-5 border-t border-gray-100 bg-gray-50 flex justify-end">

                <button
                    type="submit"
                    class="px-6 py-3 rounded-xl bg-[#1a2f5e] text-white font-medium hover:bg-[#132247] transition-all">
                    Save Settings
                </button>

            </div>

        </div>

    </form>

</div>

@endsection
