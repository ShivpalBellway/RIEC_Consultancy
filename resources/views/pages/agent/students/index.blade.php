@extends('layouts.agent')

@section('title', 'Student Management')
@section('page_title', 'Student Management')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Student Directory</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage student applications, documents, and Korean address details</p>
        </div>

        <a href="{{ route('agent.students.create') }}" class="inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-bold px-4 py-2.5 rounded-xl transition-all shadow-md text-sm">
            <i class="fa-solid fa-user-plus"></i>
            <span>Add New Student</span>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-sm">
        <form action="{{ route('agent.students.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors"
                    placeholder="Search by student name, email, passport number...">
            </div>

            <div class="w-full md:w-48">
                <select name="status" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                    <option value="">All Statuses</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="university_assigned" {{ request('status') === 'university_assigned' ? 'selected' : '' }}>University Assigned</option>
                    <option value="offer_letter" {{ request('status') === 'offer_letter' ? 'selected' : '' }}>Offer Letter</option>
                    <option value="visa" {{ request('status') === 'visa' ? 'selected' : '' }}>Visa</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-semibold rounded-xl text-sm hover:bg-primary-700 transition-colors">
                    Filter
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('agent.students.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl text-sm hover:bg-gray-200 transition-colors flex items-center justify-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-200/80">
                    <tr>
                        <th class="py-4 px-5">Student Info</th>
                        <th class="py-4 px-5">Passport No.</th>
                        <th class="py-4 px-5">Korean Address</th>
                        <th class="py-4 px-5">University</th>
                        <th class="py-4 px-5">Status</th>
                        <th class="py-4 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="py-4 px-5">
                            <div class="font-bold text-gray-900 text-base">{{ $student->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $student->email }}</div>
                            @if($student->phone)
                            <div class="text-xs text-gray-400 mt-0.5"><i class="fa-solid fa-phone text-[10px] mr-1"></i>{{ $student->phone }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-5">
                            @if($student->passport_number)
                            <span class="font-mono text-xs bg-gray-100 text-gray-800 px-2.5 py-1 rounded border border-gray-200">
                                {{ $student->passport_number }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400 italic">Not Provided</span>
                            @endif
                        </td>
                        <td class="py-4 px-5">
                            @if($student->korean_address)
                            <div class="text-xs font-medium text-gray-800 line-clamp-1" title="{{ $student->korean_address }}">
                                <i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $student->korean_address }}
                            </div>
                            <div class="text-[11px] text-gray-400">{{ $student->korean_city }}, {{ $student->korean_postal_code }}</div>
                            @else
                            <span class="text-xs text-amber-600 font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Address Pending</span>
                            @endif
                        </td>
                        <td class="py-4 px-5">
                            @if($student->university_name)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-100">
                                <i class="fa-solid fa-building-columns"></i>
                                {{ $student->university_name }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400 italic">Admin Assigned Only</span>
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
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('agent.students.show', $student) }}" class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1" title="Upload Documents & View Details">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <span>Documents</span>
                                </a>
                                <a href="{{ route('agent.students.edit', $student) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1" title="Edit Student & Address">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Edit</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-user-slash text-3xl text-gray-300 mb-3 block"></i>
                            <p class="font-semibold text-gray-600">No student records found.</p>
                            <p class="text-xs text-gray-400 mt-1">Start by adding a new student to your agency list.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
