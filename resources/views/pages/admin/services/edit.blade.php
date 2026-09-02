@extends('layouts.admin')

@section('title', 'Edit Service')
@section('page-title', 'Edit Service')

@push('styles')
    {{-- CKEditor 5 --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <style>
        /* CKEditor Custom Styling */
        .ck-editor__editable_inline {
            min-height: 250px !important;
            max-height: 400px !important;
            font-size: 14px !important;
            line-height: 1.7 !important;
        }

        .ck-editor__editable {
            border-radius: 12px !important;
            border: 1px solid #e5e7eb !important;
            padding: 16px !important;
        }

        .ck-editor__editable:focus {
            border-color: #1a2f5e !important;
            box-shadow: 0 0 0 2px rgba(26, 47, 94, 0.2) !important;
        }

        .ck-editor__top {
            border-radius: 12px 12px 0 0 !important;
            overflow: hidden !important;
        }

        .ck-editor__bottom {
            border-radius: 0 0 12px 12px !important;
            overflow: hidden !important;
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

        .ck.ck-toolbar .ck-button {
            border-radius: 6px !important;
        }

        .ck.ck-toolbar .ck-button:hover {
            background: #e5e7eb !important;
        }

        .ck.ck-toolbar .ck-button.ck-on {
            background: #1a2f5e !important;
            color: white !important;
        }

        /* Drop Zone Styling */
        #imageDropZone {
            transition: all 0.3s ease;
        }

        #imageDropZone:hover {
            border-color: #1a2f5e;
            background: #f8f9fc;
        }

        #imageDropZone.dragover {
            border-color: #1a2f5e;
            background: rgba(26, 47, 94, 0.05);
            transform: scale(1.01);
        }

        .ck-editor-wrapper {
            width: 100%;
        }
    </style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">

    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Edit Service</h2>
                        <p class="text-sm text-gray-500 mt-1">Modify the service details.</p>
                    </div>
                    @if($service->status)
                        <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">
                            <i class="fa-solid fa-check-circle mr-1"></i> Active
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                            <i class="fa-solid fa-times-circle mr-1"></i> Inactive
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6 space-y-6">

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $service->title) }}"
                           required
                           placeholder="e.g., University Admissions"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] focus:border-transparent transition">
                    @error('title')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Excerpt --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Excerpt / Short Description
                        <span class="text-xs font-normal text-gray-400 ml-1">(shown on homepage card)</span>
                    </label>
                    <textarea name="excerpt"
                              rows="3"
                              placeholder="Brief description of the service (shown on homepage card)..."
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] focus:border-transparent transition">{{ old('excerpt', $service->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Full Description (CKEditor) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Full Description
                        <span class="text-xs font-normal text-gray-400 ml-1">(shown on service detail page)</span>
                    </label>
                    <div class="ck-editor-wrapper">
                        <textarea name="description" id="descriptionEditor">{{ old('description', $service->description) }}</textarea>
                    </div>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image Upload + Icon --}}
                <div class="grid md:grid-cols-2 gap-5">

                    {{-- Image Upload --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Service Image
                            <span class="text-xs font-normal text-gray-400 ml-1">(select from your computer)</span>
                        </label>

                        {{-- Drop Zone --}}
                        <label for="imageInput"
                               class="flex flex-col items-center justify-center w-full h-48 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 cursor-pointer hover:bg-gray-100 hover:border-[#1a2f5e]/40 transition group relative overflow-hidden"
                               id="imageDropZone">

                            {{-- Preview: show existing image by default --}}
                            <img id="imagePreview"
                                 src="{{ $service->image ? asset('storage/' . $service->image) : '' }}"
                                 alt="Preview"
                                 class="absolute inset-0 w-full h-full object-cover rounded-xl {{ $service->image ? '' : 'hidden' }}">

                            {{-- Placeholder: hidden when there's an existing image --}}
                            <div id="imagePlaceholder" class="flex flex-col items-center gap-2 z-10 {{ $service->image ? 'hidden' : '' }}">
                                <div class="w-14 h-14 rounded-full bg-[#1a2f5e]/10 flex items-center justify-center group-hover:bg-[#1a2f5e]/20 transition">
                                    <i class="fa-solid fa-cloud-arrow-up text-[#1a2f5e] text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-500">Click to select image</p>
                                <p class="text-xs text-gray-400">JPG, PNG, WEBP, AVIF — Max 5MB</p>
                            </div>

                            {{-- Overlay for existing image --}}
                            <div id="imageOverlay" class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center rounded-xl {{ $service->image ? '' : 'hidden' }}">
                                <div class="text-white text-center">
                                    <i class="fa-solid fa-camera text-2xl"></i>
                                    <p class="text-xs mt-1 font-semibold">Change Image</p>
                                </div>
                            </div>

                            <input type="file"
                                   id="imageInput"
                                   name="image"
                                   accept="image/jpeg,image/png,image/webp,image/gif,image/avif,image/svg+xml,image/*"
                                   class="hidden">
                        </label>

                        {{-- File name label --}}
                        <p id="imageFileName" class="text-xs text-gray-500 mt-2 truncate">
                            @if($service->image)
                                <span class="text-green-600"><i class="fa-regular fa-circle-check mr-1"></i> Current: {{ basename($service->image) }}</span>
                            @else
                                <span class="text-gray-400">No image uploaded</span>
                            @endif
                        </p>

                        @error('image')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Icon Class (FontAwesome)
                        </label>
                        <input type="text"
                               name="icon"
                               value="{{ old('icon', $service->icon) }}"
                               placeholder="e.g., fa-solid fa-graduation-cap"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] focus:border-transparent transition">

                        {{-- Icon Preview --}}
                        <div class="mt-3 flex items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-[#1a2f5e] flex items-center justify-center shadow-lg shadow-[#1a2f5e]/20">
                                <i id="iconPreview" class="{{ old('icon', $service->icon) ?: 'fa-solid fa-circle-question' }} text-white text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500">Live preview</p>
                                <p class="text-xs text-gray-400">{{ old('icon', $service->icon) ?: 'No icon selected' }}</p>
                            </div>
                        </div>

                        @error('icon')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox"
                               name="status"
                               value="1"
                               {{ old('status', $service->status) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-[#1a2f5e] focus:ring-[#1a2f5e] focus:ring-2 transition">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.services.index') }}"
                   class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-white hover:border-gray-300 transition">
                    <i class="fa-solid fa-xmark mr-2"></i> Cancel
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition shadow-lg shadow-[#1a2f5e]/20 hover:shadow-[#1a2f5e]/30">
                    <i class="fa-solid fa-pen mr-2"></i> Update Service
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
            placeholder: 'Write a full description for this service...',
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            }
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(err => console.error(err));

    // ── Image Preview ─────────────────────────────────────────
    const imageInput       = document.getElementById('imageInput');
    const imagePreview     = document.getElementById('imagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const imageFileName    = document.getElementById('imageFileName');
    const imageDropZone    = document.getElementById('imageDropZone');
    const imageOverlay     = document.getElementById('imageOverlay');

    // File selection
    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        imageFileName.innerHTML = '<span class="text-blue-600"><i class="fa-regular fa-file mr-1"></i> Selected: ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)</span>';

        const reader = new FileReader();
        reader.onload = e => {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
            imagePlaceholder.classList.add('hidden');
            imageOverlay.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    // Drag and drop support
    imageDropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    imageDropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    imageDropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            imageInput.dispatchEvent(new Event('change'));
        }
    });

    // ── Icon Preview ──────────────────────────────────────────
    const iconInput   = document.querySelector('input[name="icon"]');
    const iconPreview = document.getElementById('iconPreview');

    if (iconInput) {
        iconInput.addEventListener('input', function () {
            const iconClass = this.value.trim();
            if (iconClass) {
                iconPreview.className = iconClass + ' text-white text-2xl';
                document.querySelector('.text-gray-400').textContent = iconClass;
            } else {
                iconPreview.className = 'fa-solid fa-circle-question text-white text-2xl';
                document.querySelector('.text-gray-400').textContent = 'No icon selected';
            }
        });
    }
</script>
@endpush
