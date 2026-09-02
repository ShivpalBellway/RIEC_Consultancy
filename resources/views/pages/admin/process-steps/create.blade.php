@extends('layouts.admin')
@section('title', 'Add Process Step')
@section('page-title', 'Add Process Step')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('admin.process-steps.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Add New Process Step</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Icon Class <span class="text-red-500">*</span></label>
                    <input type="text" name="icon" value="{{ old('icon', 'fa-regular fa-star') }}" required
                        placeholder="e.g. fa-regular fa-comments"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    <p class="text-xs text-gray-400 mt-1">Use Font Awesome class e.g. <code>fa-solid fa-folder-open</code></p>
                    @error('icon')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        placeholder="e.g. Consultation"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('title')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        placeholder="Short description for this step..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('description') }}</textarea>
                    @error('description')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                        class="w-32 rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-[#1a2f5e]">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                </div>

            </div>
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.process-steps.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-white transition">Cancel</a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition">Save Step</button>
            </div>
        </div>
    </form>
</div>
@endsection
