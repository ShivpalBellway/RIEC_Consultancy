@extends('layouts.admin')

@section('title', 'Edit About Us')
@section('page-title', 'Edit About Us')

@push('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 300px !important;
            max-height: 500px !important;
            font-size: 14px !important;
            line-height: 1.8 !important;
        }

        .ck-editor__editable {
            border-radius: 12px !important;
            border: 1px solid #e5e7eb !important;
            padding: 16px !important;
        }

        .ck.ck-editor {
            width: 100% !important;
        }

        .ck.ck-toolbar {
            border: 1px solid #e5e7eb !important;
            border-bottom: none !important;
            background: #f9fafb !important;
            padding: 8px 12px !important;
            border-radius: 12px 12px 0 0 !important;
        }

        .image-preview-wrapper {
            position: relative;
            width: 100%;
            max-width: 400px;
            height: 250px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px dashed #e5e7eb;
            margin-bottom: 12px;
        }

        .image-preview-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview-wrapper .remove-image {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .image-preview-wrapper .remove-image:hover {
            background: #dc2626;
            transform: scale(1.1);
        }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">

    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Edit About Us</h2>
                <p class="text-sm text-gray-500 mt-1">Update the About Us page content.</p>
            </div>

            <div class="p-6 space-y-6">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Title
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $about->title) }}"
                           placeholder="About Us"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] focus:border-transparent">
                    @error('title')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                        <span class="text-xs font-normal text-gray-400 ml-1">(Full content with formatting)</span>
                    </label>
                    <textarea name="description" id="descriptionEditor">{{ old('description', $about->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Section Image
                        <span class="text-xs font-normal text-gray-400 ml-1">(Who We Are section)</span>
                    </label>

                    @if($about->image)
                        <div class="image-preview-wrapper" id="imageWrapper">
                            <img src="{{ asset('storage/' . $about->image) }}" alt="About Image">
                            <button type="button" class="remove-image" onclick="removeImage()">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @endif

                    <input type="file" name="image" accept="image/*"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    <p class="text-xs text-gray-400 mt-1">Recommended: 800x600px, Max 5MB</p>
                    @error('image')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <hr class="border-gray-100">

                <!-- Hero Section -->
                <div>
                    <h3 class="text-base font-bold text-gray-800 mb-5">Hero Section</h3>
                    <div class="space-y-5">

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Hero Background Image</label>
                            @if($about->hero_image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $about->hero_image) }}" class="h-28 rounded-xl object-cover w-full">
                                    <p class="text-xs text-gray-400 mt-1">Current — upload new to replace</p>
                                </div>
                            @endif
                            <input type="file" name="hero_image" accept="image/*"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Badge Text</label>
                            <input type="text" name="hero_badge"
                                value="{{ old('hero_badge', $about->hero_badge ?? 'About REIAC') }}"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Heading Line 1</label>
                                <input type="text" name="hero_heading_line1"
                                    value="{{ old('hero_heading_line1', $about->hero_heading_line1 ?? 'Empowering Futures') }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Highlighted Word</label>
                                <input type="text" name="hero_heading_highlight"
                                    value="{{ old('hero_heading_highlight', $about->hero_heading_highlight ?? 'Worldwide.') }}"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sub Text</label>
                            <textarea name="hero_subtext" rows="3"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('hero_subtext', $about->hero_subtext ?? '') }}</textarea>
                        </div>

                    </div>
                </div>

            </div>

            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.about.index') }}"
                   class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-white hover:border-gray-300 transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition shadow-lg shadow-[#1a2f5e]/20">
                    <i class="fa-solid fa-check mr-2"></i> Update About Us
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ── CKEditor ──────────────────────────────────────────────
    ClassicEditor
        .create(document.querySelector('#descriptionEditor'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable', '|',
                'link', '|',
                'undo', 'redo'
            ],
            placeholder: 'Write about your organization...'
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(err => console.error(err));

    // ── Remove Image ──────────────────────────────────────────
    function removeImage() {
        if (confirm('Remove the current image?')) {
            document.getElementById('imageWrapper').remove();
            // Add hidden input to tell server to remove image
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_image';
            input.value = '1';
            document.querySelector('form').appendChild(input);
        }
    }
</script>
@endpush
