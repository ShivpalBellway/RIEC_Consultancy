<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index()
    {
        $programs = Program::orderBy('sort_order')->orderBy('name')->get();

        // Log: Viewed programs
        $this->log('view', 'programs', 'Viewed programs list');

        return view('pages.admin.programs.index', compact('programs'));
    }

    public function eligibilitySetup()
    {
        $programs = Program::orderBy('sort_order')->orderBy('name')->get();

        // Log: Viewed eligibility setup
        $this->log('view_eligibility_setup', 'programs', 'Viewed eligibility setup page');

        return view('pages.admin.setup.program-selector', [
            'programs' => $programs,
            'title' => 'Eligibility Setup',
            'description' => 'Select a program to manage eligibility criteria fields.',
            'targetRoute' => 'admin.programs.eligibility.index',
            'icon' => 'fa-solid fa-user-graduate',
            'tone' => 'blue',
        ]);
    }

    public function formBuilderSetup()
    {
        $programs = Program::orderBy('sort_order')->orderBy('name')->get();

        // Log: Viewed form builder setup
        $this->log('view_form_builder_setup', 'programs', 'Viewed form builder setup page');

        return view('pages.admin.setup.program-selector', [
            'programs' => $programs,
            'title' => 'Information Form Builder',
            'description' => 'Select a program to manage application information fields.',
            'targetRoute' => 'admin.programs.form-builder.index',
            'icon' => 'fa-solid fa-file-signature',
            'tone' => 'blue',
        ]);
    }

    public function create()
    {
        $types = Program::programTypes();

        // Log: Opened program creation form
        $this->log('create_form', 'programs', 'Opened program creation form');

        return view('pages.admin.programs.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'country'      => 'required|string|max:255',
            'program_type' => 'required|string',
            'description'  => 'nullable|string|max:500',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'sort_order'   => 'nullable|integer',
        ]);

        $data = $request->only(['name', 'country', 'program_type', 'description', 'sort_order']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('programs', 'public');
        }

        $program = Program::create($data);

        // Log: Created program
        $this->log('created', 'programs',
            'Added program: ' . $data['name'] .
            ' (Type: ' . $data['program_type'] .
            ', Country: ' . $data['country'] .
            ', Status: ' . ($data['is_active'] ? 'Active' : 'Inactive') . ')'
        );

        return redirect()->route('admin.programs.index')
                         ->with('success', 'Program created successfully!');
    }

    public function edit(Program $program)
    {
        $types = Program::programTypes();

        // Log: Opened program edit form
        $this->log('edit_form', 'programs',
            'Opened edit form for program: ' . $program->name . ' (ID: ' . $program->id . ')'
        );

        return view('pages.admin.programs.edit', compact('program', 'types'));
    }

    public function update(Request $request, Program $program)
    {
        $oldName = $program->name;
        $oldType = $program->program_type;
        $oldCountry = $program->country;
        $oldStatus = $program->is_active;
        $oldImage = $program->image;

        $request->validate([
            'name'         => 'required|string|max:255',
            'country'      => 'required|string|max:255',
            'program_type' => 'required|string',
            'description'  => 'nullable|string|max:500',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'sort_order'   => 'nullable|integer',
        ]);

        $data = $request->only(['name', 'country', 'program_type', 'description', 'sort_order']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('image')) {
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            $data['image'] = $request->file('image')->store('programs', 'public');
        }

        $program->update($data);

        // Log: Updated program with changes
        $changes = [];
        if ($oldName != $data['name']) $changes[] = 'name';
        if ($oldType != $data['program_type']) $changes[] = 'program_type';
        if ($oldCountry != $data['country']) $changes[] = 'country';
        if ($oldStatus != $data['is_active']) $changes[] = 'status';
        if ($request->hasFile('image')) $changes[] = 'image';

        $logMessage = 'Updated program: ' . $data['name'];
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        }
        $this->log('updated', 'programs', $logMessage);

        return redirect()->route('admin.programs.index')
                         ->with('success', 'Program updated successfully!');
    }

    public function destroy(Program $program)
    {
        $programName = $program->name;
        $programId = $program->id;

        if ($program->image) {
            Storage::disk('public')->delete($program->image);
        }
        $program->delete();

        // Log: Deleted program
        $this->log('deleted', 'programs',
            'Deleted program: ' . $programName . ' (ID: ' . $programId . ')'
        );

        return redirect()->route('admin.programs.index')
                         ->with('success', 'Program deleted successfully!');
    }
}
