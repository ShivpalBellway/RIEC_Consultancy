@extends('layouts.app')

@section('title', 'Check Eligibility — ' . $program->name)

@section('content')

{{-- ===== HERO + STEPS ===== --}}
<section class="relative h-[600px] bg-[#eaf6ff] overflow-hidden">

    {{-- Background Image --}}
    <img src="{{ asset('storage/eligiblity.png') }}"
         class="absolute inset-0 w-full h-full object-cover object-[center]"
         alt="Eligibility Banner">

    {{-- Left White Gradient --}}
    <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-transparent"></div>

    {{-- Hero Content --}}
    <div class="relative z-10 max-w-[1600px] mx-auto h-full xl:px-20 px-10 pb-24">
        <div class="grid lg:grid-cols-2 gap-10 items-center h-full">

            {{-- Left Content --}}
            <div>
                <span class="inline-flex items-center border border-primary/20 text-primary bg-white/70 backdrop-blur rounded-full px-5 py-2 text-xs font-extrabold uppercase tracking-[0.18em]">
                    Eligibility Verification
                </span>

                <h1 class="text-4xl md:text-6xl font-extrabold text-primary mt-6 leading-tight">
                    Check Your <span class="text-primary">Eligibility</span>
                </h1>

                <p class="text-primary/80 text-base md:text-lg leading-8 mt-5 max-w-xl">
                    Enter your academic and personal details below. We will validate your
                    eligibility based on the selected program requirements.
                </p>
            </div>

            {{-- Selected Program Card --}}
            <div class="flex lg:justify-end">
                <div class="bg-white/90 backdrop-blur-md rounded-[32px] shadow-2xl border border-white p-8 w-full max-w-md">
                    <p class="text-xs text-gray-500 font-extrabold uppercase mb-3">
                        Selected Program
                    </p>

                    <h3 class="text-primary font-extrabold text-2xl leading-snug">
                        {{ $program->name }}
                    </h3>

                    <div class="flex flex-wrap gap-3 mt-5">
                        <span class="bg-blue-50 text-primary px-4 py-2 rounded-full text-xs font-bold">
                            {{ $program->country }}
                        </span>

                        <span class="bg-gray-100 text-gray-600 px-4 py-2 rounded-full text-xs font-bold">
                            {{ $program->program_type_label }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== STEPS ===== --}}
<section class="relative z-20 -mt-20 px-6">
    <div class="max-w-5xl mx-auto bg-white/95 backdrop-blur-md rounded-[30px] shadow-2xl px-8 py-7 border border-white/60">

        <div class="flex items-center justify-between">

            <div class="text-center text-emerald-600 font-bold text-xs">
                <span class="mx-auto w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center mb-3 font-bold">
                    1
                </span>
                Select Program
            </div>

            <div class="h-[2px] flex-1 bg-gray-200 mx-5"></div>

            <div class="text-center text-primary font-bold text-xs">
                <span class="mx-auto w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center mb-3 font-bold">
                    2
                </span>
                Check Eligibility
            </div>

            <div class="h-[2px] flex-1 bg-gray-200 mx-5"></div>

            <div class="text-center text-gray-400 font-bold text-xs">
                <span class="mx-auto w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center mb-3 font-bold">
                    3
                </span>
                Application Form
            </div>

        </div>
    </div>
</section>

{{-- ===== CONTENT ===== --}}
<section class="py-14 px-6 bg-[#f5f9ff]">
    <div class="max-w-7xl mx-auto">
        <form action="{{ route('apply.eligibility.check', $program) }}" method="POST">
            @csrf

            <div class="grid lg:grid-cols-[1fr_300px] gap-8 items-start">

                {{-- Form Card --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-10">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-extrabold text-primary">
                                Eligibility Details
                            </h2>
                            <p class="text-sm text-gray-500 mt-2">
                                Please fill in the required information to proceed.
                            </p>
                        </div>

                        <div class="hidden md:flex w-16 h-16 rounded-2xl bg-blue-50 text-primary items-center justify-center text-2xl">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($fields as $field)
                            @php $key = $field->field_key; @endphp

                            <div class="border border-gray-200 rounded-2xl p-5 bg-white hover:shadow-md transition">
                                <label class="flex items-center gap-2 text-sm font-extrabold text-primary mb-3">
                                    <span class="w-8 h-8 rounded-full bg-blue-50 text-primary flex items-center justify-center">
                                        <i class="fa-solid fa-circle-check text-xs"></i>
                                    </span>
                                    {{ $field->label }}
                                    @if($field->is_required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @if($field->field_type === 'select')
                                    <select name="{{ $key }}"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-600 outline-none">
                                        <option value="">{{ $field->placeholder ?: 'Select option' }}</option>
                                        @foreach($field->options as $opt)
                                            <option value="{{ $opt }}" {{ old($key) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>

                                @elseif($field->field_type === 'checkbox')
                                    <label class="flex items-center gap-3">
                                        <input type="checkbox" name="{{ $key }}" value="1" {{ old($key) ? 'checked' : '' }}
                                            class="w-5 h-5 rounded text-primary border-gray-300">
                                        <span class="text-sm text-gray-600">{{ $field->placeholder ?: 'Yes, I meet this criterion' }}</span>
                                    </label>

                                @elseif($field->field_type === 'date')
                                    <input type="date" name="{{ $key }}" value="{{ old($key) }}"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-600 outline-none">

                                @else
                                    <div class="relative">
                                        <input type="{{ $field->field_type === 'number' ? 'number' : 'text' }}"
                                            step="any"
                                            name="{{ $key }}"
                                            value="{{ old($key) }}"
                                            placeholder="{{ $field->placeholder }}"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-600 outline-none {{ $field->unit ? 'pr-14' : '' }}">

                                        @if($field->unit)
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">
                                                {{ $field->unit }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row justify-between gap-4">
                        <a href="{{ route('apply.index') }}"
                           class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-gray-50 text-primary text-sm font-extrabold hover:bg-gray-100">
                            ← Back to Programs
                        </a>

                        <button type="submit"
                            class="inline-flex justify-center items-center px-8 py-3 rounded-xl bg-primary text-white text-sm font-extrabold hover:bg-primary shadow-lg">
                            Verify Eligibility →
                        </button>
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="space-y-5">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-primary font-extrabold text-lg mb-4">Program Summary</h3>

                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between border-b pb-3">
                                <span class="text-gray-500">Program</span>
                                <b class="text-primary text-right">{{ $program->name }}</b>
                            </div>
                            <div class="flex justify-between border-b pb-3">
                                <span class="text-gray-500">Country</span>
                                <b class="text-primary">{{ $program->country }}</b>
                            </div>
                            <div class="flex justify-between border-b pb-3">
                                <span class="text-gray-500">Criteria</span>
                                <b class="text-primary">{{ $fields->count() }}</b>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Step</span>
                                <b class="bg-blue-50 text-primary px-3 py-1 rounded-full text-xs">2 of 3</b>
                            </div>
                        </div>
                    </div>

                    <div class="bg-primary rounded-3xl p-6 text-white shadow-lg">
                        <div class="w-12 h-12 rounded-full bg-white/15 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <h3 class="font-extrabold text-lg mb-2">Secure Verification</h3>
                        <p class="text-white/80 text-sm leading-6">
                            Your information is used only to check eligibility based on program rules.
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-primary font-extrabold text-lg mb-4">What happens next?</h3>

                        <div class="space-y-4 text-sm text-gray-500">
                            <p><b class="text-primary">1.</b> System checks your entered criteria.</p>
                            <p><b class="text-primary">2.</b> If eligible, application form will open.</p>
                            <p><b class="text-primary">3.</b> Submit final details and documents.</p>
                        </div>
                    </div>
                </aside>

            </div>
        </form>
    </div>
</section>

@if(session('eligibility_modal') === 'success')
    <div id="eligibilityResultModal" class="fixed inset-0 z-50 flex items-center justify-center bg-primary/25 backdrop-blur-sm px-4">
        <div class="w-full max-w-[430px] rounded-3xl border border-emerald-100 bg-white shadow-2xl overflow-hidden">
            <div class="px-8 pt-10 pb-8 text-center">
                <div class="mx-auto w-20 h-20 rounded-full bg-emerald-600 text-white flex items-center justify-center text-4xl shadow-lg shadow-emerald-200">
                    <i class="fa-solid fa-check"></i>
                </div>

                <h2 class="mt-6 text-2xl font-extrabold text-gray-950">Congratulations!</h2>
                <p class="mt-2 text-sm font-semibold text-gray-600">You are eligible to study in Korea.</p>

                <div class="relative mt-8 h-32 overflow-hidden rounded-2xl bg-gradient-to-b from-sky-50 to-emerald-50">
                    <div class="absolute bottom-0 left-0 right-0 h-12 bg-emerald-200"></div>
                    <div class="absolute bottom-8 left-7 h-12 w-20 rounded-t-full bg-emerald-700"></div>
                    <div class="absolute bottom-8 right-7 h-12 w-20 rounded-t-full bg-emerald-700"></div>
                    <div class="absolute bottom-5 left-12 h-12 w-24 rounded-t-2xl bg-blue-700"></div>
                    <div class="absolute bottom-5 right-12 h-12 w-24 rounded-t-2xl bg-blue-700"></div>
                    <div class="absolute bottom-5 left-1/2 h-24 w-3 -translate-x-1/2 bg-sky-600"></div>
                    <div class="absolute bottom-28 left-1/2 h-3 w-3 -translate-x-1/2 rounded-full bg-red-500"></div>
                    <div class="absolute bottom-5 left-[43%] h-16 w-4 bg-sky-300"></div>
                    <div class="absolute bottom-5 left-[54%] h-20 w-5 bg-sky-400"></div>
                </div>

                <a href="{{ route('apply.form', $program) }}"
                   class="mt-8 inline-flex w-full items-center justify-center rounded-xl bg-emerald-700 px-6 py-4 text-sm font-extrabold uppercase text-white shadow-lg shadow-emerald-100 hover:bg-emerald-800">
                    Proceed to Information Page
                </a>
            </div>
        </div>
    </div>
@elseif(session('eligibility_modal') === 'error')
    <div id="eligibilityResultModal" class="fixed inset-0 z-50 flex items-center justify-center bg-primary/25 backdrop-blur-sm px-4">
        <div class="w-full max-w-[420px] rounded-3xl border border-red-100 bg-white shadow-2xl overflow-hidden">
            <div class="px-8 py-10 text-center bg-gradient-to-b from-red-50 to-white">
                <div class="mx-auto w-16 h-16 rounded-full bg-red-600 text-white flex items-center justify-center text-3xl shadow-lg shadow-red-100">
                    <i class="fa-solid fa-xmark"></i>
                </div>

                <h2 class="mt-6 text-2xl font-extrabold text-gray-950">Sorry!</h2>
                <p class="mt-2 text-sm font-semibold text-gray-600">
                    We are unable to further process your request.
                </p>

                @if(session('eligibility_messages'))
                    <div class="mt-6 rounded-2xl border border-red-100 bg-white p-4 text-left">
                        <ul class="space-y-2 text-sm font-semibold text-red-700">
                            @foreach(session('eligibility_messages') as $message)
                                <li class="flex gap-2">
                                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>
                                    <span>{{ $message }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="button"
                    onclick="document.getElementById('eligibilityResultModal').remove()"
                    class="mt-7 inline-flex min-w-28 items-center justify-center rounded-xl bg-red-600 px-8 py-3 text-sm font-extrabold uppercase text-white shadow-lg shadow-red-100 hover:bg-red-700">
                    OK
                </button>
            </div>
        </div>
    </div>
@endif

@endsection
