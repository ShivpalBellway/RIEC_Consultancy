@extends('layouts.app')

@section('title', 'Application Submitted Successfully — REIAC ')

@section('content')
<div class="py-16 px-6 bg-gray-50 min-h-[600px] flex items-center justify-center">
    <div class="max-w-xl w-full text-center">

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden p-8 md:p-12 space-y-6">

            {{-- Green Check Circle Animation/Icon --}}
            <div class="w-20 h-20 bg-emerald-50 border border-emerald-100 rounded-full flex items-center justify-center mx-auto text-emerald-500 text-4xl shadow-md">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div class="space-y-2">
                <h1 class="text-2xl md:text-3xl font-serif font-bold text-primary">Application Submitted!</h1>
                <p class="text-sm text-gray-500">Thank you for choosing REIAC as your study abroad partner.</p>
            </div>

            {{-- Application Details Summary Card --}}
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-150 text-left space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Application Details</span>
                    <span class="bg-[#c89b2a]/20 text-[#c89b2a] text-xs font-black px-2.5 py-1 rounded-lg">
                        ID: APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-2 text-xs">
                    <div class="text-gray-400 font-medium">Program:</div>
                    <div class="col-span-2 text-gray-800 font-bold">{{ $program->name }}</div>

                    <div class="text-gray-400 font-medium">Country:</div>
                    <div class="col-span-2 text-gray-800 font-bold">{{ $program->country }}</div>

                    <div class="text-gray-400 font-medium">Applicant:</div>
                    <div class="col-span-2 text-gray-800 font-semibold">{{ $application->name }}</div>

                    <div class="text-gray-400 font-medium">Email:</div>
                    <div class="col-span-2 text-gray-800 font-mono">{{ $application->email }}</div>

                    <div class="text-gray-400 font-medium">Phone:</div>
                    <div class="col-span-2 text-gray-800 font-mono">{{ $application->phone }}</div>
                </div>
            </div>

            {{-- Info box --}}
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 text-left text-xs leading-relaxed text-blue-800 flex gap-3">
                <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5"></i>
                <div>
                    <span class="font-bold block mb-1">What happens next?</span>
                    Our academic counselors will evaluate your eligibility score and form data. We will contact you via email or phone within 24-48 business hours with the next steps for your university application.
                </div>
            </div>

            <div class="pt-4 flex gap-4">
                <a href="{{ route('home') }}"
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3.5 px-6 rounded-xl transition text-sm">
                    Back to Home
                </a>
                <a href="{{ route('apply.index') }}"
                   class="flex-1 bg-primary hover:bg-gold text-white font-bold py-3.5 px-6 rounded-xl transition text-sm shadow-md">
                    Apply for Another Program
                </a>
            </div>

            <a href="{{ route('student.dashboard') }}"
               class="inline-flex items-center justify-center gap-2 text-primary font-bold text-sm hover:text-gold transition">
                View My Applications
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>

        </div>

    </div>
</div>
@endsection
