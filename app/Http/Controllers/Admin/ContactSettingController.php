<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactSettingController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function edit()
    {
        $setting = SiteSetting::first() ?? SiteSetting::create([]);

        // Log: Opened contact settings edit form
        $this->log('edit_form', 'contact_settings', 'Admin opened contact settings edit form');

        return view('pages.admin.contact-settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'contact_hero_image'             => 'nullable|image|max:3072',
            'contact_hero_badge'             => 'nullable|string|max:100',
            'contact_hero_heading_line1'     => 'nullable|string|max:100',
            'contact_hero_heading_highlight' => 'nullable|string|max:100',
            'contact_hero_subtext'           => 'nullable|string|max:500',
            'contact_phone'                  => 'nullable|string|max:50',
            'contact_hours'                  => 'nullable|string|max:100',
            'contact_email'                  => 'nullable|string|max:100',
            'contact_address_en'             => 'nullable|string|max:500',
            'contact_address_ko'             => 'nullable|string|max:255',
            'social_instagram'               => 'nullable|string|max:255',
            'social_facebook'                => 'nullable|string|max:255',
            'social_linkedin'                => 'nullable|string|max:255',
            'social_youtube'                 => 'nullable|string|max:255',
            'contact_map_embed'              => 'nullable|string',
            'contact_map_url'                => 'nullable|string|max:500',
        ]);

        $setting = SiteSetting::first() ?? SiteSetting::create([]);

        // Store old values for logging
        $oldValues = [
            'phone' => $setting->contact_phone,
            'email' => $setting->contact_email,
            'hours' => $setting->contact_hours,
            'address_en' => $setting->contact_address_en,
            'address_ko' => $setting->contact_address_ko,
            'hero_badge' => $setting->contact_hero_badge,
            'hero_heading_line1' => $setting->contact_hero_heading_line1,
            'hero_heading_highlight' => $setting->contact_hero_heading_highlight,
            'hero_subtext' => $setting->contact_hero_subtext,
            'instagram' => $setting->social_instagram,
            'facebook' => $setting->social_facebook,
            'linkedin' => $setting->social_linkedin,
            'youtube' => $setting->social_youtube,
            'map_embed' => $setting->contact_map_embed,
            'map_url' => $setting->contact_map_url,
        ];

        if ($request->hasFile('contact_hero_image')) {
            if ($setting->contact_hero_image) {
                Storage::disk('public')->delete($setting->contact_hero_image);
            }
            $setting->contact_hero_image = $request->file('contact_hero_image')->store('settings', 'public');
        }

        $setting->fill($request->except('contact_hero_image', '_token'))->save();

        // Track changes for logging
        $changes = [];
        $fields = [
            'contact_phone' => 'Phone',
            'contact_email' => 'Email',
            'contact_hours' => 'Hours',
            'contact_address_en' => 'Address (English)',
            'contact_address_ko' => 'Address (Korean)',
            'contact_hero_badge' => 'Hero Badge',
            'contact_hero_heading_line1' => 'Hero Heading Line 1',
            'contact_hero_heading_highlight' => 'Hero Heading Highlight',
            'contact_hero_subtext' => 'Hero Subtext',
            'social_instagram' => 'Instagram',
            'social_facebook' => 'Facebook',
            'social_linkedin' => 'LinkedIn',
            'social_youtube' => 'YouTube',
            'contact_map_embed' => 'Map Embed',
            'contact_map_url' => 'Map URL',
        ];

        foreach ($fields as $field => $label) {
            $oldValue = $oldValues[str_replace('contact_', '', str_replace('social_', '', $field))] ?? null;
            if ($request->filled($field) && $oldValue != $request->$field) {
                $changes[] = $label;
            }
        }

        if ($request->hasFile('contact_hero_image')) {
            $changes[] = 'Hero Image';
        }

        // Log: Contact settings updated
        $logMessage = 'Contact settings updated';
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        } else {
            $logMessage .= '. No changes made';
        }
        $this->log('update', 'contact_settings', $logMessage);

        return back()->with('success', 'Contact settings updated successfully.');
    }
}
