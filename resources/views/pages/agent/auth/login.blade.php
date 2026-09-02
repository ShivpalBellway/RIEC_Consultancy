<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Login — REIAC Global</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a2f5e',
                        gold: '#dca737',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css">
</head>
<body class="bg-slate-900 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-slate-800 border border-slate-700/60 rounded-2xl shadow-2xl overflow-hidden">
        <!-- Top Banner -->
        <div class="bg-gradient-to-r from-primary to-slate-900 p-8 text-center border-b border-slate-700/60 relative">
            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-gold flex items-center justify-center text-slate-900 font-black text-2xl shadow-lg shadow-gold/20">
                R
            </div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Agent Portal Login</h2>
            <p class="text-sm text-slate-300 mt-1">REIAC Global Agency Partner Network</p>
        </div>

        <div class="p-8">
            <!-- Flashes -->
            @if(session('success'))
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm flex items-center gap-2.5">
                <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm space-y-1">
                @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                    <span>{{ $error }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <form action="{{ route('agent.login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="agent@agency.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                        <input type="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-700 text-gold focus:ring-0">
                        <span>Remember Me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-gold hover:bg-amber-500 text-slate-900 font-bold rounded-xl transition-all shadow-lg shadow-gold/20 flex items-center justify-center gap-2 text-sm">
                    <span>Sign In to Agent Portal</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-700/60 text-center">
                <p class="text-xs text-slate-400">
                    Don't have an Agent account?
                    <a href="{{ route('agent.register') }}" class="text-gold hover:underline font-semibold ml-1">Register Agency Account</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>
