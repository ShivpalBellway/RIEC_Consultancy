@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
<section class="bg-[#f5f9ff] px-6 py-12 min-h-[620px]">
    <div class="max-w-5xl mx-auto space-y-6">
        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-gold">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to My Applications
        </a>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 border-b border-gray-100 pb-5 mb-6">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.16em]">APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <h1 class="text-2xl font-black text-primary mt-2">{{ $application->program?->name ?? 'Application Details' }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Submitted on {{ $application->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <span class="inline-flex rounded-full bg-primary/10 text-primary px-4 py-2 text-xs font-black capitalize">
                    {{ $application->status }}
                </span>
            </div>

            <div class="grid md:grid-cols-3 gap-4 mb-8">
                <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                    <p class="text-xs text-gray-400 font-bold mb-1">Applicant</p>
                    <p class="text-sm font-black text-gray-900">{{ $application->name }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                    <p class="text-xs text-gray-400 font-bold mb-1">Email</p>
                    <p class="text-sm font-black text-gray-900 break-all">{{ $application->email }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                    <p class="text-xs text-gray-400 font-bold mb-1">Phone</p>
                    <p class="text-sm font-black text-gray-900">{{ $application->phone }}</p>
                </div>
            </div>

            <div class="space-y-8">
                <div>
                    <h2 class="text-sm font-black text-primary uppercase tracking-[0.12em] mb-3">Eligibility Answers</h2>
                    <div class="grid md:grid-cols-2 gap-3">
                        @forelse($application->eligibility_answers ?: [] as $key => $answer)
                            <div class="rounded-xl border border-gray-100 bg-white px-4 py-3">
                                <p class="text-xs text-gray-400 font-bold">{{ $answer['label'] ?? $key }}</p>
                                <p class="text-sm font-black text-gray-900 mt-1">
                                    {{ is_array($answer['value'] ?? null) ? implode(', ', $answer['value']) : (($answer['value'] ?? null) ?: '-') }}
                                    @if(!empty($answer['unit']) && !empty($answer['value']))
                                        <span class="text-gray-500 font-semibold">{{ $answer['unit'] }}</span>
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No eligibility answers available.</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <h2 class="text-sm font-black text-primary uppercase tracking-[0.12em] mb-3">Application Answers</h2>
                    <div class="grid md:grid-cols-2 gap-3">
                        @forelse($application->form_answers ?: [] as $key => $answer)
                            <div class="rounded-xl border border-gray-100 bg-white px-4 py-3">
                                <p class="text-xs text-gray-400 font-bold">{{ $answer['label'] ?? $key }}</p>
                                <p class="text-sm font-black text-gray-900 mt-1">
                                    @if(!empty($answer['is_file']))
                                        @if(!empty($answer['store_in_system']) && !empty($answer['value']))
                                            <a href="{{ route('student.applications.attachments.download', [$application, $key]) }}"
                                               class="text-primary hover:underline">
                                                Download submitted file
                                            </a>
                                        @else
                                            File submitted by email
                                        @endif
                                    @else
                                        {{ is_array($answer['value'] ?? null) ? implode(', ', $answer['value']) : (($answer['value'] ?? null) ?: '-') }}
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No application answers available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
