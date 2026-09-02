@extends('layouts.admin')

@section('title', 'Add Program')
@section('page-title', 'Add New Program')

@section('breadcrumb')
    <span class="text-gray-300">/</span>
    <a href="{{ route('admin.programs.index') }}" class="hover:text-primary">Programs</a>
    <span class="text-gray-300">/</span>
    <span class="text-gray-500">Add New</span>
@endsection

@section('content')
<div class="max-w-7xl justify-center mx-auto">
    <form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header --}}
            <div class="px-7 py-5 border-b border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-primary text-lg">Program Details</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Fill in the information for the new program</p>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-7 grid md:grid-cols-2 gap-6">

                {{-- Program Name --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                        <span class="w-6 h-6 rounded-md bg-purple-100 text-purple-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-user"></i>
                        </span>
                        Program Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="e.g. Bachelor / Associate Degree"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-primary transition">
                </div>

                {{-- Country --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                        <span class="w-6 h-6 rounded-md bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        Country <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="country" value="{{ old('country') }}" required
                           placeholder="e.g. Nepal & Bangladesh, Sri Lanka, Any Country"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-primary transition">
                </div>

                {{-- Program Type --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                        <span class="w-6 h-6 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-building-columns"></i>
                        </span>
                        Program Type <span class="text-red-500">*</span>
                    </label>
                    <select name="program_type" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-primary transition bg-white">
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('program_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Duration --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                        <span class="w-6 h-6 rounded-md bg-amber-100 text-amber-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-clock"></i>
                        </span>
                        Duration
                    </label>
                    <input type="text" name="duration" value="{{ old('duration') }}"
                           placeholder="e.g. 4 Years"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-primary transition">
                </div>

                {{-- Language --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                        <span class="w-6 h-6 rounded-md bg-pink-100 text-pink-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-language"></i>
                        </span>
                        Language of Instruction
                    </label>
                    <input type="text" name="language" value="{{ old('language') }}"
                           placeholder="e.g. English"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-primary transition">
                </div>

                {{-- Tuition --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                        <span class="w-6 h-6 rounded-md bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-dollar-sign"></i>
                        </span>
                        Tuition Fee (USD)
                    </label>
                    <input type="number" name="tuition_fee" value="{{ old('tuition_fee') }}"
                           placeholder="e.g. 2500"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-primary transition">
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                        <span class="w-6 h-6 rounded-md bg-purple-100 text-purple-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-note-sticky"></i>
                        </span>
                        Description
                    </label>
                    <textarea name="description" rows="3"
                              placeholder="Short description of the program..."
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-primary transition resize-none">{{ old('description') }}</textarea>
                </div>

                {{-- Image Upload --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-primary mb-2">
                        Flag / Icon Image
                    </label>

                    <div class="border-2 border-dashed border-blue-200 rounded-xl p-8 text-center hover:border-primary transition cursor-pointer"
                         onclick="document.getElementById('image').click()">
                        <div class="flex items-center justify-center gap-5">
                            <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-sm text-primary font-extrabold">Click to upload Image</p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP, SVG up to 2MB</p>
                            </div>
                        </div>

                        <div id="preview-wrap" class="hidden mt-4">
                            <img id="preview-img" class="h-20 mx-auto rounded-lg object-contain">
                        </div>
                    </div>

                    <input type="file" id="image" name="image" accept="image/*" class="hidden"
                           onchange="previewImage(this)">
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                        <span class="w-6 h-6 rounded-md bg-gray-100 text-gray-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-list-ol"></i>
                        </span>
                        Sort Order
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-primary transition">
                    <p class="text-xs text-gray-400 mt-1">Lower = shown first</p>
                </div>

                {{-- Status --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                        <span class="w-6 h-6 rounded-md bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-circle-check"></i>
                        </span>
                        Status
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer mt-3">
                        <div class="relative">
                            <input type="checkbox" name="is_active" id="is_active"
                                   class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-primary transition"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                        </div>
                        <span class="text-sm text-gray-600 font-semibold">Active</span>
                    </label>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-7 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <a href="{{ route('admin.programs.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white border border-gray-200 text-sm text-gray-600 hover:text-primary font-bold transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 bg-primary text-white text-sm font-bold px-7 py-3 rounded-xl hover:bg-[#142447] transition shadow">
                    <i class="fa-solid fa-plus"></i>
                    Create Program
                </button>
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
