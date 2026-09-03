<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Registration — REIAC Global</title>
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

    <div class="w-full max-w-xl bg-slate-800 border border-slate-700/60 rounded-2xl shadow-2xl overflow-hidden my-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary to-slate-900 p-8 text-center border-b border-slate-700/60">
            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-gold flex items-center justify-center text-slate-900 font-black text-2xl shadow-lg shadow-gold/20">
                R
            </div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Register New Agency Account</h2>
            <p class="text-xs text-slate-300 mt-1">Fill in details to apply for REIAC Global Agency Partnership</p>
        </div>

        <div class="p-8">
            <!-- Notice Banner -->
            <div class="mb-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-amber-400 text-base mt-0.5"></i>
                <div>
                    <span class="font-bold block">Admin Approval Required:</span>
                    Your registration will be submitted to the REIAC Global Admin for approval. You will receive access once approved.
                </div>
            </div>

            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs space-y-1">
                @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                    <span>{{ $error }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <form action="{{ route('agent.register.post') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Agent / Contact Person Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="John Doe">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Agency / Company Name *</label>
                        <input type="text" name="agency_name" value="{{ old('agency_name') }}" required
                            class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="Global Education Consultancy">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="agent@agency.com">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Contact Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="+82 10-1234-5678">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Country</label>
                        <input type="text" name="country" value="{{ old('country') }}"
                            class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="South Korea">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Agency Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="Seoul, South Korea">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Password *</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Confirm Password *</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-gold transition-colors placeholder-slate-500"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-700/60 space-y-3">
                    <h3 class="text-sm font-bold text-white"><i class="fa-solid fa-shield-halved text-gold mr-2"></i>Applicant Consent</h3>
                    <p class="text-xs text-slate-400">Please read and agree before submitting your agency registration.</p>
                    <label class="flex items-start gap-3 p-3 rounded-xl bg-gold/10 border border-gold/30 cursor-pointer">
                        <input type="checkbox" id="agentAgreeAll" class="mt-0.5 h-4 w-4" >
                        <span class="text-xs text-gold font-bold">Agree to All</span>
                    </label>
                    <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-900/60 border border-slate-700 cursor-pointer">
                        <input type="checkbox" name="consent_collection" value="1" required class="agent-consent mt-0.5 h-4 w-4">
                        <span class="text-xs text-slate-200"><strong class="text-rose-400">[Required]</strong> Consent to the Collection and Processing of Personal Information <button type="button" class="agent-details text-gold font-bold ml-1">[View Details]</button><small class="agent-detail-text hidden block mt-2 text-slate-400">Your details may be collected and used for agency verification, partnership management, student application support, and related consultancy services.</small></span>
                    </label>
                    <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-900/60 border border-slate-700 cursor-pointer">
                        <input type="checkbox" name="consent_third_party" value="1" required class="agent-consent mt-0.5 h-4 w-4">
                        <span class="text-xs text-slate-200"><strong class="text-rose-400">[Required]</strong> Consent to the Provision of Personal Information to Third Parties <button type="button" class="agent-details text-gold font-bold ml-1">[View Details]</button><small class="agent-detail-text hidden block mt-2 text-slate-400">Information may be shared where necessary with partner institutions, authorities, and service providers for legitimate application and partnership processing.</small></span>
                    </label>
                    <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-900/40 border border-slate-700 cursor-pointer">
                        <input type="checkbox" name="consent_email_updates" value="1" class="agent-consent mt-0.5 h-4 w-4">
                        <span class="text-xs text-slate-300"><strong class="text-slate-500">[Optional]</strong> Consent to Receive Application and Partnership Updates by Email</span>
                    </label>
                    <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-900/40 border border-slate-700 cursor-pointer">
                        <input type="checkbox" name="consent_marketing" value="1" class="agent-consent mt-0.5 h-4 w-4">
                        <span class="text-xs text-slate-300"><strong class="text-slate-500">[Optional]</strong> Consent to Receive Marketing and Promotional Information</span>
                    </label>
                </div>

                <button type="submit" class="w-full mt-4 py-3 bg-gold hover:bg-amber-500 text-slate-900 font-bold rounded-xl transition-all shadow-lg shadow-gold/20 flex items-center justify-center gap-2 text-sm">
                    <span>Submit Agency Registration</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-700/60 text-center">
                <p class="text-xs text-slate-400">
                    Already registered?
                    <a href="{{ route('agent.login') }}" class="text-gold hover:underline font-semibold ml-1">Log in to Agent Portal</a>
                </p>
            </div>
        </div>
    </div>

</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const agreeAll = document.getElementById('agentAgreeAll');
    const consents = document.querySelectorAll('.agent-consent');
    agreeAll.addEventListener('change', () => consents.forEach((checkbox) => checkbox.checked = agreeAll.checked));
    consents.forEach((checkbox) => checkbox.addEventListener('change', () => {
        agreeAll.checked = Array.from(consents).every((item) => item.checked);
    }));
    document.querySelectorAll('.agent-details').forEach((button) => button.addEventListener('click', (event) => {
        event.preventDefault();
        const detail = button.parentElement.querySelector('.agent-detail-text');
        detail.classList.toggle('hidden');
        button.textContent = detail.classList.contains('hidden') ? '[View Details]' : '[Hide Details]';
    }));
});
</script>
</html>
