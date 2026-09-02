<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuccessStory;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SuccessStoryController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index()
    {
        $items = SuccessStory::orderBy('sort_order', 'asc')->paginate(10);

        // Log: Viewed success stories
        $this->log('view', 'success_stories', 'Viewed success stories list');

        return view('pages.admin.success_stories.index', compact('items'));
    }

    public function create()
    {
        // Log: Opened success story creation form
        $this->log('create_form', 'success_stories', 'Opened success story creation form');

        return view('pages.admin.success_stories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'role'   => 'nullable|string|max:255',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'review' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('success-stories', 'public');
        } else {
            unset($data['image']);
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['slug']   = Str::slug($data['name']);

        $story = SuccessStory::create($data);

        // Log: Created success story
        $this->log('created', 'success_stories',
            'Added success story: ' . $data['name'] .
            ($data['role'] ? ' (Role: ' . $data['role'] . ')' : '') .
            ' (Status: ' . ($data['status'] ? 'Active' : 'Inactive') . ')'
        );

        return redirect()->route('admin.success-stories.index')->with('success', 'Success story added successfully');
    }

    public function edit(SuccessStory $successStory)
    {
        // Log: Opened success story edit form
        $this->log('edit_form', 'success_stories',
            'Opened edit form for success story: ' . $successStory->name . ' (ID: ' . $successStory->id . ')'
        );

        return view('pages.admin.success_stories.edit', compact('successStory'));
    }

    public function update(Request $request, SuccessStory $successStory)
    {
        $oldName = $successStory->name;
        $oldRole = $successStory->role;
        $oldStatus = $successStory->status;
        $oldImage = $successStory->image;

        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'role'   => 'nullable|string|max:255',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'review' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($successStory->image && !str_starts_with($successStory->image, 'http')) {
                Storage::disk('public')->delete($successStory->image);
            }
            $data['image'] = $request->file('image')->store('success-stories', 'public');
        } else {
            unset($data['image']);
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['slug']   = Str::slug($data['name']);

        $successStory->update($data);

        // Log: Updated success story with changes
        $changes = [];
        if ($oldName != $data['name']) $changes[] = 'name';
        if ($oldRole != $data['role']) $changes[] = 'role';
        if ($oldStatus != $data['status']) $changes[] = 'status';
        if ($request->hasFile('image')) $changes[] = 'image';

        $logMessage = 'Updated success story: ' . $data['name'];
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        }
        $this->log('updated', 'success_stories', $logMessage);

        return redirect()->route('admin.success-stories.index')->with('success', 'Success story updated successfully');
    }

    public function destroy(SuccessStory $successStory)
    {
        $name = $successStory->name;

        // Delete image if exists
        if ($successStory->image && !str_starts_with($successStory->image, 'http')) {
            Storage::disk('public')->delete($successStory->image);
        }

        $successStory->delete();

        // Log: Deleted success story
        $this->log('deleted', 'success_stories', 'Deleted success story: ' . $name);

        return back()->with('success', 'Success story removed successfully');
    }

    public function toggleStatus(Request $request, SuccessStory $successStory)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $successStory->update(['status' => $request->status]);

        $status = $request->status ? 'activated' : 'deactivated';
        $this->log('toggled', 'success_stories',
            $status . ' success story: ' . $successStory->name
        );

        return response()->json([
            'success' => true,
            'message' => 'Success story status updated successfully.',
            'status' => $request->status
        ]);
    }
}
