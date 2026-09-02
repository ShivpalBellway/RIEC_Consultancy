<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\ActivityLog;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $partners = Partner::orderBy('sort_order', 'asc')->paginate(10);

        // Log: Viewed partners
        $this->log('view', 'partners', 'Viewed partners list');

        return view('pages.admin.partners.index', compact('partners'));
    }

    public function create()
    {
        // Log: Opened partner creation form
        $this->log('create_form', 'partners', 'Opened partner creation form');

        return view('pages.admin.partners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'status' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'alt_text' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('partners', 'public');
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $partner = Partner::create($data);

        // Log: Created partner with details
        $this->log('created', 'partners',
            'Added partner: ' . ($data['alt_text'] ?? 'Partner #' . $partner->id) .
            ' (Status: ' . ($data['status'] ? 'Active' : 'Inactive') . ')'
        );

        return redirect()->route('admin.partners.index')->with('success', 'Partner added successfully.');
    }

    public function edit(Partner $partner)
    {
        // Log: Opened partner edit form
        $this->log('edit_form', 'partners',
            'Opened edit form for partner: ' . ($partner->alt_text ?? 'Partner #' . $partner->id)
        );

        return view('pages.admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $oldAltText = $partner->alt_text;
        $oldStatus = $partner->status;
        $oldImage = $partner->image;

        $data = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'status' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'alt_text' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
        ]);

        // Handle image upload - delete old if replacing
        if ($request->hasFile('image')) {
            if ($partner->image && Storage::disk('public')->exists($partner->image)) {
                Storage::disk('public')->delete($partner->image);
            }
            $data['image'] = $request->file('image')->store('partners', 'public');
        } else {
            unset($data['image']);
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $partner->update($data);

        // Log: Updated partner with changes
        $changes = [];
        // if ($oldAltText != $data['alt_text']) $changes[] = 'alt_text';
        if ($oldStatus != $data['status']) $changes[] = 'status';
        if ($request->hasFile('image')) $changes[] = 'image';

        $logMessage = 'Updated partner: ' . ($data['alt_text'] ?? 'Partner #' . $partner->id);
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        }
        $this->log('updated', 'partners', $logMessage);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner)
    {
        $partnerName = $partner->alt_text ?? 'Partner #' . $partner->id;

        if ($partner->image && Storage::disk('public')->exists($partner->image)) {
            Storage::disk('public')->delete($partner->image);
        }

        $partner->delete();

        // Log: Deleted partner
        $this->log('deleted', 'partners', 'Deleted partner: ' . $partnerName);

        return back()->with('success', 'Partner deleted successfully.');
    }

    public function toggleStatus(Request $request, Partner $partner)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $partner->update(['status' => $request->status]);

        $status = $request->status ? 'activated' : 'deactivated';
        $this->log('toggled', 'partners',
            $status . ' partner: ' . ($partner->alt_text ?? 'Partner #' . $partner->id)
        );

        return response()->json([
            'success' => true,
            'message' => 'Partner status updated successfully.',
            'status' => $request->status
        ]);
    }
}
