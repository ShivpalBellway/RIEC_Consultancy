<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification — REIAC</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a2f5e',
                        gold: '#dca737'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css">
</head>

<body class="font-sans min-h-screen overflow-hidden bg-[#061225]">

    {{-- Background --}}
    <div class="fixed inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-[#1a2f5e] via-[#0b1833] to-[#030816]"></div>

        <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#dca737]/20 rounded-full blur-3xl"></div>
        <div class="absolute top-20 -right-32 w-[420px] h-[420px] bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-120px] left-1/2 -translate-x-1/2 w-[520px] h-[520px] bg-[#dca737]/10 rounded-full blur-3xl"></div>

        <div class="absolute inset-0 opacity-[0.07]"
            style="background-image: linear-gradient(rgba(255,255,255,.5) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.5) 1px, transparent 1px); background-size: 44px 44px;">
        </div>
    </div>

    <main class="relative z-10 min-h-screen flex items-center justify-center p-4">

        <div class="w-full max-w-5xl grid lg:grid-cols-2 items-center gap-10">

            {{-- Left Content --}}
            <div class="hidden lg:block text-white">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 backdrop-blur-md mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#dca737] animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-[0.22em] text-white/70">Two-Factor Authentication</span>
                </div>

                <h1 class="text-5xl font-black leading-tight tracking-tight">
                    Secure Your <br>
                    Admin <span class="text-[#dca737]">Account.</span>
                </h1>

                <p class="mt-5 text-white/60 text-sm leading-7 max-w-md">
                    We've sent a One-Time Password (OTP) to your registered email. Enter it below to complete your login securely.
                </p>

                <div class="mt-8 grid grid-cols-3 gap-4 max-w-md">
                    <div class="rounded-2xl bg-white/10 border border-white/10 p-4 backdrop-blur-md">
                        <i class="fa-solid fa-envelope text-[#dca737] mb-3 text-lg"></i>
                        <p class="text-xs font-bold text-white/80">Email Sent</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 border border-white/10 p-4 backdrop-blur-md">
                        <i class="fa-solid fa-clock text-[#dca737] mb-3 text-lg"></i>
                        <p class="text-xs font-bold text-white/80">5 Minutes</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 border border-white/10 p-4 backdrop-blur-md">
                        <i class="fa-solid fa-shield text-[#dca737] mb-3 text-lg"></i>
                        <p class="text-xs font-bold text-white/80">Verified</p>
                    </div>
                </div>
            </div>

            {{-- OTP Verification Box --}}
            <div class="w-full max-w-md mx-auto">

                {{-- Mobile Logo --}}
                <div class="lg:hidden text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#dca737] to-[#b98218] rounded-2xl flex items-center justify-center text-white font-black text-2xl mx-auto mb-4 shadow-2xl shadow-[#dca737]/30">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h1 class="text-white font-extrabold text-2xl">REIAC</h1>
                    <p class="text-white/50 text-sm mt-1">OTP Verification</p>
                </div>

                <div class="bg-white/95 backdrop-blur-2xl rounded-2xl shadow-2xl shadow-black/30 border border-white/20 overflow-hidden">

                    {{-- Card Header --}}
                    <div class="relative px-8 pt-8 pb-7 bg-gradient-to-br from-white to-gray-50 border-b border-gray-100">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#dca737]/10 rounded-bl-full"></div>

                        <div class="relative flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#1a2f5e] to-[#081733] rounded-2xl flex items-center justify-center text-[#dca737] shadow-lg shadow-[#1a2f5e]/20">
                                <i class="fa-solid fa-key text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Verify OTP</h2>
                                <p class="text-sm text-gray-500 mt-0.5">Enter the code sent to your email</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-8">

                        @if(session('success'))
                        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-500 flex-shrink-0"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm flex items-center gap-3">
                            <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <p class="mb-1 last:mb-0">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <p class="text-sm text-blue-800 flex items-start gap-3">
                                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 flex-shrink-0"></i>
                                <span>Check your email for the 6-digit OTP. It will expire in 5 minutes.</span>
                            </p>
                        </div>

                        <form action="{{ route('admin.otp.verify') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-3">Enter 6-Digit OTP</label>
                                <input type="text"
                                    name="otp"
                                    value="{{ old('otp') }}"
                                    inputmode="numeric"
                                    maxlength="6"
                                    placeholder="000000"
                                    required
                                    class="w-full px-6 py-4 text-center text-4xl font-black tracking-[0.3em] bg-white border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-[#1a2f5e]/20 focus:border-[#1a2f5e] transition duration-200 shadow-sm"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                @error('otp')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                        <i class="fa-solid fa-exclamation-circle text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="relative w-full overflow-hidden bg-gradient-to-r from-[#1a2f5e] to-[#081733] text-white py-3.5 rounded-xl font-extrabold text-sm shadow-lg shadow-[#1a2f5e]/30 hover:shadow-xl hover:shadow-[#1a2f5e]/40 hover:-translate-y-0.5 active:translate-y-0 transition duration-200">
                                <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full hover:translate-x-full transition duration-700"></span>
                                <span class="relative flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-check-circle text-[#dca737]"></i>
                                    Verify OTP
                                </span>
                            </button>
                        </form>

                        <div class="mt-8 pt-6 border-t border-gray-200 space-y-3">
                            <form action="{{ route('admin.otp.resend') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-[#1a2f5e] bg-gray-100 hover:bg-gray-200 rounded-xl transition duration-200 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-redo text-xs"></i>
                                    Resend OTP
                                </button>
                            </form>

                            <a href="{{ route('admin.login') }}" class="block text-center text-xs font-semibold text-gray-600 hover:text-gray-900 transition duration-200 py-2">
                                <i class="fa-solid fa-arrow-left text-xs mr-1"></i>
                                Back to Login
                            </a>
                        </div>

                        <div class="mt-6 pt-5 border-t border-gray-200 flex items-center justify-center gap-2 text-xs text-gray-500">
                            <i class="fa-solid fa-shield text-[#dca737] text-xs"></i>
                            <span>Secure verification powered by REIAC</span>
                        </div>
                    </div>
                </div>

                <p class="text-center text-white/35 text-xs mt-6">
                    © {{ date('Y') }} REIAC. All rights reserved.
                </p>
            </div>
        </div>
    </main>

</body>

</html>
