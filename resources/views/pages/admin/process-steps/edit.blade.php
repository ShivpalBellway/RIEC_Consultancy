@extends('layouts.admin')
@section('title', 'Edit Process Step')
@section('page-title', 'Edit Process Step')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('admin.process-steps.update', $processStep) }}" method="POST">
        @csrf @method('PUT')
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Edit Process Step</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Icon Class <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-[#1a2f5e]/10 flex items-center justify-center">
                            <i id="iconPreview" class="{{ old('icon', $processStep->icon) }} text-[#1a2f5e]"></i>
                        </div>
                        <span class="text-xs text-gray-400">Live preview</span>
                    </div>
                    <input type="text" name="icon" id="iconInput" value="{{ old('icon', $processStep->icon) }}" required
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('icon')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $processStep->title) }}" required
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('title')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('description', $processStep->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $processStep->sort_order) }}"
                        class="w-32 rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="status" value="1"
                            {{ old('status', $processStep->status) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-[#1a2f5e]">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                </div>

            </div>
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.process-steps.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-white transition">Cancel</a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition">Update Step</button>
            </div>
        </div>
    </form>
</div>
<script>
document.getElementById('iconInput')?.addEventListener('input', function() {
    const preview = document.getElementById('iconPreview');
    preview.className = this.value + ' text-[#1a2f5e]';
});
</script>
@endsection
