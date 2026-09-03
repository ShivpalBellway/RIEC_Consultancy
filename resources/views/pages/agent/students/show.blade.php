@extends('layouts.agent')

@section('title', 'Student – ' . $student->full_name)
@section('page_title', 'Student Application Details')

@section('content')
@php
    // Build uploaded docs map keyed by document_type
    $uploadedDocsMap = [];
    foreach ($documents as $d) {
        $uploadedDocsMap[$d->document_type] = [
            'id'          => $d->id,
            'type'        => $d->document_type,
            'type_name'   => $d->document_type_name,
            'name'        => $d->document_name,
            'url'         => \Illuminate\Support\Facades\Storage::disk('public')->url($d->file_path),
            'uploaded_at' => $d->created_at->format('d M Y, h:i A'),
            'status'      => $d->status,            // pending / verified / rejected
            'admin_comment' => $d->admin_comment,
            'removal_request_status' => $d->removal_request_status,
        ];
    }

    $mandatoryKeys            = array_keys($mandatoryTypes);
    $uploadedMandatoryCount   = count(array_filter($mandatoryKeys, fn ($key) => isset($uploadedDocsMap[$key]) && $uploadedDocsMap[$key]['status'] !== 'rejected'));
    $totalMandatoryCount      = count($mandatoryKeys);

    // Phase definitions (matches admin status values)
    $phases = [
        'submitted'           => ['label' => 'Submitted',           'icon' => 'fa-paper-plane',      'desc' => 'Application has been submitted by agent.'],
        'under_review'        => ['label' => 'Under Review',        'icon' => 'fa-magnifying-glass',  'desc' => 'REIAC Admin team is reviewing the application and documents.'],
        'university_assigned' => ['label' => 'University Assigned', 'icon' => 'fa-building-columns',  'desc' => 'A university has been assigned to the student.'],
        'offer_letter'        => ['label' => 'Offer Letter',        'icon' => 'fa-file-signature',    'desc' => 'Student has received an offer letter from the university.'],
        'visa'                => ['label' => 'Visa Phase',          'icon' => 'fa-passport',          'desc' => 'Student is in the visa application phase.'],
        'completed'           => ['label' => 'Completed',           'icon' => 'fa-circle-check',      'desc' => 'Student enrollment is complete. Welcome to Korea!'],
    ];
    $phaseKeys  = array_keys($phases);
    $currentIdx = array_search($student->status, $phaseKeys);
    if ($currentIdx === false) $currentIdx = 0;
@endphp

<div x-data="{
    /* ── Active tab tracks current phase ── */
    activeTab: '{{ $student->status }}',

    /* ── Upload modal ── */
    uploadModalOpen: false,

    /* ── Removal modal ── */
    removalModalOpen: false,
    selectedDocId: null,
    selectedDocName: '',
    phaseAlert: '',

    showPhaseAlert(message) {
        this.phaseAlert = message;
        setTimeout(() => { this.phaseAlert = ''; }, 5000);
    },

    /* ── Docs state (reactive) ── */
    uploadedDocs: {{ json_encode($uploadedDocsMap) }},
    uploadingType: null,
    batchUploading: false,
    submittingReview: false,
    uploadSuccessMsg: '',
    uploadErrorMsg: '',
    uploadedCount: {{ $uploadedMandatoryCount }},
    totalCount: {{ $totalMandatoryCount }},

    get percentage() {
        return Math.round((this.uploadedCount / Math.max(this.totalCount, 1)) * 100);
    },

    async uploadRowFile(type, fileInput) {
        if (!fileInput.files || !fileInput.files[0]) {
            this.uploadErrorMsg = 'Please choose a file to upload first.';
            return;
        }
        this.uploadingType   = type;
        this.uploadSuccessMsg = '';
        this.uploadErrorMsg = '';
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('document_type', type);
        formData.append('file', fileInput.files[0]);
        try {
            const res  = await fetch('{{ route('agent.documents.upload', $student) }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                this.uploadedDocs[type]  = data.document;
                this.uploadedCount       = data.uploaded_count;
                this.totalCount          = data.total_count;
                this.uploadSuccessMsg    = data.message;
                setTimeout(() => { this.uploadSuccessMsg = ''; }, 4000);
            } else {
                this.uploadErrorMsg = data.message || 'Upload failed.';
            }
        } catch (e) {
            console.error(e);
            this.uploadErrorMsg = 'Upload failed. Please try again.';
        } finally {
            this.uploadingType = null;
        }
    }
    ,async uploadSelectedFiles() {
        const formData = new FormData();
        let selectedCount = 0;
        document.querySelectorAll('[data-batch-file]').forEach((input) => {
            if (input.files && input.files[0]) {
                formData.append('files[' + input.dataset.batchFile + ']', input.files[0]);
                selectedCount++;
            }
        });

        if (!selectedCount) {
            this.uploadErrorMsg = 'Please choose at least one document first.';
            return;
        }

        this.batchUploading = true;
        this.uploadErrorMsg = '';
        this.uploadSuccessMsg = '';
        formData.append('_token', '{{ csrf_token() }}');
        try {
            const res = await fetch('{{ route('agent.documents.upload-batch', $student) }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                this.uploadSuccessMsg = data.message;
                setTimeout(() => window.location.reload(), 700);
            } else {
                this.uploadErrorMsg = data.message || 'Upload failed.';
            }
        } catch (e) {
            console.error(e);
            this.uploadErrorMsg = 'Upload failed. Please try again.';
        } finally {
            this.batchUploading = false;
        }
    }
}" class="space-y-6">

    <div x-show="phaseAlert" x-transition x-cloak
         class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-sm font-semibold shadow-sm"
         role="status">
        <i class="fa-solid fa-lock text-amber-600 mt-0.5"></i>
        <span x-text="phaseAlert"></span>
        <button type="button" @click="phaseAlert = ''" class="ml-auto text-amber-600 hover:text-amber-800" aria-label="Dismiss notification">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════
     |  PAGE HEADER: Student info + Action Buttons
     |══════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-600 font-extrabold text-xl">
                {{ strtoupper(substr($student->first_name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-gray-900 leading-tight">{{ $student->full_name }}</h2>
                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 mt-0.5">
                    <span><i class="fa-solid fa-envelope text-gray-400 mr-1"></i>{{ $student->email }}</span>
                    @if($student->phone)<span>• <i class="fa-solid fa-phone text-gray-400 mr-1"></i>{{ $student->phone }}</span>@endif
                    @if($student->passport_number)<span>• <i class="fa-solid fa-passport text-gray-400 mr-1"></i>{{ $student->passport_number }}</span>@endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('agent.students.edit', $student) }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
            <button @click="uploadModalOpen = true"
                    class="px-4 py-2 bg-gold hover:bg-amber-500 text-slate-900 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Documents
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
     |  APPLICATION TIMELINE — CLICKABLE TABS
     |══════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">

        {{-- Tab buttons row --}}
        <div class="flex overflow-x-auto border-b border-gray-100 bg-gray-50/60">
            @foreach($phases as $phaseKey => $phaseInfo)
            @php
                $pIdx          = array_search($phaseKey, $phaseKeys);
                $maxAllowedIdx = max($currentIdx, 1);
                $isLocked      = $pIdx > $maxAllowedIdx;
                $isDone        = $pIdx < $currentIdx;
                $isCurr        = $phaseKey === $student->status;
            @endphp
            <button
                @if(!$isLocked)
                    @click="activeTab = '{{ $phaseKey }}'"
                @else
                    @click="showPhaseAlert('Admin has not progressed the application to {{ $phaseInfo['label'] }} phase yet.')"
                @endif
                :class="activeTab === '{{ $phaseKey }}'
                    ? '{{ $isCurr ? 'border-b-2 border-gold text-primary-700 bg-white font-extrabold shadow-sm' : 'border-b-2 border-primary-400 text-primary-700 bg-white font-bold' }}'
                    : '{{ $isLocked ? 'opacity-40 cursor-not-allowed text-gray-400 bg-gray-100/50' : 'text-gray-600 hover:text-gray-900 hover:bg-white/80' }}'"
                class="px-4 py-3.5 flex items-center gap-2 text-xs whitespace-nowrap transition-all shrink-0 relative">

                {{-- Step circle --}}
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0
                    @if($isCurr) bg-gold text-slate-900 shadow-sm
                    @elseif($isDone) bg-emerald-500 text-white
                    @elseif($isLocked) bg-gray-300 text-gray-500
                    @else bg-gray-200 text-gray-700 @endif">
                    @if($isDone) <i class="fa-solid fa-check"></i>
                    @elseif($isLocked) <i class="fa-solid fa-lock text-[9px]"></i>
                    @else {{ $loop->iteration }}
                    @endif
                </span>

                <span class="@if($isLocked) text-gray-400 font-medium @endif">{{ $phaseInfo['label'] }}</span>

                {{-- Badges --}}
                @if($isCurr)
                <span class="ml-1 px-1.5 py-0.5 bg-gold text-slate-900 text-[9px] font-extrabold rounded uppercase tracking-wide shadow-sm">Current</span>
                @elseif($isLocked)
                <i class="fa-solid fa-lock text-gray-400 text-[10px] ml-0.5" title="Locked by Admin"></i>
                @endif
            </button>
            @if(!$loop->last)
            <div class="flex items-center text-gray-200 px-1 shrink-0">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </div>
            @endif
            @endforeach
        </div>

        {{-- Tab Content Panels --}}

        {{-- ── TAB: SUBMITTED ── --}}
        <div x-show="activeTab === 'submitted'" class="p-6 space-y-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-gray-900">Phase 1 — Application Submitted</h3>
                    <p class="text-xs text-gray-500">{{ $phases['submitted']['desc'] }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach([
                    ['label'=>'Full Name',     'value'=>$student->full_name],
                    ['label'=>'Email',         'value'=>$student->email],
                    ['label'=>'Phone',         'value'=>$student->phone ?? 'N/A'],
                    ['label'=>'Date of Birth', 'value'=>$student->date_of_birth?->format('d M Y') ?? 'N/A'],
                    ['label'=>'Nationality',   'value'=>$student->nationality ?? 'N/A'],
                    ['label'=>'Passport No.',  'value'=>$student->passport_number ?? 'N/A'],
                ] as $field)
                <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[11px] text-gray-400 font-semibold block">{{ $field['label'] }}</span>
                    <span class="font-bold text-gray-800 text-sm block mt-0.5">{{ $field['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── TAB: UNDER REVIEW ── --}}
        <div x-show="activeTab === 'under_review'" class="p-6 space-y-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-gray-900">Phase 2 — Under Review</h3>
                    <p class="text-xs text-gray-500">{{ $phases['under_review']['desc'] }}</p>
                </div>
            </div>

            {{-- Document upload progress --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="text-xs font-bold text-gray-700">Document Upload Progress</div>
                    <div class="text-xs text-gray-500 mt-0.5"><span x-text="uploadedCount"></span> of <span x-text="totalCount"></span> mandatory documents uploaded</div>
                </div>
                <div class="w-full sm:w-48">
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gold h-3 rounded-full transition-all duration-500" :style="'width: ' + percentage + '%'"></div>
                    </div>
                    <div class="text-[10px] font-bold text-gray-600 text-right mt-1"><span x-text="percentage"></span>% Complete</div>
                </div>
            </div>

            {{-- Per-doc status list --}}
            <div class="space-y-2">
                @foreach($mandatoryTypes as $typeKey => $typeName)
                <div class="p-3.5 rounded-xl border flex flex-col sm:flex-row sm:items-center justify-between gap-2"
                    :class="uploadedDocs['{{ $typeKey }}']
                        ? (uploadedDocs['{{ $typeKey }}'].status === 'verified' ? 'bg-emerald-50 border-emerald-200' : (uploadedDocs['{{ $typeKey }}'].status === 'rejected' ? 'bg-rose-50 border-rose-200' : 'bg-blue-50 border-blue-200'))
                        : 'bg-gray-50 border-gray-200'">
                    <div class="flex items-center gap-2.5">
                        {{-- Status icon --}}
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0"
                            :class="uploadedDocs['{{ $typeKey }}']
                                ? (uploadedDocs['{{ $typeKey }}'].status === 'verified' ? 'bg-emerald-500 text-white' : (uploadedDocs['{{ $typeKey }}'].status === 'rejected' ? 'bg-rose-500 text-white' : 'bg-blue-400 text-white'))
                                : 'bg-gray-200 text-gray-400'">
                            <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'verified'">
                                <i class="fa-solid fa-check"></i>
                            </template>
                            <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'rejected'">
                                <i class="fa-solid fa-xmark"></i>
                            </template>
                            <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'pending'">
                                <i class="fa-solid fa-clock"></i>
                            </template>
                            <template x-if="!uploadedDocs['{{ $typeKey }}']">
                                <i class="fa-solid fa-minus"></i>
                            </template>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-900 text-sm">{{ $typeName }}</span>
                            <template x-if="uploadedDocs['{{ $typeKey }}']">
                                <div class="text-xs mt-0.5">
                                    <span x-text="uploadedDocs['{{ $typeKey }}'].status === 'verified' ? '✅ Verified by Admin' : (uploadedDocs['{{ $typeKey }}'].status === 'rejected' ? '❌ Rejected — ' + (uploadedDocs['{{ $typeKey }}'].admin_comment || '') : '⏳ Pending Review')"></span>
                                </div>
                            </template>
                            <template x-if="!uploadedDocs['{{ $typeKey }}']">
                                <div class="text-xs text-gray-400 mt-0.5">Not uploaded yet</div>
                            </template>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 shrink-0">
                        {{-- If NOT uploaded → Show upload button --}}
                        <template x-if="!uploadedDocs['{{ $typeKey }}']">
                            <button @click="uploadModalOpen = true" class="px-3 py-1.5 bg-gold hover:bg-amber-500 text-slate-900 text-xs font-bold rounded-lg transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Upload
                            </button>
                        </template>

                        {{-- If uploaded & verified → No removal --}}
                        <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'verified'">
                            <span class="px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> Verified
                            </span>
                        </template>

                        {{-- If uploaded & rejected → Show Re-upload button --}}
                        <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'rejected'">
                            <button @click="uploadModalOpen = true" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5">
                                <i class="fa-solid fa-rotate"></i> Re-upload
                            </button>
                        </template>

                        {{-- If uploaded & pending → Show pending badge only --}}
                        <template x-if="uploadedDocs['{{ $typeKey }}'] && ['pending', 'uploaded'].includes(uploadedDocs['{{ $typeKey }}'].status)">
                            <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Pending Review</span>
                        </template>
                    </div>
                </div>
                @endforeach
            </div>

            <button @click="uploadModalOpen = true"
                    class="w-full py-3 border-2 border-dashed border-gold/50 hover:border-gold hover:bg-gold/5 rounded-xl text-xs font-bold text-gold transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up"></i> Open Document Upload Checklist Modal
            </button>
        </div>

        {{-- ── TAB: UNIVERSITY ASSIGNED ── --}}
        <div x-show="activeTab === 'university_assigned'" class="p-6 space-y-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-building-columns text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-gray-900">Phase 3 — University Assigned</h3>
                    <p class="text-xs text-gray-500">{{ $phases['university_assigned']['desc'] }}</p>
                </div>
            </div>

            @if($student->university_name)
            <div class="p-6 bg-indigo-50 border border-indigo-200 rounded-2xl text-center">
                <i class="fa-solid fa-university text-4xl text-indigo-600 mb-3 block"></i>
                <div class="font-extrabold text-indigo-900 text-xl">{{ $student->university_name }}</div>
                <span class="mt-2 inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold">
                    ✓ Officially Assigned by REIAC Global Admin
                </span>
            </div>
            @else
            <div class="p-8 bg-gray-50 border border-gray-200 rounded-2xl text-center text-gray-400">
                <i class="fa-solid fa-hourglass-half text-3xl text-gray-300 mb-2 block"></i>
                <p class="font-bold text-gray-600 text-sm">Pending University Assignment</p>
                <p class="text-xs mt-1 text-gray-400">REIAC Admin will assign the university after reviewing your submitted documents.</p>
            </div>
            @endif

            {{-- Korean Address --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h4 class="font-bold text-gray-900 text-sm mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-house-flag text-rose-500"></i> Korean Address
                </h4>
                @if($student->korean_address)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div><span class="text-gray-400 block font-medium">Address:</span><span class="font-bold text-gray-800">{{ $student->korean_address }}</span></div>
                    <div><span class="text-gray-400 block font-medium">City:</span><span class="font-bold text-gray-800">{{ $student->korean_city ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-400 block font-medium">Postal:</span><span class="font-mono font-bold text-gray-800">{{ $student->korean_postal_code ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-400 block font-medium">Korean Phone:</span><span class="font-bold text-gray-800">{{ $student->korean_contact_number ?? 'N/A' }}</span></div>
                </div>
                @else
                <div class="text-center py-4 text-amber-600 text-xs font-semibold">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                    Korean address not added.
                    <a href="{{ route('agent.students.edit', $student) }}" class="font-bold hover:underline text-primary-600 ml-1">Add Now →</a>
                </div>
                @endif
            </div>
        </div>

        {{-- ── TAB: OFFER LETTER ── --}}
        <div x-show="activeTab === 'offer_letter'" class="p-6 space-y-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-file-signature text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-gray-900">Phase 4 — Offer Letter</h3>
                    <p class="text-xs text-gray-500">{{ $phases['offer_letter']['desc'] }}</p>
                </div>
            </div>

            @php $offerDoc = $uploadedDocsMap['offer_letter'] ?? null; @endphp
            @if($offerDoc)
            <div class="p-5 bg-purple-50 border border-purple-200 rounded-2xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-file-pdf text-3xl text-purple-600"></i>
                    <div>
                        <div class="font-bold text-purple-950 text-base">Official Offer Letter Issued</div>
                        <div class="text-xs text-purple-700 font-medium mt-0.5">{{ $offerDoc['name'] }} • Uploaded on {{ $offerDoc['uploaded_at'] }}</div>
                        <span class="inline-block mt-1 px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-extrabold">
                            ✓ Uploaded & Verified by REIAC Admin
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ $offerDoc['url'] }}" target="_blank"
                       class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                        <i class="fa-solid fa-download"></i> View & Download Offer Letter
                    </a>
                </div>
            </div>
            @else
            <div class="p-8 bg-gray-50 border border-gray-200 rounded-2xl text-center">
                <i class="fa-solid fa-hourglass-half text-3xl text-gray-300 mb-2 block"></i>
                <p class="font-bold text-gray-700 text-sm">Official Offer Letter Pending Admin Upload</p>
                <p class="text-xs text-gray-400 mt-1">REIAC Admin will upload the official offer letter here once the university issues it.</p>
                <!-- 
                Agent upload is disabled as Admin uploads the official Offer Letter.
                <button @click="uploadModalOpen = true" class="mt-3 px-4 py-2 bg-gold text-slate-900 font-bold text-xs rounded-xl">Upload Offer Letter</button> 
                -->
            </div>
            @endif
        </div>

        {{-- ── TAB: VISA PHASE ── --}}
        <div x-show="activeTab === 'visa'" class="p-6 space-y-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-2xl bg-cyan-100 text-cyan-600 flex items-center justify-center">
                    <i class="fa-solid fa-passport text-lg"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-gray-900">Phase 5 — Visa Phase</h3>
                    <p class="text-xs text-gray-500">{{ $phases['visa']['desc'] }}</p>
                </div>
            </div>

            <div class="p-4 bg-cyan-50 border border-cyan-200 rounded-xl text-xs text-cyan-800 font-semibold flex items-start gap-2">
                <i class="fa-solid fa-circle-info text-cyan-500 mt-0.5"></i>
                <span>Student is currently in the Visa Application phase. Ensure all mandatory documents are verified before visa interview. Contact REIAC Global admin for visa-specific guidance.</span>
            </div>

            {{-- Document checklist summary --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Mandatory Document Status for Visa</h4>
                <div class="space-y-2">
                    @foreach($mandatoryTypes as $typeKey => $typeName)
                    <div class="p-3 rounded-xl border flex items-center justify-between"
                        :class="uploadedDocs['{{ $typeKey }}']
                            ? (uploadedDocs['{{ $typeKey }}'].status === 'verified' ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200')
                            : 'bg-rose-50 border-rose-200'">
                        <div class="flex items-center gap-2">
                            <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'verified'">
                                <i class="fa-solid fa-check text-emerald-600 w-4"></i>
                            </template>
                            <template x-if="!uploadedDocs['{{ $typeKey }}'] || uploadedDocs['{{ $typeKey }}'].status !== 'verified'">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 w-4"></i>
                            </template>
                            <span class="text-xs font-semibold text-gray-800">{{ $typeName }}</span>
                        </div>
                        <span class="text-[11px] font-bold"
                            :class="uploadedDocs['{{ $typeKey }}']
                                ? (uploadedDocs['{{ $typeKey }}'].status === 'verified' ? 'text-emerald-700' : 'text-amber-700')
                                : 'text-rose-700'"
                            x-text="uploadedDocs['{{ $typeKey }}']
                                ? (uploadedDocs['{{ $typeKey }}'].status === 'verified' ? '✅ Verified' : '⏳ Pending Verification')
                                : '❌ Not Uploaded'">
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── TAB: COMPLETED ── --}}
        <div x-show="activeTab === 'completed'" class="p-6">
            <div class="text-center py-10 space-y-4">
                <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-500 flex items-center justify-center text-4xl mx-auto">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-2xl">🎉 Enrollment Complete!</h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto">{{ $student->full_name }} has successfully completed all phases and is now enrolled.</p>
                @if($student->university_name)
                <div class="inline-block px-6 py-3 bg-indigo-50 border border-indigo-200 rounded-2xl">
                    <div class="text-xs text-indigo-500 font-semibold">Enrolled At</div>
                    <div class="font-extrabold text-indigo-900 text-lg">{{ $student->university_name }}</div>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════
     |  ALL DOCUMENTS TABLE (always visible below)
     |══════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-extrabold text-gray-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-folder-open text-primary-600"></i> All Uploaded Documents
                </h3>
                <p class="text-xs text-gray-500">Full document history with status and removal requests</p>
            </div>
            <button @click="uploadModalOpen = true"
                    class="inline-flex items-center gap-2 bg-gold hover:bg-amber-500 text-slate-900 font-bold px-4 py-2 rounded-xl text-xs shadow-sm transition-all">
                <i class="fa-solid fa-plus"></i> Upload Document
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500 border-b border-gray-200/80">
                    <tr>
                        <th class="py-3.5 px-5">Document</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Uploaded</th>
                        <th class="py-3.5 px-5">University</th>
                        <th class="py-3.5 px-5 text-right">Remove Request</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm">{{ $doc->document_type_name }}</div>
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($doc->file_path) }}" target="_blank"
                                       class="text-xs text-primary-600 hover:underline flex items-center gap-1 mt-0.5">
                                        <i class="fa-solid fa-download text-[10px]"></i> {{ $doc->document_name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                @if($doc->status === 'verified') bg-emerald-100 text-emerald-800
                                @elseif($doc->status === 'rejected') bg-rose-100 text-rose-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($doc->status) }}
                            </span>
                            @if($doc->admin_comment)
                            <div class="text-[11px] text-rose-600 mt-1 italic">
                                <i class="fa-solid fa-comment-dots mr-1"></i>{{ $doc->admin_comment }}
                            </div>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-xs text-gray-500">{{ $doc->created_at->format('d M Y, h:i A') }}</td>
                        <td class="py-4 px-5">
                            @if($student->university_name)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 font-semibold text-xs">
                                <i class="fa-solid fa-building-columns text-[10px]"></i> {{ $student->university_name }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400 italic">Admin Managed</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-right">
                            @if($doc->status === 'verified')
                            {{-- Verified docs: No removal allowed --}}
                            <span class="text-xs text-gray-400 italic flex items-center justify-end gap-1">
                                <i class="fa-solid fa-lock text-emerald-400"></i> Removal Disabled (Verified)
                            </span>
                            @elseif($doc->removal_request_status === 'requested')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-bold text-xs">
                                <i class="fa-solid fa-clock text-[10px]"></i> Removal Pending
                            </span>
                            @elseif($doc->removal_request_status === 'rejected')
                            <div class="space-y-1 text-right">
                                <span class="text-[11px] text-rose-600 font-bold block">Removal Rejected</span>
                                <button @click="selectedDocId = {{ $doc->id }}; selectedDocName = '{{ $doc->document_type_name }}'; removalModalOpen = true"
                                        class="text-xs text-rose-600 font-bold hover:underline">Re-Request</button>
                            </div>
                            @else
                            <button @click="selectedDocId = {{ $doc->id }}; selectedDocName = '{{ $doc->document_type_name }}'; removalModalOpen = true"
                                    class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1">
                                <i class="fa-solid fa-trash-can-arrow-up"></i> Request Removal
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-file-circle-plus text-3xl text-gray-300 mb-2 block"></i>
                            <p class="font-semibold text-gray-600">No documents uploaded yet.</p>
                            <button @click="uploadModalOpen = true" class="text-xs text-primary-600 font-bold hover:underline mt-1">
                                Click here to upload documents
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
     |  MODAL 1: Mandatory Document Upload Checklist
     |══════════════════════════════════════════════════════ --}}
    <div x-show="uploadModalOpen"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-cloak
         @click.away="if(uploadedCount >= totalCount) { uploadModalOpen = false; }">
         <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-primary-600 to-indigo-900 p-5 text-white flex items-center justify-between shrink-0">
                <div>
                    <h3 class="font-extrabold text-lg flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-gold"></i> Mandatory Document Upload Checklist
                    </h3>
                    <p class="text-xs text-indigo-100 mt-0.5">Student: <strong class="text-white">{{ $student->full_name }}</strong></p>
                </div>
                <button x-show="uploadedCount >= totalCount" @click="uploadModalOpen = false; window.location.reload()"
                        class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 w-8 h-8 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-5 overflow-y-auto flex-1">

                {{-- Toast --}}
                <div x-show="uploadSuccessMsg" x-transition
                     class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span x-text="uploadSuccessMsg"></span>
                </div>
                <div x-show="uploadErrorMsg" x-transition
                     class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span x-text="uploadErrorMsg"></span>
                    <button type="button" @click="uploadErrorMsg = ''" class="ml-auto text-rose-600" aria-label="Dismiss upload error">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Progress --}}
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="text-xs font-bold text-gray-700 uppercase tracking-wider">Overall Progress</div>
                        <div class="text-xs text-gray-500 mt-0.5"><span x-text="uploadedCount"></span> of <span x-text="totalCount"></span> mandatory documents uploaded</div>
                    </div>
                    <div class="w-full sm:w-48">
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-gold h-3 rounded-full transition-all duration-500" :style="'width: ' + percentage + '%'"></div>
                        </div>
                        <div class="text-[10px] font-bold text-gray-600 text-right mt-1"><span x-text="percentage"></span>% Complete</div>
                    </div>
                </div>

                {{-- Per-Row Upload Rows --}}
                <div class="space-y-3">
                    @foreach($mandatoryTypes as $typeKey => $typeName)
                    <div class="p-4 rounded-xl border transition-all"
                        :class="uploadedDocs['{{ $typeKey }}']
                            ? (uploadedDocs['{{ $typeKey }}'].status === 'verified' ? 'bg-emerald-50 border-emerald-200' : (uploadedDocs['{{ $typeKey }}'].status === 'rejected' ? 'bg-rose-50 border-rose-200' : 'bg-blue-50 border-blue-200'))
                            : 'bg-gray-50 border-gray-200/90 hover:border-gray-300'">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">

                            {{-- Left --}}
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                    :class="uploadedDocs['{{ $typeKey }}']
                                        ? (uploadedDocs['{{ $typeKey }}'].status === 'verified' ? 'bg-emerald-500 text-white' : (uploadedDocs['{{ $typeKey }}'].status === 'rejected' ? 'bg-rose-500 text-white' : 'bg-blue-400 text-white'))
                                        : 'bg-gray-200 text-gray-500'">
                                    <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'verified'">
                                        <i class="fa-solid fa-check"></i>
                                    </template>
                                    <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'rejected'">
                                        <i class="fa-solid fa-xmark"></i>
                                    </template>
                                    <template x-if="uploadedDocs['{{ $typeKey }}'] && ['pending', 'uploaded'].includes(uploadedDocs['{{ $typeKey }}'].status)">
                                        <i class="fa-solid fa-clock"></i>
                                    </template>
                                    <template x-if="!uploadedDocs['{{ $typeKey }}']">
                                        <i class="fa-solid fa-asterisk"></i>
                                    </template>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-sm text-gray-900">{{ $typeName }}</span>
                                        <span class="text-[11px] text-rose-500 font-semibold">* Mandatory</span>
                                    </div>
                                    <template x-if="uploadedDocs['{{ $typeKey }}']">
                                        <div class="text-xs mt-0.5">
                                            <a :href="uploadedDocs['{{ $typeKey }}'].url" target="_blank" class="hover:underline font-bold"
                                               :class="uploadedDocs['{{ $typeKey }}'].status === 'verified' ? 'text-emerald-700' : (uploadedDocs['{{ $typeKey }}'].status === 'rejected' ? 'text-rose-700' : 'text-blue-700')"
                                               x-text="uploadedDocs['{{ $typeKey }}'].name"></a>
                                            <span class="text-gray-400 ml-1" x-text="'• ' + uploadedDocs['{{ $typeKey }}'].uploaded_at"></span>
                                        </div>
                                    </template>
                                    <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].admin_comment">
                                        <div class="text-xs text-rose-600 font-medium mt-0.5 flex items-center gap-1">
                                            <i class="fa-solid fa-comment-dots"></i>
                                            <span x-text="'Admin: ' + uploadedDocs['{{ $typeKey }}'].admin_comment"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Right: Upload Controls --}}
                            <div class="flex items-center gap-2 shrink-0">

                                {{-- VERIFIED: locked, no re-upload --}}
                                <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'verified'">
                                    <span class="px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center gap-1">
                                        <i class="fa-solid fa-lock text-emerald-600"></i> Verified & Locked
                                    </span>
                                </template>

                                {{-- REJECTED: force re-upload --}}
                                <template x-if="uploadedDocs['{{ $typeKey }}'] && uploadedDocs['{{ $typeKey }}'].status === 'rejected'">
                                    <div class="flex items-center gap-2">
                                        <input type="file" data-batch-file="{{ $typeKey }}" :id="'file_{{ $typeKey }}'" accept=".pdf,.jpg,.jpeg,.png"
                                               class="text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700">
                                        <button type="button"
                                            @click="uploadRowFile('{{ $typeKey }}', document.getElementById('file_{{ $typeKey }}'))"
                                            :disabled="uploadingType === '{{ $typeKey }}'"
                                            class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs transition-colors flex items-center gap-1">
                                            <template x-if="uploadingType === '{{ $typeKey }}'">
                                                <i class="fa-solid fa-spinner animate-spin"></i>
                                            </template>
                                            <template x-if="uploadingType !== '{{ $typeKey }}'">
                                                <span class="flex items-center gap-1"><i class="fa-solid fa-rotate"></i> Re-upload</span>
                                            </template>
                                        </button>
                                    </div>
                                </template>

                                {{-- PENDING (uploaded but not verified): Locked until admin review --}}
                                <template x-if="uploadedDocs['{{ $typeKey }}'] && ['pending', 'uploaded'].includes(uploadedDocs['{{ $typeKey }}'].status)">
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1.5 rounded-full bg-blue-100 text-blue-800 font-bold text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-clock"></i> Pending Review
                                        </span>
                                    </div>
                                </template>

                                {{-- NOT UPLOADED: Fresh upload --}}
                                <template x-if="!uploadedDocs['{{ $typeKey }}']">
                                    <div class="flex items-center gap-2">
                                        <input type="file" data-batch-file="{{ $typeKey }}" :id="'file_{{ $typeKey }}'" accept=".pdf,.jpg,.jpeg,.png"
                                               class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                        <button type="button"
                                            @click="uploadRowFile('{{ $typeKey }}', document.getElementById('file_{{ $typeKey }}'))"
                                            :disabled="uploadingType === '{{ $typeKey }}'"
                                            class="px-4 py-2 bg-gold hover:bg-amber-500 text-slate-900 font-bold rounded-xl text-xs shadow-sm transition-all flex items-center gap-1.5 shrink-0">
                                            <template x-if="uploadingType === '{{ $typeKey }}'">
                                                <span class="flex items-center gap-1"><i class="fa-solid fa-spinner animate-spin"></i> Uploading...</span>
                                            </template>
                                            <template x-if="uploadingType !== '{{ $typeKey }}'">
                                                <span class="flex items-center gap-1"><i class="fa-solid fa-cloud-arrow-up"></i> Upload</span>
                                            </template>
                                        </button>
                                    </div>
                                </template>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between shrink-0">
                <span class="text-xs text-gray-500 font-medium" x-text="uploadedCount < totalCount ? (totalCount - uploadedCount) + ' mandatory document(s) still missing' : 'All mandatory documents uploaded ✓'"></span>
                <div class="flex items-center gap-3">
                    <button type="button" @click="uploadSelectedFiles()" :disabled="batchUploading"
                            class="px-5 py-2 bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold rounded-xl text-xs shadow-sm flex items-center gap-1.5">
                        <i class="fa-solid fa-layer-group"></i>
                        <span x-text="batchUploading ? 'Uploading...' : 'Upload Selected Documents'"></span>
                    </button>
                    <span x-show="uploadedCount < totalCount" class="text-xs font-semibold text-rose-600">
                        Upload all mandatory documents to continue
                    </span>
                    @if($student->status === 'submitted')
                    <form action="{{ route('agent.documents.submit', $student) }}" method="POST" x-show="uploadedCount >= totalCount">
                        @csrf
                        <button type="submit" :disabled="uploadedCount < totalCount || submittingReview"
                                @click="submittingReview = true"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-xl text-xs shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane"></i> Submit for Admin Review
                        </button>
                    </form>
                    @else
                    <button type="button" @click="uploadModalOpen = false; window.location.reload()"
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-sm flex items-center gap-1.5">
                        <i class="fa-solid fa-check-double"></i> Done
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
     |  MODAL 2: Request Document Removal
     |══════════════════════════════════════════════════════ --}}
    <div x-show="removalModalOpen"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-cloak>
        <div @click.away="removalModalOpen = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="bg-rose-600 p-5 text-white flex items-center justify-between">
                <h3 class="font-extrabold text-base flex items-center gap-2">
                    <i class="fa-solid fa-trash-can-arrow-up"></i> Request Document Removal
                </h3>
                <button @click="removalModalOpen = false" class="text-white/80 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form x-bind:action="'/agent/documents/' + selectedDocId + '/request-removal'" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="p-3 bg-rose-50 border border-rose-100 rounded-xl text-xs text-rose-800">
                    <span class="font-bold">Note:</span> Removal request will be sent to Admin for review and approval.
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Document:</label>
                    <input type="text" x-model="selectedDocName" readonly
                           class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-xl text-xs font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Reason for Removal *</label>
                    <textarea name="reason" rows="3" required placeholder="State why this document needs to be removed..."
                              class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-rose-500 resize-none"></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="removalModalOpen = false"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs">Cancel</button>
                    <button type="submit"
                            class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-md flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane"></i> Submit Removal Request
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
