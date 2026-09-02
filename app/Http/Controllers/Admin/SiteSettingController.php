<?php

namespace App\Http\Controllers\Admin;

use App\Models\SiteSetting;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    use LogsActivity;

    public function edit()
    {
        $setting = SiteSetting::first();

        if (!$setting) {
            $setting = SiteSetting::create([]);
        }

        // Log: Opened site settings
        $this->log('edit_form', 'site_settings', 'Admin opened site settings');

        return view('pages.admin.site.site-settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'header_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'hero_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'hero_badge'              => 'nullable|string|max:100',
            'hero_heading_line1'      => 'nullable|string|max:100',
            'hero_heading_line2'      => 'nullable|string|max:100',
            'hero_heading_highlight'  => 'nullable|string|max:100',
            'hero_subtext'            => 'nullable|string|max:500',
            'stats_badge'             => 'nullable|string|max:100',
            'stats_heading_line1'     => 'nullable|string|max:100',
            'stats_heading_line2'     => 'nullable|string|max:100',
            'stats_heading_highlight' => 'nullable|string|max:100',
            'stats_subtext'           => 'nullable|string|max:500',
            'stat_countries'          => 'nullable|string|max:20',
            'stat_satisfaction'       => 'nullable|string|max:20',
            'hero_btn1_text'          => 'nullable|string|max:50',
            'hero_btn2_text'          => 'nullable|string|max:50',
            'hero_btn2_url'           => 'nullable|string|max:255',
            'application_recipient_email' => 'required|email:rfc|max:255',
        ]);

        $setting = SiteSetting::first() ?? SiteSetting::create([]);

        // Store old values for logging
        $oldValues = [
            'hero_badge' => $setting->hero_badge,
            'hero_heading_line1' => $setting->hero_heading_line1,
            'hero_heading_line2' => $setting->hero_heading_line2,
            'hero_heading_highlight' => $setting->hero_heading_highlight,
            'hero_subtext' => $setting->hero_subtext,
            'stats_badge' => $setting->stats_badge,
            'stats_heading_line1' => $setting->stats_heading_line1,
            'stats_heading_line2' => $setting->stats_heading_line2,
            'stats_heading_highlight' => $setting->stats_heading_highlight,
            'stats_subtext' => $setting->stats_subtext,
            'stat_countries' => $setting->stat_countries,
            'stat_satisfaction' => $setting->stat_satisfaction,
            'hero_btn1_text' => $setting->hero_btn1_text,
            'hero_btn2_text' => $setting->hero_btn2_text,
            'hero_btn2_url' => $setting->hero_btn2_url,
            'application_recipient_email' => $setting->application_recipient_email,
        ];

        $hasImageUpload = false;

        if ($request->hasFile('header_logo')) {
            if ($setting->header_logo) {
                Storage::disk('public')->delete($setting->header_logo);
            }
            $setting->header_logo = $request->file('header_logo')->store('settings', 'public');
            $hasImageUpload = true;
        }

        if ($request->hasFile('footer_logo')) {
            if ($setting->footer_logo) {
                Storage::disk('public')->delete($setting->footer_logo);
            }
            $setting->footer_logo = $request->file('footer_logo')->store('settings', 'public');
            $hasImageUpload = true;
        }

        if ($request->hasFile('hero_image')) {
            if ($setting->hero_image) {
                Storage::disk('public')->delete($setting->hero_image);
            }
            $setting->hero_image = $request->file('hero_image')->store('settings', 'public');
            $hasImageUpload = true;
        }

        $setting->fill($request->only([
            'hero_badge', 'hero_heading_line1', 'hero_heading_line2',
            'hero_heading_highlight', 'hero_subtext',
            'stats_badge', 'stats_heading_line1', 'stats_heading_line2',
            'stats_heading_highlight', 'stats_subtext',
            'stat_countries', 'stat_satisfaction',
            'hero_btn1_text', 'hero_btn2_text', 'hero_btn2_url',
            'application_recipient_email',
        ]))->save();

        // Track changes for logging
        $changes = [];
        $fields = [
            'hero_badge' => 'Hero Badge',
            'hero_heading_line1' => 'Hero Heading Line 1',
            'hero_heading_line2' => 'Hero Heading Line 2',
            'hero_heading_highlight' => 'Hero Heading Highlight',
            'hero_subtext' => 'Hero Subtext',
            'stats_badge' => 'Stats Badge',
            'stats_heading_line1' => 'Stats Heading Line 1',
            'stats_heading_line2' => 'Stats Heading Line 2',
            'stats_heading_highlight' => 'Stats Heading Highlight',
            'stats_subtext' => 'Stats Subtext',
            'stat_countries' => 'Stats Countries',
            'stat_satisfaction' => 'Stats Satisfaction',
            'hero_btn1_text' => 'Hero Button 1 Text',
            'hero_btn2_text' => 'Hero Button 2 Text',
            'hero_btn2_url' => 'Hero Button 2 URL',
            'application_recipient_email' => 'Application Recipient Email',
        ];

        foreach ($fields as $field => $label) {
            $oldValue = $oldValues[$field] ?? null;
            $newValue = $request->$field ?? null;
            if ($oldValue != $newValue) {
                $changes[] = $label;
            }
        }

        if ($hasImageUpload) {
            $changes[] = 'Image(s)';
        }

        // Log: Site settings updated
        $logMessage = 'Site settings updated';
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        } else {
            $logMessage .= '. No changes made';
        }
        $this->log('update', 'site_settings', $logMessage);

        return back()->with('success', 'Settings updated successfully.');
    }
}
