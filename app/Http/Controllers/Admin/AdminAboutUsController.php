<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAboutUsController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index()
    {
        $about = AboutUs::getContent();

        // Log: View about us page
        $this->log('view', 'about', 'Admin viewed about us page');

        return view('pages.admin.about.index', compact('about'));
    }

    public function edit()
    {
        $about = AboutUs::getContent();

        // Log: Open edit form
        $this->log('edit_form', 'about', 'Admin opened about us edit form');

        return view('pages.admin.about.edit', compact('about'));
    }

    public function update(Request $request)
    {
        $about = AboutUs::first() ?? new AboutUs();

        $data = $request->validate([
            'title'                 => 'nullable|string|max:255',
            'description'           => 'nullable|string',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'hero_image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'hero_badge'            => 'nullable|string|max:100',
            'hero_heading_line1'    => 'nullable|string|max:100',
            'hero_heading_highlight'=> 'nullable|string|max:100',
            'hero_subtext'          => 'nullable|string|max:500',
        ]);

        // Store old data for logging
        $oldTitle = $about->title;
        $oldDescription = $about->description;
        $oldImage = $about->image;
        $oldHeroImage = $about->hero_image;

        if ($request->hasFile('image')) {
            if ($about->image && Storage::disk('public')->exists($about->image)) {
                Storage::disk('public')->delete($about->image);
            }
            $data['image'] = $request->file('image')->store('about', 'public');
        }

        if ($request->hasFile('hero_image')) {
            if ($about->hero_image && Storage::disk('public')->exists($about->hero_image)) {
                Storage::disk('public')->delete($about->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')->store('about', 'public');
        }

        $about->fill($data)->save();

        // Log: About us updated with changes
        $changes = [];
        if ($oldTitle != $about->title) $changes[] = 'title';
        if ($oldDescription != $about->description) $changes[] = 'description';
        if ($request->hasFile('image')) $changes[] = 'image';
        if ($request->hasFile('hero_image')) $changes[] = 'hero_image';

        $logMessage = 'About Us updated. Changes: ' . (empty($changes) ? 'none' : implode(', ', $changes));
        $this->log('update', 'about', $logMessage);

        return redirect()->route('admin.about.index')->with('success', 'About Us updated successfully');
    }
}
