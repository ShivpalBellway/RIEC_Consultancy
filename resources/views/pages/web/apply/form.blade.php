@extends('layouts.app')

@section('title', 'Application Details — ' . $program->name)

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative h-[370px] bg-primary overflow-visible">
    <img src="{{ asset('storage/application-banner.png') }}"
        class="absolute inset-0 w-full h-full object-cover object-center opacity-100"
        alt="Application Banner">

    <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/20 to-primary/20"></div>

    <div class="relative z-10 max-w-7xl mx-auto h-full px-6 lg:px-12 flex items-center pb-16">
        <div class="grid lg:grid-cols-2 gap-8 items-center w-full">

            {{-- Left --}}
            <div>
                <span class="inline-flex items-center gap-2 border border-gold/50 text-gold bg-gold/10 rounded-full px-4 py-2 text-[11px] font-bold uppercase tracking-[0.18em]">
                    <i class="fa-solid fa-folder-open"></i>
                    Application Form
                </span>

                <h1 class="text-3xl md:text-[44px] font-extrabold text-white mt-4 leading-[1.1]">
                    Complete Your <span class="text-gold">Application</span>
                </h1>

                <p class="text-white/75 text-sm md:text-[15px] leading-7 mt-4 max-w-lg">
                    Fill your details carefully and upload the required information for
                    <span class="font-bold text-white">{{ $program->name }}</span>.
                </p>
            </div>

            {{-- Right --}}
            <div class="flex lg:justify-end">
                <div class="bg-primary/10 backdrop-blur-lg border border-white/20 rounded-2xl p-5 w-full max-w-[340px] shadow-xl">
                    <p class="text-white/60 text-[11px] font-bold uppercase tracking-[0.18em] mb-2">
                        Selected Program
                    </p>

                    <h3 class="text-white font-extrabold text-lg">
                        {{ $program->name }}
                    </h3>

                    <div class="flex flex-wrap gap-2 mt-4">
                        <span class="bg-white/15 text-white px-3 py-1.5 rounded-full text-[11px] font-semibold">
                            <i class="fa-solid fa-location-dot text-primary mr-1"></i>
                            {{ $program->country }}
                        </span>

                        <span class="bg-primary/20 text-primary px-3 py-1.5 rounded-full text-[11px] font-semibold">
                            {{ $program->program_type_label }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== STEP BAR ===== --}}
<section class="relative z-20 -mt-16 px-6">
    <div class="max-w-5xl mx-auto bg-white rounded-[24px] shadow-xl px-6 py-5 border border-gray-100">
        <div class="flex items-center justify-between">

            <div class="text-center text-emerald-600 text-[11px] font-semibold">
                <span class="mx-auto w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mb-2">
                    <i class="fa-solid fa-check text-xs"></i>
                </span>
                Select Program
            </div>

            <div class="h-px flex-1 border-t border-dashed border-gray-300 mx-6"></div>

            <div class="text-center text-emerald-600 text-[11px] font-semibold">
                <span class="mx-auto w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mb-2">
                    <i class="fa-solid fa-check text-xs"></i>
                </span>
                Check Eligibility
            </div>

            <div class="h-px flex-1 border-t border-dashed border-gray-300 mx-6"></div>

            <div class="text-center text-primary text-[11px] font-semibold">
                <span class="mx-auto w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center mb-2 shadow-lg">
                    3
                </span>
                Application Form
            </div>

        </div>
    </div>
</section>

{{-- ===== FORM AREA ===== --}}
<section class="relative pt-16 pb-14 px-6 bg-[#f5f9ff] overflow-hidden">
    <div class="relative max-w-7xl mx-auto">

        @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-5 py-4 text-sm">
            <b>Congratulations!</b> {{ session('success') }}
        </div>
        @endif

        <form id="applyForm" action="{{ route('apply.form.submit', $program) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="grid lg:grid-cols-[240px_1fr_280px] gap-6 items-start">

                {{-- LEFT DYNAMIC TABS --}}
                <aside class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 lg:sticky lg:top-6">
                    <div class="space-y-5">
                        @foreach($sections as $index => $section)
                        <button type="button"
                            onclick="showSection({{ $index }})"
                            class="section-tab w-full flex items-center gap-3 text-left text-primary font-bold text-[13px] {{ $loop->first ? 'active-tab' : '' }}"
                            data-section="{{ $section->id }}"
                            data-index="{{ $index }}">

                            <span class="tab-icon w-10 h-10 rounded-full flex items-center justify-center {{ $loop->first ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500' }}">
                                <i class="fa-solid {{ $loop->first ? 'fa-user' : 'fa-folder' }}"></i>
                            </span>

                            <span>{{ $section->name }}</span>
                        </button>

                        @if(!$loop->last)
                        <div class="ml-5 h-6 border-l border-dashed border-gray-300"></div>
                        @endif
                        @endforeach
                    </div>
                </aside>

                {{-- CENTER FORM ONLY ACTIVE SECTION --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 min-h-[520px] ">

                    @foreach($sections as $section)
                    <div id="section-{{ $section->id }}"
                        class="section-content {{ !$loop->first ? 'hidden' : '' }}"
                        data-index="{{ $loop->index }}">

                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </div>

                            <div>
                                <h2 class="text-xl font-extrabold text-primary">
                                    {{ $section->name }}
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $section->description ?: 'Please provide your details accurately.' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-5">
                            @foreach($section->fields as $field)
                            @php $key = $field->field_key; @endphp

                            <div class="{{ in_array($field->field_type, ['textarea', 'checkbox', 'file']) ? 'md:col-span-2' : '' }}">
                                <label class="block text-[11px] font-bold text-primary uppercase tracking-[0.15em] mb-2">
                                    {{ $field->label }}
                                    @if($field->is_required)
                                    <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @if($field->field_type === 'select')
                                <select name="{{ $key }}" {{ $field->is_required ? 'required' : '' }}
                                    class="w-full px-3.5 py-2.5 border @error($key) border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white">
                                    <option value="">Select option...</option>
                                    @foreach($field->options as $opt)
                                    <option value="{{ $opt }}" {{ old($key) == $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                    @endforeach
                                </select>

                                @elseif($field->field_type === 'textarea')
                                <textarea name="{{ $key }}" rows="3" placeholder="{{ $field->placeholder }}" {{ $field->is_required ? 'required' : '' }}
                                    class="w-full px-3.5 py-2.5 border @error($key) border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none">{{ old($key) }}</textarea>

                                @elseif($field->field_type === 'checkbox')
                                <label class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200">
                                    <input type="checkbox" name="{{ $key }}" value="1" {{ old($key) ? 'checked' : '' }} {{ $field->is_required ? 'required' : '' }}
                                        class="w-4 h-4 mt-0.5 rounded text-primary">
                                    <span class="text-sm text-gray-600">
                                        {{ $field->placeholder ?: 'I confirm that the information provided above is accurate.' }}
                                    </span>
                                </label>

                                @elseif($field->field_type === 'file')
                                <input type="file" name="{{ $key }}" {{ $field->is_required ? 'required' : '' }}
                                    class="w-full text-sm border border-gray-200 rounded-xl p-2.5 file:bg-primary file:text-white file:border-0 file:px-3 file:py-2 file:rounded-lg">

                                @elseif($field->field_type === 'phone')
                                <input type="tel" name="{{ $key }}" value="{{ old($key) }}" placeholder="{{ $field->placeholder }}"
                                    {{ $field->is_required ? 'required' : '' }}
                                    class="w-full px-3.5 py-2.5 border @error($key) border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">

                                @elseif($field->field_type === 'email')
                                <input type="email" name="{{ $key }}" value="{{ old($key) }}" placeholder="{{ $field->placeholder }}"
                                    {{ $field->is_required ? 'required' : '' }}
                                    class="w-full px-3.5 py-2.5 border @error($key) border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">

                                @elseif($field->field_type === 'date')
                                <input type="date" name="{{ $key }}" value="{{ old($key) }}"
                                    {{ $field->is_required ? 'required' : '' }}
                                    class="w-full px-3.5 py-2.5 border @error($key) border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">

                                @elseif($field->field_type === 'number')
                                <input type="number" step="any" name="{{ $key }}" value="{{ old($key) }}" placeholder="{{ $field->placeholder }}"
                                    {{ $field->is_required ? 'required' : '' }}
                                    class="w-full px-3.5 py-2.5 border @error($key) border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">

                                @else
                                <input type="text" name="{{ $key }}" value="{{ old($key) }}" placeholder="{{ $field->placeholder }}"
                                    {{ $field->is_required ? 'required' : '' }}
                                    class="w-full px-3.5 py-2.5 border @error($key) border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                @endif

                                @error($key)
                                <p class="text-xs text-red-600 font-semibold mt-2">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row justify-between gap-4">
                            <div>
                                @if($loop->first)
                                <a href="{{ route('apply.eligibility', $program) }}"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-primary font-bold text-sm hover:bg-gray-50">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Edit Eligibility
                                </a>
                                @else
                                <button type="button"
                                    onclick="showSection({{ $loop->index - 1 }})"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-primary font-bold text-sm hover:bg-gray-50">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Previous
                                </button>
                                @endif
                            </div>

                            <div>
                                @if($loop->last)
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-3 px-7 py-3 rounded-xl bg-primary text-white font-bold text-sm hover:bg-gold shadow-lg transition duration-200">
                                    Submit Application
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                                @else
                                <button type="button"
                                    onclick="showSection({{ $loop->index + 1 }})"
                                    class="inline-flex items-center justify-center gap-3 px-7 py-3 rounded-xl bg-primary text-white font-bold text-sm hover:bg-gold shadow-lg transition duration-200">
                                    Next
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                                @endif
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>

                {{-- RIGHT SIDEBAR --}}
                <aside class="space-y-5 lg:sticky lg:top-6">
                    <div class="bg-primary rounded-2xl p-5 text-white shadow-xl">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="font-extrabold text-lg">Application Summary</h3>
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fa-solid fa-clipboard"></i>
                            </div>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-white/10 pb-3">
                                <span>Program</span>
                                <b class="text-white text-right">{{ $program->name }}</b>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-3">
                                <span>Country</span>
                                <b>{{ $program->country }}</b>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-3">
                                <span>Sections</span>
                                <b>{{ $sections->count() }}</b>
                            </div>
                            <div class="flex justify-between">
                                <span>Status</span>
                                <b class="bg-white text-emerald-600 px-3 py-1 rounded-full text-xs">Eligible</b>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <h3 class="text-primary font-extrabold text-xl mb-3">Need Assistance?</h3>
                        <p class="text-gray-500 text-sm leading-6 mb-5">
                            Our team is here to help you complete your application.
                        </p>

                        <a href="#"
                            class="w-full inline-flex justify-center items-center gap-2 border border-primary text-primary rounded-xl px-5 py-3 font-bold text-sm hover:bg-primary hover:text-white transition duration-200">
                            Contact Support
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </aside>

            </div>
        </form>
    </div>
</section>

{{-- Custom Validation Modal --}}
<div id="validationModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-primary/45 backdrop-blur-sm transition-opacity duration-300"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 p-6 md:p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="validationModalContent">
        <div class="flex flex-col items-center text-center">
            <!-- Warning Icon -->
            <div class="w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-2xl mb-4 animate-bounce">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>

            <h3 class="text-xl font-extrabold text-primary mb-2">Required Fields Missing</h3>
            <p class="text-sm text-gray-500 leading-relaxed mb-6">
                Please fill all the required or star-marked fields first before submitting the application.
            </p>

            <button type="button" onclick="closeValidationModal()"
                class="w-full bg-primary text-white text-sm font-bold py-3.5 rounded-2xl hover:bg-gold shadow-lg shadow-primary/20 transition duration-200">
                Okay, I'll check
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ============================================
    // SECTION NAVIGATION
    // ============================================
    function showSection(index) {
        var tabs = Array.from(document.querySelectorAll('.section-tab'));
        var btn = tabs[index];

        if (!btn) {
            return;
        }

        var sectionId = btn.dataset.section;

        document.querySelectorAll('.section-content').forEach(function(section) {
            section.classList.add('hidden');
        });

        document.getElementById('section-' + sectionId).classList.remove('hidden');

        tabs.forEach(function(tab) {
            tab.classList.remove('active-tab');
            tab.querySelector('.tab-icon').classList.remove('bg-primary', 'text-white');
            tab.querySelector('.tab-icon').classList.add('bg-gray-100', 'text-gray-500');
        });

        btn.classList.add('active-tab');
        btn.querySelector('.tab-icon').classList.remove('bg-gray-100', 'text-gray-500');
        btn.querySelector('.tab-icon').classList.add('bg-primary', 'text-white');
    }

    // ============================================
    // MODAL FUNCTIONS
    // ============================================
    function openValidationModal() {
        const modal = document.getElementById('validationModal');
        const content = document.getElementById('validationModalContent');

        modal.classList.remove('hidden');
        modal.offsetHeight;

        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }

    function closeValidationModal() {
        const modal = document.getElementById('validationModal');
        const content = document.getElementById('validationModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // ============================================
    // VALIDATION ERROR HELPERS
    // ============================================
    function showError(input, message) {
        input.classList.remove('border-gray-200');
        input.classList.add('border-red-400');

        const container = input.closest('div');
        if (container) {
            const existingMsgs = container.querySelectorAll('.js-error-message, .text-red-600');
            existingMsgs.forEach(msg => msg.remove());

            const errorMsg = document.createElement('p');
            errorMsg.className = 'text-xs text-red-600 font-semibold mt-2 js-error-message';
            errorMsg.textContent = message;
            container.appendChild(errorMsg);
        }
    }

    function clearError(input) {
        input.classList.remove('border-red-400');
        input.classList.add('border-gray-200');

        const container = input.closest('div');
        if (container) {
            const errorMsgs = container.querySelectorAll('.js-error-message, .text-red-600');
            errorMsgs.forEach(msg => msg.remove());
        }
    }

    // ============================================
    // CONSENT FUNCTIONALITY - FIXED VERSION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('applyForm');
        if (!form) return;

        // Consent functionality removed since consents are moved to student registration.

        // ---- Clear error on interaction ----
        form.addEventListener('input', function(e) {
            var target = e.target;
            if (target.hasAttribute('required')) {
                if (target.type === 'checkbox' && target.checked) {
                    clearError(target);
                } else if (target.value.trim() !== '') {
                    clearError(target);
                }
            }
        });

        form.addEventListener('change', function(e) {
            var target = e.target;
            if (target.hasAttribute('required')) {
                if (target.type === 'file' && target.files.length > 0) {
                    clearError(target);
                } else if (target.type === 'checkbox' && target.checked) {
                    clearError(target);
                } else if (target.type !== 'checkbox' && target.value.trim() !== '') {
                    clearError(target);
                }
            }
        });

        // ---- FORM SUBMIT VALIDATION ----
        form.addEventListener('submit', function(e) {
            var isValid = true;
            var firstInvalidField = null;
            var firstInvalidSectionIndex = null;

            // 1. Check all required fields
            var requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(function(field) {
                var fieldValid = true;
                var errorMsg = 'This field is required.';

                if (field.type === 'checkbox') {
                    if (!field.checked) {
                        fieldValid = false;
                        errorMsg = 'You must agree/confirm this field.';
                    }
                } else if (field.type === 'file') {
                    if (field.files.length === 0) {
                        fieldValid = false;
                        errorMsg = 'Please upload the required file.';
                    }
                } else {
                    if (field.value.trim() === '') {
                        fieldValid = false;
                        errorMsg = 'Please fill out this field.';
                    }
                }

                if (!fieldValid) {
                    isValid = false;
                    showError(field, errorMsg);

                    if (!firstInvalidField) {
                        firstInvalidField = field;
                        var sectionDiv = field.closest('.section-content');
                        if (sectionDiv) {
                            firstInvalidSectionIndex = parseInt(sectionDiv.dataset.index);
                        }
                    }
                } else {
                    clearError(field);
                }
            });

            // Consent validation removed since consents are moved to student registration.

            if (!isValid) {
                e.preventDefault();
                openValidationModal();

                if (firstInvalidSectionIndex !== null) {
                    showSection(firstInvalidSectionIndex);
                }

                if (firstInvalidField) {
                    setTimeout(function() {
                        firstInvalidField.focus();
                        if (firstInvalidField.type === 'checkbox') {
                            firstInvalidField.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    }, 400);
                }
            }
        });
    });
</script>
@endpush

@endsection
