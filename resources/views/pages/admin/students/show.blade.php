@extends('layouts.admin')

@section('title', 'Student – ' . $student->full_name)

@section('content')
@php
    $mandatoryTypes = \App\Models\StudentDocument::mandatoryDocumentTypes();
    $uploadedDocsMap = [];
    foreach ($student->documents as $d) {
        $uploadedDocsMap[$d->document_type] = $d;
    }

    $statuses = [
        'submitted'           => ['label' => 'Submitted',          'icon' => 'fa-paper-plane'],
        'under_review'        => ['label' => 'Under Review',       'icon' => 'fa-magnifying-glass'],
        'university_assigned' => ['label' => 'University Assigned','icon' => 'fa-building-columns'],
        'offer_letter'        => ['label' => 'Offer Letter',       'icon' => 'fa-file-signature'],
        'visa'                => ['label' => 'Visa Phase',         'icon' => 'fa-passport'],
        'completed'           => ['label' => 'Completed',          'icon' => 'fa-circle-check'],
    ];
    $statusKeys = array_keys($statuses);
    $currentIdx = array_search($student->status, $statusKeys);
@endphp

<div x-data="{ activeTab: '{{ session('active_tab', request('tab', 'overview')) }}', rejectDocId: null, rejectDocName: '' }" class="space-y-6">

    {{-- ── Success / Error Alerts ── --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-semibold">
        <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error_msg'))
    <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm font-semibold">
        <i class="fa-solid fa-circle-xmark text-rose-500 text-base"></i>
        {{ session('error_msg') }}
    </div>
    @endif

    {{-- ── PAGE HEADER ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary via-[#223d78] to-[#2b4d97] px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/10 text-white font-extrabold text-xl flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($student->first_name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-white leading-tight">{{ $student->full_name }}</h2>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-white/70 mt-0.5">
                        <span><i class="fa-solid fa-envelope mr-1 text-gold"></i>{{ $student->email }}</span>
                        @if($student->phone)<span>• <i class="fa-solid fa-phone mr-1 text-gold"></i>{{ $student->phone }}</span>@endif
                        <span>•</span>
                        <span class="bg-white/10 px-2 py-0.5 rounded text-white font-semibold">
                            Agent: {{ $student->agent?->name }} ({{ $student->agent?->agency_name }})
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-gold text-slate-900 capitalize">
                    {{ str_replace('_', ' ', ucfirst($student->status)) }}
                </span>
                <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-xl transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        {{-- Application Phase Timeline --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200/80 overflow-x-auto">
            <div class="flex items-center gap-1 min-w-max">
                @foreach($statuses as $sKey => $sInfo)
                    @php
                        $sIdx = array_search($sKey, $statusKeys);
                        $isDone = $sIdx < $currentIdx;
                        $isCurr = $sKey === $student->status;
                    @endphp
                    <div class="flex items-center">
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition-all
                            @if($isCurr) bg-primary text-white shadow
                            @elseif($isDone) bg-emerald-100 text-emerald-800
                            @else bg-slate-100 text-slate-400 @endif">
                            <i class="fa-solid {{ $isDone ? 'fa-check' : $sInfo['icon'] }} text-[10px]"></i>
                            <span>{{ $sInfo['label'] }}</span>
                        </div>
                        @if(!$loop->last)
                        <i class="fa-solid fa-chevron-right text-slate-300 text-[10px] mx-1"></i>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── TAB NAVIGATION ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="flex border-b border-slate-200/80 overflow-x-auto">
            @foreach([
                ['key'=>'overview',   'icon'=>'fa-address-card',    'label'=>'Student Overview'],
                ['key'=>'documents',  'icon'=>'fa-folder-open',     'label'=>'Documents Review'],
                ['key'=>'status',     'icon'=>'fa-sliders',         'label'=>'Manage Status'],
            ] as $tab)
            <button
                @click="activeTab = '{{ $tab['key'] }}'"
                :class="activeTab === '{{ $tab['key'] }}' ? 'border-b-2 border-primary text-primary font-bold bg-primary/5' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                class="px-5 py-3.5 text-xs font-semibold flex items-center gap-2 whitespace-nowrap transition-all">
                <i class="fa-solid {{ $tab['icon'] }}"></i>
                {{ $tab['label'] }}
            </button>
            @endforeach
        </div>

        {{-- ════════════════════════════════════════════
         |  TAB 1: OVERVIEW
         |════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'overview'" class="p-6 space-y-6">

            {{-- Personal Info Grid --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Personal Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach([
                        ['label'=>'Full Name',     'value'=>$student->full_name],
                        ['label'=>'Email',         'value'=>$student->email],
                        ['label'=>'Phone',         'value'=>$student->phone ?? 'N/A'],
                        ['label'=>'Date of Birth', 'value'=>$student->date_of_birth?->format('d M Y') ?? 'N/A'],
                        ['label'=>'Nationality',   'value'=>$student->nationality ?? 'N/A'],
                        ['label'=>'Passport No.',  'value'=>$student->passport_number ?? 'N/A'],
                    ] as $field)
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[11px] text-slate-400 font-semibold block">{{ $field['label'] }}</span>
                        <span class="font-bold text-slate-800 text-sm block mt-0.5">{{ $field['value'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Korean Address --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Korean Address</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach([
                        ['label'=>'Street Address',    'value'=>$student->korean_address ?? 'Not provided'],
                        ['label'=>'City',              'value'=>$student->korean_city ?? 'N/A'],
                        ['label'=>'Postal Code',       'value'=>$student->korean_postal_code ?? 'N/A'],
                        ['label'=>'Korean Phone',      'value'=>$student->korean_contact_number ?? 'N/A'],
                    ] as $field)
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[11px] text-slate-400 font-semibold block">{{ $field['label'] }}</span>
                        <span class="font-bold text-slate-800 text-sm block mt-0.5">{{ $field['value'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Agent Info --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Agent Partner</h3>
                <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-200 text-indigo-800 font-extrabold text-sm flex items-center justify-center">
                            {{ strtoupper(substr($student->agent?->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-extrabold text-indigo-900 text-sm">{{ $student->agent?->name }}</div>
                            <div class="text-xs text-indigo-700">{{ $student->agent?->agency_name }} • {{ $student->agent?->email }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.agents.index') }}" class="text-xs font-bold text-indigo-700 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-external-link-alt text-[10px]"></i> View Agent Panel
                    </a>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════
         |  TAB 2: DOCUMENTS REVIEW
         |════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'documents'" class="p-6 space-y-4">
            <form action="{{ route('admin.students.documents.upload', $student) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex flex-col md:flex-row md:items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Upload Document as Admin</label>
                    <select name="document_type" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                        <option value="">Select document type</option>
                        @foreach($mandatoryTypes as $typeKey => $typeName)
                        <option value="{{ $typeKey }}">{{ $typeName }}</option>
                        @endforeach
                        <option value="offer_letter">Official Offer Letter</option>
                    </select>
                </div>
                <input type="file" name="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="text-xs text-slate-600 bg-white border border-slate-200 rounded-lg p-1.5">
                <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold"><i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload</button>
            </form>

            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Mandatory Document Checklist — Admin Review</h3>
                @php
                    $verifiedCount = $student->documents->where('status', 'verified')->count();
                    $totalUploaded = $student->documents->count();
                @endphp
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $verifiedCount }} / {{ count($mandatoryTypes) }} Verified
                </span>
            </div>

            {{-- Mandatory Documents Checklist --}}
            <div class="space-y-3">
                @foreach($mandatoryTypes as $typeKey => $typeName)
                @php $doc = $uploadedDocsMap[$typeKey] ?? null; @endphp
                <div class="rounded-xl border p-4 {{ $doc ? ($doc->status === 'verified' ? 'bg-emerald-50 border-emerald-200' : ($doc->status === 'rejected' ? 'bg-rose-50 border-rose-200' : 'bg-blue-50 border-blue-200')) : 'bg-slate-50 border-slate-200' }}">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">

                        {{-- Left: Type label + status --}}
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-xs font-bold
                                {{ $doc ? ($doc->status === 'verified' ? 'bg-emerald-500 text-white' : ($doc->status === 'rejected' ? 'bg-rose-500 text-white' : 'bg-blue-500 text-white')) : 'bg-slate-200 text-slate-500' }}">
                                @if($doc)
                                    @if($doc->status === 'verified') <i class="fa-solid fa-check"></i>
                                    @elseif($doc->status === 'rejected') <i class="fa-solid fa-xmark"></i>
                                    @else <i class="fa-solid fa-clock"></i>
                                    @endif
                                @else
                                    <i class="fa-solid fa-minus"></i>
                                @endif
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 text-sm">{{ $typeName }}</span>
                                <span class="ml-2 text-[11px] text-rose-500 font-semibold">* Mandatory</span>
                                @if($doc)
                                <div class="mt-0.5">
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($doc->file_path) }}" target="_blank"
                                       class="text-xs text-primary hover:underline flex items-center gap-1">
                                        <i class="fa-solid fa-download text-[10px]"></i>
                                        {{ $doc->document_name }} — Uploaded {{ $doc->created_at->format('d M Y') }}
                                    </a>
                                    @if($doc->admin_comment)
                                    <div class="text-[11px] text-rose-700 font-medium mt-0.5">
                                        <i class="fa-solid fa-comment-dots mr-1"></i>Admin Note: {{ $doc->admin_comment }}
                                    </div>
                                    @endif
                                </div>
                                @else
                                <div class="text-xs text-slate-400 mt-0.5 italic">Not uploaded yet by agent</div>
                                @endif
                            </div>
                        </div>

                        {{-- Right: Admin Verify / Reject Actions --}}
                        @if($doc)
                        <div class="flex items-center gap-2 shrink-0">
                            @if($doc->status === 'verified')
                                <span class="px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check"></i> Verified
                                </span>
                            @else
                                {{-- Verify Button --}}
                                <form action="{{ route('admin.students.documents.verify', $doc->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition-colors flex items-center gap-1.5 shadow-sm">
                                        <i class="fa-solid fa-circle-check"></i> Verify
                                    </button>
                                </form>

                                {{-- Reject Button --}}
                                <button type="button"
                                    @click="activeTab = 'documents'; $dispatch('open-reject-modal', { id: {{ $doc->id }}, name: '{{ $typeName }}' })"
                                    class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs transition-colors flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-circle-xmark"></i> Reject
                                </button>
                            @endif
                        </div>
                        @else
                        <span class="text-xs text-slate-400 bg-slate-100 px-3 py-1.5 rounded-full font-semibold shrink-0">Awaiting Upload</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- All other (non-mandatory) documents if any --}}
            @php
                $extraDocs = $student->documents->whereNotIn('document_type', array_keys($mandatoryTypes));
            @endphp
            @if($extraDocs->count() > 0)
            <div class="mt-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Additional Documents</h4>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                            <tr>
                                <th class="px-4 py-3">Document</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Uploaded</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($extraDocs as $doc)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800">{{ $doc->document_type_name }}</div>
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($doc->file_path) }}" target="_blank" class="text-xs text-primary hover:underline">Download</a>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                        {{ $doc->status === 'verified' ? 'bg-emerald-100 text-emerald-800' : ($doc->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($doc->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-500">{{ $doc->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if($doc->status !== 'verified')
                                    <form action="{{ route('admin.students.documents.verify', $doc->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-xs font-bold text-emerald-700 hover:underline mr-3">Verify</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- ════════════════════════════════════════════
         |  TAB 3: MANAGE STATUS
         |════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'status'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- University Assignment --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 pb-2 border-b border-slate-100">
                        <i class="fa-solid fa-building-columns text-indigo-500 mr-1"></i> Assign / Change University
                    </h3>

                    <form action="{{ route('admin.students.update-university', $student) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">University Name *</label>
                            <input type="text" name="university_name" value="{{ old('university_name', $student->university_name) }}" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-gold"
                                placeholder="e.g. Korea University / Yonsei University">
                        </div>
                        @if($student->university_name)
                        <div class="p-3 bg-indigo-50 rounded-xl border border-indigo-100 text-xs text-indigo-700">
                            <span class="font-bold">Current:</span> {{ $student->university_name }}
                        </div>
                        @endif
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check-double"></i> Save & Notify Agent via Email
                        </button>
                    </form>
                </div>

                {{-- Status Update --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 pb-2 border-b border-slate-100">
                        <i class="fa-solid fa-sliders text-primary mr-1"></i> Update Application Phase
                    </h3>

                    <form action="{{ route('admin.students.update-status', $student) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Application Status</label>
                            <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-gold">
                                <option value="submitted"           {{ $student->status === 'submitted'           ? 'selected' : '' }}>Submitted</option>
                                <option value="under_review"        {{ $student->status === 'under_review'        ? 'selected' : '' }}>Under Review</option>
                                <option value="university_assigned" {{ $student->status === 'university_assigned' ? 'selected' : '' }}>University Assigned</option>
                                <option value="offer_letter"        {{ $student->status === 'offer_letter'        ? 'selected' : '' }}>Offer Letter Phase</option>
                                <option value="visa"                {{ $student->status === 'visa'                ? 'selected' : '' }}>Visa Phase</option>
                                <option value="completed"           {{ $student->status === 'completed'           ? 'selected' : '' }}>Completed / Enrolled</option>
                            </select>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 text-xs text-amber-700">
                            <i class="fa-solid fa-envelope mr-1"></i>
                            Agent will be <strong>notified via email</strong> automatically on status change.
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-primary hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-sync"></i> Update Phase & Notify Agent
                        </button>
                    </form>
                </div>

            </div>

            {{-- ── OFFER LETTER UPLOAD BY ADMIN ── --}}
            <div class="mt-6 bg-purple-50/60 border border-purple-200/80 rounded-2xl p-5 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-purple-100">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-file-signature text-purple-600 text-base"></i>
                        <span>Upload Official Offer Letter (Admin Only)</span>
                    </h3>
                    <span class="text-[10px] font-bold uppercase bg-purple-100 text-purple-800 px-2.5 py-0.5 rounded-full">
                        Admin Managed Upload
                    </span>
                </div>

                @php
                    $offerDoc = $student->documents->firstWhere('document_type', 'offer_letter');
                @endphp

                @if($offerDoc)
                <div class="p-4 bg-white rounded-xl border border-purple-200 flex items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-file-pdf text-lg"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 inline-block mb-1">
                                ✓ Official Offer Letter Uploaded
                            </span>
                            <div class="font-bold text-slate-900 text-sm">{{ $offerDoc->document_name }}</div>
                            <div class="text-xs text-slate-500">Uploaded on {{ $offerDoc->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>

                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($offerDoc->file_path) }}" target="_blank"
                       class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-xs transition-colors flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-download"></i> View / Download
                    </a>
                </div>
                @endif

                <form action="{{ route('admin.students.upload-offer-letter', $student) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            {{ $offerDoc ? 'Replace / Re-upload Offer Letter (PDF, DOCX, JPG - Max 10MB)' : 'Select Official Offer Letter File (PDF, DOCX, JPG - Max 10MB)' }}
                        </label>
                        <input type="file" name="offer_letter_file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="w-full text-xs text-slate-500 bg-white border border-purple-200 rounded-xl p-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200">
                    </div>

                    <button type="submit" class="px-5 py-2.5 bg-purple-700 hover:bg-purple-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>{{ $offerDoc ? 'Replace Offer Letter & Notify Agent' : 'Upload Offer Letter & Notify Agent' }}</span>
                    </button>
                </form>
            </div>

            {{-- Document Removal Requests --}}
            @php
                $pendingRemovals = $student->documents->where('removal_request_status', 'requested');
            @endphp
            @if($pendingRemovals->count() > 0)
            <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-700 mb-3">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                    Pending Document Removal Requests ({{ $pendingRemovals->count() }})
                </h4>
                <div class="space-y-2">
                    @foreach($pendingRemovals as $remDoc)
                    <div class="flex items-center justify-between bg-white rounded-xl p-3 border border-amber-100">
                        <div>
                            <span class="font-bold text-slate-800 text-sm">{{ $remDoc->document_type_name }}</span>
                            <div class="text-xs text-slate-500">Reason: {{ $remDoc->removal_request_reason }}</div>
                        </div>
                        <a href="{{ route('admin.document-removals.index') }}" class="text-xs font-bold text-amber-700 hover:underline">Review →</a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>

    {{-- ══════════════════════════════════════
     |  REJECT DOCUMENT MODAL
     |══════════════════════════════════════ --}}
    <div
        x-data="{ open: false, docId: null, docName: '' }"
        @open-reject-modal.window="open = true; docId = $event.detail.id; docName = $event.detail.name"
        x-show="open"
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
        x-cloak>
        <div @click.away="open = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">

            <div class="bg-rose-600 p-5 text-white flex items-center justify-between">
                <h3 class="font-extrabold text-base flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark"></i> Reject Document
                </h3>
                <button @click="open = false" class="text-white/80 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'/admin/students/documents/' + docId + '/reject'" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="p-3 bg-rose-50 border border-rose-100 rounded-xl text-xs text-rose-800">
                    <strong>Rejection Notice:</strong> The agent will receive an <strong>email notification</strong> with your comment, asking them to re-upload the corrected document.
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Document:</label>
                    <input type="text" :value="docName" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-700">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Rejection Reason / Comment for Agent *</label>
                    <textarea name="admin_comment" rows="3" required placeholder="e.g. Document is blurry or expired. Please upload a clear, valid copy..."
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-rose-500 resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-md flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane"></i> Submit Rejection & Notify Agent
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
