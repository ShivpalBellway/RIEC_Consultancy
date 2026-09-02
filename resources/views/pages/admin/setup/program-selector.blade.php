@extends('layouts.admin')

@section('title', $title)
@section('page-title', $title)

@section('breadcrumb')
    <span class="text-gray-300">/</span>
    <span class="text-gray-500">Application Setup</span>
    <span class="text-gray-300">/</span>
    <span class="text-gray-500">{{ $title }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.programs.create') }}"
       class="bg-[#1a2f5e] text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-[#142447] transition flex items-center gap-2 shadow">
        <i class="fa-solid fa-plus"></i> Add Program
    </a>
@endsection

@section('content')
@php
    $toneClasses = [
        'amber' => [
            'icon' => 'bg-blue-50 text-blue-600',
            'button' => 'bg-blue-500 hover:bg-blue-600',
            'soft' => 'bg-blue-50 text-blue-700',
        ],
        'purple' => [
            'icon' => 'bg-purple-50 text-purple-600',
            'button' => 'bg-purple-600 hover:bg-purple-700',
            'soft' => 'bg-purple-50 text-purple-700',
        ],
    ][$tone] ?? [
        'icon' => 'bg-blue-50 text-blue-600',
        'button' => 'bg-[#1a2f5e] hover:bg-[#142447]',
        'soft' => 'bg-blue-50 text-blue-700',
    ];
@endphp

<div class="mb-6 rounded-2xl bg-white border border-gray-100 shadow-sm p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl {{ $toneClasses['icon'] }} flex items-center justify-center text-2xl">
                <i class="fa-solid {{ $icon }}"></i>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900">{{ $title }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $description }}</p>
            </div>
        </div>
        <span class="inline-flex w-fit items-center rounded-full {{ $toneClasses['soft'] }} px-4 py-2 text-xs font-extrabold">
            {{ $programs->count() }} Programs
        </span>
    </div>
</div>

@if($programs->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-20 text-center text-gray-400">
        <i class="fa-solid fa-graduation-cap text-5xl mb-4 block text-gray-200"></i>
        <p class="font-semibold text-gray-500">No programs found</p>
        <p class="text-sm mt-1">Create a program first, then setup its fields.</p>
        <a href="{{ route('admin.programs.create') }}"
           class="mt-4 inline-flex items-center gap-2 bg-[#1a2f5e] text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-[#142447] transition">
            <i class="fa-solid fa-plus"></i> Add Program
        </a>
    </div>
@else
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($programs as $program)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#1a2f5e]/10 flex items-center justify-center overflow-hidden shrink-0">
                        @if($program->image)
                            <img src="{{ $program->image_url }}" alt="" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-graduation-cap text-[#1a2f5e]"></i>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-extrabold text-gray-900 truncate">{{ $program->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $program->country }} · {{ $program->program_type_label }}</p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3 text-xs">
                    <div class="rounded-xl bg-gray-50 p-3">
                        <span class="block text-gray-400 font-semibold">Eligibility Fields</span>
                        <b class="text-gray-900 text-lg">{{ $program->eligibilityFields()->count() }}</b>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <span class="block text-gray-400 font-semibold">Form Sections</span>
                        <b class="text-gray-900 text-lg">{{ $program->formSections()->count() }}</b>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-between gap-3">
                    <span class="text-xs px-3 py-1 rounded-full font-semibold {{ $program->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $program->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <a href="{{ route($targetRoute, $program) }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl {{ $toneClasses['button'] }} px-4 py-2.5 text-xs font-extrabold text-white transition shadow-sm">
                        Open Setup
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
