<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — REIAC</title>

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
                    <span class="text-xs font-bold uppercase tracking-[0.22em] text-white/70">Secure Admin Access</span>
                </div>

                <h1 class="text-5xl font-black leading-tight tracking-tight">
                    Manage REIAC <br>
                    with <span class="text-[#dca737]">confidence.</span>
                </h1>

                <p class="mt-5 text-white/60 text-sm leading-7 max-w-md">
                    Premium control panel for programs, eligibility, applications and student management.
                </p>

                <div class="mt-8 grid grid-cols-3 gap-4 max-w-md">
                    <div class="rounded-2xl bg-white/10 border border-white/10 p-4 backdrop-blur-md">
                        <i class="fa-solid fa-shield-halved text-[#dca737] mb-3"></i>
                        <p class="text-xs font-bold text-white/80">Secure</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 border border-white/10 p-4 backdrop-blur-md">
                        <i class="fa-solid fa-gauge-high text-[#dca737] mb-3"></i>
                        <p class="text-xs font-bold text-white/80">Fast</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 border border-white/10 p-4 backdrop-blur-md">
                        <i class="fa-solid fa-layer-group text-[#dca737] mb-3"></i>
                        <p class="text-xs font-bold text-white/80">Organized</p>
                    </div>
                </div>
            </div>

            {{-- Login Box --}}
            <div class="w-full max-w-md mx-auto">

                {{-- Mobile Logo --}}
                <div class="lg:hidden text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#dca737] to-[#b98218] rounded-2xl flex items-center justify-center text-white font-black text-2xl mx-auto mb-4 shadow-2xl shadow-[#dca737]/30">
                        R
                    </div>
                    <h1 class="text-white font-extrabold text-2xl">REIAC</h1>
                    <p class="text-white/50 text-sm mt-1">Admin Panel</p>
                </div>

                <div class="bg-white/95 backdrop-blur-2xl rounded-[28px] shadow-2xl shadow-black/30 border border-white/20 overflow-hidden">

                    {{-- Card Header --}}
                    <div class="relative px-8 pt-8 pb-6 bg-gradient-to-br from-white to-gray-50">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#dca737]/10 rounded-bl-full"></div>

                        <div class="relative flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#1a2f5e] to-[#081733] rounded-2xl flex items-center justify-center text-[#dca737] shadow-lg shadow-[#1a2f5e]/20">
                                <i class="fa-solid fa-user-shield text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Welcome Back</h2>
                                <p class="text-sm text-gray-500 mt-0.5">Login to continue dashboard access</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 pb-8">

                        @if(session('success'))
                        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-4 py-3 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            {{ session('success') }}
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl px-4 py-3 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                            {{ $errors->first() }}
                        </div>
                        @endif

                        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                <div class="relative group">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#1a2f5e] transition">
                                        <i class="fa-solid fa-envelope text-sm"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        placeholder="admin@reiac.com"
                                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1a2f5e]/10 focus:border-[#1a2f5e] transition">
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-bold text-gray-700">Password</label>
                                    <span class="text-xs font-semibold text-gray-400">Protected</span>
                                </div>

                                <div class="relative group">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#1a2f5e] transition">
                                        <i class="fa-solid fa-lock text-sm"></i>
                                    </span>

                                    <input id="password" type="password" name="password" required
                                        placeholder="Enter your password"
                                        class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#1a2f5e]/10 focus:border-[#1a2f5e] transition">

                                    <button type="button" onclick="togglePassword()"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#1a2f5e] transition">
                                        <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit"
                                class="relative w-full overflow-hidden bg-gradient-to-r from-[#1a2f5e] to-[#081733] text-white py-3.5 rounded-2xl font-extrabold text-sm shadow-xl shadow-[#1a2f5e]/25 hover:shadow-2xl hover:shadow-[#1a2f5e]/35 hover:-translate-y-0.5 active:translate-y-0 transition">
                                <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full hover:translate-x-full transition duration-700"></span>
                                <span class="relative">
                                    <i class="fa-solid fa-right-to-bracket mr-2 text-[#dca737]"></i>
                                    Sign In to Admin Panel
                                </span>
                            </button>
                        </form>

                        <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-center gap-2 text-xs text-gray-400">
                            <i class="fa-solid fa-lock text-[#dca737]"></i>
                            <span>Secure login powered by REIAC</span>
                        </div>
                    </div>
                </div>

                <p class="text-center text-white/35 text-xs mt-6">
                    © {{ date('Y') }} REIAC. All rights reserved.
                </p>
            </div>
        </div>
    </main>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>
