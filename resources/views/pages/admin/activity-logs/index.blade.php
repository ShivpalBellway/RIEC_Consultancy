@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'Manage Activity Logs')

@section('header-actions')
{{-- No buttons at top --}}
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

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Logs</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCount ?? $logs->total() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-list text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Today's Logs</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $todayLogs ?? \App\Models\ActivityLog::whereDate('created_at', today())->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                <i class="fa-solid fa-calendar-day text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Unique Actions</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\ActivityLog::distinct('action')->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i class="fa-solid fa-tags text-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Modules</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\ActivityLog::distinct('module')->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-cubes text-lg"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filter Section --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-sm font-bold text-gray-700">
            <i class="fa-solid fa-filter mr-2"></i> Filter Logs
        </h3>
    </div>
    <div class="px-6 py-5">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">Action</label>
                <select name="action" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">Module</label>
                <select name="module" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Modules</option>
                    @foreach($modules as $module)
                        <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $module)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">From Date</label>
                <input type="date" name="from_date" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('from_date') }}">
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">To Date</label>
                <input type="date" name="to_date" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('to_date') }}">
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">Search</label>
                <input type="text" name="search" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="md:col-span-5 flex gap-2 mt-2">
                <button type="submit" class="inline-flex items-center gap-2 bg-[#1a2f5e] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#132247] transition">
                    <i class="fa-solid fa-filter text-xs"></i> Apply Filters
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center gap-2 bg-gray-200 text-gray-700 px-5 py-2 rounded-xl text-sm font-semibold hover:bg-gray-300 transition">
                    <i class="fa-solid fa-undo text-xs"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Logs Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                <i class="fa-solid fa-history mr-2"></i> Activity Logs
            </h2>
            <p class="text-sm text-gray-500 mt-1">{{ $logs->total() }} entries found</p>
        </div>
        <div class="flex gap-2">
            {{-- Single Export Button with Format Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                    <i class="fa-solid fa-file-export text-xs"></i>
                    Export
                    @if(request()->hasAny(['action', 'module', 'from_date', 'to_date', 'search']))
                        <span class="bg-white/20 text-white text-[10px] px-2 py-0.5 rounded-full">Filtered</span>
                    @else
                        <span class="bg-white/20 text-white text-[10px] px-2 py-0.5 rounded-full">All</span>
                    @endif
                    <i class="fa-solid fa-chevron-down text-[10px] ml-1"></i>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                    <a href="{{ route('admin.activity-logs.export-excel', request()->query()) }}"
                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <i class="fa-solid fa-file-excel text-green-600 text-base"></i>
                        Excel
                        @if(request()->hasAny(['action', 'module', 'from_date', 'to_date', 'search']))
                            <span class="text-[10px] text-blue-600 ml-auto">Filtered</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.activity-logs.export-csv', request()->query()) }}"
                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <i class="fa-solid fa-file-csv text-blue-600 text-base"></i>
                        CSV
                        @if(request()->hasAny(['action', 'module', 'from_date', 'to_date', 'search']))
                            <span class="text-[10px] text-blue-600 ml-auto">Filtered</span>
                        @endif
                    </a>
                </div>
            </div>

            <button type="button" class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-amber-700 transition" data-bs-toggle="modal" data-bs-target="#retentionModal">
                <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                Delete Logs Older Than 365 Days
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">#</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">User</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Action</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Module</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Description</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">IP Address</th>
                    <th class="px-6 py-4 text-left font-bold text-gray-600">Time</th>
                    <th class="px-6 py-4 text-right font-bold text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-400">{{ $logs->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-800">{{ $log->admin_name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $badgeColor = $log->action == 'deleted' || $log->action == 'delete' ? 'red' :
                                          ($log->action == 'created' ? 'green' :
                                          ($log->action == 'updated' ? 'amber' :
                                          ($log->action == 'toggled' ? 'blue' : 'gray')));
                        @endphp
                        <span class="px-3 py-1 rounded-full bg-{{ $badgeColor }}-50 text-{{ $badgeColor }}-700 text-xs font-bold">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-medium">
                            {{ ucfirst(str_replace('_', ' ', $log->module)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $log->description }}">
                        {{ Str::limit($log->description, 50) }}
                    </td>
                    <td class="px-6 py-4">
                        <code class="text-xs bg-gray-50 px-2 py-1 rounded">{{ $log->ip_address }}</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $date = $log->created_at;
                        @endphp
                        @if($date)
                            <div class="text-sm font-medium text-gray-800">
                                {{ $date instanceof \Carbon\Carbon ? $date->format('d M Y, h:i A') : \Carbon\Carbon::parse($date)->format('d M Y, h:i A') }}
                            </div>
                            @if($date instanceof \Carbon\Carbon && !$date->isFuture())
                                <div class="text-[10px] text-gray-400">
                                    {{ $date->diffForHumans() }}
                                </div>
                            @endif
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.activity-logs.show', $log) }}"
                               class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition"
                               title="View Details">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa-solid fa-inbox text-4xl text-gray-300 block mb-3"></i>
                        No activity logs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
        <div class="text-sm text-gray-500">
            Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} entries
        </div>
        <div>
            {{ $logs->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Retention Cleanup Modal --}}
<div class="modal fade" id="retentionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-2xl border-0 shadow-2xl">
            <div class="modal-header border-b border-gray-100 px-6 py-4">
                <h5 class="modal-title font-bold text-gray-800">
                    <i class="fa-solid fa-shield-halved mr-2 text-amber-600"></i> Audit Log Retention
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.activity-logs.delete-old') }}" method="POST">
                @csrf
                <div class="modal-body px-6 py-5">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                        <div class="flex items-start gap-3 text-amber-800">
                            <i class="fa-solid fa-circle-info text-lg mt-0.5"></i>
                            <div>
                                <span class="font-bold block">Minimum 365-day retention is mandatory</span>
                                <span class="text-sm">Only logs older than 365 days can be permanently deleted. Newer logs are protected by the backend and will not be removed.</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">
                        Eligible logs:
                        <strong class="text-gray-800">{{ \App\Models\ActivityLog::where('created_at', '<', now()->subDays(365))->count() }}</strong>
                    </p>
                    <div class="mt-4 flex items-start gap-2">
                        <input class="form-check-input mt-0.5 cursor-pointer" type="checkbox" name="confirmation" id="retentionConfirmation" value="yes" required>
                        <label class="text-sm text-gray-600 cursor-pointer" for="retentionConfirmation">
                            I understand that only logs older than 365 days will be deleted.
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-t border-gray-100 px-6 py-4">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold transition" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        <i class="fa-solid fa-broom mr-1"></i> Delete Eligible Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
