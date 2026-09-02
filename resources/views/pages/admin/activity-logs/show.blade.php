@extends('layouts.admin')

@section('title', 'Activity Log Details')
@section('page-title', 'Activity Log Details')

@section('header-actions')
<a href="{{ route('admin.activity-logs.index') }}"
   class="inline-flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">
    <i class="fa-solid fa-arrow-left text-xs"></i>
    Back to Logs
</a>
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
            <h2 class="text-lg font-bold text-gray-900">
                <i class="fa-solid fa-info-circle mr-2 text-blue-600"></i> Log Information
                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full ml-2">#{{ $activityLog->id }}</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">Complete details of this activity log entry</p>
        </div>
        <div>
            @php
                $actionColors = [
                    'deleted' => 'red',
                    'delete' => 'red',
                    'created' => 'green',
                    'updated' => 'amber',
                    'toggled' => 'blue',
                ];
                $actionIcons = [
                    'deleted' => 'trash',
                    'delete' => 'trash',
                    'created' => 'plus',
                    'updated' => 'pen',
                    'toggled' => 'toggle-on',
                ];
                $color = $actionColors[$activityLog->action] ?? 'gray';
                $icon = $actionIcons[$activityLog->action] ?? 'circle-info';
            @endphp
            <span class="px-4 py-2 rounded-full bg-{{ $color }}-50 text-{{ $color }}-700 text-sm font-bold">
                <i class="fa-solid fa-{{ $icon }} mr-1"></i>
                {{ ucfirst($activityLog->action) }}
            </span>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="px-6 py-5">
        {{-- Two Column Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left Column --}}
            <div>
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-200">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Field</span>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Value</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-600">ID</span>
                        <span class="text-sm font-medium text-gray-800">#{{ $activityLog->id }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-600">User / Admin</span>
                        <span class="text-sm font-medium text-gray-800">
                            <i class="fa-solid fa-user mr-1 text-gray-400"></i>
                            {{ $activityLog->admin_name }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-600">Action</span>
                        <span class="px-3 py-1 rounded-full bg-{{ $color }}-50 text-{{ $color }}-700 text-xs font-bold">
                            <i class="fa-solid fa-{{ $icon }} mr-1"></i>
                            {{ ucfirst($activityLog->action) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-600">Module</span>
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-medium">
                            {{ ucfirst(str_replace('_', ' ', $activityLog->module)) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-200">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Field</span>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Value</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-600">IP Address</span>
                        <code class="text-xs bg-gray-200 px-2 py-1 rounded">{{ $activityLog->ip_address ?? 'N/A' }}</code>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-600">Created At</span>
                        <div class="text-right">
                            @if($activityLog->created_at)
                                <div class="text-sm font-medium text-gray-800">
                                    {{ $activityLog->created_at instanceof \Carbon\Carbon ?
                                       $activityLog->created_at->format('d M Y, h:i A') :
                                       \Carbon\Carbon::parse($activityLog->created_at)->format('d M Y, h:i A') }}
                                </div>
                                @if($activityLog->created_at instanceof \Carbon\Carbon && !$activityLog->created_at->isFuture())
                                    <div class="text-[10px] text-gray-400">
                                        {{ $activityLog->created_at->diffForHumans() }}
                                    </div>
                                @endif
                            @else
                                <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-600">Quick Actions</span>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.activity-logs.index', ['search' => $activityLog->admin_name]) }}"
                               class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-100 transition">
                                <i class="fa-solid fa-user"></i> User Logs
                            </a>
                            <a href="{{ route('admin.activity-logs.index', ['module' => $activityLog->module]) }}"
                               class="inline-flex items-center gap-1 bg-purple-50 text-purple-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-purple-100 transition">
                                <i class="fa-solid fa-cube"></i> Module Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Description Section --}}
        <div class="mt-6">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-align-left text-blue-600 mt-0.5"></i>
                    <div class="flex-1">
                        <h6 class="font-bold text-gray-700 text-sm mb-1">Description</h6>
                        <p class="text-gray-700 text-sm leading-relaxed mb-0">
                            {{ $activityLog->description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Previous Activity (if exists) --}}
        @if(isset($activityLog->previous_log) && $activityLog->previous_log)
        <div class="mt-6">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-link text-amber-600 mt-0.5"></i>
                    <div class="flex-1">
                        <h6 class="font-bold text-amber-700 text-sm mb-1">Previous Activity</h6>
                        <p class="text-sm text-gray-700 mb-0">
                            <a href="{{ route('admin.activity-logs.show', $activityLog->previous_log) }}"
                               class="text-blue-600 hover:underline font-medium">
                                Log #{{ $activityLog->previous_log->id }}
                            </a>
                            <span class="text-gray-500"> - </span>
                            <span class="text-gray-600">{{ ucfirst($activityLog->previous_log->action) }}</span>
                            <span class="text-gray-400">:</span>
                            <span class="text-gray-600">{{ Str::limit($activityLog->previous_log->description, 100) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
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
