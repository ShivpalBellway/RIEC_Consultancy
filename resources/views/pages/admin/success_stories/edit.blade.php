@extends('layouts.admin')

@section('title', 'Edit Success Story')
@section('page-title', 'Edit Success Story')

@section('content')
<div class="max-w-3xl mx-auto">

    <form action="{{ route('admin.success-stories.update', $successStory) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Edit Success Story</h2>
                <p class="text-sm text-gray-500 mt-1">Modify the testimonial or success story details.</p>
            </div>

            <div class="p-6 space-y-6">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $successStory->name) }}"
                           required
                           placeholder="e.g., Min Jae Kim"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Role
                    </label>
                    <input type="text"
                           name="role"
                           value="{{ old('role', $successStory->role) }}"
                           placeholder="e.g., Student, Canada"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('role')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Image
                    </label>
                    @if($successStory->image)
                        <div class="mb-3">
                            <img src="{{ Str::startsWith($successStory->image, 'http') ? $successStory->image : asset('storage/' . $successStory->image) }}"
                                 class="w-16 h-16 rounded-full object-cover border border-gray-200">
                            <p class="text-xs text-gray-400 mt-1">Current image — upload new to replace</p>
                        </div>
                    @endif
                    <input type="file"
                           name="image"
                           accept="image/*"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('image')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Review / Testimonial <span class="text-red-500">*</span>
                    </label>
                    <textarea name="review"
                              rows="5"
                              required
                              placeholder="Enter the testimonial text..."
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('review', $successStory->review) }}</textarea>
                    @error('review')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               {{ old('status', $successStory->status) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-[#1a2f5e] focus:ring-[#1a2f5e]">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.success-stories.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-white transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition">
                    Update Story
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
