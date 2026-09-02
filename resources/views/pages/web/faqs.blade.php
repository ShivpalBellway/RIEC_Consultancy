@extends('layouts.app')

@section('title', 'FAQs - REIAC')

@section('content')
{{-- ===== FAQ HERO HEADER ===== --}}
<section class="relative bg-[#061d43] py-20 px-6 overflow-hidden">
    {{-- Background Image Overlay with low opacity --}}
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1800&q=80"
             alt="FAQ Header Background"
             class="w-full h-full object-cover">
    </div>

    <div class="relative max-w-4xl mx-auto text-center">
        <p class="text-[#dca737] text-xs font-extrabold uppercase tracking-[0.25em] mb-4">
            HAVE QUESTIONS?
        </p>
        <h1 class="text-white text-4xl md:text-5xl font-black mb-6 leading-tight">
            Frequently Asked <span class="text-[#dca737]">Questions</span>
        </h1>
        <p class="text-gray-200 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
            Find quick answers to common queries regarding study abroad admissions, visa processing, institutional collaborations, and customized career guidance.
        </p>
    </div>
</section>

{{-- ===== FAQ CONTENT ===== --}}
<section class="py-20 px-6 bg-gray-50">
    <div class="max-w-4xl mx-auto">

        @if($faqs->count() > 0)
            <div class="space-y-5">
                @foreach($faqs as $faq)
                    <details class="group bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 [&_summary::-webkit-details-marker]:hidden"
                             @if($loop->first) open @endif>
                        <summary class="flex justify-between items-center px-6 py-5 md:px-8 cursor-pointer select-none text-[#061d43] font-bold text-base md:text-lg hover:text-gold transition">
                            <span class="pr-4">{{ $faq->question }}</span>
                            <span class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0 transition duration-300 group-open:bg-gold/10 group-open:text-gold">
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-open:text-gold group-open:rotate-180 transition-transform duration-300"></i>
                            </span>
                        </summary>
                        <div class="px-6 pb-6 pt-3 md:px-8 md:pb-8 border-t border-gray-50 text-gray-600 leading-relaxed text-[15px] whitespace-pre-line">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </details>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white border border-gray-100 rounded-3xl shadow-sm">
                <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-circle-info text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-[#061d43] mb-2">No FAQs Available</h3>
                <p class="text-gray-500 max-w-sm mx-auto text-sm">
                    We are currently updating our FAQs. Please check back later or contact us directly.
                </p>
                <div class="mt-6">
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-opacity-95 transition shadow-sm">
                        Contact Us
                    </a>
                </div>
            </div>
        @endif

        {{-- Contact CTA Banner --}}
        <div class="mt-16 bg-gradient-to-br from-[#061d43] to-[#0d234a] rounded-3xl p-8 md:p-12 text-center text-white relative overflow-hidden shadow-xl">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-gold via-transparent to-transparent"></div>
            <div class="relative z-10">
                <h3 class="text-2xl font-bold mb-3">Still have questions?</h3>
                <p class="text-gray-300 text-sm max-w-md mx-auto mb-6 leading-relaxed">
                    Our team of experienced educational consultants is here to guide you every step of the way. Get in touch with us today.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('contact') }}"
                       class="bg-gold text-white hover:bg-yellow-600 px-6 py-3 rounded-xl font-bold text-sm transition">
                        Get Free Consultation
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
