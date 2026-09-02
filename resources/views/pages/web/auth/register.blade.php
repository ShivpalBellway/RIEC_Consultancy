@extends('layouts.app')

@section('title', 'Student Register')

@section('content')
<section class="bg-[#f5f9ff] px-6 py-16 min-h-[620px]">
    <div class="max-w-xl mx-auto bg-white border border-gray-100 rounded-2xl shadow-xl p-6 md:p-8">
        <div class="text-center mb-8">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary text-white mb-4">
                <i class="fa-solid fa-user-plus"></i>
            </span>
            <h1 class="text-2xl font-black text-primary">Create Student Account</h1>
            <p class="text-sm text-gray-500 mt-2">Enter your details and verify your email with an OTP.</p>
        </div>

        <form action="{{ route('student.register.post') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-[0.15em] mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                @error('name')
                    <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-[0.15em] mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border @error('email') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                @error('email')
                    <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- ===== APPLICANT CONSENT SECTION ===== --}}
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h4 class="text-sm font-bold text-primary uppercase tracking-[0.15em] mb-1">
                    <i class="fa-solid fa-shield-halved mr-2"></i>
                    Applicant Consent
                </h4>
                <p class="text-xs text-gray-500 mb-4">Please read the following carefully before submitting your application.</p>

                {{-- Master Agree to All --}}
                <label class="flex items-start gap-3 rounded-xl border border-primary/20 bg-primary/5 p-3 mb-4 cursor-pointer hover:bg-primary/10 transition">
                    <input type="checkbox" id="agreeAll"
                        class="mt-0.5 h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary focus:ring-2 cursor-pointer">
                    <span class="text-sm font-bold text-primary">
                        <i class="fa-solid fa-check-circle mr-2"></i>
                        Agree to All (I agree to all of the items below)
                    </span>
                </label>

                {{-- Sub-consents Tree --}}
                <div class="space-y-3 pl-1">

                    {{-- 1. Required - Collection --}}
                    <div class="consent-item">
                        <label class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border @error('consent_collection') border-red-300 bg-red-50/50 @else border-gray-200 hover:border-primary/30 @enderror transition cursor-pointer">
                            <input type="checkbox" name="consent_collection" value="1"
                                class="sub-consent required-consent mt-0.5 h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-2 cursor-pointer"
                                {{ old('consent_collection') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 leading-6">
                                <span class="font-bold text-red-600">[Required]</span>
                                Consent to the Collection and Processing of Personal Information
                                <button type="button" class="text-primary text-xs font-bold ml-2 toggle-details hover:text-gold transition focus:outline-none">
                                    [View Details <i class="fa-solid fa-chevron-down"></i>]
                                </button>
                            </span>
                        </label>
                        <div class="consent-details hidden mt-2 p-3 bg-blue-50/50 rounded-lg text-xs text-gray-600 border border-blue-100/50 leading-relaxed">
                            I have read and understood the R.E.I.A Consultancy Personal Information Processing Policy. I voluntarily consent to the collection, processing, use, and retention of my personal information for the purposes of university application and admission support, document verification, application processing with partner universities, visa-related services, customer consultation, eligibility evaluation, notification of results, and the establishment and maintenance of communication channels related to my application.
                        </div>
                        @error('consent_collection')
                            <p class="text-xs text-red-600 font-semibold mt-1 pl-3">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 2. Required - Third Parties --}}
                    <div class="consent-item">
                        <label class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border @error('consent_third_party') border-red-300 bg-red-50/50 @else border-gray-200 hover:border-primary/30 @enderror transition cursor-pointer">
                            <input type="checkbox" name="consent_third_party" value="1"
                                class="sub-consent required-consent mt-0.5 h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-2 cursor-pointer"
                                {{ old('consent_third_party') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 leading-6">
                                <span class="font-bold text-red-600">[Required]</span>
                                Consent to the Provision of Personal Information to Third Parties
                                <button type="button" class="text-primary text-xs font-bold ml-2 toggle-details hover:text-gold transition focus:outline-none">
                                    [View Details <i class="fa-solid fa-chevron-down"></i>]
                                </button>
                            </span>
                        </label>
                        <div class="consent-details hidden mt-2 p-3 bg-blue-50/50 rounded-lg text-xs text-gray-600 border border-blue-100/50 leading-relaxed">
                            I understand and agree that R.E.I.A Consultancy may provide my personal information to partner universities, educational institutions, government authorities, and other organizations involved in the admission process only to the extent necessary for application processing, eligibility evaluation, academic registration, visa processing, and other related services, in accordance with applicable laws and regulations.
                        </div>
                        @error('consent_third_party')
                            <p class="text-xs text-red-600 font-semibold mt-1 pl-3">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 3. Optional - Email Updates --}}
                    <div class="consent-item">
                        <label class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200 hover:border-primary/30 transition cursor-pointer opacity-90">
                            <input type="checkbox" name="consent_email_updates" value="1"
                                class="sub-consent mt-0.5 h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-2 cursor-pointer"
                                {{ old('consent_email_updates') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 leading-6">
                                <span class="font-semibold text-gray-500">[Optional]</span>
                                Consent to Receive Application Status Updates by Email
                                <button type="button" class="text-primary text-xs font-bold ml-2 toggle-details hover:text-gold transition focus:outline-none">
                                    [View Details <i class="fa-solid fa-chevron-down"></i>]
                                </button>
                            </span>
                        </label>
                        <div class="consent-details hidden mt-2 p-3 bg-blue-50/50 rounded-lg text-xs text-gray-600 border border-blue-100/50 leading-relaxed">
                            I voluntarily consent to receive updates regarding my application status, document verification, admission progress, university responses, visa-related progress, appointment notifications, and other application-related communications from R.E.I.A Consultancy through my registered email address. I understand that this consent is optional and may be withdrawn at any time.
                        </div>
                    </div>

                    {{-- 4. Optional - Marketing --}}
                    <div class="consent-item">
                        <label class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200 hover:border-primary/30 transition cursor-pointer opacity-90">
                            <input type="checkbox" name="consent_marketing" value="1"
                                class="sub-consent mt-0.5 h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-2 cursor-pointer"
                                {{ old('consent_marketing') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 leading-6">
                                <span class="font-semibold text-gray-500">[Optional]</span>
                                Consent to Receive Marketing and Promotional Information
                                <button type="button" class="text-primary text-xs font-bold ml-2 toggle-details hover:text-gold transition focus:outline-none">
                                    [View Details <i class="fa-solid fa-chevron-down"></i>]
                                </button>
                            </span>
                        </label>
                        <div class="consent-details hidden mt-2 p-3 bg-blue-50/50 rounded-lg text-xs text-gray-600 border border-blue-100/50 leading-relaxed">
                            I voluntarily consent to the use of my name, contact information, and email address for receiving customized study abroad information, scholarship opportunities, educational events, university news, promotional materials, and other marketing communications from R.E.I.A Consultancy. I understand that this consent is optional and may be withdrawn at any time.
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-gold text-white rounded-xl py-3 font-bold transition mt-6">
                Register and Send OTP
            </button>
        </form>

        <p class="text-sm text-gray-500 text-center mt-6">
            Already registered?
            <a href="{{ route('student.login') }}" class="font-bold text-primary hover:text-gold">Login</a>
        </p>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var agreeAll = document.getElementById('agreeAll');
        var subConsents = document.querySelectorAll('.sub-consent');

        if (agreeAll) {
            agreeAll.addEventListener('change', function() {
                var isChecked = this.checked;
                subConsents.forEach(function(cb) {
                    cb.checked = isChecked;
                });
            });
        }

        subConsents.forEach(function(cb) {
            cb.addEventListener('change', function() {
                if (agreeAll) {
                    var allChecked = Array.from(subConsents).every(function(c) {
                        return c.checked;
                    });
                    agreeAll.checked = allChecked;
                }
            });
        });

        document.querySelectorAll('.toggle-details').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var parent = this.closest('.consent-item');
                if (!parent) return;

                var details = parent.querySelector('.consent-details');
                var icon = this.querySelector('i');

                if (details) {
                    details.classList.toggle('hidden');
                    if (icon) {
                        icon.classList.toggle('fa-chevron-down');
                        icon.classList.toggle('fa-chevron-up');
                    }

                    if (details.classList.contains('hidden')) {
                        this.innerHTML = '[View Details <i class="fa-solid fa-chevron-down"></i>]';
                    } else {
                        this.innerHTML = '[Hide Details <i class="fa-solid fa-chevron-up"></i>]';
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection
