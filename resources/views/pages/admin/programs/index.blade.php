@extends('layouts.admin')

@section('title', 'Programs')
@section('page-title', 'Programs')

@section('breadcrumb')
    <span class="text-gray-300">/</span>
    <span class="text-gray-500">Programs</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.programs.create') }}"
       class="bg-[#1a2f5e] text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-[#142447] transition flex items-center gap-2 shadow">
        <i class="fa-solid fa-plus"></i> Add Program
    </a>
@endsection

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Table Header --}}
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $programs->count() }} program(s) total</p>
    </div>

    @if($programs->isEmpty())
        <div class="py-20 text-center text-gray-400">
            <i class="fa-solid fa-graduation-cap text-5xl mb-4 block text-gray-200"></i>
            <p class="font-semibold text-gray-500">No programs found</p>
            <p class="text-sm mt-1">Start by adding your first program.</p>
            <a href="{{ route('admin.programs.create') }}"
               class="mt-4 inline-block bg-[#1a2f5e] text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-[#142447] transition">
                + Add Program
            </a>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">#</th>
                    <th class="px-6 py-3 text-left">Program</th>
                    <th class="px-6 py-3 text-left">Country</th>
                    <th class="px-6 py-3 text-left">Type</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Order</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($programs as $program)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $program->id }}</td>

                    {{-- Program Name + Image --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#1a2f5e]/10 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($program->image)
                                    <img src="{{ $program->image_url }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-graduation-cap text-[#1a2f5e] text-sm"></i>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">{{ $program->name }}</div>
                                @if($program->description)
                                    <div class="text-xs text-gray-400 truncate max-w-[200px]">{{ $program->description }}</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-gray-600">{{ $program->country }}</td>

                    <td class="px-6 py-4">
                        <span class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full font-medium">
                            {{ $program->program_type_label }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                            {{ $program->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $program->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center text-gray-500">{{ $program->sort_order }}</td>

                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.programs.eligibility.index', $program) }}"
                               title="Eligibility Criteria"
                               class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition text-xs">
                                <i class="fa-solid fa-list-check"></i>
                            </a>
                            <a href="{{ route('admin.programs.form-builder.index', $program) }}"
                               title="Information Form Builder"
                               class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition text-xs">
                                <i class="fa-solid fa-table-list"></i>
                            </a>
                            <a href="{{ route('admin.programs.edit', $program) }}"
                               title="Edit"
                               class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition text-xs">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.programs.destroy', $program) }}" method="POST"
                                  onsubmit="return confirm('Delete this program? All eligibility fields and form sections will also be deleted.')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Delete"
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

{{-- Legend --}}
<div class="mt-4 flex items-center gap-6 text-xs text-gray-400">
    <span class="flex items-center gap-1.5"><span class="w-6 h-6 bg-amber-50 rounded flex items-center justify-center"><i class="fa-solid fa-list-check text-amber-600 text-xs"></i></span> Eligibility Criteria</span>
    <span class="flex items-center gap-1.5"><span class="w-6 h-6 bg-purple-50 rounded flex items-center justify-center"><i class="fa-solid fa-table-list text-purple-600 text-xs"></i></span> Information Form Builder</span>
    <span class="flex items-center gap-1.5"><span class="w-6 h-6 bg-blue-50 rounded flex items-center justify-center"><i class="fa-solid fa-pen text-blue-600 text-xs"></i></span> Edit</span>
</div>

@endsection
