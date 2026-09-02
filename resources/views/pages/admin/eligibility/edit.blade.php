@extends('layouts.admin')

@section('title', 'Edit Eligibility Field')
@section('page-title', 'Edit Eligibility Field')

@section('breadcrumb')
    <span class="text-gray-300">/</span>
    <a href="{{ route('admin.programs.index') }}" class="hover:text-primary">Programs</a>
    <span class="text-gray-300">/</span>
    <a href="{{ route('admin.programs.eligibility.index', $program) }}" class="hover:text-primary">{{ $program->name }}</a>
    <span class="text-gray-300">/</span>
    <span class="text-gray-500">Edit Field</span>
@endsection

@section('content')
<div class="max-w-8xl mx-auto">
<form action="{{ route('admin.programs.eligibility.update', [$program, $field]) }}" method="POST">
@csrf
@method('PUT')

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-[#1a2f5e] to-[#0f1f45]">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-white text-lg">Edit: {{ $field->label }}</h2>
                <p class="text-xs text-white/70 mt-1">
                    {{ $program->name }} —
                    <span class="font-semibold text-[#dca737]">{{ $program->country }}</span>
                </p>
            </div>

            <div class="hidden md:flex w-12 h-12 rounded-2xl bg-white/10 text-[#dca737] items-center justify-center">
                <i class="fa-solid fa-pen-to-square text-xl"></i>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-7 bg-gray-50/60">

        {{-- Basic Details --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-[#1a2f5e]/10 text-[#1a2f5e] flex items-center justify-center">
                    <i class="fa-solid fa-circle-info text-xs"></i>
                </span>
                Basic Field Details
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                <div class="xl:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Field Label <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="label" value="{{ old('label', $field->label) }}" required class="form-input">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Field Type <span class="text-red-500">*</span>
                    </label>
                    <select name="field_type" id="field_type" required onchange="toggleFieldOptions(this.value)" class="form-input bg-white">
                        @foreach($fieldTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('field_type', $field->field_type) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Placeholder Text</label>
                    <input type="text" name="placeholder" value="{{ old('placeholder', $field->placeholder) }}" class="form-input">
                </div>

                <div id="unit-section">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Unit</label>
                    <input type="text" name="unit" value="{{ old('unit', $field->unit) }}" class="form-input">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $field->sort_order) }}" min="0" class="form-input">
                </div>
            </div>
        </div>

        {{-- Rules --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-[#dca737]/15 text-[#b88618] flex items-center justify-center">
                    <i class="fa-solid fa-sliders text-xs"></i>
                </span>
                Validation & Rules
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Required / Optional</label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_required" class="sr-only peer" {{ old('is_required', $field->is_required) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-red-500 transition"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">Required</span>
                    </label>
                    <p class="text-xs text-gray-400 mt-2">Toggle off = Optional</p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Status</label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_active" class="sr-only peer" {{ old('is_active', $field->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-[#1a2f5e] transition"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">Active</span>
                    </label>
                    <p class="text-xs text-gray-400 mt-2">Show this field on frontend</p>
                </div>

                <div id="min-max-section" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Min Value</label>
                        <input type="text" name="min_value" value="{{ old('min_value', $field->min_value) }}" placeholder="e.g. 17, 2.0" class="form-input">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Max Value</label>
                        <input type="text" name="max_value" value="{{ old('max_value', $field->max_value) }}" placeholder="e.g. 35, 4.0" class="form-input">
                    </div>
                </div>

                <div class="xl:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Custom Validation Message</label>
                    <input type="text" name="validation_message" value="{{ old('validation_message', $field->validation_message) }}" class="form-input">
                </div>
            </div>
        </div>

        {{-- Store in System Toggle (only for File fields) --}}
        <!-- <div id="store-in-system-section" class="hidden bg-white rounded-2xl border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-hard-drive text-xs"></i>
                </span>
                File Storage Location
            </h3>
            <label class="flex items-center justify-between cursor-pointer">
                <span class="text-sm text-gray-500 font-semibold">Save uploaded files in system storage (if disabled, files will only be emailed)</span>
                <div class="relative">
                    <input type="checkbox" name="store_in_system" id="store_in_system" value="1" class="sr-only peer" {{ old('store_in_system', $field->store_in_system) ? 'checked' : '' }}>
                    <div class="w-12 h-7 bg-gray-200 rounded-full peer-checked:bg-emerald-500 transition"></div>
                    <div class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                </div>
            </label>
        </div> -->

        {{-- Options --}}
        <div id="options-section" class="hidden bg-white rounded-2xl border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-list-ul text-xs"></i>
                </span>
                Dropdown / Checkbox Options
            </h3>

            <div id="options-list" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                @php $existingOptions = old('options', $field->options ?? []); @endphp

                @forelse($existingOptions as $opt)
                    <div class="flex gap-2 option-row">
                        <input type="text" name="options[]" value="{{ $opt }}" class="form-input" placeholder="Option value">
                        <button type="button" onclick="removeOption(this)"
                                class="w-11 h-11 shrink-0 rounded-xl bg-red-50 text-red-400 hover:bg-red-100 flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                @empty
                    <div class="flex gap-2 option-row">
                        <input type="text" name="options[]" class="form-input" placeholder="Option value">
                        <button type="button" onclick="removeOption(this)"
                                class="w-11 h-11 shrink-0 rounded-xl bg-red-50 text-red-400 hover:bg-red-100 flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                @endforelse
            </div>

            <button type="button" onclick="addOption()"
                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#1a2f5e]/10 text-[#1a2f5e] text-xs font-bold hover:bg-[#1a2f5e] hover:text-white transition">
                <i class="fa-solid fa-plus"></i> Add Option
            </button>
        </div>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-white flex items-center justify-between">
        <a href="{{ route('admin.programs.eligibility.index', $program) }}"
           class="text-sm text-gray-500 hover:text-gray-700 font-semibold">
            ← Cancel
        </a>

        <button type="submit"
                class="bg-[#1a2f5e] text-white text-sm font-bold px-7 py-3 rounded-xl hover:bg-[#142447] transition shadow">
            <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Changes
        </button>
    </div>
</div>
</form>
</div>

<style>
    .form-input {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        font-size: 14px;
        outline: none;
        background: #fff;
        transition: all .2s ease;
    }

    .form-input:focus {
        border-color: #1a2f5e;
        box-shadow: 0 0 0 3px rgba(26, 47, 94, .12);
    }
</style>

@push('scripts')
<script>
function toggleFieldOptions(type) {
    const minMax  = document.getElementById('min-max-section');
    const options = document.getElementById('options-section');
    const unit    = document.getElementById('unit-section');
    const storeInSystem = document.getElementById('store-in-system-section');

    minMax.classList.toggle('hidden', !['number', 'date'].includes(type));
    options.classList.toggle('hidden', !['select', 'checkbox'].includes(type));
    unit.classList.toggle('hidden', ['select', 'checkbox', 'file'].includes(type));
    if (storeInSystem) {
        storeInSystem.classList.toggle('hidden', !['file'].includes(type));
    }
}

function addOption() {
    const list = document.getElementById('options-list');

    const div = document.createElement('div');
    div.className = 'flex gap-2 option-row';

    div.innerHTML = `
        <input type="text" name="options[]" class="form-input" placeholder="Option value">
        <button type="button" onclick="removeOption(this)"
                class="w-11 h-11 shrink-0 rounded-xl bg-red-50 text-red-400 hover:bg-red-100 flex items-center justify-center text-xs transition">
            <i class="fa-solid fa-times"></i>
        </button>
    `;

    list.appendChild(div);
}

function removeOption(btn) {
    const rows = document.querySelectorAll('.option-row');
    if (rows.length > 1) {
        btn.closest('.option-row').remove();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    toggleFieldOptions(document.getElementById('field_type').value);
});
</script>
@endpush
@endsection
