<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EligibilityField;
use App\Models\Program;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Http\Request;

class EligibilityFieldController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index(Program $program)
    {
        $fields = $program->eligibilityFields()->get();

        // Log: Viewed eligibility fields
        $this->log('view', 'eligibility_fields',
            'Viewed eligibility fields for program: ' . $program->name
        );

        return view('pages.admin.eligibility.index', compact('program', 'fields'));
    }

    public function create(Program $program)
    {
        $fieldTypes = EligibilityField::fieldTypes();

        // Log: Opened eligibility field creation form
        $this->log('create_form', 'eligibility_fields',
            'Opened eligibility field creation form for program: ' . $program->name
        );

        return view('pages.admin.eligibility.create', compact('program', 'fieldTypes'));
    }

    public function store(Request $request, Program $program)
    {
        $request->validate([
            'label'              => 'required|string|max:255',
            'field_type'         => 'required|string',
            'min_value'          => 'nullable|string|max:100',
            'max_value'          => 'nullable|string|max:100',
            'placeholder'        => 'nullable|string|max:255',
            'validation_message' => 'nullable|string|max:255',
            'unit'               => 'nullable|string|max:50',
            'sort_order'         => 'nullable|integer',
            'options'            => 'nullable|array',
            'options.*'          => 'nullable|string|max:255',
            'store_in_system'    => 'nullable|boolean',
        ]);

        $options = null;
        if ($request->filled('options')) {
            $options = array_filter($request->options, fn($o) => !is_null($o) && !empty(trim($o)));
            $options = array_values($options);
        }

        $field_key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $request->label));

        $store_in_system = true;

        $field = $program->eligibilityFields()->create([
            'label'              => $request->label,
            'field_key'          => $field_key,
            'field_type'         => $request->field_type,
            'is_required'        => $request->has('is_required'),
            'min_value'          => $request->min_value,
            'max_value'          => $request->max_value,
            'options'            => !empty($options) ? $options : null,
            'placeholder'        => $request->placeholder,
            'validation_message' => $request->validation_message,
            'unit'               => $request->unit,
            'is_active'          => $request->has('is_active'),
            'sort_order'         => $request->sort_order ?? 0,
            'store_in_system'    => $store_in_system,
        ]);

        // Log: Created eligibility field
        $this->log('created', 'eligibility_fields',
            'Added eligibility field "' . $request->label . '" (Type: ' . $request->field_type . ') for program: ' . $program->name
        );

        return redirect()->route('admin.programs.eligibility.index', $program)
                         ->with('success', 'Eligibility field added successfully!');
    }

    public function edit(Program $program, EligibilityField $field)
    {
        $fieldTypes = EligibilityField::fieldTypes();

        // Log: Opened eligibility field edit form
        $this->log('edit_form', 'eligibility_fields',
            'Opened edit form for eligibility field "' . $field->label . '" (ID: ' . $field->id . ')'
        );

        return view('pages.admin.eligibility.edit', compact('program', 'field', 'fieldTypes'));
    }

    public function update(Request $request, Program $program, EligibilityField $field)
    {
        $request->validate([
            'label'              => 'required|string|max:255',
            'field_type'         => 'required|string',
            'min_value'          => 'nullable|string|max:100',
            'max_value'          => 'nullable|string|max:100',
            'placeholder'        => 'nullable|string|max:255',
            'validation_message' => 'nullable|string|max:255',
            'unit'               => 'nullable|string|max:50',
            'sort_order'         => 'nullable|integer',
            'options'            => 'nullable|array',
            'options.*'          => 'nullable|string|max:255',
            'store_in_system'    => 'nullable|boolean',
        ]);

        $oldLabel = $field->label;
        $oldType = $field->field_type;

        $options = null;
        if ($request->filled('options')) {
            $options = array_filter($request->options, fn($o) => !is_null($o) && !empty(trim($o)));
            $options = array_values($options);
        }

        $field_key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $request->label));

        $store_in_system = true;

        $field->update([
            'label'              => $request->label,
            'field_key'          => $field_key,
            'field_type'         => $request->field_type,
            'is_required'        => $request->has('is_required'),
            'min_value'          => $request->min_value,
            'max_value'          => $request->max_value,
            'options'            => !empty($options) ? $options : null,
            'placeholder'        => $request->placeholder,
            'validation_message' => $request->validation_message,
            'unit'               => $request->unit,
            'is_active'          => $request->has('is_active'),
            'sort_order'         => $request->sort_order ?? 0,
            'store_in_system'    => $store_in_system,
        ]);

        // Log: Updated eligibility field
        $changes = [];
        if ($oldLabel != $request->label) $changes[] = 'label';
        if ($oldType != $request->field_type) $changes[] = 'field_type';

        $logMessage = 'Updated eligibility field "' . $oldLabel . '"';
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        }
        $this->log('updated', 'eligibility_fields', $logMessage);

        return redirect()->route('admin.programs.eligibility.index', $program)
                         ->with('success', 'Eligibility field updated successfully!');
    }

    public function destroy(Program $program, EligibilityField $field)
    {
        $fieldLabel = $field->label;
        $fieldId = $field->id;

        $field->delete();

        // Log: Deleted eligibility field
        $this->log('deleted', 'eligibility_fields',
            'Deleted eligibility field "' . $fieldLabel . '" (ID: ' . $fieldId . ') from program: ' . $program->name
        );

        return redirect()->route('admin.programs.eligibility.index', $program)
                         ->with('success', 'Eligibility field deleted!');
    }
}
