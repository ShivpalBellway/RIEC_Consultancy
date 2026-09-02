@extends('layouts.app')

@section('title', 'My Applications')

@section('content')
<section class="bg-[#f5f9ff] px-6 py-12 min-h-[620px]">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-xs text-gold font-black uppercase tracking-[0.18em]">Student Portal</p>
                <h1 class="text-3xl font-black text-primary mt-2">My Applications</h1>
                <p class="text-sm text-gray-500 mt-2">Track your submitted applications and current status.</p>
            </div>

            <a href="{{ route('apply.index') }}" class="inline-flex items-center justify-center gap-2 bg-gold text-white rounded-xl px-5 py-3 text-sm font-bold hover:bg-yellow-600 transition">
                New Application
                <i class="fa-solid fa-plus text-xs"></i>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @if($applications->isEmpty())
                <div class="text-center py-16 px-6">
                    <i class="fa-regular fa-file-lines text-5xl text-gray-200 mb-4"></i>
                    <h2 class="text-lg font-black text-gray-700">No applications yet</h2>
                    <p class="text-sm text-gray-500 mt-2">Start an application and it will appear here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="px-5 py-3 text-left">Application</th>
                                <th class="px-5 py-3 text-left">Program</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-left">Submitted</th>
                                <th class="px-5 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($applications as $application)
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'reviewed' => 'bg-blue-50 text-blue-700',
                                        'eligible' => 'bg-amber-50 text-amber-800',
                                        'contacted' => 'bg-purple-50 text-purple-700',
                                        'closed' => 'bg-emerald-50 text-emerald-700',
                                    ];
                                    $color = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-5 py-4 font-mono text-xs text-gray-500">
                                        APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-bold text-gray-800">{{ $application->program?->name ?? 'Program unavailable' }}</p>
                                        <p class="text-xs text-gray-400">{{ $application->program?->country }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-black capitalize {{ $color }}">
                                            {{ $application->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-gray-500">
                                        {{ $application->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('student.applications.show', $application) }}" class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-bold text-primary hover:bg-gray-50">
                                            View
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($applications->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $applications->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</section>
@endsection
