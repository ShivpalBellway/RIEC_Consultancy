@extends('layouts.admin')

@section('title', 'Our Partners')
@section('page-title', 'Manage Partners')

@section('header-actions')
<a href="{{ route('admin.partners.create') }}"
    class="inline-flex items-center gap-2 bg-[#1a2f5e] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#132247] transition">
    <i class="fa-solid fa-plus text-xs"></i>
    Add Partner
</a>
@endsection

@section('content')
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">All Partners</h2>
                <p class="text-sm text-gray-500 mt-1">Add, update, or remove partner logos displayed on the website.</p>
            </div>
            @if($partners->total() > 0)
            <span class="text-sm text-gray-500">
                Showing {{ $partners->firstItem() }} to {{ $partners->lastItem() }} of {{ $partners->total() }} partners
            </span>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">#</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Logo</th>
                    <!-- <th class="px-6 py-4 text-left font-bold text-gray-600">Alt Text</th> -->
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Sort Order</th>
                    <th class="px-6 py-4 text-right font-bold text-gray-600">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($partners as $index => $partner)
                <tr class="hover:bg-gray-50 transition" id="partner-row-{{ $partner->id }}">
                    <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                        {{ $partners->firstItem() + $index }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="w-32 h-20 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center p-3">
                            @if($partner->image)
                            <img src="{{ asset('storage/'.$partner->image) }}"
                                class="max-h-full max-w-full object-contain"
                                alt="{{ $partner->alt_text ?? 'Partner' }}">
                            @else
                            <span class="text-gray-400 text-xs">No image</span>
                            @endif
                        </div>
                    </td>

                    <!-- <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                        {{ $partner->alt_text ?? '—' }}
                    </td> -->

                    <td class="px-6 py-4">
                        <!-- Toggle Switch -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                class="sr-only peer status-toggle"
                                data-id="{{ $partner->id }}"
                                data-url="{{ route('admin.partners.toggle-status', $partner->id) }}"
                                {{ $partner->status ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#1a2f5e] rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1a2f5e]"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 status-label">
                                {{ $partner->status ? 'Active' : 'Inactive' }}
                            </span>
                        </label>
                    </td>

                    <td class="px-6 py-4 text-gray-700 font-semibold">
                        {{ $partner->sort_order ?? 0 }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.partners.edit', $partner) }}"
                                class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>

                            <form action="{{ route('admin.partners.destroy', $partner) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this partner?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fa-solid fa-handshake text-4xl text-gray-300"></i>
                            <p>No partners added yet.</p>
                            <a href="{{ route('admin.partners.create') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Add your first partner →
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($partners->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing {{ $partners->firstItem() }} to {{ $partners->lastItem() }} of {{ $partners->total() }} results
            </div>
            <div>
                {{ $partners->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggles = document.querySelectorAll('.status-toggle');

        toggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.dataset.id;
                const url = this.dataset.url;
                const status = this.checked ? 1 : 0;
                const row = document.getElementById(`partner-row-${id}`);
                const label = row.querySelector('.status-label');

                // Show loading state
                this.disabled = true;
                label.textContent = 'Updating...';

                // Send AJAX request
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update label
                            label.textContent = status ? 'Active' : 'Inactive';

                            // Show success toast
                            if (window.adminToast) {
                                window.adminToast('success', `Partner ${status ? 'activated' : 'deactivated'} successfully`);
                            }
                        } else {
                            // Revert toggle if failed
                            this.checked = !this.checked;
                            label.textContent = this.checked ? 'Active' : 'Inactive';
                            if (window.adminToast) {
                                window.adminToast('error', data.message || 'Failed to update status');
                            }
                        }
                    })
                    .catch(error => {
                        // Revert toggle on error
                        this.checked = !this.checked;
                        label.textContent = this.checked ? 'Active' : 'Inactive';
                        if (window.adminToast) {
                            window.adminToast('error', 'Something went wrong. Please try again.');
                        }
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
            });
        });
    });
</script>
@endpush
