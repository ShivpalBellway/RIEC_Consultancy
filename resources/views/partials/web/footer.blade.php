{{-- CTA Banner --}}
<!-- <section class="bg-primary text-white py-12 px-4">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold"><span class="text-gold">Ready</span> to Take the Next Step?</h2>
            <p class="text-gray-300 mt-1 text-sm">Let REIAC  help you achieve your global education goals.</p>
        </div>
        <div class="flex gap-4">
            <a href="#" class="bg-gold text-white px-6 py-3 rounded font-semibold text-sm hover:bg-yellow-600 transition">Get Free Consultation →</a>
            <a href="#" class="border border-white text-white px-6 py-3 rounded font-semibold text-sm hover:bg-white hover:text-primary transition">Contact Us →</a>
        </div>
    </div>
</section> -->

@if(!request()->routeIs('institution'))
{{-- ===== CTA SECTION ===== --}}
<section class="relative bg-primary py-14 px-6 overflow-hidden">
    {{-- Background Image Overlay --}}
    <div class="absolute inset-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=80"
            alt=""
            class="w-full h-full object-cover">
    </div>

    <div class="relative max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-8">

        {{-- Left Content --}}
        <div class="text-center lg:text-left">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-5">
                <span class="text-gold">Ready</span> to Take the Next Step?
            </h2>

            <p class="text-gray-200 text-base leading-7 max-w-md">
                Let REIAC help you achieve your global education goals.
            </p>
        </div>

        {{-- Buttons --}}
        <div class="flex flex-col sm:flex-row gap-6">
            <a href="{{ route('apply.index') }}"
                class="bg-gold text-white px-10 py-4 rounded-md font-bold shadow hover:opacity-90 transition">
                Get Free Consultation →
            </a>

            <a href="{{ route('contact') }}"
                class="border-2 border-white/40 text-white px-10 py-4 rounded-md font-bold hover:bg-white hover:text-primary transition">
                Contact Us →
            </a>
        </div>

    </div>
</section>
@endif

{{-- Footer --}}
<footer class="bg-gray-900 text-gray-400 text-sm py-12 px-4">
    <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-5 gap-8">

        {{-- Brand --}}
        <div class="col-span-2 md:col-span-1">
            <div class="flex items-center gap-2 mb-3">
                <a href="/" class="flex items-center">

                        @if($siteSetting && $siteSetting->footer_logo)
                        <img
                            src="{{ asset('storage/' . $siteSetting->footer_logo) }}"
                            alt="REIAC Logo"
                            class="h-14 w-[150px] object-contain">
                        @else
                        <img
                            src="{{ asset('storage/logo/logo-gold.png') }}"
                            alt="REIAC Logo"
                            class="h-14 w-[150px] object-contain">
                        @endif

                    </a>
                </a>
            </div>
            <p class="text-xs leading-relaxed">REIAC is a trusted education consultancy connecting students and institutions to global opportunities.</p>
            <div class="flex gap-3 mt-4">
                <a href="{{ $siteSetting->social_instagram ?? '#' }}" {{ $siteSetting && $siteSetting->social_instagram ? 'target=_blank' : '' }} class="hover:text-gold"><i class="fa-brands fa-instagram"></i></a>
                <a href="{{ $siteSetting->social_facebook ?? '#' }}" {{ $siteSetting && $siteSetting->social_facebook ? 'target=_blank' : '' }} class="hover:text-gold"><i class="fa-brands fa-facebook"></i></a>
                <a href="{{ $siteSetting->social_linkedin ?? '#' }}" {{ $siteSetting && $siteSetting->social_linkedin ? 'target=_blank' : '' }} class="hover:text-gold"><i class="fa-brands fa-linkedin"></i></a>
            </div>
        </div>

        {{-- Quick Links --}}
        <div>
            <h4 class="text-white font-semibold mb-3">Quick Links</h4>
            <ul class="space-y-2">
                <li><a href="{{route('home')}}" class="hover:text-gold">Home</a></li>
                <li><a href="{{route('aboutUs')}}" class="hover:text-gold">About Us</a></li>
                <li><a href="{{ route('apply.index') }}" class="hover:text-gold">For Students</a></li>
                <li><a href="{{route('contact')}}" class="hover:text-gold">Contact Us</a></li>
            </ul>
        </div>

        {{-- Services --}}
        <div>
            <h4 class="text-white font-semibold mb-3">Services</h4>
            <ul class="space-y-2">
                @foreach($headerServices as $service)
                    <li><a href="{{ route('services.show', $service->slug) }}" class="hover:text-gold">{{ $service->title }}</a></li>
                @endforeach
            </ul>
        </div>

        {{-- Resources --}}
        <div>
            <h4 class="text-white font-semibold mb-3">Resources</h4>
            <ul class="space-y-2">
                <li><a href="{{ route('blogs.index') }}" class="hover:text-gold">Blogs</a></li>
                <li><a href="{{ route('faqs.index') }}" class="hover:text-gold">FAQs</a></li>
                {{-- <li><a href="#" class="hover:text-gold">Guides & Tips</a></li>
                <li><a href="#" class="hover:text-gold">Scholarships</a></li> --}}

            </ul>
        </div>

        {{-- Contact --}}
        <div>
            <h4 class="text-white font-semibold mb-3">Contact Us</h4>
            <ul class="space-y-3 text-xs text-gray-400 leading-relaxed">

                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-phone text-gold mt-1 w-4"></i>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSetting->contact_phone ?? '+821065528264') }}" class="hover:text-gold transition">
                        {{ $siteSetting->contact_phone ?? '+82 10-6552-8264' }}
                    </a>
                </li>

                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-envelope text-gold mt-1 w-4"></i>
                    <a href="mailto:{{ $siteSetting->contact_email ?? 'application.reiac@gmail.com' }}" class="hover:text-gold transition break-all">
                        {{ $siteSetting->contact_email ?? 'application.reiac@gmail.com' }}
                    </a>
                </li>

                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-location-dot text-gold mt-1 w-4"></i>
                    <span>{!! nl2br(e($siteSetting->contact_address_en ?? "3rd Floor, Room No. 305,\n118 Sujeong-ro, Sujeong-gu,\nSeongnam-si, Gyeonggi-do")) !!}</span>
                </li>

                <li class="flex items-start gap-2">
                    <i class="fa-regular fa-clock text-gold mt-1 w-4"></i>
                    <span>{{ $siteSetting->contact_hours ?? 'Mon - Sun: 9:00 AM - 8:00 PM (KST)' }}</span>
                </li>

            </ul>
        </div>
    </div>

    <!-- <div class="max-w-7xl mx-auto border-t border-gray-700 mt-8 pt-6 flex flex-col md:flex-row justify-between text-xs">
        <span>© 2024 REIAC . All Rights Reserved.</span>
        <span>Developed by :- <a href="https://bellwayinfotech.com/" target="_blank" class="hover:text-gold">Bellway Infotech</a></span>
        <div class="flex gap-4 mt-2 md:mt-0">
            <a href="#" class="hover:text-gold">Privacy Policy</a>
            <a href="#" class="hover:text-gold">Terms & Conditions</a>
        </div>
    </div> -->
    <div class="max-w-7xl mx-auto border-t border-gray-700/60 mt-8 pt-5 flex flex-col md:flex-row items-center justify-between gap-3 text-xs">

        <span class="text-gray-400">
            © 2024 REIAC . All Rights Reserved.
        </span>

        <div class="relative snake-border rounded-full p-[1.5px] inline-block">
            <a href="https://bellwayinfotech.com/"
                target="_blank"
                class="group relative inline-flex items-center gap-2 rounded-full bg-[#08152f] px-3 py-1.5 backdrop-blur-sm transition-all duration-300 hover:bg-[#0d1b3d]">

                {{-- Gold Dot --}}
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full rounded-full bg-[#dca737] opacity-70 animate-ping"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-[#dca737]"></span>
                </span>

                <span class="text-gray-400 text-[11px]">
                    Developed by
                </span>

                <span class="font-bold bg-gradient-to-r from-[#f6d57b] to-[#dca737] bg-clip-text text-transparent">
                    Bellway Infotech
                </span>
            </a>
        </div>
        <style>
            @property --angle {
                syntax: "<angle>";
                initial-value: 0deg;
                inherits: false;
            }

            .snake-border {
                position: relative;
                overflow: hidden;
            }

            .snake-border::before {
                content: "";
                position: absolute;
                inset: -50%;
                background: conic-gradient(from var(--angle),
                        transparent 0%,
                        transparent 78%,
                        #dca737 84%,
                        #22c55e 90%,
                        #ef4444 96%,
                        transparent 100%);
                animation: snakeSpin 2.5s linear infinite;
            }

            .snake-border::after {
                content: "";
                position: absolute;
                inset: 1.5px;
                background: #08152f;
                /* footer bg color */
                border-radius: 9999px;
                z-index: 1;
            }

            .snake-border>a {
                position: relative;
                z-index: 2;
            }

            @keyframes snakeSpin {
                from {
                    --angle: 0deg;
                }

                to {
                    --angle: 360deg;
                }
            }
        </style>

        <div class="flex gap-4 text-gray-400">
            <a href="{{ route('privacy-policy') }}" class="hover:text-[#dca737] transition">Privacy Policy</a>
            <a href="{{ route('terms-conditions') }}" class="hover:text-[#dca737] transition">Terms & Conditions</a>
        </div>

    </div>

</footer>
