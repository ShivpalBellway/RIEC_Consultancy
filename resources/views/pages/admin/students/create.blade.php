@extends('layouts.admin')

@section('title', 'Add Student')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Add Student</h2>
            <p class="text-xs text-slate-500 mt-0.5">Create a student profile and assign it to an active agent.</p>
        </div>
        <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-semibold">Back</a>
    </div>

    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
    </div>
    @endif

    <form action="{{ route('admin.students.store') }}" method="POST" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Assigned Agent *</label>
                <select name="agent_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    <option value="">Select active agent</option>
                    @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->name }} — {{ $agent->agency_name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="field-label">First Name *</label><input name="first_name" value="{{ old('first_name') }}" required class="field-input"></div>
            <div><label class="field-label">Last Name *</label><input name="last_name" value="{{ old('last_name') }}" required class="field-input"></div>
            <div><label class="field-label">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="field-input"></div>
            <div><label class="field-label">Phone</label><input name="phone" value="{{ old('phone') }}" class="field-input"></div>
            <div><label class="field-label">Passport Number</label><input name="passport_number" value="{{ old('passport_number') }}" class="field-input"></div>
            <div><label class="field-label">Date of Birth</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="field-input"></div>
            <div><label class="field-label">Nationality</label><input name="nationality" value="{{ old('nationality') }}" class="field-input"></div>
            <div><label class="field-label">Gender</label><select name="gender" class="field-input"><option value="">Select Gender</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
        </div>

        <div class="border-t border-slate-100 pt-5">
            <h3 class="font-bold text-slate-900 text-sm mb-4">Korean Address</h3>
            <div class="space-y-4">
                <div><label class="field-label">Address</label><textarea name="korean_address" rows="2" class="field-input">{{ old('korean_address') }}</textarea></div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div><label class="field-label">City</label><input name="korean_city" value="{{ old('korean_city') }}" class="field-input"></div>
                    <div><label class="field-label">Postal Code</label><input name="korean_postal_code" value="{{ old('korean_postal_code') }}" class="field-input"></div>
                    <div><label class="field-label">Contact Number</label><input name="korean_contact_number" value="{{ old('korean_contact_number') }}" class="field-input"></div>
                </div>
            </div>
        </div>

        <div class="flex justify-end"><button class="px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-bold"><i class="fa-solid fa-save mr-1"></i> Create Student</button></div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .field-label { display:block; font-size:.7rem; font-weight:600; color:#334155; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.25rem }
    .field-input { width:100%; padding:.625rem 1rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:.75rem; font-size:.875rem; }
</style>
@endpush
