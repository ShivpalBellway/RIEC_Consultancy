@extends('layouts.admin')
@section('title', 'Process Steps')
@section('page-title', 'Manage Process Steps')

@section('header-actions')
<a href="{{ route('admin.process-steps.create') }}"
   class="inline-flex items-center gap-2 bg-[#1a2f5e] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#132247] transition">
    <i class="fa-solid fa-plus text-xs"></i> Add Step
</a>
@endsection

@section('content')
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-900">All Process Steps</h2>
        <p class="text-sm text-gray-500 mt-1">Manage the "Our Process" section steps shown on the homepage.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">#</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Icon</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Title</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Description</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Order</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-right font-bold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($steps as $step)
                <tr class="hover:bg-gray-50 transition" id="step-row-{{ $step->id }}">
                    <td class="px-6 py-4 text-gray-500 font-medium">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">
                        <div class="w-10 h-10 rounded-xl bg-[#1a2f5e]/10 flex items-center justify-center">
                            <i class="{{ $step->icon }} text-[#1a2f5e]"></i>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $step->title }}</td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $step->description }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $step->sort_order }}</td>
                    <td class="px-6 py-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer status-toggle"
                                   data-id="{{ $step->id }}"
                                   data-url="{{ route('admin.process-steps.toggle-status', $step->id) }}"
                                   {{ $step->status ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1a2f5e]"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 status-label">{{ $step->status ? 'Active' : 'Inactive' }}</span>
                        </label>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.process-steps.edit', $step) }}"
                               class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.process-steps.destroy', $step) }}" method="POST"
                                  onsubmit="return confirm('Delete this step?')">
                                @csrf @method('DELETE')
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
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No process steps added yet.
                        <a href="{{ route('admin.process-steps.create') }}" class="text-blue-600 font-semibold">Add first step →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.status-toggle').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        const row   = document.getElementById('step-row-' + this.dataset.id);
        const label = row.querySelector('.status-label');
        const status = this.checked ? 1 : 0;
        this.disabled = true;
        fetch(this.dataset.url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ status })
        }).then(r => r.json()).then(data => {
            label.textContent = status ? 'Active' : 'Inactive';
        }).finally(() => { this.disabled = false; });
    });
});
</script>
@endpush
