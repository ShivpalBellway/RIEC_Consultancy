@extends('layouts.admin')

@section('title', 'Edit Program')
@section('page-title', 'Edit Program')

@section('breadcrumb')
    <span class="text-gray-300">/</span>
    <a href="{{ route('admin.programs.index') }}" class="hover:text-primary">Programs</a>
    <span class="text-gray-300">/</span>
    <span class="text-gray-500">Edit</span>
@endsection

@section('content')
<div class="w-full">
    <form action="{{ route('admin.programs.update', $program) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-7 py-6 bg-gradient-to-r from-purple-50/80 via-white to-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-blue-800">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 text-base">Edit: {{ $program->name }}</h2>
                        <p class="text-xs text-gray-400 mt-1">{{ $program->country }}</p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-7 py-6 space-y-5">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Program Name <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-3">
                            <span class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-blue-600">
                                <i class="fa-regular fa-bookmark"></i>
                            </span>
                            <input type="text" name="name" value="{{ old('name', $program->name) }}" required
                                class="w-full h-11 px-4 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Country <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-3">
                            <span class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-globe"></i>
                            </span>
                            <input type="text" name="country" value="{{ old('country', $program->country) }}" required
                                class="w-full h-11 px-4 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Program Type <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-3">
                            <span class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-table-list"></i>
                            </span>
                            <select name="program_type" required
                                class="w-full h-11 px-4 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ old('program_type', $program->program_type) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Sort Order</label>
                        <div class="flex items-center gap-3">
                            <span class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-arrow-down-1-9"></i>
                            </span>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $program->sort_order) }}" min="0"
                                class="w-full h-11 px-4 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <div class="flex gap-3">
                        <span class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-blue-600 shrink-0 mt-4">
                            <i class="fa-solid fa-pen"></i>
                        </span>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition resize-none">{{ old('description', $program->description) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Flag / Icon Image</label>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 p-4 border border-gray-100 rounded-2xl bg-gray-50/40">
                        @if($program->image)
                            <div class="flex items-center gap-4">
                                <img src="{{ $program->image_url }}" alt=""
                                    class="h-16 w-16 object-contain rounded-xl bg-white p-1 border border-gray-200 shadow-sm">
                                <div>
                                    <p class="text-sm font-bold text-gray-700">Current Image</p>
                                    <p class="text-xs text-gray-400 mt-1">Upload a new image to replace it</p>
                                </div>
                            </div>
                        @endif

                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center hover:border-purple-500 hover:bg-purple-50/40 transition cursor-pointer"
                            onclick="document.getElementById('image').click()">
                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-blue-600 mb-2 block"></i>
                            <p class="text-sm text-blue-600 font-semibold">Click to upload new image</p>
                            <p class="text-xs text-gray-400">JPG, PNG or WEBP (Max 2MB)</p>

                            <div id="preview-wrap" class="hidden mt-3">
                                <img id="preview-img" class="h-16 mx-auto rounded-lg object-contain">
                            </div>
                        </div>
                    </div>

                    <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_active" id="is_active" class="sr-only peer"
                                {{ old('is_active', $program->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600 transition"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                        </div>
                        <span class="text-sm text-gray-700 font-medium">Active</span>
                    </label>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-7 py-4 border-t border-gray-100 bg-gray-50 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('admin.programs.index') }}"
                    class="h-10 px-6 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm font-bold flex items-center gap-2 hover:bg-gray-100 transition">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </a>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.programs.eligibility.index', $program) }}"
                        class="h-10 px-7 rounded-xl bg-amber-50 text-amber-700 text-sm font-bold flex items-center gap-2 hover:bg-amber-100 transition">
                        <i class="fa-solid fa-list-check"></i> Eligibility
                    </a>

                    <a href="{{ route('admin.programs.form-builder.index', $program) }}"
                        class="h-10 px-7 rounded-xl bg-purple-50 text-blue-700 text-sm font-bold flex items-center gap-2 hover:bg-purple-100 transition">
                        <i class="fa-solid fa-table-list"></i> Information Form Builder
                    </a>

                    <button type="submit"
                        class="h-10 px-8 rounded-xl bg-blue-800 text-white text-sm font-bold flex items-center gap-2 hover:bg-blue-700 transition shadow-lg shadow-purple-200">
                        <i class="fa-solid fa-floppy-disk"></i> Save Changes
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const wrap = document.getElementById('preview-wrap');
    const img  = document.getElementById('preview-img');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            wrap.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
