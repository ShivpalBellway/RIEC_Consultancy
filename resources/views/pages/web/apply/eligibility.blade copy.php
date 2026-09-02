@extends('layouts.app')

@section('title', 'Check Eligibility — ' . $program->name)

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative bg-primary text-white py-12 px-6 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1800&auto=format&fit=crop"
             class="w-full h-full object-cover opacity-20" alt="">
        <div class="absolute inset-0 bg-gradient-to-r from-primary via-primary/95 to-primary/80"></div>
    </div>

    <div class="relative max-w-6xl mx-auto flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <span class="inline-flex items-center gap-2 bg-gold/15 text-gold text-xs font-extrabold uppercase tracking-[0.18em] px-4 py-2 rounded-full border border-gold/30">
                <i class="fa-solid fa-shield-check"></i>
                Eligibility Verification
            </span>

            <h1 class="text-3xl md:text-5xl font-extrabold mt-5 leading-tight">
                Check Your <span class="text-gold">Eligibility</span>
            </h1>

            <p class="text-white/70 text-sm md:text-base mt-4 max-w-2xl leading-7">
                Enter your academic and personal details below. The system will validate your eligibility based on the selected program requirements.
            </p>
        </div>

        <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-5 min-w-[260px]">
            <p class="text-xs text-white/60 uppercase tracking-widest mb-2">Selected Program</p>
            <h3 class="font-extrabold text-lg">{{ $program->name }}</h3>
            <div class="flex flex-wrap gap-2 mt-3">
                <span class="bg-white/10 border border-white/10 px-3 py-1 rounded-full text-xs">
                    <i class="fa-solid fa-location-dot text-gold mr-1"></i>{{ $program->country }}
                </span>
                <span class="bg-gold/20 border border-gold/30 px-3 py-1 rounded-full text-xs text-gold">
                    {{ $program->program_type_label ?? 'Program' }}
                </span>
            </div>
        </div>
    </div>
</section>


{{-- ===== STEP INDICATOR ===== --}}
<div class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-5xl mx-auto px-6 py-5">
        <div class="flex items-center justify-between">

            <div class="flex flex-col items-center gap-2 text-emerald-600 font-bold text-xs sm:text-sm">
                <span class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-check text-xs"></i>
                </span>
                <span class="hidden sm:block">Select Program</span>
            </div>

            <div class="h-px flex-1 bg-emerald-200 mx-3"></div>

            <div class="flex flex-col items-center gap-2 text-primary font-bold text-xs sm:text-sm">
                <span class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-lg ring-4 ring-blue-100">
                    2
                </span>
                <span>Check Eligibility</span>
            </div>

            <div class="h-px flex-1 bg-gray-200 mx-3"></div>

            <div class="flex flex-col items-center gap-2 text-gray-400 font-bold text-xs sm:text-sm">
                <span class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center">
                    3
                </span>
                <span class="hidden sm:block">Application Form</span>
            </div>

        </div>
    </div>
</div>


{{-- ===== CONTENT ===== --}}
<section class="py-12 px-6 bg-[#f5f7fb] min-h-[600px]">
    <div class="max-w-6xl mx-auto">

        <form action="{{ route('apply.eligibility.check', $program) }}" method="POST">
            @csrf

            <div class="grid lg:grid-cols-[1fr_320px] gap-8 items-start">

                {{-- LEFT FORM --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Card Header --}}
                    <div class="bg-gradient-to-r from-primary to-blue-900 text-white p-7 md:p-8 relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-36 h-36 bg-gold/20 rounded-full"></div>
                        <div class="absolute right-10 bottom-6 text-white/10 text-7xl">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>

                        <div class="relative">
                            <h2 class="text-2xl md:text-3xl font-extrabold">
                                Eligibility Details
                            </h2>
                            <p class="text-white/70 text-sm mt-2 max-w-xl leading-6">
                                Fill in the required eligibility criteria for
                                <span class="text-gold font-bold">{{ $program->name }} ({{ $program->country }})</span>.
                            </p>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-800 p-5 rounded-2xl text-sm flex gap-4">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-red-900">Eligibility check failed</h4>
                                    <p class="mt-1 text-xs text-red-700 leading-5">
                                        Please review the criteria below. Some answers did not meet the program requirements.
                                    </p>
                                </div>
                            </div>
                        @endif

                        @foreach($fields as $field)
                            @php $key = $field->field_key; @endphp

                            <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-5">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <label class="block text-sm font-extrabold text-gray-800">
                                        {{ $field->label }}
                                        @if($field->is_required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if($field->is_required)
                                        <span class="shrink-0 text-[10px] bg-red-50 text-red-600 border border-red-100 px-2 py-1 rounded-full font-bold uppercase">
                                            Required
                                        </span>
                                    @else
                                        <span class="shrink-0 text-[10px] bg-gray-100 text-gray-500 border border-gray-200 px-2 py-1 rounded-full font-bold uppercase">
                                            Optional
                                        </span>
                                    @endif
                                </div>

                                {{-- Min Max Hint --}}
                                @if($field->field_type === 'number' && ($field->min_value || $field->max_value))
                                    <div class="mb-3 inline-flex items-center gap-2 text-[11px] text-gray-500 bg-white border border-gray-100 px-3 py-1.5 rounded-full">
                                        <i class="fa-solid fa-circle-info text-gold"></i>
                                        @if($field->min_value && $field->max_value)
                                            Required range: {{ $field->min_value }} to {{ $field->max_value }} {{ $field->unit }}
                                        @elseif($field->min_value)
                                            Minimum: {{ $field->min_value }} {{ $field->unit }}
                                        @elseif($field->max_value)
                                            Maximum: {{ $field->max_value }} {{ $field->unit }}
                                        @endif
                                    </div>
                                @endif

                                {{-- Input --}}
                                @if($field->field_type === 'select')
                                    <div class="relative">
                                        <select name="{{ $key }}" {{ $field->is_required ? 'required' : '' }}
                                            class="w-full px-4 py-3 border @error($key) border-red-400 bg-red-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white appearance-none">
                                            <option value="">{{ $field->placeholder ?: 'Select option...' }}</option>
                                            @foreach($field->options as $opt)
                                                <option value="{{ $opt }}" {{ old($key) == $opt ? 'selected' : '' }}>
                                                    {{ $opt }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                    </div>

                                @elseif($field->field_type === 'checkbox')
                                    <label class="flex items-start gap-3 cursor-pointer bg-white border border-gray-200 rounded-xl p-4 hover:border-primary transition">
                                        <input type="checkbox" name="{{ $key }}" value="1" {{ old($key) ? 'checked' : '' }}
                                            class="w-5 h-5 mt-0.5 rounded text-primary focus:ring-primary border-gray-300">
                                        <span class="text-sm text-gray-600 font-medium leading-6">
                                            {{ $field->placeholder ?: 'Yes, I meet this criterion' }}
                                        </span>
                                    </label>

                                @elseif($field->field_type === 'date')
                                    <input type="date" name="{{ $key }}" value="{{ old($key) }}" {{ $field->is_required ? 'required' : '' }}
                                        class="w-full px-4 py-3 border @error($key) border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">

                                @else
                                    <div class="relative">
                                        <input type="{{ $field->field_type === 'number' ? 'number' : 'text' }}"
                                            step="any"
                                            name="{{ $key }}"
                                            value="{{ old($key) }}"
                                            placeholder="{{ $field->placeholder }}"
                                            {{ $field->is_required ? 'required' : '' }}
                                            class="w-full px-4 py-3 border @error($key) border-red-400 bg-red-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white {{ $field->unit ? 'pr-16' : '' }}">

                                        @if($field->unit)
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-xs text-gray-400 font-bold">
                                                {{ $field->unit }}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @error($key)
                                    <p class="text-xs text-red-600 font-medium flex items-center gap-1 mt-2">
                                        <i class="fa-solid fa-circle-info"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        @endforeach

                        {{-- Buttons --}}
                        <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <a href="{{ route('apply.index') }}"
                               class="inline-flex items-center justify-center gap-2 text-sm text-gray-500 hover:text-primary font-extrabold px-5 py-3 rounded-xl bg-gray-50 hover:bg-blue-50 transition">
                                <i class="fa-solid fa-arrow-left text-xs"></i>
                                Back to Programs
                            </a>

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 bg-primary text-white font-extrabold px-8 py-4 rounded-xl hover:bg-gold transition shadow-lg hover:shadow-xl">
                                Verify Eligibility
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDEBAR --}}
                <aside class="lg:sticky lg:top-6 space-y-6">

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-primary font-extrabold text-lg mb-4">
                            Program Summary
                        </h3>

                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <span class="text-gray-500">Program</span>
                                <span class="font-bold text-primary text-right">{{ $program->name }}</span>
                            </div>

                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <span class="text-gray-500">Country</span>
                                <span class="font-bold text-primary">{{ $program->country }}</span>
                            </div>

                            <div class="flex justify-between gap-4 border-b border-gray-100 pb-3">
                                <span class="text-gray-500">Criteria</span>
                                <span class="font-bold text-primary">{{ $fields->count() }}</span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Step</span>
                                <span class="bg-blue-50 text-primary px-3 py-1 rounded-full text-xs font-bold">
                                    2 of 3
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-primary rounded-3xl shadow-sm p-6 text-white overflow-hidden relative">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-gold/20 rounded-full"></div>

                        <div class="relative">
                            <div class="w-12 h-12 rounded-2xl bg-gold text-white flex items-center justify-center mb-4">
                                <i class="fa-solid fa-lock"></i>
                            </div>

                            <h3 class="font-extrabold text-lg mb-2">Secure Verification</h3>
                            <p class="text-white/70 text-sm leading-6">
                                Your information is used only to check eligibility based on program rules.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-primary font-extrabold text-lg mb-4">
                            What happens next?
                        </h3>

                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <span class="w-7 h-7 rounded-full bg-blue-50 text-primary flex items-center justify-center text-xs font-bold">1</span>
                                <p class="text-sm text-gray-500 leading-5">System checks your entered criteria.</p>
                            </div>

                            <div class="flex gap-3">
                                <span class="w-7 h-7 rounded-full bg-blue-50 text-primary flex items-center justify-center text-xs font-bold">2</span>
                                <p class="text-sm text-gray-500 leading-5">If eligible, application form will open.</p>
                            </div>

                            <div class="flex gap-3">
                                <span class="w-7 h-7 rounded-full bg-blue-50 text-primary flex items-center justify-center text-xs font-bold">3</span>
                                <p class="text-sm text-gray-500 leading-5">Submit final details and documents.</p>
                            </div>
                        </div>
                    </div>

                </aside>

            </div>
        </form>

    </div>
</section>

@endsection
