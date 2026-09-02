@extends('layouts.admin')

@section('title', 'Information Form Builder')
@section('page-title', 'Information Form Builder')

@section('breadcrumb')
    <span class="text-gray-300">/</span>
    <a href="{{ route('admin.programs.index') }}" class="hover:text-primary">Programs</a>
    <span class="text-gray-300">/</span>
    <span class="text-gray-500">{{ $program->name }} — Information Form Builder</span>
@endsection

@section('content')

@php
    $navy = '#061d43';
    $gold = '#dca737';
@endphp

<div class="space-y-6">

    {{-- Program Info --}}
    <div class="relative overflow-hidden bg-white border border-[#dca737]/40 rounded-2xl shadow-sm">
        <div class="absolute right-0 top-0 w-48 h-full bg-gradient-to-l from-[#dca737]/20 to-transparent"></div>

        <div class="relative px-5 py-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#1A2F5E] flex items-center justify-center shadow">
                <i class="fa-solid fa-graduation-cap text-white text-xl"></i>
            </div>

            <div>
                <h3 class="text-sm font-extrabold text-[#061d43]">
                    {{ $program->name }} — {{ $program->country }}
                </h3>
                <p class="text-xs text-[#dca737] font-bold mt-1">
                    {{ $sections->count() }} section(s) · {{ $sections->sum(fn($s) => $s->fields->count()) }} field(s)
                </p>
            </div>

            <div class="ml-auto flex gap-2">
                <a href="{{ route('admin.programs.eligibility.index', $program) }}"
                   class="px-4 py-2 rounded-xl border border-[#dca737]/50 text-[#061d43] bg-[#dca737]/10 hover:bg-[#dca737] hover:text-white text-xs font-bold transition">
                    <i class="fa-solid fa-list-check mr-1"></i> Eligibility
                </a>

                <a href="{{ route('admin.programs.edit', $program) }}"
                   class="px-4 py-2 rounded-xl bg-[#1A2F5E] text-white hover:bg-[#0b2b5a] text-xs font-bold transition">
                    <i class="fa-solid fa-pen mr-1"></i> Edit Program
                </a>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-12 gap-6">

        {{-- Add Section --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-2xl shadow-xl shadow-[#061d43]/5 border border-gray-100 overflow-hidden sticky top-6">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-extrabold text-[#061d43] text-base">Add New Section</h3>
                    <p class="text-xs text-gray-400 mt-1">Group related fields together</p>
                    <div class="w-12 h-1 bg-[#dca737] rounded-full mt-3"></div>
                </div>

                <form action="{{ route('admin.programs.form-builder.sections.store', $program) }}" method="POST" class="p-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-[#061d43] mb-1.5">
                            Section Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required placeholder="e.g. Personal Information"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#dca737]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#061d43] mb-1.5">Description</label>
                        <input type="text" name="description" placeholder="Optional description"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#dca737]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#061d43] mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ $sections->count() + 1 }}" min="0"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#dca737]">
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_active" class="sr-only peer" checked>
                            <div class="w-10 h-5 bg-gray-200 rounded-full peer-checked:bg-[#dca737] transition"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                        </div>
                        <span class="text-xs text-[#061d43] font-bold">Active</span>
                    </label>

                    <button type="submit"
                            class="w-full bg-[#1A2F5E] text-white text-sm font-extrabold py-3 rounded-xl hover:bg-[#0b2b5a] transition shadow">
                        <i class="fa-solid fa-plus mr-1"></i> Add Section
                    </button>
                </form>
            </div>
        </div>

        {{-- Sections --}}
        <div class="lg:col-span-8 space-y-4">
            @forelse($sections as $index => $section)
                <div x-data="{ open: {{ $index == 0 ? 'true' : 'false' }} }"
                     class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                    {{-- Header --}}
                    <div class="px-5 py-4 flex items-center justify-between border-l-4 border-[#dca737] bg-gradient-to-r from-white to-[#dca737]/5">
                        <button type="button" @click="open = !open" class="flex items-center gap-3 flex-1 text-left">
                            <div class="w-10 h-10 rounded-xl bg-[#1A2F5E] flex items-center justify-center">
                                <i class="fa-solid fa-layer-group text-white text-sm"></i>
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-extrabold text-[#061d43] text-sm">{{ $section->name }}</h3>
                                    <span class="text-[11px] px-2 py-0.5 rounded-full  text-green-600 bg-green-100 font-bold">
                                        {{ $section->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                @if($section->description)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $section->description }}</p>
                                @endif
                            </div>
                        </button>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.programs.form-builder.fields.create', [$program, $section]) }}"
                               class="bg-[#1A2F5E] text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-[#0b2b5a] transition">
                                <i class="fa-solid fa-plus mr-1"></i> Add Field
                            </a>

                            <form action="{{ route('admin.programs.form-builder.sections.destroy', [$program, $section]) }}" method="POST"
                                  onsubmit="return confirm('Delete this section and all its fields?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>

                            <button type="button" @click="open = !open"
                                    class="w-9 h-9 rounded-lg border border-[#dca737]/40 text-[#dca737] hover:bg-[#dca737]/10 transition">
                                <i class="fa-solid fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div x-show="open" x-collapse>
                        @if($section->fields->isEmpty())
                            <div class="px-5 py-10 text-center">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-plus text-gray-300"></i>
                                </div>
                                <p class="text-xs text-gray-400">
                                    No fields yet.
                                    <a href="{{ route('admin.programs.form-builder.fields.create', [$program, $section]) }}"
                                       class="text-[#dca737] font-bold">Add first field →</a>
                                </p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-[#1A2F5E] text-white text-xs">
                                            <th class="px-5 py-3 text-left">#</th>
                                            <th class="px-5 py-3 text-left">Field Label</th>
                                            <th class="px-5 py-3 text-left">Field Type</th>
                                            <th class="px-5 py-3 text-left">Required</th>
                                            <th class="px-5 py-3 text-right">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($section->fields as $field)
                                            <tr class="hover:bg-[#dca737]/5 transition {{ !$field->is_active ? 'opacity-50' : '' }}">
                                                <td class="px-5 py-4 text-xs text-gray-400 font-bold">
                                                    {{ $field->sort_order }}
                                                </td>

                                                <td class="px-5 py-4">
                                                    <div class="font-bold text-[#061d43] text-sm">{{ $field->label }}</div>
                                                    @if($field->placeholder)
                                                        <div class="text-xs text-gray-400 italic mt-0.5">{{ $field->placeholder }}</div>
                                                    @endif
                                                </td>

                                                <td class="px-5 py-4">
                                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-[#061d43]">
                                                        <i class="fa-solid fa-tag text-[#dca737]"></i>
                                                        {{ ucfirst($field->field_type) }}
                                                    </span>
                                                </td>

                                                <td class="px-5 py-4">
                                                    @if($field->is_required)
                                                        <span class="px-3 py-1 rounded-lg bg-red-100 text-red-600 border border-red-200 text-xs font-bold">
                                                            Required
                                                        </span>
                                                    @else
                                                        <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-500 text-xs font-bold">
                                                            Optional
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="px-5 py-4">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <a href="{{ route('admin.programs.form-builder.fields.edit', [$program, $section, $field]) }}"
                                                           class="w-8 h-8 rounded-lg border border-[#061d43]/20 text-[#061d43] hover:bg-[#1A2F5E] hover:text-white flex items-center justify-center transition">
                                                            <i class="fa-solid fa-pen text-xs"></i>
                                                        </a>

                                                        <form action="{{ route('admin.programs.form-builder.fields.destroy', [$program, $section, $field]) }}" method="POST"
                                                              onsubmit="return confirm('Delete this field?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                    class="w-8 h-8 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 flex items-center justify-center transition">
                                                                <i class="fa-solid fa-trash text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-20 text-center text-gray-400">
                    <i class="fa-solid fa-table-list text-5xl mb-3 block text-gray-200"></i>
                    <p class="font-bold text-gray-500">No sections yet</p>
                    <p class="text-sm mt-1">Use the form on the left to add your first section.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
