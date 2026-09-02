@extends('layouts.admin')

@section('title', 'FAQs')
@section('page-title', 'Manage FAQs')

@section('header-actions')
<a href="{{ route('admin.faqs.create') }}"
   class="inline-flex items-center gap-2 bg-[#1a2f5e] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#132247] transition">
    <i class="fa-solid fa-plus text-xs"></i>
    Add FAQ
</a>
@endsection

@section('content')
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">All FAQs</h2>
                <p class="text-sm text-gray-500 mt-1">Add, update, or remove FAQs displayed on the website.</p>
            </div>
            @if($faqs->total() > 0)
            <span class="text-sm text-gray-500">
                Showing {{ $faqs->firstItem() }} to {{ $faqs->lastItem() }} of {{ $faqs->total() }} FAQs
            </span>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">#</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Question</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Answer Preview</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Sort Order</th>
                    <th class="px-6 py-4 text-right font-bold text-gray-600">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($faqs as $index => $faq)
                    <tr class="hover:bg-gray-50 transition" id="faq-row-{{ $faq->id }}">
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                            {{ $faqs->firstItem() + $index }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-900 max-w-xs truncate">
                            {{ $faq->question }}
                        </td>

                        <td class="px-6 py-4 text-gray-600 max-w-sm truncate">
                            {{ Str::limit($faq->answer, 80) }}
                        </td>

                        <td class="px-6 py-4">
                            <!-- Toggle Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       class="sr-only peer status-toggle"
                                       data-id="{{ $faq->id }}"
                                       data-url="{{ route('admin.faqs.toggle-status', $faq->id) }}"
                                       {{ $faq->is_active ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#1a2f5e] rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1a2f5e]"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900 status-label">
                                    {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </label>
                        </td>

                        <td class="px-6 py-4 text-gray-700 font-semibold">
                            {{ $faq->sort_order ?? 0 }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.faqs.edit', $faq) }}"
                                   class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>

                                <form action="{{ route('admin.faqs.destroy', $faq) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this FAQ?')">
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
                                <i class="fa-solid fa-circle-question text-4xl text-gray-300"></i>
                                <p>No FAQs added yet.</p>
                                <a href="{{ route('admin.faqs.create') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Add your first FAQ →
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($faqs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing {{ $faqs->firstItem() }} to {{ $faqs->lastItem() }} of {{ $faqs->total() }} results
            </div>
            <div>
                {{ $faqs->links() }}
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
                const isActive = this.checked ? 1 : 0;
                const row = document.getElementById(`faq-row-${id}`);
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
                    body: JSON.stringify({ is_active: isActive })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update label
                        label.textContent = isActive ? 'Active' : 'Inactive';

                        // Show success toast
                        if (window.adminToast) {
                            window.adminToast('success', `FAQ ${isActive ? 'activated' : 'deactivated'} successfully`);
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
