@extends('layouts.app')

@section('title', 'Select Study Program — REIAC ')

@section('content')

{{-- ===== HERO ===== --}}
<!-- <section class="relative bg-primary text-white py-20 px-6 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1800&auto=format&fit=crop"
             class="w-full h-full object-cover opacity-25" alt="Apply">
        <div class="absolute inset-0 bg-gradient-to-r from-primary via-primary/95 to-primary/80"></div>
    </div>

    <div class="relative max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="inline-flex items-center gap-2 bg-gold/15 text-gold text-xs font-extrabold uppercase tracking-[0.2em] px-4 py-2 rounded-full border border-gold/30">
                <i class="fa-solid fa-graduation-cap"></i>
                Eligibility & Application Wizard
            </span>

            <h1 class="text-4xl md:text-5xl font-extrabold mt-6 leading-tight">
                Choose Your <br>
                <span class="text-gold">Dream Program</span>
            </h1>

            <p class="text-white/75 text-base md:text-lg mt-6 max-w-xl leading-8">
                Select a study program, check your eligibility instantly and complete your application through a guided step-by-step process.
            </p>

            <div class="flex flex-wrap gap-4 mt-8">
                <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-list-check text-gold"></i>
                    <span class="text-sm font-semibold">Eligibility Check</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-file-signature text-gold"></i>
                    <span class="text-sm font-semibold">Dynamic Forms</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-cloud-arrow-up text-gold"></i>
                    <span class="text-sm font-semibold">Document Upload</span>
                </div>
            </div>
        </div>

        {{-- Right Info Box --}}
        <div class="hidden lg:block">
            <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-3xl p-8 shadow-2xl">
                <div class="bg-white rounded-2xl p-6 text-primary">
                    <h3 class="text-lg font-extrabold mb-5">Application Journey</h3>

                    <div class="space-y-5">
                        <div class="flex gap-4">
                            <span class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">1</span>
                            <div>
                                <h4 class="font-bold">Select Program</h4>
                                <p class="text-gray-500 text-sm">Choose country and program type.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <span class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">2</span>
                            <div>
                                <h4 class="font-bold">Check Eligibility</h4>
                                <p class="text-gray-500 text-sm">System validates your details.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <span class="w-9 h-9 rounded-full bg-gold text-white flex items-center justify-center font-bold text-sm">3</span>
                            <div>
                                <h4 class="font-bold">Submit Application</h4>
                                <p class="text-gray-500 text-sm">Fill information and upload documents.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section> -->

{{-- <section class="relative overflow-hidden bg-[#071A4A] text-white">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="{{ asset('assets/images/global-education-bg.jpg') }}"
             alt="Global Education"
             class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-r from-[#071A4A] via-[#0B2F78]/95 to-[#0A66C2]/85"></div>
    </div>

    <!-- Glow Effects -->
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-blue-400/30 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 -left-20 w-72 h-72 bg-yellow-300/20 rounded-full blur-3xl"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-12 grid lg:grid-cols-2 gap-10 items-center">

        <!-- Left Content -->
        <div>
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 px-4 py-1.5 rounded-full text-xs font-semibold mb-4 backdrop-blur">
                <i class="fa-solid fa-globe"></i>
                Global Admissions Platform
            </span>

            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4">
                Start Your Journey <br>
                to <span class="text-yellow-300">Study Abroad</span>
            </h1>

            <p class="text-blue-100 text-sm md:text-base leading-relaxed max-w-xl mb-6">
                Apply to universities across multiple countries through one simple guided process —
                check eligibility, fill application details and upload documents online.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="#programs"
                   class="bg-yellow-400 text-[#071A4A] px-5 py-2.5 rounded-lg font-bold text-sm hover:bg-yellow-300 transition">
                    Start Application
                </a>

                <a href="#workflow"
                   class="bg-white/10 border border-white/25 px-5 py-2.5 rounded-lg font-bold text-sm hover:bg-white/20 transition">
                    View Process
                </a>
            </div>
        </div>

        <!-- Right Workflow UI -->
        <div class="relative">
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-5 md:p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-xl font-bold">Application Workflow</h3>
                        <p class="text-blue-100 text-xs">Track your admission journey step-by-step</p>
                    </div>

                    <div class="w-11 h-11 rounded-2xl bg-yellow-400 text-[#071A4A] flex items-center justify-center">
                        <i class="fa-solid fa-route"></i>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl p-3">
                        <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center font-bold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold text-sm">Select Program & Country</h4>
                            <p class="text-xs text-blue-100">Choose your preferred study destination</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl p-3">
                        <div class="w-9 h-9 rounded-full bg-cyan-500 flex items-center justify-center font-bold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold text-sm">Check Eligibility</h4>
                            <p class="text-xs text-blue-100">Verify academic and language requirements</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl p-3">
                        <div class="w-9 h-9 rounded-full bg-purple-500 flex items-center justify-center font-bold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold text-sm">Fill Application Details</h4>
                            <p class="text-xs text-blue-100">Personal, passport and academic information</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-2xl p-3">
                        <div class="w-9 h-9 rounded-full bg-orange-400 flex items-center justify-center font-bold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold text-sm">Upload Documents</h4>
                            <p class="text-xs text-blue-100">Submit certificates and required files</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 bg-green-500/20 border border-green-300/30 rounded-2xl p-3">
                        <div class="w-9 h-9 rounded-full bg-green-500 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm">Review & Submit</h4>
                            <p class="text-xs text-blue-100">Application sent to admission team</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 bg-white/10 rounded-full h-2 overflow-hidden">
                    <div class="bg-yellow-400 h-full w-[70%] rounded-full"></div>
                </div>

                <p class="text-xs text-blue-100 mt-2 text-right">Guided application progress</p>
            </div>
        </div>

    </div>
</section> --}}

{{-- ===== HERO ===== --}}
<section class="relative bg-primary text-white py-20 px-6 overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{asset('storage/apply-hero.png')}}"
             class="w-full h-full object-cover opacity-100" alt="Apply">
        <div class="absolute inset-0 bg-gradient-to-t from-primary "></div>
    </div>

    <div class="relative max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="inline-flex items-center gap-2 bg-gold/15 text-gold text-xs font-extrabold uppercase tracking-[0.2em] px-4 py-2 rounded-full border border-gold/30">
                <i class="fa-solid fa-graduation-cap"></i>
                Eligibility & Application Wizard
            </span>

            <h1 class="text-4xl md:text-5xl font-extrabold mt-6 leading-tight">
                Choose Your <br>
                <span class="text-gold">Dream Program</span>
            </h1>

            <p class="text-white/75 text-base md:text-lg mt-6 max-w-xl leading-8">
                Select a study program, check your eligibility instantly and complete your application through a guided step-by-step process.
            </p>

            <div class="flex flex-wrap gap-4 mt-8">
                <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-user-graduate text-gold"></i>
                    <span class="text-sm font-semibold">Eligibility Check</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-file-signature text-gold"></i>
                    <span class="text-sm font-semibold">Information & Documents</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 border border-white/10 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-cloud-arrow-up text-gold"></i>
                    <span class="text-sm font-semibold">Application Process</span>
                </div>
            </div>
        </div>

        {{-- Right Info Box --}}
        <div class="hidden lg:block">
            <div class="bg-white/10 backdrop-blur-md border border-gold rounded-3xl p-8 shadow-2xl">
                <div class="bg-primary rounded-2xl p-6 text-white">
                    <h3 class="text-lg font-extrabold mb-5">Application Journey</h3>

                    <div class="space-y-5">
                        <div class="flex gap-4">
                            <span class="w-9 h-9 rounded-full bg-black text-white flex items-center justify-center font-bold text-sm">1</span>
                            <div>
                                <h4 class="font-bold">Select Program</h4>
                                <p class="text-gray-200 text-sm">Choose country and program type.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <span class="w-9 h-9 rounded-full bg-gold text-white flex items-center justify-center font-bold text-sm">2</span>
                            <div>
                                <h4 class="font-bold">Check Eligibility</h4>
                                <p class="text-gray-200 text-sm">System validates your details.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <span class="w-9 h-9 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-sm">3</span>
                            <div>
                                <h4 class="font-bold">Submit Application</h4>
                                <p class="text-gray-200 text-sm">Fill information and upload documents.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ===== PROGRAMS ===== --}}
<section class="relative py-20 px-6 bg-[#f6f8fc] overflow-hidden">

    {{-- Decorative Background --}}
    <div class="absolute top-0 left-0 w-72 h-72 bg-primary/5 rounded-full -translate-x-32 -translate-y-32"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-gold/10 rounded-full translate-x-40 translate-y-40"></div>

    <div class="relative max-w-7xl mx-auto">

        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-flex items-center gap-2 bg-gold/10 text-gold border border-gold/20 px-4 py-2 rounded-full text-xs font-extrabold uppercase tracking-[0.18em]">
                <i class="fa-solid fa-graduation-cap"></i>
                Available Programs
            </span>

            <h2 class="text-4xl md:text-5xl font-extrabold text-primary mt-5 leading-tight">
                Choose the Right Program for Your
                <span class="text-gold">Future</span>
            </h2>

            <p class="text-gray-500 text-base leading-7 mt-5">
                Select your preferred study program, check eligibility requirements and continue your application with a guided process.
            </p>
        </div>

        {{-- Programs List --}}
        <div class="space-y-8">
            @forelse($programs as $program)

                <div class="group bg-white rounded-[28px] border border-gray-100 shadow-sm hover:shadow-2xl transition duration-300 overflow-hidden">

                    <div class="grid lg:grid-cols-[360px_1fr]">

                        {{-- Image Area --}}
                        <div class="relative min-h-[260px] bg-primary overflow-hidden">
                            @if($program->image)
                                <img src="{{ $program->image_url }}"
                                     alt="{{ $program->name }}"
                                     class="w-full h-full object-cover opacity-85 group-hover:scale-110 transition duration-700">
                            @else
                                <img src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?q=80&w=1200&auto=format&fit=crop"
                                     alt="{{ $program->name }}"
                                     class="w-full h-full object-cover opacity-80 group-hover:scale-110 transition duration-700">
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/30 to-transparent"></div>

                            {{-- Country Badge --}}
                            <div class="absolute left-5 top-5">
                                <span class="inline-flex items-center gap-2 bg-white/95 text-primary px-4 py-2 rounded-full text-xs font-extrabold shadow">
                                    <i class="fa-solid fa-location-dot text-gold"></i>
                                    {{ $program->country }}
                                </span>
                            </div>

                            {{-- Program Type --}}
                            <div class="absolute left-5 bottom-5">
                                <span class="inline-flex items-center gap-2 bg-gold text-white px-4 py-2 rounded-full text-xs font-extrabold shadow capitalize">
                                    <i class="fa-solid fa-book-open"></i>
                                    {{ $program->program_type_label }}
                                </span>
                            </div>
                        </div>

                        {{-- Content Area --}}
                        <div class="p-7 md:p-9 flex flex-col justify-between">

                            <div>
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5 mb-5">

                                    <div>
                                        <h3 class="text-2xl md:text-3xl font-extrabold text-primary leading-tight">
                                            {{ $program->name }}
                                        </h3>

                                        <p class="text-gray-500 text-sm md:text-base leading-7 mt-4 max-w-2xl">
                                            {{ $program->description ?: 'Explore this educational program and begin your international study journey with expert admission guidance.' }}
                                        </p>
                                    </div>

                                    <div class="shrink-0">
                                        <span class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-100 px-4 py-2 rounded-full text-xs font-extrabold">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Open Now
                                        </span>
                                    </div>
                                </div>

                                {{-- Feature Boxes --}}
                                <div class="grid sm:grid-cols-3 gap-4 mt-7">

                                    <div class="bg-[#fff8e8] border border-amber-100 rounded-2xl p-4">
                                        <div class="w-10 h-10 rounded-xl bg-white text-gold flex items-center justify-center shadow-sm mb-3">
                                           <i class="fa-solid fa-user-graduate"></i>
                                        </div>
                                        <p class="text-xl font-extrabold text-primary">
                                            Eligibility Check
                                        </p>
                                        <p class="text-xs font-bold text-gray-500 mt-1">
                                             {{ $program->eligibilityFields()->active()->count() }} Requirements
                                        </p>
                                    </div>

                                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                        <div class="w-10 h-10 rounded-xl bg-white text-primary flex items-center justify-center shadow-sm mb-3">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </div>
                                        <p class="text-xl font-extrabold text-primary">
                                           Personal & Academic Information
                                        </p>
                                        <p class="text-xs font-bold text-gray-500 mt-1">
                                            Application Form
                                        </p>
                                    </div>

                                    <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4">
                                        <div class="w-10 h-10 rounded-xl bg-white text-purple-700 flex items-center justify-center shadow-sm mb-3">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                        </div>
                                        <p class="text-xl font-extrabold text-primary">
                                            Submit Application
                                        </p>
                                        <p class="text-xs font-bold text-gray-500 mt-1">
                                            Required Documents
                                        </p>
                                    </div>

                                </div>
                            </div>

                            {{-- Bottom Action --}}
                            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                                <div class="flex items-center gap-3 text-sm text-gray-500">
                                    <div class="w-10 h-10 rounded-full bg-primary/5 text-primary flex items-center justify-center">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-primary">Quick Eligibility Check</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Takes only a few minutes</p>
                                    </div>
                                </div>

                                <a href="{{ route('apply.eligibility', $program) }}"
                                   class="inline-flex items-center justify-center gap-3 bg-primary text-white font-extrabold py-4 px-7 rounded-2xl hover:bg-gold transition shadow-lg hover:shadow-xl">
                                    Check Eligibility & Apply
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>

                            </div>

                        </div>
                    </div>
                </div>

            @empty

                <div class="bg-white rounded-[28px] p-16 text-center border border-gray-100 shadow-sm">
                    <div class="w-24 h-24 bg-blue-50 text-primary rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>

                    <h3 class="text-2xl font-extrabold text-primary">
                        No programs available
                    </h3>

                    <p class="text-gray-500 text-sm mt-3 max-w-md mx-auto leading-6">
                        We are currently preparing new study programs. Please check back later.
                    </p>
                </div>

            @endforelse
        </div>

    </div>
</section>

@endsection
