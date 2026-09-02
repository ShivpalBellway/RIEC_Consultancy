@extends('layouts.app')

@section('title', 'Student Login')

@section('content')
<section class="bg-[#f5f9ff] px-6 py-16 min-h-[620px]">
    <div class="max-w-md mx-auto bg-white border border-gray-100 rounded-2xl shadow-xl p-6 md:p-8">
        <div class="text-center mb-8">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary text-white mb-4">
                <i class="fa-solid fa-user-graduate"></i>
            </span>
            <h1 class="text-2xl font-black text-primary">Student Login</h1>
            <p class="text-sm text-gray-500 mt-2">Enter your email to receive a secure login OTP.</p>
        </div>

        @if(session('auth_notice'))
            <div class="mb-5 rounded-xl border border-gold/30 bg-gold/10 px-4 py-3 text-sm text-primary font-semibold">
                {{ session('auth_notice') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('student.login.post') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-primary uppercase tracking-[0.15em] mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border @error('email') border-red-400 @else border-gray-200 @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                @error('email')
                    <p class="text-xs text-red-600 font-semibold mt-2">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" value="1" class="rounded text-primary">
                Remember me
            </label>

            <button type="submit" class="w-full bg-primary hover:bg-gold text-white rounded-xl py-3 font-bold transition">
                Send Login OTP
            </button>
        </form>

        <p class="text-sm text-gray-500 text-center mt-6">
            New student?
            <a href="{{ route('student.register') }}" class="font-bold text-primary hover:text-gold">Create an account</a>
        </p>
    </div>
</section>
@endsection
