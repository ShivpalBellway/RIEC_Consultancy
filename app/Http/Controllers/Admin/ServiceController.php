<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $services = Service::orderBy('sort_order', 'asc')->paginate(10);

        // Log: Viewed services
        $this->log('view', 'services', 'Viewed services list');

        return view('pages.admin.services.index', compact('services'));
    }

    public function create()
    {
        // Log: Opened service creation form
        $this->log('create_form', 'services', 'Opened service creation form');

        return view('pages.admin.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'excerpt'     => 'nullable|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,image/gif,image/avif,image/svg+xml|max:5120',
            'icon'        => 'nullable|string|max:100',
            'status'      => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        } else {
            unset($data['image']);
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['slug'] = Str::slug($data['title']);

        $service = Service::create($data);

        // Log: Created service
        $this->log('created', 'services',
            'Added service: ' . $data['title'] .
            ' (Status: ' . ($data['status'] ? 'Active' : 'Inactive') .
            ($data['icon'] ? ', Icon: ' . $data['icon'] : '') . ')'
        );

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        // Log: Opened service edit form
        $this->log('edit_form', 'services',
            'Opened edit form for service: ' . $service->title . ' (ID: ' . $service->id . ')'
        );

        return view('pages.admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $oldTitle = $service->title;
        $oldStatus = $service->status;
        $oldIcon = $service->icon;
        $oldImage = $service->image;

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'excerpt'     => 'nullable|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,image/gif,image/avif,image/svg+xml|max:5120',
            'icon'        => 'nullable|string|max:100',
            'status'      => 'nullable|boolean',
        ]);

        // Handle image upload - delete old if replacing
        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        } else {
            unset($data['image']); // keep existing image
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['slug'] = Str::slug($data['title']);

        $service->update($data);

        // Log: Updated service with changes
        $changes = [];
        if ($oldTitle != $data['title']) $changes[] = 'title';
        if ($oldStatus != $data['status']) $changes[] = 'status';
        if ($oldIcon != $data['icon']) $changes[] = 'icon';
        if ($request->hasFile('image')) $changes[] = 'image';

        $logMessage = 'Updated service: ' . $data['title'];
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        }
        $this->log('updated', 'services', $logMessage);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $title = $service->title;

        // Delete image if exists
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        // Log: Deleted service
        $this->log('deleted', 'services', 'Deleted service: ' . $title);

        return back()->with('success', 'Service removed successfully.');
    }

    public function toggleStatus(Request $request, Service $service)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $service->update(['status' => $request->status]);

        $status = $request->status ? 'activated' : 'deactivated';
        $this->log('toggled', 'services',
            $status . ' service: ' . $service->title
        );

        return response()->json([
            'success' => true,
            'message' => 'Service status updated successfully.',
            'status' => $request->status
        ]);
    }
}
