@extends('layouts.admin')

@section('title', 'Eligibility Criteria')
@section('page-title', 'Eligibility Criteria')

@section('breadcrumb')
    <span class="text-gray-300">/</span>
    <a href="{{ route('admin.programs.index') }}" class="hover:text-primary">Programs</a>
    <span class="text-gray-300">/</span>
    <span class="text-gray-500">{{ $program->name }} — Eligibility</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.programs.eligibility.create', $program) }}"
       class="bg-[#1a2f5e] text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-[#142447] transition flex items-center gap-2 shadow">
        <i class="fa-solid fa-plus"></i> Add Field
    </a>
@endsection

@section('content')

{{-- Program Info Bar --}}
<div class="bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 mb-6 flex items-center gap-4">
    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
        <i class="fa-solid fa-user-graduate text-amber-600"></i>
    </div>
    <div>
        <p class="text-sm font-bold text-amber-900">{{ $program->name }} — {{ $program->country }}</p>
        <p class="text-xs text-amber-700 mt-0.5">{{ $fields->count() }} eligibility field(s) defined</p>
    </div>
    <div class="ml-auto flex gap-2">
        <a href="{{ route('admin.programs.form-builder.index', $program) }}"
           class="text-xs bg-purple-100 text-purple-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-purple-200 transition">
            <i class="fa-solid fa-table-list mr-1"></i>Information Form Builder
        </a>
        <a href="{{ route('admin.programs.edit', $program) }}"
           class="text-xs bg-white text-gray-600 font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-100 transition border border-gray-200">
            <i class="fa-solid fa-pen mr-1"></i> Edit Program
        </a>
    </div>
</div>

{{-- Fields Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-bold text-gray-800 text-sm">Eligibility Fields</h2>
        <p class="text-xs text-gray-400 mt-0.5">These fields appear in the eligibility check form for this program</p>
    </div>

    @if($fields->isEmpty())
        <div class="py-16 text-center text-gray-400">
            <i class="fa-solid fa-clipboard-question text-5xl mb-3 block text-gray-200"></i>
            <p class="font-semibold text-gray-500">No eligibility fields defined yet</p>
            <p class="text-sm mt-1">Add fields to define who is eligible for this program.</p>
            <a href="{{ route('admin.programs.eligibility.create', $program) }}"
               class="mt-4 inline-block bg-[#1a2f5e] text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-[#142447] transition">
                + Add First Field
            </a>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">Order</th>
                    <th class="px-5 py-3 text-left">Field Label</th>
                    <th class="px-5 py-3 text-left">Type</th>
                    <th class="px-5 py-3 text-center">Required</th>
                    <th class="px-5 py-3 text-left">Min / Max</th>
                    <th class="px-5 py-3 text-left">Options</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($fields as $field)
                <tr class="hover:bg-gray-50/50 transition {{ !$field->is_active ? 'opacity-50' : '' }}">
                    <td class="px-5 py-3 text-gray-400 text-xs font-mono">{{ $field->sort_order }}</td>

                    <td class="px-5 py-3">
                        <div class="font-semibold text-gray-800">{{ $field->label }}</div>
                        @if($field->unit)
                            <div class="text-xs text-gray-400">Unit: {{ $field->unit }}</div>
                        @endif
                        @if($field->placeholder)
                            <div class="text-xs text-gray-400 italic">{{ $field->placeholder }}</div>
                        @endif
                    </td>

                    <td class="px-5 py-3">
                        <span class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full font-medium capitalize">
                            {{ $field->field_type }}
                        </span>
                    </td>

                    <td class="px-5 py-3 text-center">
                        @if($field->is_required)
                            <span class="text-xs bg-red-50 text-red-600 px-2.5 py-1 rounded-full font-semibold">Required</span>
                        @else
                            <span class="text-xs bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full font-semibold">Optional</span>
                        @endif
                    </td>

                    <td class="px-5 py-3 text-xs text-gray-500">
                        @if($field->min_value || $field->max_value)
                            <span class="bg-gray-100 rounded px-2 py-0.5">
                                {{ $field->min_value ?? '—' }} → {{ $field->max_value ?? '—' }}
                            </span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>

                    <td class="px-5 py-3 text-xs text-gray-500">
                        @if($field->options)
                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                @foreach(array_slice($field->options, 0, 3) as $opt)
                                    <span class="bg-gray-100 text-gray-600 rounded px-1.5 py-0.5">{{ $opt }}</span>
                                @endforeach
                                @if(count($field->options) > 3)
                                    <span class="text-gray-400">+{{ count($field->options) - 3 }} more</span>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>

                    <td class="px-5 py-3 text-center">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                            {{ $field->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $field->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.programs.eligibility.edit', [$program, $field]) }}"
                               class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition text-xs">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.programs.eligibility.destroy', [$program, $field]) }}" method="POST"
                                  onsubmit="return confirm('Delete this eligibility field?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition text-xs">
                                    <i class="fa-solid fa-trash"></i>
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

@endsection
