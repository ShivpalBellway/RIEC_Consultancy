@extends('layouts.admin')

@section('title', 'Edit Partner')
@section('page-title', 'Edit Partner')

@section('content')
<div class="max-w-3xl">

    <form action="{{ route('admin.partners.update', $partner) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Edit Partner Logo</h2>
                <p class="text-sm text-gray-500 mt-1">Update partner image, status and order.</p>
            </div>

            <div class="p-6 space-y-6">

                @if($partner->image)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Current Logo
                        </label>
                        <div class="w-40 h-24 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center p-3">
                            <img src="{{ asset('storage/'.$partner->image) }}"
                                 class="max-h-full max-w-full object-contain"
                                 alt="Partner">
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Change Logo
                    </label>
                    <input type="file"
                           name="image"
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
                            <option value="1" {{ $partner->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $partner->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input type="number"
                               name="sort_order"
                               value="{{ old('sort_order', $partner->sort_order) }}"
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
        value="{{ old('link', $partner->link) }}"
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
                    Update Partner
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
