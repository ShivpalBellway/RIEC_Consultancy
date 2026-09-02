@extends('layouts.admin')
@section('title', 'Contact Page Settings')
@section('page-title', 'Contact Page Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.contact-settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- HERO SECTION --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-8 py-5 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Hero Section</h2>
            </div>
            <div class="p-8 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hero Background Image</label>
                    @if($setting->contact_hero_image)
                        <img src="{{ asset('storage/'.$setting->contact_hero_image) }}" class="h-24 rounded-xl object-cover w-full mb-2">
                        <p class="text-xs text-gray-400 mb-2">Current — upload new to replace</p>
                    @endif
                    <input type="file" name="contact_hero_image" accept="image/*"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Badge Text</label>
                    <input type="text" name="contact_hero_badge"
                        value="{{ old('contact_hero_badge', $setting->contact_hero_badge ?? 'CONTACT US') }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Heading Line 1</label>
                        <input type="text" name="contact_hero_heading_line1"
                            value="{{ old('contact_hero_heading_line1', $setting->contact_hero_heading_line1 ?? "We'd Love to") }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Highlighted Word</label>
                        <input type="text" name="contact_hero_heading_highlight"
                            value="{{ old('contact_hero_heading_highlight', $setting->contact_hero_heading_highlight ?? 'Hear From You!') }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sub Text</label>
                    <textarea name="contact_hero_subtext" rows="2"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('contact_hero_subtext', $setting->contact_hero_subtext ?? '') }}</textarea>
                </div>

            </div>
        </div>

        {{-- CONTACT INFO --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-8 py-5 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Contact Information</h2>
            </div>
            <div class="p-8 space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fa-solid fa-phone text-[#1a2f5e] mr-1"></i> Phone</label>
                        <input type="text" name="contact_phone"
                            value="{{ old('contact_phone', $setting->contact_phone ?? '') }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fa-regular fa-clock text-[#1a2f5e] mr-1"></i> Office Hours</label>
                        <input type="text" name="contact_hours"
                            value="{{ old('contact_hours', $setting->contact_hours ?? '') }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fa-solid fa-envelope text-[#1a2f5e] mr-1"></i> Email</label>
                    <input type="text" name="contact_email"
                        value="{{ old('contact_email', $setting->contact_email ?? '') }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fa-solid fa-location-dot text-[#1a2f5e] mr-1"></i> Address (English)</label>
                        <textarea name="contact_address_en" rows="3"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('contact_address_en', $setting->contact_address_en ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Address (Korean)</label>
                        <textarea name="contact_address_ko" rows="3"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('contact_address_ko', $setting->contact_address_ko ?? '') }}</textarea>
                    </div>
                </div>

            </div>
        </div>

        {{-- SOCIAL LINKS --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-8 py-5 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Social Media Links</h2>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fa-brands fa-instagram text-pink-500 mr-1"></i> Instagram URL</label>
                    <input type="text" name="social_instagram"
                        value="{{ old('social_instagram', $setting->social_instagram ?? '') }}"
                        placeholder="https://instagram.com/..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fa-brands fa-facebook text-blue-600 mr-1"></i> Facebook URL</label>
                    <input type="text" name="social_facebook"
                        value="{{ old('social_facebook', $setting->social_facebook ?? '') }}"
                        placeholder="https://facebook.com/..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fa-brands fa-linkedin text-blue-700 mr-1"></i> LinkedIn URL</label>
                    <input type="text" name="social_linkedin"
                        value="{{ old('social_linkedin', $setting->social_linkedin ?? '') }}"
                        placeholder="https://linkedin.com/..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2"><i class="fa-brands fa-youtube text-red-600 mr-1"></i> YouTube URL</label>
                    <input type="text" name="social_youtube"
                        value="{{ old('social_youtube', $setting->social_youtube ?? '') }}"
                        placeholder="https://youtube.com/..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>
            </div>
        </div>

        {{-- MAP --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-8 py-5 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Map Settings</h2>
            </div>
            <div class="p-8 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Google Maps Direction URL</label>
                    <input type="text" name="contact_map_url"
                        value="{{ old('contact_map_url', $setting->contact_map_url ?? '') }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Google Maps Embed URL
                        <span class="text-xs font-normal text-gray-400 ml-1">( src="..." se sirf URL copy karo)</span>
                    </label>
                    <textarea name="contact_map_embed" rows="3"
                        placeholder="https://maps.google.com/maps?q=..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('contact_map_embed', $setting->contact_map_embed ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="px-8 py-3 rounded-xl bg-[#1a2f5e] text-white font-semibold hover:bg-[#132247] transition">
                Save Contact Settings
            </button>
        </div>

    </form>
</div>
@endsection
