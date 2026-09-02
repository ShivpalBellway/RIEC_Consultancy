@extends('layouts.admin')

@section('title', 'Edit FAQ')
@section('page-title', 'Edit FAQ')

@section('content')
<div class="max-w-3xl mx-auto">

    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Edit FAQ</h2>
                <p class="text-sm text-gray-500 mt-1">Modify the question, answer, status, or sorting order.</p>
            </div>

            <div class="p-6 space-y-6">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Question
                    </label>
                    <input type="text"
                           name="question"
                           value="{{ old('question', $faq->question) }}"
                           required
                           placeholder="e.g., What documents are required for university admissions?"
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('question')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Answer
                    </label>
                    <textarea name="answer"
                              rows="6"
                              required
                              placeholder="Describe the answer here..."
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">{{ old('answer', $faq->answer) }}</textarea>
                    @error('answer')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="is_active"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                            <option value="1" {{ old('is_active', $faq->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $faq->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number"
                               name="sort_order"
                               value="{{ old('sort_order', $faq->sort_order) }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                        @error('sort_order')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.faqs.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-white transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition">
                    Update FAQ
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
