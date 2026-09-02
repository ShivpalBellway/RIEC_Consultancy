@extends('layouts.agent')

@section('title', 'Edit Student')
@section('page_title', 'Edit Student Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">Edit Profile: {{ $student->full_name }}</h2>
            <p class="text-xs text-gray-500 mt-0.5">Update personal details and Korean address information</p>
        </div>

        <a href="{{ route('agent.students.show', $student) }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-xl text-xs transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Student Details</span>
        </a>
    </div>

    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
        <div class="font-bold flex items-center gap-2 text-rose-700">
            <i class="fa-solid fa-circle-exclamation"></i>
            Please fix validation errors:
        </div>
        @foreach($errors->all() as $error)
        <div>• {{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form action="{{ route('agent.students.update', $student) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Personal Info -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm space-y-4">
            <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-user-graduate text-primary-600"></i>
                    <span>Personal Details</span>
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Contact Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Passport Number</label>
                    <input type="text" name="passport_number" value="{{ old('passport_number', $student->passport_number) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Gender</label>
                    <select name="gender" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Korean Address Section -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm space-y-4">
            <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-house-flag text-rose-600"></i>
                    <span>Korean Address Section</span>
                </h3>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Korean Street Address</label>
                <textarea name="korean_address" rows="2"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">{{ old('korean_address', $student->korean_address) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Korean City</label>
                    <input type="text" name="korean_city" value="{{ old('korean_city', $student->korean_city) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Postal Code</label>
                    <input type="text" name="korean_postal_code" value="{{ old('korean_postal_code', $student->korean_postal_code) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Korean Contact Phone</label>
                    <input type="text" name="korean_contact_number" value="{{ old('korean_contact_number', $student->korean_contact_number) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-600 transition-colors">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('agent.students.show', $student) }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm shadow-md transition-all flex items-center gap-2">
                <i class="fa-solid fa-save"></i>
                <span>Save Profile Changes</span>
            </button>
        </div>

    </form>

</div>
@endsection
