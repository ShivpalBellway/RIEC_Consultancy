{{-- resources/views/pages/admin/notices/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Notice')
@section('page-title', 'Edit Notice')

@section('header-actions')
<a href="{{ route('admin.notices.index') }}" 
   class="inline-flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">
    <i class="fa-solid fa-arrow-left text-xs"></i>
    Back to Notices
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <form action="{{ route('admin.notices.update', $notice) }}" method="POST" enctype="multipart/form-data" id="noticeForm">
        @csrf
        @method('PUT')
        
        <div class="px-6 py-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-gray-700 block mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $notice->title) }}"
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
                              placeholder="Write notice content here...">{{ old('content', $notice->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Type</label>
                    <select name="type" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="general" {{ old('type', $notice->type) == 'general' ? 'selected' : '' }}>General</option>
                        <option value="important" {{ old('type', $notice->type) == 'important' ? 'selected' : '' }}>Important</option>
                        <option value="urgent" {{ old('type', $notice->type) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="event" {{ old('type', $notice->type) == 'event' ? 'selected' : '' }}>Event</option>
                        <option value="update" {{ old('type', $notice->type) == 'update' ? 'selected' : '' }}>Update</option>
                    </select>
                </div>

                {{-- Priority --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Priority</label>
                    <select name="priority" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="low" {{ old('priority', $notice->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $notice->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $notice->priority) == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $notice->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="draft" {{ old('status', $notice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $notice->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $notice->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                {{-- Published At --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Publish Date</label>
                    <input type="datetime-local" name="published_at" 
                           value="{{ old('published_at', $notice->published_at ? $notice->published_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Expires At --}}
                <div>
                    <label class="text-sm font-bold text-gray-700 block mb-1">Expiry Date</label>
                    <input type="datetime-local" name="expires_at" 
                           value="{{ old('expires_at', $notice->expires_at ? $notice->expires_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Files Upload --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-gray-700 block mb-1">Upload New Files</label>
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

                {{-- Existing Files --}}
                @php
                    $filePaths = $notice->file_paths;
                    if (is_string($filePaths)) {
                        $filePaths = json_decode($filePaths, true);
                    }
                    $fileNames = $notice->file_names;
                    if (is_string($fileNames)) {
                        $fileNames = json_decode($fileNames, true);
                    }
                    $fileCount = is_array($filePaths) ? count($filePaths) : 0;
                @endphp

                @if($fileCount > 0)
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-gray-700 block mb-2">Existing Files</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($filePaths as $index => $path)
                        <div class="bg-gray-50 rounded-xl p-3 flex items-center justify-between border border-gray-200">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-file text-gray-600"></i>
                                <span class="text-xs text-gray-600 truncate max-w-[100px]">{{ $fileNames[$index] ?? basename($path) }}</span>
                            </div>
                            <label class="flex items-center gap-1 text-red-600 cursor-pointer text-xs">
                                <input type="checkbox" name="delete_files[]" value="{{ $index }}" class="rounded border-red-300">
                                Remove
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Check the box to remove selected files</p>
                </div>
                @endif

                {{-- Options --}}
                <div class="md:col-span-2 flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_pinned" value="1" 
                               {{ old('is_pinned', $notice->is_pinned) ? 'checked' : '' }}
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
                <i class="fa-solid fa-save mr-2"></i> Update Notice
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