@extends('layouts.admin')

@section('title', 'Edit Feature')
@section('page-title', 'Edit Feature')

@section('content')
<div class="max-w-3xl mx-auto">

    <form action="{{ route('admin.features.update', $feature) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Edit Feature</h2>
                <p class="text-sm text-gray-500 mt-1">Modify the feature card details.</p>
            </div>

            <div class="p-6 space-y-6">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $feature->title) }}"
                           required
                           placeholder="e.g., Global University Admissions"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('title')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Subtitle
                    </label>
                    <input type="text"
                           name="subtitle"
                           value="{{ old('subtitle', $feature->subtitle) }}"
                           placeholder="e.g., Top universities across the world."
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('subtitle')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Icon Class (FontAwesome)
                    </label>
                    <input type="text"
                           name="icon"
                           value="{{ old('icon', $feature->icon) }}"
                           placeholder="e.g., fa-solid fa-graduation-cap"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('icon')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               {{ old('status', $feature->status) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-[#1a2f5e] focus:ring-[#1a2f5e]">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.features.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-white transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition">
                    Update Feature
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
