@extends('layouts.agent')

@section('title', 'Dashboard')
@section('page_title', 'Agent Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome Hero Banner -->
    <div class="bg-gradient-to-r from-primary-600 via-primary-700 to-indigo-900 rounded-2xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl font-black text-white pointer-events-none">
            REIAC
        </div>
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold/20 text-gold text-xs font-semibold backdrop-blur-md mb-3 border border-gold/30">
                <i class="fa-solid fa-crown text-gold"></i>
                Agency Portal Partner
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Welcome back, {{ auth('agent')->user()->name }}!</h2>
            <p class="text-indigo-100 text-sm mt-1.5 leading-relaxed">
                Manage your student applications, upload mandatory documents, and track university admissions in real-time.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('agent.students.create') }}" class="inline-flex items-center gap-2 bg-gold hover:bg-amber-500 text-slate-900 font-bold px-4 py-2.5 rounded-xl transition-all shadow-md text-sm">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Add New Student</span>
                </a>
                <a href="{{ route('agent.students.index') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-4 py-2.5 rounded-xl transition-all text-sm backdrop-blur-md border border-white/20">
                    <i class="fa-solid fa-list"></i>
                    <span>View All Students</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Dashboard Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Students -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Students</div>
                <div class="text-3xl font-black text-gray-900 mt-1">{{ $totalStudents }}</div>
                <div class="text-xs text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-users"></i>
                    <span>Managed Students</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-primary-600 text-xl font-bold">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>

        <!-- Active Applications -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">Active Applications</div>
                <div class="text-3xl font-black text-gray-900 mt-1">{{ $activeApplications }}</div>
                <div class="text-xs text-blue-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-spinner animate-spin"></i>
                    <span>In Progress</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
        </div>

        <!-- Under Review -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">Under Review</div>
                <div class="text-3xl font-black text-gray-900 mt-1">{{ $underReview }}</div>
                <div class="text-xs text-amber-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-clock"></i>
                    <span>Admin Verification</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-xl font-bold">
                <i class="fa-solid fa-magnifying-glass-doc"></i>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">Approved</div>
                <div class="text-3xl font-black text-gray-900 mt-1">{{ $approvedCount }}</div>
                <div class="text-xs text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Finalized Applications</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-xl font-bold">
                <i class="fa-solid fa-award"></i>
            </div>
        </div>
    </div>

    <!-- Main Grid: Recent Students & Notifications Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Students Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-gray-900 text-base">Recent Students</h3>
                    <p class="text-xs text-gray-500">Latest students added to your agency portal</p>
                </div>
                <a href="{{ route('agent.students.index') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                    <span>View All</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/80 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="py-3.5 px-5">Student Name</th>
                            <th class="py-3.5 px-5">University</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentStudents as $student)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-gray-900">{{ $student->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $student->email }}</div>
                            </td>
                            <td class="py-3.5 px-5">
                                @if($student->university_name)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 font-semibold text-xs border border-indigo-100">
                                    <i class="fa-solid fa-building-columns"></i>
                                    {{ $student->university_name }}
                                </span>
                                @else
                                <span class="text-xs text-gray-400 italic">Pending Assignment</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold 
                                    @if($student->status === 'completed') bg-emerald-100 text-emerald-800
                                    @elseif($student->status === 'under_review') bg-amber-100 text-amber-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $student->status)) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <a href="{{ route('agent.students.show', $student) }}" class="inline-flex items-center gap-1 text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-3 py-1.5 rounded-lg hover:bg-primary-100 transition-colors">
                                    <span>Manage</span>
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400 text-sm">
                                <i class="fa-solid fa-user-slash text-2xl mb-2 text-gray-300 block"></i>
                                No students added yet. <a href="{{ route('agent.students.create') }}" class="text-primary-600 font-bold hover:underline">Add First Student</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- In-App Notifications Feed -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5 flex flex-col">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <h3 class="font-extrabold text-gray-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-bell text-gold"></i>
                    <span>In-App Notifications</span>
                </h3>
            </div>

            <div class="space-y-3.5 flex-1 overflow-y-auto max-h-[380px] pr-1">
                @forelse($notifications as $notif)
                <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100 hover:bg-gray-100/80 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-gray-900">{{ $notif->title }}</span>
                        <span class="text-[10px] text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $notif->message }}</p>
                    @if($notif->link)
                    <a href="{{ $notif->link }}" class="text-[11px] font-bold text-primary-600 hover:underline inline-block mt-1.5">View Details &rarr;</a>
                    @endif
                </div>
                @empty
                <div class="py-8 text-center text-gray-400 text-xs">
                    <i class="fa-regular fa-bell-slash text-2xl text-gray-300 block mb-2"></i>
                    No notifications yet.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
