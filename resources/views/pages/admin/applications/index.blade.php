@extends('layouts.admin')

@section('title', 'Applications')
@section('page-title', 'Submitted Applications')

@section('breadcrumb')
<span class="text-gray-300">/</span>
<span class="text-gray-500">Applications</span>
@endsection

@section('content')
{{-- Filter & Search Card --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form action="{{ route('admin.applications.index') }}" method="GET" class="grid md:grid-cols-4 gap-4 items-end">
        {{-- Search Keyword --}}
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Search Keyword</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, phone..."
                class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] transition bg-gray-50/50">
        </div>

        {{-- Filter by Program --}}
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Filter by Program</label>
            <select name="program_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] transition bg-white">
                <option value="">All Programs</option>
                @foreach($programs as $p)
                <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->name }} ({{ $p->country }})
                </option>
                @endforeach
            </select>
        </div>

        {{-- Filter by Status --}}
        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Filter by Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] transition bg-white">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="eligible" {{ request('status') === 'eligible' ? 'selected' : '' }}>Eligible</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        {{-- Submit & Clear Buttons --}}
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-[#1a2f5e] text-white text-sm font-bold py-2 rounded-xl hover:bg-[#142447] transition shadow">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'program_id', 'status']))
            <a href="{{ route('admin.applications.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 font-bold py-2 px-4 rounded-xl text-sm transition flex items-center justify-center">
                Clear
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Submissions Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <p class="text-sm text-gray-500">{{ $applications->total() }} application(s) total</p>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
            <a href="{{ route('admin.applications.export', request()->query()) }}"
                download="applications_export.xlsx"
                class="inline-flex items-center justify-center gap-2 bg-[#1a2f5e] text-white text-xs font-bold py-2 px-4 rounded-xl hover:bg-[#142447] transition shadow-sm">
                <i class="fa-solid fa-file-arrow-down"></i>
                Export to Excel
            </a>
        </div>
    </div>

    @if($applications->isEmpty())
    <div class="py-20 text-center text-gray-400">
        <i class="fa-solid fa-inbox text-5xl mb-4 block text-gray-200"></i>
        <p class="font-semibold text-gray-500">No applications found</p>
        <p class="text-sm mt-1">Submissions from the student eligibility flow will appear here.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Applicant</th>
                    <th class="px-6 py-3 text-left">Program</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-left">Submitted Date</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($applications as $app)
                <tr class="hover:bg-gray-50/50 transition">
                    {{-- App formatted ID --}}
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">
                        APP-{{ str_pad($app->id, 5, '0', STR_PAD_LEFT) }}
                    </td>

                    {{-- Candidate details --}}
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-800">{{ $app->name }}</div>
                        <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $app->email }}</div>
                        <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $app->phone }}</div>
                        @if($app->user)
                        <div class="text-[11px] text-blue-600 font-bold mt-1">
                            <i class="fa-solid fa-user-check mr-1"></i> Account linked
                        </div>
                        @endif
                    </td>

                    {{-- Program --}}
                    <td class="px-6 py-4 text-gray-600">
                        @if($app->program)
                        <div class="font-medium text-gray-800 text-sm">{{ $app->program->name }}</div>
                        <div class="text-xs text-gray-400">📍 {{ $app->program->country }}</div>
                        @else
                        <span class="text-red-500 text-xs italic">Deleted Program</span>
                        @endif
                    </td>

                    {{-- Status Badge --}}
                    <td class="px-6 py-4 text-center">
                        @php
                        $statusColors = [
                        'pending' => 'bg-gray-100 text-gray-700 border-gray-200',
                        'reviewed' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'eligible' => 'bg-amber-50 text-amber-800 border-amber-100',
                        'contacted' => 'bg-purple-50 text-purple-700 border-purple-100',
                        'closed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        ];
                        $color = $statusColors[$app->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                        @endphp
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold border capitalize {{ $color }}">
                            {{ $app->status }}
                        </span>
                    </td>

                    {{-- Date --}}
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $app->created_at->format('M d, Y') }}<br>
                        <span class="text-gray-400">{{ $app->created_at->format('h:i A') }}</span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.applications.show', $app) }}"
                            class="inline-flex items-center gap-1 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 transition shadow-sm">
                            <i class="fa-solid fa-eye text-gray-400"></i> View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination links --}}
    @if($applications->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $applications->links() }}
    </div>
    @endif

    @endif
</div>
@endsection
