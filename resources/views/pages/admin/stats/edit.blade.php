@extends('layouts.admin')
@section('title', 'Stats Section')
@section('page-title', 'Stats Section')

@section('content')
<div class="max-w-3xl mx-auto">

    <form action="{{ route('admin.stats.update') }}" method="POST">
        @csrf

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-8 py-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Stats Section</h2>
                <p class="text-sm text-gray-500 mt-1">Manage the "Why Choose REIAC" section on the homepage.</p>
            </div>

            <div class="p-8 space-y-6">

                {{-- Badge --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Badge Text</label>
                    <input type="text" name="stats_badge"
                        value="{{ old('stats_badge', $setting->stats_badge ?? 'WHY CHOOSE REIAC?') }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>

                {{-- Heading --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Heading Line 1</label>
                        <input type="text" name="stats_heading_line1"
                            value="{{ old('stats_heading_line1', $setting->stats_heading_line1 ?? 'Trusted Guidance.') }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Heading Line 2</label>
                        <input type="text" name="stats_heading_line2"
                            value="{{ old('stats_heading_line2', $setting->stats_heading_line2 ?? 'Proven') }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Highlighted Word</label>
                        <input type="text" name="stats_heading_highlight"
                            value="{{ old('stats_heading_highlight', $setting->stats_heading_highlight ?? 'Results.') }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    </div>
                </div>

                {{-- Subtext --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sub Text</label>
                    <textarea name="stats_subtext" rows="3"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('stats_subtext', $setting->stats_subtext ?? '') }}</textarea>
                </div>

                <hr class="border-gray-100">

                {{-- Stat Cards --}}
                <div>
                    <p class="text-sm font-bold text-gray-700 mb-4">Stat Cards</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Auto: Admissions --}}
                        <div class="rounded-xl border border-green-100 bg-green-50 p-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fa-solid fa-shield-halved text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Successful Admissions</p>
                                <p class="text-2xl font-extrabold text-green-700 mt-0.5">{{ $statAdmissions }}+</p>
                                <p class="text-xs text-gray-400 mt-0.5">Auto-counted from Applications</p>
                            </div>
                        </div>

                        {{-- Auto: Partners --}}
                        <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fa-solid fa-building-columns text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Partner Institutions</p>
                                <p class="text-2xl font-extrabold text-blue-700 mt-0.5">{{ $statPartners }}+</p>
                                <p class="text-xs text-gray-400 mt-0.5">Auto-counted from Partners</p>
                            </div>
                        </div>

                        {{-- Manual: Countries --}}
                        <div class="rounded-xl border border-gray-200 p-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa-solid fa-globe text-[#1a2f5e] mr-1"></i> Countries Covered
                            </label>
                            <input type="text" name="stat_countries"
                                value="{{ old('stat_countries', $setting->stat_countries ?? '15+') }}"
                                placeholder="e.g. 15+"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                        </div>

                        {{-- Manual: Satisfaction --}}
                        <div class="rounded-xl border border-gray-200 p-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa-solid fa-users text-[#1a2f5e] mr-1"></i> Student Satisfaction
                            </label>
                            <input type="text" name="stat_satisfaction"
                                value="{{ old('stat_satisfaction', $setting->stat_satisfaction ?? '98%') }}"
                                placeholder="e.g. 98%"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                        </div>

                    </div>
                </div>

            </div>

            <div class="px-8 py-5 border-t border-gray-100 bg-gray-50 flex justify-end">
                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-[#1a2f5e] text-white font-medium hover:bg-[#132247] transition-all">
                    Save Stats
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
