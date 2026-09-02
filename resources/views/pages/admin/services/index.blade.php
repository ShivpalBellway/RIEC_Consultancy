@extends('layouts.admin')

@section('title', 'Services')
@section('page-title', 'Manage Services')

@section('header-actions')
<a href="{{ route('admin.services.create') }}"
   class="inline-flex items-center gap-2 bg-[#1a2f5e] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#132247] transition">
    <i class="fa-solid fa-plus text-xs"></i>
    Add Service
</a>
@endsection

@section('content')
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">All Services</h2>
                <p class="text-sm text-gray-500 mt-1">Add, update, or remove services displayed on the homepage.</p>
            </div>
            @if($services->total() > 0)
            <span class="text-sm text-gray-500">
                Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} services
            </span>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">#</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Title</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Image</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-right font-bold text-gray-600">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($services as $index => $service)
                    <tr class="hover:bg-gray-50 transition" id="service-row-{{ $service->id }}">
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                            {{ $services->firstItem() + $index }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-900 max-w-xs truncate">
                            {{ $service->title }}
                        </td>

                        <td class="px-6 py-4">
                            @if($service->image)
                                <img src="{{ Str::startsWith($service->image, 'http') ? $service->image : asset('storage/' . $service->image) }}"
                                     alt="{{ $service->title }}"
                                     class="h-12 w-16 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="h-12 w-16 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                    <i class="fa-solid fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <!-- Toggle Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       class="sr-only peer status-toggle"
                                       data-id="{{ $service->id }}"
                                       data-url="{{ route('admin.services.toggle-status', $service->id) }}"
                                       {{ $service->status ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#1a2f5e] rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1a2f5e]"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900 status-label">
                                    {{ $service->status ? 'Active' : 'Inactive' }}
                                </span>
                            </label>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.services.edit', $service) }}"
                                   class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>

                                <form action="{{ route('admin.services.destroy', $service) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this service?')">
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
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fa-solid fa-cube text-4xl text-gray-300"></i>
                                <p>No services added yet.</p>
                                <a href="{{ route('admin.services.create') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Add your first service →
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($services->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} results
            </div>
            <div>
                {{ $services->links() }}
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
                const row = document.getElementById(`service-row-${id}`);
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
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update label
                        label.textContent = status ? 'Active' : 'Inactive';

                        // Show success toast
                        if (window.adminToast) {
                            window.adminToast('success', `Service ${status ? 'activated' : 'deactivated'} successfully`);
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
