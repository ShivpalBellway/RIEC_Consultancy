@extends('layouts.agent')

@section('title', 'Add New Student')
@section('page_title', 'Add New Student')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Add New Student Profile</h2>
            <p class="text-xs text-gray-500 mt-0.5">Enter student personal details and Korean address information</p>
        </div>

        <a href="{{ route('agent.students.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-xl text-xs transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Directory</span>
        </a>
    </div>

    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
        <div class="font-bold flex items-center gap-2 text-rose-700">
            <i class="fa-solid fa-circle-exclamation"></i>
            Please fix the following validation errors:
        </div>
        @foreach($errors->all() as $error)
        <div>• {{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form action="{{ route('agent.students.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Section 1: Basic Personal Information -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm space-y-4">
            <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-user-graduate text-primary-600"></i>
                    <span>1. Basic Personal Information</span>
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors"
                        placeholder="John">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors"
                        placeholder="Doe">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors"
                        placeholder="student@example.com">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Contact Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors"
                        placeholder="+82 10-0000-0000">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Passport Number</label>
                    <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors font-mono"
                        placeholder="M12345678">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Gender</label>
                    <select name="gender" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Korean Address Details -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm space-y-4">
            <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-house-flag text-rose-600"></i>
                    <span>2. Korean Address Section</span>
                </h3>
                <span class="text-xs text-gray-400 font-normal">Editable until application finalized</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Korean Street Address</label>
                <textarea name="korean_address" rows="2"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors placeholder-gray-400"
                    placeholder="e.g. 145 Anam-ro, Seongbuk-gu, Seoul, Republic of Korea">{{ old('korean_address') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Korean City</label>
                    <input type="text" name="korean_city" value="{{ old('korean_city') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors"
                        placeholder="Seoul">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Postal Code</label>
                    <input type="text" name="korean_postal_code" value="{{ old('korean_postal_code') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors font-mono"
                        placeholder="02841">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Korean Contact Phone</label>
                    <input type="text" name="korean_contact_number" value="{{ old('korean_contact_number') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors"
                        placeholder="+82 2-1234-5678">
                </div>
            </div>
        </div>

        <!-- Section 3: University Assignment Notice -->
        <div class="bg-indigo-50/60 rounded-2xl p-5 border border-indigo-100 flex items-start gap-3">
            <i class="fa-solid fa-shield-halved text-indigo-600 text-lg mt-0.5"></i>
            <div>
                <h4 class="font-bold text-indigo-950 text-sm">University Assignment Permissions Notice</h4>
                <p class="text-xs text-indigo-800/80 mt-0.5 leading-relaxed">
                    Universities (e.g. Korea University, Yonsei University, Hanyang University) are assigned and managed exclusively by REIAC Global System Administrators. Once you submit this student profile, Admin will assign the appropriate university.
                </p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('agent.students.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm shadow-md transition-all flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>Save Student & Continue to Upload Documents</span>
            </button>
        </div>

    </form>

</div>
@endsection
