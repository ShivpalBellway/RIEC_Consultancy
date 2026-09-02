@extends('layouts.admin')

@section('title', 'Application Details')
@section('page-title')
Application details: APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}
@endsection

@section('breadcrumb')
<span class="text-gray-300">/</span>
<a href="{{ route('admin.applications.index') }}" class="hover:text-primary">Applications</a>
<span class="text-gray-300">/</span>
<span class="text-gray-500">Details</span>
@endsection

@section('content')
<div class="max-w-8xl mx-auto space-y-6">

    {{-- Top Action --}}
    <!-- <div class="flex justify-end">
        <button type="button"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-white text-[#1a2f5e] text-xs font-bold shadow-sm hover:bg-gray-50 transition">
            <i class="fa-solid fa-download"></i>
            Download Summary
        </button>
    </div> -->

    {{-- Top Profile Summary --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 items-center">

            <div class="md:col-span-1 flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-[#eef1f8] flex items-center justify-center text-[#1a2f5e] text-2xl font-black">
                    {{ strtoupper(substr($application->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-black text-gray-900 text-base">{{ $application->name }}</h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Submitted: {{ $application->created_at->format('M d, Y h:i A') }}
                    </p>
                </div>
            </div>

            <div class="hidden md:block h-12 border-l border-gray-100"></div>

            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fa-regular fa-id-badge"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold">Application ID</p>
                    <h4 class="text-sm font-black text-gray-900">APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</h4>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 md:col-span-1">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold">Current Status</p>
                        <h4 class="text-sm font-black text-orange-500">
                            {{ ucwords(str_replace('_', ' ', $application->status)) }}
                        </h4>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i class="fa-regular fa-calendar"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold">Submitted On</p>
                        <h4 class="text-sm font-black text-gray-900">{{ $application->created_at->format('M d, Y') }}</h4>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="grid lg:grid-cols-12 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Status --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-list-check text-xs"></i>
                    </span>
                    <h4 class="text-xs font-black text-gray-900 uppercase tracking-wide">Application Status</h4>
                </div>

                <form action="{{ route('admin.applications.update-status', $application) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <select name="status"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] bg-white">
                        <option value="pending"               {{ $application->status === 'pending'               ? 'selected' : '' }}>Pending</option>
                        <option value="received"              {{ $application->status === 'received'              ? 'selected' : '' }}>Received</option>
                        <option value="university_applied"    {{ $application->status === 'university_applied'    ? 'selected' : '' }}>University Applied</option>
                        <option value="tuition_fee_confirmed" {{ $application->status === 'tuition_fee_confirmed' ? 'selected' : '' }}>Tuition Fee Confirmed</option>
                        <option value="visa_applied"          {{ $application->status === 'visa_applied'          ? 'selected' : '' }}>Visa Applied</option>
                        <option value="visa_granted"          {{ $application->status === 'visa_granted'          ? 'selected' : '' }}>Visa Granted</option>
                        <option value="visa_rejected"         {{ $application->status === 'visa_rejected'         ? 'selected' : '' }}>Visa Rejected</option>
                        <option value="studying"              {{ $application->status === 'studying'              ? 'selected' : '' }}>Studying</option>
                        <option value="refund_complete"       {{ $application->status === 'refund_complete'       ? 'selected' : '' }}>Refund Complete</option>
                    </select>

                    <button type="submit"
                        class="w-full bg-[#1a2f5e] text-white text-xs font-black py-3 rounded-xl hover:bg-[#142447] transition shadow">
                        Update Status
                    </button>
                </form>
            </div>

            {{-- Student Account --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-user-graduate text-xs"></i>
                    </span>
                    <h4 class="text-xs font-black text-gray-900 uppercase tracking-wide">Student Account</h4>
                </div>

                @if($application->user)
                <div class="space-y-2">
                    <p class="text-sm font-black text-gray-900">{{ $application->user->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $application->user->email }}</p>
                </div>
                @else
                <p class="text-xs text-gray-400 italic">No student account linked.</p>
                @endif
            </div>

            {{-- Consent --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                    </span>
                    <h4 class="text-xs font-black text-gray-900 uppercase tracking-wide">Student Consents</h4>
                </div>

                @if($application->user)
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-2 rounded-xl bg-gray-50 border border-gray-100 text-xs">
                        <span class="text-gray-600 font-semibold truncate mr-2">1. Personal Info Collection (Required)</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $application->user->consent_collection ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $application->user->consent_collection ? 'Checked' : 'Unchecked' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-gray-50 border border-gray-100 text-xs">
                        <span class="text-gray-600 font-semibold truncate mr-2">2. Third Party Provision (Required)</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $application->user->consent_third_party ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $application->user->consent_third_party ? 'Checked' : 'Unchecked' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-gray-50 border border-gray-100 text-xs">
                        <span class="text-gray-600 font-semibold truncate mr-2">3. Email Status Updates (Optional)</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $application->user->consent_email_updates ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $application->user->consent_email_updates ? 'Checked' : 'Unchecked' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-gray-50 border border-gray-100 text-xs">
                        <span class="text-gray-600 font-semibold truncate mr-2">4. Marketing & Promotion (Optional)</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $application->user->consent_marketing ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $application->user->consent_marketing ? 'Checked' : 'Unchecked' }}
                        </span>
                    </div>
                    @if($application->user->consents_accepted_at)
                    <p class="text-[10px] text-gray-400 font-semibold text-right mt-1">
                        Accepted on: {{ $application->user->consents_accepted_at->format('M d, Y h:i A') }}
                    </p>
                    @endif
                </div>
                @else
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                    <p class="text-xs font-black {{ $application->consent_accepted ? 'text-emerald-700' : 'text-red-600' }}">
                        {{ $application->consent_accepted ? 'Accepted' : 'Not accepted' }}
                    </p>
                    <p class="text-xs text-gray-600 leading-5 mt-2">
                        {{ $application->consent_text ?: 'Personal details storage consent.' }}
                    </p>
                    @if($application->consent_accepted_at)
                    <p class="text-[11px] text-gray-400 font-semibold mt-2">
                        Accepted on {{ $application->consent_accepted_at->format('M d, Y h:i A') }}
                    </p>
                    @endif
                </div>
                @endif
            </div>

            {{-- Eligibility --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center">
                        <i class="fa-solid fa-list-check text-xs"></i>
                    </span>
                    <h4 class="text-xs font-black text-gray-900 uppercase tracking-wide">Eligibility Check Answers</h4>
                </div>

                <div class="space-y-3">
                    @forelse($application->eligibility_answers ?: [] as $key => $ans)
                    <div class="flex items-center justify-between gap-3 bg-white border border-gray-200 rounded-xl px-4 py-3 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clipboard-check text-xs"></i>
                            </div>
                            <p class="text-xs text-gray-500 font-semibold truncate">
                                {{ $ans['label'] ?? $key }}
                            </p>
                        </div>

                        <div class="text-xs font-black text-[#1a2f5e] whitespace-nowrap">
                            {{ is_array($ans['value']) ? implode(', ', $ans['value']) : ($ans['value'] ?: '—') }}
                            @if(!empty($ans['unit']) && !empty($ans['value']))
                            <span class="font-semibold text-gray-500">{{ $ans['unit'] }}</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic text-center py-5">No eligibility check performed.</p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6">

                <div class="flex items-center gap-2 border-b border-gray-100 pb-5 mb-6">
                    <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-clipboard-list text-xs"></i>
                    </span>
                    <h3 class="font-black text-gray-900 text-xs uppercase tracking-wide">
                        Dynamic Form Builder Answers
                    </h3>
                </div>

                <div class="space-y-7">
                    @forelse($groupedAnswers as $group)
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-user text-purple-600 text-xs"></i>
                            <h4 class="text-xs font-black text-purple-700 uppercase tracking-wide">
                                {{ $group['section_name'] }}
                            </h4>
                        </div>

                        <div class="grid md:grid-cols-2 gap-3">
                            @foreach($group['answers'] as $ans)
                            <div class="border border-gray-200 rounded-xl px-4 py-3 bg-white hover:bg-gray-50 transition {{ empty($ans['value']) ? 'opacity-60' : '' }}">
                                <p class="text-[11px] text-gray-400 font-bold mb-1">
                                    {{ $ans['label'] }}
                                </p>

                                @if($ans['is_file'])
                                <div class="flex items-center gap-2 pt-1">
                                    @if($ans['value'])
                                    @if(isset($ans['store_in_system']) && !$ans['store_in_system'])
                                    <i class="fa-solid fa-envelope text-blue-600"></i>
                                    <span class="text-xs text-blue-600 font-bold bg-blue-50 px-2 py-1 rounded-lg">
                                        {{ $ans['value'] }}
                                    </span>
                                    @else
                                    <i class="fa-solid fa-file-arrow-down text-[#1a2f5e]"></i>
                                    <a href="{{ route('admin.applications.attachments.download', [$application, $ans['field_key']]) }}"
                                        class="text-xs text-[#1a2f5e] hover:underline font-black">
                                        Download Attachment
                                    </a>
                                    @endif
                                    @else
                                    <i class="fa-solid fa-file-arrow-down text-gray-400"></i>
                                    <span class="text-xs text-gray-400 italic">No file uploaded</span>
                                    @endif
                                </div>
                                @else
                                <p class="text-sm font-black text-gray-900">
                                    {{ is_array($ans['value']) ? implode(', ', $ans['value']) : ($ans['value'] ?: '—') }}
                                </p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-gray-400 text-sm">
                        <i class="fa-solid fa-file-invoice text-4xl block text-gray-200 mb-3"></i>
                        No form builder answers submitted.
                    </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
