@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')

@php
    $s = $setting ?? $siteSetting ?? null;

    $heroImg        = $s && $s->contact_hero_image ? asset('storage/'.$s->contact_hero_image) : asset('storage/contact-hero.png');
    $heroBadge      = $s->contact_hero_badge             ?? 'CONTACT US';
    $heroLine1      = $s->contact_hero_heading_line1      ?? "We'd Love to";
    $heroHighlight  = $s->contact_hero_heading_highlight  ?? 'Hear From You!';
    $heroSubtext    = $s->contact_hero_subtext            ?? 'Have questions about studying abroad? Our team is here to help you every step of the way.';

    $phone          = $s->contact_phone        ?? '+82 10-6552-8264';
    $hours          = $s->contact_hours        ?? 'Mon - Sun: 9:00 AM - 8:00 PM (KST)';
    $email          = $s->contact_email        ?? 'application.reiac@gmail.com';
    $addressEn      = $s->contact_address_en   ?? "3rd Floor Room No. 305,\n118 Sujeong-ro, Sujeong-gu,\nSeongnam-si, Gyeonggi-do";
    $addressKo      = $s->contact_address_ko   ?? '경기도 성남시 수정구 수정로 118. 3층 305호';

    $instagram      = $s->social_instagram ?? '#';
    $facebook       = $s->social_facebook  ?? '#';
    $linkedin       = $s->social_linkedin  ?? '#';
    $youtube        = $s->social_youtube   ?? '#';

    $mapEmbed       = $s->contact_map_embed ?? 'https://maps.google.com/maps?q=118%20Sujeong-ro,%20Sujeong-gu,%20Seongnam-si,%20Gyeonggi-do&t=&z=15&ie=UTF8&iwloc=&output=embed';
    $mapUrl         = $s->contact_map_url   ?? 'https://maps.google.com/?q=118+Sujeong-ro,+Sujeong-gu,+Seongnam-si,+Gyeonggi-do';
@endphp

{{-- ================= CONTACT HERO ================= --}}
<section class="relative overflow-hidden h-[620px]">
    <img src="{{ $heroImg }}" class="absolute inset-0 w-full h-full object-cover" alt="Contact Banner">
    <div class="absolute inset-0 bg-gradient-to-r from-[#061d43]/95 via-[#061d43]/85 to-[#061d43]/20"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 h-full flex items-center">
        <div class="max-w-xl text-white">
            <div class="text-xs text-white/70 mb-8">
                <a href="{{ route('home') }}" class="hover:text-[#dca737]">Home</a>
                <span class="mx-2">›</span>
                <span>Contact Us</span>
            </div>

            <p class="text-[#dca737] text-xs uppercase tracking-[0.25em] font-bold mb-4">{{ $heroBadge }}</p>

            <h1 class="text-4xl md:text-5xl font-black leading-tight mb-4">
                {{ $heroLine1 }} <br>
                <span class="text-[#dca737]">{{ $heroHighlight }}</span>
            </h1>

            <div class="w-12 h-1 bg-[#dca737] mb-6"></div>

            <p class="text-white/85 text-sm md:text-base leading-7 max-w-lg">{{ $heroSubtext }}</p>
        </div>
    </div>
</section>

{{-- ================= CONTACT FORM AREA ================= --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-12 gap-10">

            {{-- LEFT INFO --}}
            <div class="lg:col-span-4">
                <h2 class="text-3xl font-black text-[#102b5c] mb-3">Get in Touch</h2>
                <div class="w-10 h-1 bg-[#dca737] mb-6"></div>
                <p class="text-gray-500 text-sm leading-7 mb-8">
                    Reach out to us for personalized guidance and support on your study abroad journey.
                </p>

                <div class="space-y-6">

                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-[#102b5c] text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#dca737]">Phone</h4>
                            <p class="text-sm text-gray-700">{{ $phone }}</p>
                            <p class="text-xs text-gray-500">{{ $hours }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-[#102b5c] text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#dca737]">Email</h4>
                            <p class="text-sm text-gray-700">{{ $email }}</p>
                            <p class="text-xs text-gray-500">We reply within 24 hours</p>
                        </div>
                    </div>

                    <hr>

                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-[#102b5c] text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#dca737]">Location</h4>
                            <p class="text-sm text-gray-700 leading-6">{!! nl2br(e($addressEn)) !!}</p>
                            @if($addressKo)
                            <p class="text-xs text-gray-500 mt-1">{{ $addressKo }}</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-[#102b5c] text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#dca737] mb-3">Follow Us</h4>
                            <div class="flex gap-3">
                                @if($instagram && $instagram !== '#')
                                <a href="{{ $instagram }}" target="_blank" class="text-[#102b5c] hover:text-[#dca737] transition"><i class="fa-brands fa-instagram"></i></a>
                                @endif
                                @if($facebook && $facebook !== '#')
                                <a href="{{ $facebook }}" target="_blank" class="text-[#102b5c] hover:text-[#dca737] transition"><i class="fa-brands fa-facebook"></i></a>
                                @endif
                                @if($linkedin && $linkedin !== '#')
                                <a href="{{ $linkedin }}" target="_blank" class="text-[#102b5c] hover:text-[#dca737] transition"><i class="fa-brands fa-linkedin"></i></a>
                                @endif
                                @if($youtube && $youtube !== '#')
                                <a href="{{ $youtube }}" target="_blank" class="text-[#102b5c] hover:text-[#dca737] transition"><i class="fa-brands fa-youtube"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- RIGHT FORM --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xl p-6 md:p-8">
                    <h2 class="text-3xl font-black text-[#102b5c] mb-3">Send Us a Message</h2>
                    <div class="w-10 h-1 bg-[#dca737] mb-8"></div>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4">
                            <input type="text" name="name" placeholder="Your Name *" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#102b5c] outline-none">
                            <input type="email" name="email" placeholder="Your Email *" value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#102b5c] outline-none">
                        </div>
                        <input type="text" name="phone" placeholder="Phone Number" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#102b5c] outline-none">
                        <input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#102b5c] outline-none">
                        <textarea name="message" rows="6" placeholder="Your Message *"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#102b5c] outline-none">{{ old('message') }}</textarea>
                        <button type="submit"
                            class="w-full bg-[#dca737] hover:bg-yellow-600 text-white py-3 rounded-lg font-bold transition">
                            Send Message <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================= HELP SECTION ================= --}}
<section class="pb-10 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-lg p-8">
            <h2 class="text-center text-3xl font-black text-[#102b5c] mb-3">We're Here to Help</h2>
            <div class="w-10 h-1 bg-[#dca737] mx-auto mb-10"></div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['fa-headset','Expert Guidance','Get advice from experienced education counselors.'],
                    ['fa-file-signature','Fast Response','We respond to all inquiries within 24 hours.'],
                    ['fa-globe','Global Support','Assistance for students worldwide.'],
                    ['fa-handshake','End-to-End Help','From university selection to visa & beyond.']
                ] as $item)
                <div class="text-center px-4 lg:border-r lg:last:border-r-0 border-gray-200">
                    <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-[#102b5c] text-2xl mb-4">
                        <i class="fa-solid {{ $item[0] }}"></i>
                    </div>
                    <h4 class="font-bold text-[#102b5c] mb-2">{{ $item[1] }}</h4>
                    <p class="text-xs text-gray-500 leading-5">{{ $item[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ================= MAP SECTION ================= --}}
<section class="pb-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-12 rounded-2xl overflow-hidden shadow-xl border border-gray-100">

            <div class="lg:col-span-3 bg-[#061d43] p-8 text-white flex flex-col justify-center">
                <h3 class="text-3xl font-black mb-4 text-[#dca737]">Visit Our Office</h3>
                <p class="font-semibold text-sm leading-6 mb-6">{!! nl2br(e($addressEn)) !!}</p>
                <a href="{{ $mapUrl }}" target="_blank"
                    class="inline-flex items-center justify-center gap-2 border border-white/30 px-5 py-3 rounded-lg text-sm font-bold hover:bg-white hover:text-[#061d43] transition">
                    Get Directions <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="lg:col-span-9 h-[320px]">
                <iframe src="{{ $mapEmbed }}" width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"></iframe>
            </div>

        </div>
    </div>
</section>

@if(session('success'))
<div id="contactToast" style="position:fixed;top:24px;right:24px;z-index:99999;display:flex;align-items:flex-start;gap:14px;background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.15);padding:18px 22px;min-width:340px;max-width:420px;border-left:5px solid #22c55e;transform:translateX(120%);opacity:0;transition:transform 0.5s cubic-bezier(0.34,1.56,0.64,1),opacity 0.4s ease;">
    <div style="width:44px;height:44px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="11" stroke="#22c55e" stroke-width="2"/><path d="M7 12.5l3.5 3.5L17 9" stroke="#22c55e" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </div>
    <div style="flex:1;">
        <p style="font-size:14px;font-weight:800;color:#15803d;margin:0 0 3px;">Message Sent!</p>
        <p style="font-size:13px;color:#374151;margin:0;line-height:1.5;">{{ session('success') }}</p>
        <div style="margin-top:10px;height:3px;background:#dcfce7;border-radius:99px;overflow:hidden;">
            <div id="contactToastBar" style="height:100%;width:100%;background:#22c55e;border-radius:99px;transition:width 5s linear;"></div>
        </div>
    </div>
    <button onclick="closeContactToast()" style="background:none;border:none;cursor:pointer;color:#9ca3af;font-size:18px;">✕</button>
</div>
<script>
(function(){
    var t=document.getElementById('contactToast'),b=document.getElementById('contactToastBar');
    setTimeout(function(){t.style.transform='translateX(0)';t.style.opacity='1';setTimeout(function(){b.style.width='0%';},50);setTimeout(closeContactToast,5200);},300);
})();
function closeContactToast(){var t=document.getElementById('contactToast');if(!t)return;t.style.transform='translateX(120%)';t.style.opacity='0';setTimeout(function(){if(t.parentNode)t.parentNode.removeChild(t);},500);}
</script>
@endif

@endsection
