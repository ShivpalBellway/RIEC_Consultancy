<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcessStep;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Http\Request;

class ProcessStepController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index()
    {
        $steps = ProcessStep::orderBy('sort_order')->get();

        // Log: Viewed process steps
        $this->log('view', 'process_steps', 'Viewed process steps list');

        return view('pages.admin.process-steps.index', compact('steps'));
    }

    public function create()
    {
        // Log: Opened process step creation form
        $this->log('create_form', 'process_steps', 'Opened process step creation form');

        return view('pages.admin.process-steps.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'icon'        => 'required|string|max:100',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer',
        ]);
        $data['status']     = $request->has('status') ? 1 : 0;
        $data['sort_order'] = $data['sort_order'] ?? ProcessStep::max('sort_order') + 1;

        ProcessStep::create($data);

        // Log: Created process step
        $this->log('created', 'process_steps',
            'Added process step: ' . $data['title'] .
            ' (Icon: ' . $data['icon'] . ', Status: ' . ($data['status'] ? 'Active' : 'Inactive') . ')'
        );

        return redirect()->route('admin.process-steps.index')->with('success', 'Step added successfully.');
    }

    public function edit(ProcessStep $processStep)
    {
        // Log: Opened process step edit form
        $this->log('edit_form', 'process_steps',
            'Opened edit form for process step: ' . $processStep->title
        );

        return view('pages.admin.process-steps.edit', compact('processStep'));
    }

    public function update(Request $request, ProcessStep $processStep)
    {
        $oldTitle = $processStep->title;
        $oldIcon = $processStep->icon;
        $oldStatus = $processStep->status;

        $data = $request->validate([
            'icon'        => 'required|string|max:100',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer',
        ]);
        $data['status'] = $request->has('status') ? 1 : 0;

        $processStep->update($data);

        // Log: Updated process step
        $changes = [];
        if ($oldTitle != $data['title']) $changes[] = 'title';
        if ($oldIcon != $data['icon']) $changes[] = 'icon';
        if ($oldStatus != $data['status']) $changes[] = 'status';

        $logMessage = 'Updated process step: ' . $data['title'];
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        }
        $this->log('updated', 'process_steps', $logMessage);

        return redirect()->route('admin.process-steps.index')->with('success', 'Step updated successfully.');
    }

    public function destroy(ProcessStep $processStep)
    {
        $title = $processStep->title;
        $processStep->delete();

        // Log: Deleted process step
        $this->log('deleted', 'process_steps', 'Deleted process step: ' . $title);

        return back()->with('success', 'Step deleted successfully.');
    }

    public function toggleStatus(Request $request, ProcessStep $processStep)
    {
        $request->validate(['status' => 'required|boolean']);

        $processStep->update(['status' => $request->status]);

        $status = $request->status ? 'activated' : 'deactivated';
        $this->log('toggled', 'process_steps',
            $status . ' process step: ' . $processStep->title
        );

        return response()->json([
            'success' => true,
            'status' => $request->status
        ]);
    }
}
