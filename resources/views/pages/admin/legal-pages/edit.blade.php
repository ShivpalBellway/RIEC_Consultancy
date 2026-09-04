@extends('layouts.admin')

@section('title', 'Privacy Policy & Terms')
@section('page-title', 'Privacy Policy & Terms')

@push('styles')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <style>
        .ck-editor__editable_inline {
            min-height: 520px !important;
            max-height: 760px !important;
            overflow-y: auto !important;
            font-size: 14px !important;
            line-height: 1.8 !important;
        }
        .legal-tab {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.85rem 1.25rem;
            border-bottom: 2px solid transparent;
            transition: color 0.2s, border-color 0.2s;
        }
        .legal-tab:hover,
        .legal-tab.active {
            color: #1a2f5e;
            border-bottom-color: #dca737;
        }
        @media (max-width: 640px) {
            .legal-tab { flex: 1; padding-left: 0.5rem; padding-right: 0.5rem; }
        }
    </style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Website Legal Pages</h2>
        <p class="text-sm text-gray-500 mt-1">Manage the pages linked in your public website footer.</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex border-b border-gray-100 px-3 pt-3" role="tablist">
            <button type="button" class="legal-tab active" data-tab="privacy-panel" role="tab" aria-selected="true">
                <i class="fa-solid fa-user-shield mr-2"></i> Privacy Policy
            </button>
            <button type="button" class="legal-tab" data-tab="terms-panel" role="tab" aria-selected="false">
                <i class="fa-solid fa-file-contract mr-2"></i> Terms & Conditions
            </button>
        </div>

        <div id="privacy-panel" class="legal-panel p-6" role="tabpanel">
            <form action="{{ route('admin.legal-pages.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="page" value="privacy-policy">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Page Title</label>
                <input type="text" name="title" value="{{ old('title', $privacyPolicy->title) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm mb-5" placeholder="Privacy Policy">
                @error('title') <p class="text-sm text-red-600 mb-3">{{ $message }}</p> @enderror
                <label class="block text-sm font-semibold text-gray-700 mb-2">Page Content</label>
                <textarea name="content" id="privacyEditor">{{ old('content', $privacyPolicy->content) }}</textarea>
                @error('content') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition">
                        <i class="fa-solid fa-check mr-2"></i> Save Privacy Policy
                    </button>
                </div>
            </form>
        </div>

        <div id="terms-panel" class="legal-panel p-6 hidden" role="tabpanel">
            <form action="{{ route('admin.legal-pages.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="page" value="terms-conditions">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Page Title</label>
                <input type="text" name="title" value="{{ old('title', $termsConditions->title) }}"
                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm mb-5" placeholder="Terms & Conditions">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Page Content</label>
                <textarea name="content" id="termsEditor">{{ old('content', $termsConditions->content) }}</textarea>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition">
                        <i class="fa-solid fa-check mr-2"></i> Save Terms & Conditions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const editors = {};
    ClassicEditor.create(document.querySelector('#privacyEditor')).then(editor => editors.privacy = editor);
    ClassicEditor.create(document.querySelector('#termsEditor')).then(editor => editors.terms = editor);

    const selectedTab = @json(old('page', 'privacy-policy'));
    const initialTab = document.querySelector(`[data-tab="${selectedTab === 'terms-conditions' ? 'terms-panel' : 'privacy-panel'}"]`);
    if (initialTab) initialTab.click();

    document.querySelectorAll('.legal-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.legal-tab').forEach(item => {
                item.classList.toggle('active', item === tab);
                item.setAttribute('aria-selected', item === tab ? 'true' : 'false');
            });
            document.querySelectorAll('.legal-panel').forEach(panel => {
                panel.classList.toggle('hidden', panel.id !== tab.dataset.tab);
            });
        });
    });
</script>
@endpush