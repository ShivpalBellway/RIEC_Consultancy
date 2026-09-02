{{-- resources/views/pages/admin/notices/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Create Notice')
@section('page-title', 'Create Notice')

@section('header-actions')
<a href="{{ route('admin.notices.index') }}" 
   class="inline-flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">
    <i class="fa-solid fa-arrow-left text-xs"></i>
    Back to Notices
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <form action="{{ route('admin.notices.store') }}" method="POST" enctype="multipart/form-data" id="noticeForm">
        @csrf
        
        <div class="px-6 py-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-gray-700 block mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter notice title" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-gray-700 block mb-1">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="10"
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Write notice content here...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Type</label>
                    <select name="type" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="important" {{ old('type') == 'important' ? 'selected' : '' }}>Important</option>
                        <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>Event</option>
                        <option value="update" {{ old('type') == 'update' ? 'selected' : '' }}>Update</option>
                    </select>
                </div>

                {{-- Priority --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Priority</label>
                    <select name="priority" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                {{-- Published At --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Publish Date</label>
                    <input type="datetime-local" name="published_at" 
                           value="{{ old('published_at') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Expires At --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Expiry Date</label>
                    <input type="datetime-local" name="expires_at" 
                           value="{{ old('expires_at') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Files Upload --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-gray-700 block mb-1">Attach Files</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition" id="dropZone">
                        <div id="fileInputWrapper">
                            <input type="file" name="files[]" multiple id="fileInput"
                                   class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-400 mt-2">Upload PDF, Word, Excel, Images (Max 10MB each)</p>
                        </div>
                        
                        {{-- File List - Shows selected files --}}
                        <div id="fileList" class="mt-3 text-left hidden">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Selected Files:</p>
                            <ul id="selectedFilesList" class="space-y-1"></ul>
                        </div>
                    </div>
                    @error('files.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Options --}}
                <div class="md:col-span-2 flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_pinned" value="1" 
                               {{ old('is_pinned') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Pin this notice</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
            <a href="{{ route('admin.notices.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-xl text-sm font-semibold transition">
                Cancel
            </a>
            <button type="submit" class="bg-[#1a2f5e] hover:bg-[#132247] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition">
                <i class="fa-solid fa-save mr-2"></i> Create Notice
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const selectedFilesList = document.getElementById('selectedFilesList');
    const dropZone = document.getElementById('dropZone');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(this.files);
        if (files.length === 0) {
            fileList.classList.add('hidden');
            return;
        }

        // Show file list
        fileList.classList.remove('hidden');
        selectedFilesList.innerHTML = '';

        files.forEach((file, index) => {
            const li = document.createElement('li');
            li.className = 'flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2 text-sm';
            
            // File size formatting
            const size = (file.size / 1024).toFixed(2);
            const sizeUnit = size > 1024 ? 'MB' : 'KB';
            const fileSize = size > 1024 ? (size / 1024).toFixed(2) : size;

            li.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-file text-gray-500"></i>
                    <span class="text-gray-700 font-medium">${file.name}</span>
                    <span class="text-xs text-gray-400">(${fileSize} ${sizeUnit})</span>
                </div>
                <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700 transition">
                    <i class="fa-solid fa-times"></i>
                </button>
            `;
            selectedFilesList.appendChild(li);
        });

        // Store files reference for removal
        window.selectedFiles = files;
    });

    // Remove file function
    window.removeFile = function(index) {
        const files = Array.from(fileInput.files);
        files.splice(index, 1);
        
        // Create new FileList
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
        
        // Trigger change event to update list
        fileInput.dispatchEvent(new Event('change'));
    };

    // Drag and drop support
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const dataTransfer = new DataTransfer();
            Array.from(files).forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });
});
</script>
@endpush
@endsection