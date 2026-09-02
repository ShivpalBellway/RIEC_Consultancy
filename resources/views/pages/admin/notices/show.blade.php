{{-- resources/views/pages/admin/notices/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Notice Details')
@section('page-title', 'Notice Details')

@section('header-actions')
<div class="flex gap-2">
    <a href="{{ route('admin.notices.edit', $notice) }}"
       class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-amber-700 transition">
        <i class="fa-solid fa-pen text-xs"></i>
        Edit
    </a>
    <a href="{{ route('admin.notices.index') }}"
       class="inline-flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">
        <i class="fa-solid fa-arrow-left text-xs"></i>
        Back to Notices
    </a>
</div>
@endsection

@section('content')

{{-- Toast Notification --}}
<div id="toast-msg" style="
    display:none;
    position:fixed;
    top:24px;
    right:24px;
    z-index:9999;
    color:#fff;
    padding:12px 20px;
    border-radius:12px;
    font-size:13px;
    font-weight:600;
    box-shadow:0 4px 20px rgba(0,0,0,0.15);
    transition:opacity 0.3s ease;
"></div>

{{-- Main Card --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    {{-- Card Header --}}
    <div class="px-6 py-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $notice->title }}</h2>
            <div class="flex flex-wrap items-center gap-2 mt-1">
                {{-- Priority Badge --}}
                <span class="px-3 py-1 rounded-full bg-{{ $notice->priority_color }}-50 text-{{ $notice->priority_color }}-700 text-xs font-bold">
                    <i class="fa-solid fa-flag mr-1"></i> {{ $notice->priority_label }}
                </span>

                {{-- Status Badge --}}
                <span id="statusBadge-{{ $notice->id }}" class="px-3 py-1 rounded-full bg-{{ $notice->status_color }}-50 text-{{ $notice->status_color }}-700 text-xs font-bold">
                    <i class="fa-solid fa-circle mr-1"></i> {{ $notice->status_label }}
                </span>

                {{-- Type Badge --}}
                <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-bold">
                    <i class="fa-solid fa-tag mr-1"></i> {{ ucfirst($notice->type) }}
                </span>

                {{-- Pinned Badge --}}
                <span id="pinnedBadge-{{ $notice->id }}" class="px-3 py-1 rounded-full {{ $notice->is_pinned ? 'bg-red-50 text-red-700' : 'bg-gray-50 text-gray-400' }} text-xs font-bold">
                    <i class="fa-solid fa-thumbtack mr-1"></i> {{ $notice->is_pinned ? 'Pinned' : 'Not Pinned' }}
                </span>

                {{-- Views --}}
                <span class="text-xs text-gray-400">
                    <i class="fa-regular fa-eye mr-1"></i> {{ $notice->views }} views
                </span>

                {{-- Downloads --}}
                <span class="text-xs text-gray-400">
                    <i class="fa-regular fa-download mr-1"></i> {{ $notice->downloads }} downloads
                </span>
            </div>
        </div>
        <div class="flex gap-2">
            {{-- Toggle Status --}}
            <button onclick="toggleStatus({{ $notice->id }}, '{{ $notice->status }}')"
                    class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                <i class="fa-solid fa-rotate text-xs"></i>
                Toggle Status
            </button>

            {{-- Toggle Pinned --}}
            <button onclick="togglePinned({{ $notice->id }}, {{ $notice->is_pinned ? 'true' : 'false' }})"
                    class="inline-flex items-center gap-2 {{ $notice->is_pinned ? 'bg-red-600' : 'bg-gray-600' }} text-white px-4 py-2 rounded-xl text-sm font-semibold hover:opacity-80 transition">
                <i class="fa-solid fa-thumbtack text-xs"></i>
                {{ $notice->is_pinned ? 'Unpin' : 'Pin' }}
            </button>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="px-6 py-5">
        {{-- Content --}}
        <div class="bg-gray-50 rounded-xl p-6 mb-6">
            <h4 class="text-sm font-bold text-gray-700 mb-3">
                <i class="fa-solid fa-align-left mr-2"></i> Content
            </h4>
            <div class="prose max-w-none">
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                    {{ $notice->content }}
                </p>
            </div>
        </div>

        {{-- Files Section --}}
        @php
            $filePaths = $notice->file_paths;
            if (is_string($filePaths)) {
                $filePaths = json_decode($filePaths, true);
            }
            $fileNames = $notice->file_names;
            if (is_string($fileNames)) {
                $fileNames = json_decode($fileNames, true);
            }
            $fileCount = is_array($filePaths) ? count($filePaths) : 0;
        @endphp

        @if($fileCount > 0)
        <div class="mb-6">
            <h4 class="text-sm font-bold text-gray-700 mb-3">
                <i class="fa-solid fa-paperclip mr-2"></i> Attached Files
                <span class="text-xs text-gray-400">({{ $fileCount }} files)</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($filePaths as $index => $path)
                <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between hover:shadow-md transition border border-gray-200">
                    <div class="flex items-center gap-3">
                        @php
                            $ext = pathinfo($path, PATHINFO_EXTENSION);
                            $iconMap = [
                                'pdf' => 'fa-regular fa-file-pdf text-red-600',
                                'doc' => 'fa-regular fa-file-word text-blue-600',
                                'docx' => 'fa-regular fa-file-word text-blue-600',
                                'xls' => 'fa-regular fa-file-excel text-green-600',
                                'xlsx' => 'fa-regular fa-file-excel text-green-600',
                                'jpg' => 'fa-regular fa-file-image text-purple-600',
                                'jpeg' => 'fa-regular fa-file-image text-purple-600',
                                'png' => 'fa-regular fa-file-image text-purple-600',
                                'gif' => 'fa-regular fa-file-image text-purple-600',
                                'webp' => 'fa-regular fa-file-image text-purple-600',
                                'svg' => 'fa-regular fa-file-image text-purple-600',
                                'zip' => 'fa-regular fa-file-archive text-amber-600',
                                'rar' => 'fa-regular fa-file-archive text-amber-600',
                                'txt' => 'fa-regular fa-file-lines text-gray-600',
                                'csv' => 'fa-regular fa-file-csv text-green-600',
                            ];
                            $iconClass = $iconMap[$ext] ?? 'fa-regular fa-file text-gray-600';
                            $fileName = is_array($fileNames) && isset($fileNames[$index]) ? $fileNames[$index] : basename($path);
                            $fileExists = Storage::disk('public')->exists($path);
                        @endphp
                        <i class="{{ $iconClass }} text-2xl"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $fileName }}</p>
                            <p class="text-xs text-gray-400">
                                @if($fileExists)
                                    {{ number_format(Storage::disk('public')->size($path) / 1024, 2) }} KB
                                @else
                                    File not found
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($fileExists)
                        <a href="{{ route('admin.notices.download', [$notice, $index]) }}"
                           class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-blue-700 transition">
                            <i class="fa-solid fa-download text-xs"></i> Download
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 bg-gray-300 text-gray-500 px-4 py-2 rounded-xl text-xs font-semibold cursor-not-allowed">
                            <i class="fa-solid fa-xmark text-xs"></i> Unavailable
                        </span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Meta Information --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-400">Type</p>
                <p class="text-sm font-semibold text-gray-700">{{ ucfirst($notice->type) }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-400">Published</p>
                <p class="text-sm font-semibold text-gray-700">{{ $notice->published_at ? $notice->published_at->format('d M Y, h:i A') : 'N/A' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-400">Expires</p>
                <p class="text-sm font-semibold text-gray-700">{{ $notice->expires_at ? $notice->expires_at->format('d M Y, h:i A') : 'Never' }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-400">Created By</p>
                <p class="text-sm font-semibold text-gray-700">{{ $notice->creator->name ?? 'Unknown' }}</p>
            </div>
        </div>

        {{-- Timestamps --}}
        <div class="mt-4 text-xs text-gray-400 text-center border-t border-gray-100 pt-4">
            Created: {{ $notice->created_at ? $notice->created_at->format('d M Y, h:i A') : 'N/A' }}
            @if($notice->updated_at && $notice->updated_at != $notice->created_at)
                | Updated: {{ $notice->updated_at->format('d M Y, h:i A') }}
            @endif
            @if($notice->deleted_at)
                | Deleted: {{ $notice->deleted_at->format('d M Y, h:i A') }}
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePinned(id, currentStatus) {
    if (!confirm('Are you sure you want to toggle pin status?')) return;

    fetch(`/admin/notices/${id}/toggle-pinned`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, false);
            const badge = document.getElementById(`pinnedBadge-${id}`);
            const btn = document.querySelector(`button[onclick*="togglePinned(${id}"]`);
            if (data.is_pinned) {
                badge.className = 'px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold';
                badge.innerHTML = '<i class="fa-solid fa-thumbtack mr-1"></i> Pinned';
                if (btn) {
                    btn.className = 'inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:opacity-80 transition';
                    btn.innerHTML = '<i class="fa-solid fa-thumbtack text-xs"></i> Unpin';
                    btn.setAttribute('onclick', `togglePinned(${id}, true)`);
                }
            } else {
                badge.className = 'px-3 py-1 rounded-full bg-gray-50 text-gray-400 text-xs font-bold';
                badge.innerHTML = '<i class="fa-solid fa-thumbtack mr-1"></i> Not Pinned';
                if (btn) {
                    btn.className = 'inline-flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:opacity-80 transition';
                    btn.innerHTML = '<i class="fa-solid fa-thumbtack text-xs"></i> Pin';
                    btn.setAttribute('onclick', `togglePinned(${id}, false)`);
                }
            }
        } else {
            showToast('Failed to toggle pin status', true);
        }
    })
    .catch(error => {
        showToast('Error: ' + error.message, true);
    });
}

function toggleStatus(id, currentStatus) {
    if (!confirm('Are you sure you want to change status?')) return;

    fetch(`/admin/notices/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, false);
            const badge = document.getElementById(`statusBadge-${id}`);
            const statusColors = {
                'draft': 'gray',
                'published': 'green',
                'archived': 'red'
            };
            const color = statusColors[data.status] || 'gray';
            badge.className = `px-3 py-1 rounded-full bg-${color}-50 text-${color}-700 text-xs font-bold`;
            badge.innerHTML = `<i class="fa-solid fa-circle mr-1"></i> ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}`;

            const btn = document.querySelector(`button[onclick*="toggleStatus(${id}"]`);
            if (btn) {
                btn.setAttribute('onclick', `toggleStatus(${id}, '${data.status}')`);
            }
        } else {
            showToast('Failed to change status', true);
        }
    })
    .catch(error => {
        showToast('Error: ' + error.message, true);
    });
}

function showToast(msg, isError) {
    const toast = document.getElementById('toast-msg');
    toast.textContent = msg;
    toast.style.backgroundColor = isError ? '#dc2626' : '#16a34a';
    toast.style.display = 'block';
    toast.style.opacity = '1';
    setTimeout(function() {
        toast.style.opacity = '0';
        setTimeout(function() {
            toast.style.display = 'none';
        }, 300);
    }, 2500);
}
</script>
@endpush
@endsection