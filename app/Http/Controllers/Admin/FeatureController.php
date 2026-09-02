<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\ActivityLog;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeatureController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $items = Feature::orderBy('sort_order', 'asc')->paginate(10);

        // Log: Viewed features
        $this->log('view', 'features', 'Viewed features list');

        return view('pages.admin.features.index', compact('items'));
    }

    public function create()
    {
        // Log: Opened feature creation form
        $this->log('create_form', 'features', 'Opened feature creation form');

        return view('pages.admin.features.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['slug'] = Str::slug($data['title']);

        Feature::create($data);

        $this->log('created', 'features', 'Added feature: ' . $data['title']);

        return redirect()->route('admin.features.index')->with('success', 'Feature added successfully');
    }

    public function edit(Feature $feature)
    {
        // Log: Opened feature edit form
        $this->log('edit_form', 'features', 'Opened edit form for feature: ' . $feature->title);

        return view('pages.admin.features.edit', compact('feature'));
    }

    public function update(Request $request, Feature $feature)
    {
        $oldTitle = $feature->title;

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['slug'] = Str::slug($data['title']);

        $feature->update($data);

        $logMessage = 'Updated feature';
        if ($oldTitle != $data['title']) {
            $logMessage .= ' from "' . $oldTitle . '" to "' . $data['title'] . '"';
        } else {
            $logMessage .= ': ' . $data['title'];
        }
        $this->log('updated', 'features', $logMessage);

        return redirect()->route('admin.features.index')->with('success', 'Feature updated successfully');
    }

    public function destroy(Feature $feature)
    {
        $title = $feature->title;
        $feature->delete();

        $this->log('deleted', 'features', 'Deleted feature: ' . $title);

        return back()->with('success', 'Feature removed successfully');
    }

    public function toggleStatus(Request $request, Feature $feature)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $feature->update(['status' => $request->status]);

        $status = $request->status ? 'activated' : 'deactivated';
        $this->log('toggled', 'features', $status . ' feature: ' . $feature->title);

        return response()->json([
            'success' => true,
            'message' => 'Feature status updated successfully.',
            'status' => $request->status
        ]);
    }
}
