@extends('layouts.admin')

@section('title', 'Agent Students')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-user-graduate text-primary"></i>
                <span>Agent Students Directory</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage students registered through agency partners and assign universities</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="px-4 py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Add Student
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-gold transition-colors"
                    placeholder="Search student name, email, passport, university...">
            </div>

            <div class="w-full md:w-48">
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-gold transition-colors">
                    <option value="">All Statuses</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="university_assigned" {{ request('status') === 'university_assigned' ? 'selected' : '' }}>University Assigned</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl text-sm hover:bg-slate-800 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                    <tr>
                        <th class="py-4 px-5">Student Info</th>
                        <th class="py-4 px-5">Agency Partner</th>
                        <th class="py-4 px-5">Assigned University</th>
                        <th class="py-4 px-5">Status</th>
                        <th class="py-4 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-5">
                            <div class="font-extrabold text-slate-900 text-base">{{ $student->full_name }}</div>
                            <div class="text-xs text-slate-500">{{ $student->email }}</div>
                            @if($student->passport_number)<div class="text-[11px] font-mono text-slate-400">PP: {{ $student->passport_number }}</div>@endif
                        </td>

                        <td class="py-4 px-5">
                            <div class="font-bold text-slate-900 text-xs">{{ $student->agent?->agency_name ?? 'N/A' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $student->agent?->name }}</div>
                        </td>

                        <td class="py-4 px-5">
                            @if($student->university_name)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-100">
                                <i class="fa-solid fa-building-columns"></i>
                                {{ $student->university_name }}
                            </span>
                            @else
                            <span class="text-xs text-amber-600 font-bold italic"><i class="fa-solid fa-circle-exclamation mr-1"></i>Unassigned</span>
                            @endif
                        </td>

                        <td class="py-4 px-5">
                            <span class="px-3 py-1 rounded-full text-xs font-bold 
                                @if($student->status === 'completed') bg-emerald-100 text-emerald-800
                                @elseif($student->status === 'under_review') bg-amber-100 text-amber-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $student->status)) }}
                            </span>
                        </td>

                        <td class="py-4 px-5 text-right">
                            <a href="{{ route('admin.students.edit', $student) }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors inline-flex items-center gap-1 mr-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit</span>
                            </a>
                            <a href="{{ route('admin.students.show', $student) }}" class="px-3.5 py-1.5 bg-primary text-white rounded-lg text-xs font-bold hover:bg-slate-800 transition-colors inline-flex items-center gap-1">
                                <i class="fa-solid fa-sliders"></i>
                                <span>Manage & Assign</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-user-slash text-3xl text-slate-300 mb-2 block"></i>
                            <p class="font-semibold text-slate-600">No agent students found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
