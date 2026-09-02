@extends('layouts.admin')

@section('title', 'Add Partner')
@section('page-title', 'Add Partner')

@section('content')
<div class="max-w-3xl">

    <form action="{{ route('admin.partners.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Add Partner Logo</h2>
                <p class="text-sm text-gray-500 mt-1">Upload partner image and manage visibility.</p>
            </div>

            <div class="p-6 space-y-6">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Partner Logo
                    </label>
                    <input type="file"
                           name="image"
                           required
                           class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    @error('image')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="status"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number"
                               name="sort_order"
                               value="{{ old('sort_order', 0) }}"
                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]">
                    </div>
                </div>

               <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Link (Optional)
    </label>

    <input
        type="text"
        name="link"
        value="{{ old('link') }}"
        placeholder="https://example.com"
        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]"
    >

    @error('link')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

            </div>

            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.partners.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-white transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#1a2f5e] text-white text-sm font-semibold hover:bg-[#132247] transition">
                    Save Partner
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
