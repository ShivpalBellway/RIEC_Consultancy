@extends('layouts.admin')

@section('title', 'Add Form Field')
@section('page-title', 'Add Form Field')

@section('breadcrumb')
    <span class="text-gray-300">/</span>
    <a href="{{ route('admin.programs.index') }}" class="hover:text-primary">Programs</a>
    <span class="text-gray-300">/</span>
    <a href="{{ route('admin.programs.form-builder.index', $program) }}" class="hover:text-primary">
        {{ $program->name }} Information Form Builder
    </a>
    <span class="text-gray-300">/</span>
    <span class="text-gray-500">Add Field</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">

    <form action="{{ route('admin.programs.form-builder.fields.store', [$program, $section]) }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Left Info Card --}}
            <div class="lg:col-span-4">
                <div class="bg-gradient-to-br from-[#061d43] via-[#102b5c] to-[#1a2f5e] rounded-3xl shadow-xl overflow-hidden sticky top-6">
                    <div class="p-7 text-white relative">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#dca737]/20 rounded-full blur-2xl"></div>

                        <div class="w-14 h-14 rounded-2xl bg-[#dca737] flex items-center justify-center shadow-lg mb-5">
                            <i class="fa-solid fa-list-check text-white text-xl"></i>
                        </div>

                        <p class="text-xs uppercase tracking-[0.25em] text-[#dca737] font-bold mb-2">
                            Form Builder
                        </p>

                        <h2 class="text-2xl font-black leading-tight">
                            Add New Dynamic Field
                        </h2>

                        <p class="text-sm text-white/70 mt-3 leading-relaxed">
                            Create a custom field for student application form. You can control type, required status, options and order.
                        </p>

                        <div class="mt-7 space-y-4">
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <p class="text-xs text-white/50 mb-1">Program</p>
                                <h4 class="text-sm font-bold">{{ $program->name }}</h4>
                            </div>

                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <p class="text-xs text-white/50 mb-1">Section</p>
                                <h4 class="text-sm font-bold text-[#dca737]">{{ $section->name }}</h4>
                            </div>
                        </div>

                        <div class="mt-7 border-t border-white/10 pt-5">
                            <ul class="space-y-3 text-sm text-white/75">
                                <li class="flex items-center gap-3">
                                    <i class="fa-solid fa-circle-check text-[#dca737]"></i>
                                    Supports text, select, file, date etc.
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="fa-solid fa-circle-check text-[#dca737]"></i>
                                    Custom validation message
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="fa-solid fa-circle-check text-[#dca737]"></i>
                                    Sortable student form fields
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Form --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                    <div class="px-7 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="font-black text-gray-800 text-lg">New Form Field</h2>
                                <p class="text-xs text-gray-400 mt-1">
                                    Fill field details carefully. These settings will reflect on frontend application form.
                                </p>
                            </div>

                            <div class="hidden md:flex w-12 h-12 rounded-2xl bg-[#1a2f5e]/10 text-[#1a2f5e] items-center justify-center">
                                <i class="fa-solid fa-plus text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="p-7">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Label --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Field Label <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="label" value="{{ old('label') }}" required
                                       placeholder="e.g. Full Name, Passport Number, Work Experience"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] focus:border-transparent transition">
                                <p class="text-xs text-gray-400 mt-1.5">This label will appear on student form.</p>
                            </div>

                            {{-- Field Type --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Field Type <span class="text-red-500">*</span>
                                </label>
                                <select name="field_type" id="field_type" required onchange="toggleFieldOptions(this.value)"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] focus:border-transparent transition">
                                    @foreach($fieldTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('field_type', 'text') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Sort Order --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] focus:border-transparent transition">
                            </div>

                            {{-- Placeholder --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Placeholder Text</label>
                                <input type="text" name="placeholder" value="{{ old('placeholder') }}"
                                       placeholder="e.g. Enter your full name as in passport"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] focus:border-transparent transition">
                            </div>

                            {{-- Toggles --}}
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Required / Optional</label>
                                    <label class="flex items-center justify-between cursor-pointer">
                                        <span class="text-sm text-gray-500">Make this field required</span>
                                        <div class="relative">
                                            <input type="checkbox" name="is_required" id="is_required" class="sr-only peer" {{ old('is_required', true) ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-gray-200 rounded-full peer-checked:bg-red-500 transition"></div>
                                            <div class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                                        </div>
                                    </label>
                                </div>

                                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Field Status</label>
                                    <label class="flex items-center justify-between cursor-pointer">
                                        <span class="text-sm text-gray-500">Show this field on form</span>
                                        <div class="relative">
                                            <input type="checkbox" name="is_active" id="is_active" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-gray-200 rounded-full peer-checked:bg-[#1a2f5e] transition"></div>
                                            <div class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Store in System Toggle (only for File fields) --}}
                            <!-- <div id="store-in-system-section" class="hidden md:col-span-2">
                                <div class="rounded-2xl border border-gray-100 bg-gray-50/70 p-5">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">File Storage Location</label>
                                    <label class="flex items-center justify-between cursor-pointer">
                                        <span class="text-sm text-gray-500">Save uploaded files in system storage (if disabled, files will only be emailed)</span>
                                        <div class="relative">
                                            <input type="checkbox" name="store_in_system" id="store_in_system" value="1" class="sr-only peer" {{ old('store_in_system', true) ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-gray-200 rounded-full peer-checked:bg-emerald-500 transition"></div>
                                            <div class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                                        </div>
                                    </label>
                                </div>
                            </div> -->

                            {{-- Options --}}
                            <div id="options-section" class="hidden md:col-span-2">
                                <div class="rounded-3xl border border-[#dca737]/20 bg-[#dca737]/5 p-5">
                                    <div class="flex items-center justify-between mb-3">
                                        <label class="block text-sm font-bold text-gray-700">Dropdown Options</label>

                                        <button type="button" onclick="addOption()"
                                                class="text-xs bg-[#1a2f5e] text-white font-bold px-3 py-2 rounded-xl hover:bg-[#142447] transition">
                                            <i class="fa-solid fa-plus mr-1"></i> Add Option
                                        </button>
                                    </div>

                                    <div id="options-list" class="space-y-2">
                                        @if(old('options'))
                                            @foreach(old('options') as $opt)
                                                <div class="flex gap-2 option-row">
                                                    <input type="text" name="options[]" value="{{ $opt }}"
                                                           class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] transition"
                                                           placeholder="Option value">
                                                    <button type="button" onclick="removeOption(this)"
                                                            class="w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-xs transition">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="flex gap-2 option-row">
                                                <input type="text" name="options[]"
                                                       class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1a2f5e]"
                                                       placeholder="e.g. Male">
                                                <button type="button" onclick="removeOption(this)"
                                                        class="w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-xs transition">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Validation Message --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Custom Validation Message</label>
                                <input type="text" name="validation_message" value="{{ old('validation_message') }}"
                                       placeholder="e.g. Please enter a valid passport number"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] focus:border-transparent transition">
                                <p class="text-xs text-gray-400 mt-1.5">Shown to student when validation fails.</p>
                            </div>

                        </div>
                    </div>

                    <div class="px-7 py-5 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                        <a href="{{ route('admin.programs.form-builder.index', $program) }}"
                           class="text-sm text-gray-500 hover:text-gray-800 font-bold transition">
                            <i class="fa-solid fa-arrow-left mr-1.5"></i> Cancel
                        </a>

                        <button type="submit"
                                class="bg-[#1a2f5e] text-white text-sm font-black px-7 py-3 rounded-2xl hover:bg-[#142447] transition shadow-lg shadow-[#1a2f5e]/20">
                            <i class="fa-solid fa-plus mr-1.5"></i> Add Field
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleFieldOptions(type) {
    const options = document.getElementById('options-section');
    options.classList.toggle('hidden', !['select'].includes(type));

    const storeInSystem = document.getElementById('store-in-system-section');
    if (storeInSystem) {
        storeInSystem.classList.toggle('hidden', !['file'].includes(type));
    }
}

function addOption() {
    const list = document.getElementById('options-list');
    const div = document.createElement('div');

    div.className = 'flex gap-2 option-row';
    div.innerHTML = `
        <input type="text" name="options[]"
               class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1a2f5e] transition"
               placeholder="Option value">
        <button type="button" onclick="removeOption(this)"
                class="w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-xs transition">
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
