{{-- resources/views/pages/admin/notices/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Notices')
@section('page-title', 'Manage Notices')

@section('header-actions')
<a href="{{ route('admin.notices.create') }}"
   class="inline-flex items-center gap-2 bg-[#1a2f5e] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#132247] transition">
    <i class="fa-solid fa-plus text-xs"></i>
    Add Notice
</a>
@endsection

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-list text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Published</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['published'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                <i class="fa-solid fa-check-circle text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Draft</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['draft'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-pen-to-square text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Archived</p>
                <p class="text-2xl font-bold text-gray-600 mt-1">{{ $stats['archived'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center">
                <i class="fa-solid fa-archive text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pinned</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['pinned'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                <i class="fa-solid fa-thumbtack text-lg"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filter Section --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-sm font-bold text-gray-700">
            <i class="fa-solid fa-filter mr-2"></i> Filter Notices
        </h3>
    </div>
    <div class="px-6 py-5">
        <form method="GET" action="{{ route('admin.notices.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">Status</label>
                <select name="status" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">Priority</label>
                <select name="priority" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">Search</label>
                <input type="search" name="search" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search title or content..." value="{{ request('search') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-[#1a2f5e] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#132247] transition">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i> Search / Filter
                </button>
                <a href="{{ route('admin.notices.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">
                    <i class="fa-solid fa-undo text-xs"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Notices Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                <i class="fa-solid fa-bullhorn mr-2"></i> All Notices
            </h2>
            <p class="text-sm text-gray-500 mt-1">{{ $notices->total() }} notices found</p>
        </div>
        <div>
            <button onclick="bulkDelete()"
                    class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-red-700 transition">
                <i class="fa-solid fa-trash text-xs"></i> Delete Selected
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left font-bold text-gray-600 w-12">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                    </th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Title</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Priority</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Status</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Files</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Views</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Published</th>
                    <th class="px-6 py-4 text-right font-bold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($notices as $notice)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="notice-checkbox rounded border-gray-300" value="{{ $notice->id }}">
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @if($notice->is_pinned)
                                <i class="fa-solid fa-thumbtack text-red-500 text-xs" title="Pinned"></i>
                            @endif
                            <span class="font-semibold text-gray-800">{{ Str::limit($notice->title, 50) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-{{ $notice->priority_color }}-50 text-{{ $notice->priority_color }}-700 text-xs font-bold">
                            {{ $notice->priority_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-{{ $notice->status_color }}-50 text-{{ $notice->status_color }}-700 text-xs font-bold">
                            {{ $notice->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $filePaths = $notice->file_paths;
                            if (is_string($filePaths)) {
                                $filePaths = json_decode($filePaths, true);
                            }
                            $fileCount = is_array($filePaths) ? count($filePaths) : 0;
                        @endphp
                        @if($fileCount > 0)
                            <span class="inline-flex items-center gap-1 text-blue-600">
                                <i class="fa-solid fa-paperclip text-xs"></i>
                                {{ $fileCount }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $notice->views }}</td>
                    <td class="px-6 py-4 text-gray-600 text-sm">
                        {{ $notice->published_at ? $notice->published_at->format('d M Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.notices.show', $notice) }}"
                               class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition"
                               title="View">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('admin.notices.edit', $notice) }}"
                               class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-100 transition"
                               title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <button onclick="togglePinned({{ $notice->id }})"
                               class="w-9 h-9 rounded-xl {{ $notice->is_pinned ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-600' }} flex items-center justify-center hover:bg-gray-100 transition"
                               title="{{ $notice->is_pinned ? 'Unpin' : 'Pin' }}">
                                <i class="fa-solid fa-thumbtack text-xs"></i>
                            </button>
                            <button onclick="toggleStatus({{ $notice->id }})"
                               class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-100 transition"
                               title="Toggle Status">
                                <i class="fa-solid fa-rotate text-xs"></i>
                            </button>
                            <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" class="inline" onsubmit="return confirm('Delete this notice?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                       class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition"
                                       title="Delete">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl text-gray-300 block mb-3"></i>
                        No notices found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($notices->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
        <div class="text-sm text-gray-500">
            Showing {{ $notices->firstItem() }} to {{ $notices->lastItem() }} of {{ $notices->total() }} entries
        </div>
        <div>
            {{ $notices->links() }}
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.notice-checkbox').forEach(cb => cb.checked = this.checked);
});

function togglePinned(id) {
    if (!confirm('Toggle pin status?')) return;

    fetch(`/admin/notices/${id}/toggle-pinned`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function toggleStatus(id) {
    if (!confirm('Change status?')) return;

    fetch(`/admin/notices/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function bulkDelete() {
    const selected = document.querySelectorAll('.notice-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select at least one notice to delete.');
        return;
    }

    if (!confirm(`Are you sure you want to delete ${selected.length} notice(s)?`)) return;

    const ids = Array.from(selected).map(cb => cb.value);

    fetch('{{ route("admin.notices.bulk-delete") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection
