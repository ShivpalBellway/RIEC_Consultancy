@extends('layouts.admin')

@section('title', 'Add Blog Post')
@section('page-title', 'Add Blog Post')

@section('content')
<div class="max-w-4xl mx-auto">

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Add New Blog Post</h2>
                <p class="text-sm text-gray-500 mt-1">Fill out the details to draft or publish a new article.</p>
            </div>

            <div class="p-6 space-y-6">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Title
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           required
                           placeholder="e.g., Guide to Studying in Canada 2026"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('title')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Excerpt
                    </label>
                    <textarea name="excerpt"
                              rows="3"
                              placeholder="A brief summary of the blog post shown in listings..."
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('excerpt') }}</textarea>
                    @error('excerpt')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Cover Image
                        </label>
                        <input type="file"
                               name="image"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                        @error('image')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="is_published"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                            <option value="0" {{ old('is_published') == '0' || old('is_published') === null ? 'selected' : '' }}>Draft</option>
                            <option value="1" {{ old('is_published') == '1' ? 'selected' : '' }}>Publish Immediately</option>
                        </select>
                        @error('is_published')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Content Body
                    </label>
                    <textarea name="content"
                              id="content-editor"
                              rows="12"
                              placeholder="Write the full post contents here..."
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.blogs.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-white transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition">
                    Save Blog Post
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content-editor', {
        filebrowserUploadUrl: "{{ route('admin.blogs.upload-image', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form',
        height: 400,
        allowedContent: true,
        versionCheck: false
    });
</script>
@endpush
