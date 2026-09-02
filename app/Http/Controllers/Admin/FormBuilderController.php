<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormField;
use App\Models\FormSection;
use App\Models\Program;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Http\Request;

class FormBuilderController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index(Program $program)
    {
        $sections = $program->formSections()->with('fields')->get();

        // Log: Viewed form builder
        $this->log('view', 'form_builder',
            'Viewed form builder for program: ' . $program->name
        );

        return view('pages.admin.form-builder.index', compact('program', 'sections'));
    }

    public function storeSection(Request $request, Program $program)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer',
        ]);

        $section = $program->formSections()->create([
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->has('is_active'),
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        // Log: Created form section
        $this->log('created', 'form_builder',
            'Added form section "' . $request->name . '" to program: ' . $program->name
        );

        return redirect()->route('admin.programs.form-builder.index', $program)
                         ->with('success', 'Section added successfully!');
    }

    public function destroySection(Program $program, FormSection $section)
    {
        $sectionName = $section->name;
        $section->delete();

        // Log: Deleted form section
        $this->log('deleted', 'form_builder',
            'Deleted form section "' . $sectionName . '" from program: ' . $program->name
        );

        return redirect()->route('admin.programs.form-builder.index', $program)
                         ->with('success', 'Section deleted!');
    }

    public function createField(Program $program, FormSection $section)
    {
        $fieldTypes = FormField::fieldTypes();

        // Log: Opened field creation form
        $this->log('create_form', 'form_builder',
            'Opened field creation form for section "' . $section->name . '" in program: ' . $program->name
        );

        return view('pages.admin.form-builder.create-field', compact('program', 'section', 'fieldTypes'));
    }

    public function storeField(Request $request, Program $program, FormSection $section)
    {
        $request->validate([
            'label'              => 'required|string|max:255',
            'field_type'         => 'required|string',
            'placeholder'        => 'nullable|string|max:255',
            'validation_message' => 'nullable|string|max:255',
            'sort_order'         => 'nullable|integer',
            'options'            => 'nullable|array',
            'options.*'          => 'nullable|string|max:255',
            'store_in_system'    => 'nullable|boolean',
        ]);

        $options = null;
        if ($request->filled('options')) {
            $options = array_values(array_filter($request->options, fn($o) => !is_null($o) && !empty(trim($o))));
        }

        $field_key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $request->label));

        $store_in_system = true;

        $field = $section->fields()->create([
            'label'              => $request->label,
            'field_key'          => $field_key,
            'field_type'         => $request->field_type,
            'is_required'        => $request->has('is_required'),
            'options'            => !empty($options) ? $options : null,
            'placeholder'        => $request->placeholder,
            'validation_message' => $request->validation_message,
            'is_active'          => $request->has('is_active'),
            'sort_order'         => $request->sort_order ?? 0,
            'store_in_system'    => $store_in_system,
        ]);

        // Log: Created form field
        $this->log('created', 'form_builder',
            'Added form field "' . $request->label . '" (Type: ' . $request->field_type .
            ') to section "' . $section->name . '" in program: ' . $program->name
        );

        return redirect()->route('admin.programs.form-builder.index', $program)
                         ->with('success', 'Field added successfully!');
    }

    public function editField(Program $program, FormSection $section, FormField $field)
    {
        $fieldTypes = FormField::fieldTypes();

        // Log: Opened field edit form
        $this->log('edit_form', 'form_builder',
            'Opened edit form for field "' . $field->label . '" in section "' . $section->name . '"'
        );

        return view('pages.admin.form-builder.edit-field', compact('program', 'section', 'field', 'fieldTypes'));
    }

    public function updateField(Request $request, Program $program, FormSection $section, FormField $field)
    {
        $oldLabel = $field->label;
        $oldType = $field->field_type;

        $request->validate([
            'label'              => 'required|string|max:255',
            'field_type'         => 'required|string',
            'placeholder'        => 'nullable|string|max:255',
            'validation_message' => 'nullable|string|max:255',
            'sort_order'         => 'nullable|integer',
            'options'            => 'nullable|array',
            'options.*'          => 'nullable|string|max:255',
            'store_in_system'    => 'nullable|boolean',
        ]);

        $options = null;
        if ($request->filled('options')) {
            $options = array_values(array_filter($request->options, fn($o) => !is_null($o) && !empty(trim($o))));
        }

        $field_key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $request->label));

        $store_in_system = true;

        $field->update([
            'label'              => $request->label,
            'field_key'          => $field_key,
            'field_type'         => $request->field_type,
            'is_required'        => $request->has('is_required'),
            'options'            => !empty($options) ? $options : null,
            'placeholder'        => $request->placeholder,
            'validation_message' => $request->validation_message,
            'is_active'          => $request->has('is_active'),
            'sort_order'         => $request->sort_order ?? 0,
            'store_in_system'    => $store_in_system,
        ]);

        // Log: Updated form field
        $changes = [];
        if ($oldLabel != $request->label) $changes[] = 'label';
        if ($oldType != $request->field_type) $changes[] = 'field_type';

        $logMessage = 'Updated field "' . $oldLabel . '" in section "' . $section->name . '"';
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        }
        $this->log('updated', 'form_builder', $logMessage);

        return redirect()->route('admin.programs.form-builder.index', $program)
                         ->with('success', 'Field updated successfully!');
    }

    public function destroyField(Program $program, FormSection $section, FormField $field)
    {
        $fieldLabel = $field->label;
        $sectionName = $section->name;

        $field->delete();

        // Log: Deleted form field
        $this->log('deleted', 'form_builder',
            'Deleted field "' . $fieldLabel . '" from section "' . $sectionName . '" in program: ' . $program->name
        );

        return redirect()->route('admin.programs.form-builder.index', $program)
                         ->with('success', 'Field deleted!');
    }
}
