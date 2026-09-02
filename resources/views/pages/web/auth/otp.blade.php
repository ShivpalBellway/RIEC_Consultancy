@extends('layouts.app')

@section('title', $isRegistration ? 'Verify Email OTP' : 'Verify Login OTP')

@section('content')
<section class="bg-[#f5f9ff] px-6 py-16 min-h-[620px]">
    <div class="max-w-md mx-auto bg-white border border-gray-100 rounded-2xl shadow-xl p-6 md:p-8">
        <div class="text-center mb-8">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary text-white mb-4">
                <i class="fa-solid fa-envelope-circle-check"></i>
            </span>
            <h1 class="text-2xl font-black text-primary">{{ $isRegistration ? 'Verify Your Email' : 'Verify Login OTP' }}</h1>
            <p class="text-sm text-gray-500 mt-2">
                Enter the 6-digit {{ $isRegistration ? 'verification' : 'login' }} code sent to
                <span class="font-bold text-primary">{{ $maskedEmail }}</span>.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('student.otp.verify') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="otp" class="block text-xs font-bold text-primary uppercase tracking-[0.15em] mb-2">One-Time Password</label>
                <input id="otp" type="text" name="otp" value="{{ old('otp') }}" required autofocus
                    inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}"
                    class="w-full px-4 py-3 text-center text-xl tracking-[0.45em] border @error('otp') border-red-400 @else border-gray-200 @enderror rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                @error('otp')
                    <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-gold text-white rounded-xl py-3 font-bold transition">
                {{ $isRegistration ? 'Verify Email and Continue' : 'Verify and Login' }}
            </button>
        </form>

        <form action="{{ route('student.otp.resend') }}" method="POST" class="mt-4 text-center">
            @csrf
            <button type="submit" class="text-sm font-bold text-primary hover:text-gold">Resend OTP</button>
        </form>

        <p class="text-sm text-gray-500 text-center mt-5">
            <a href="{{ $isRegistration ? route('student.register') : route('student.login') }}" class="font-bold text-primary hover:text-gold">Use a different email</a>
        </p>
    </div>
</section>
@endsection
