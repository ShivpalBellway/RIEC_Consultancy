<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Application;
use App\Models\Partner;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Http\Request;

class StatsController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function edit()
    {
        $setting        = SiteSetting::first() ?? SiteSetting::create([]);
        $statAdmissions = Application::count();
        $statPartners   = Partner::where('status', 1)->count();

        // Log: Opened stats edit form
        $this->log('edit_form', 'stats',
            'Opened stats section edit form. Current stats: Admissions=' . $statAdmissions .
            ', Partners=' . $statPartners
        );

        return view('pages.admin.stats.edit', compact('setting', 'statAdmissions', 'statPartners'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'stats_badge'             => 'nullable|string|max:100',
            'stats_heading_line1'     => 'nullable|string|max:100',
            'stats_heading_line2'     => 'nullable|string|max:100',
            'stats_heading_highlight' => 'nullable|string|max:100',
            'stats_subtext'           => 'nullable|string|max:500',
            'stat_countries'          => 'nullable|string|max:20',
            'stat_satisfaction'       => 'nullable|string|max:20',
        ]);

        $setting = SiteSetting::first() ?? SiteSetting::create([]);

        // Store old values for logging
        $oldValues = [
            'stats_badge' => $setting->stats_badge,
            'stats_heading_line1' => $setting->stats_heading_line1,
            'stats_heading_line2' => $setting->stats_heading_line2,
            'stats_heading_highlight' => $setting->stats_heading_highlight,
            'stats_subtext' => $setting->stats_subtext,
            'stat_countries' => $setting->stat_countries,
            'stat_satisfaction' => $setting->stat_satisfaction,
        ];

        $setting->fill($request->only([
            'stats_badge', 'stats_heading_line1', 'stats_heading_line2',
            'stats_heading_highlight', 'stats_subtext',
            'stat_countries', 'stat_satisfaction',
        ]))->save();

        // Track changes for logging
        $changes = [];
        $fields = [
            'stats_badge' => 'Stats Badge',
            'stats_heading_line1' => 'Stats Heading Line 1',
            'stats_heading_line2' => 'Stats Heading Line 2',
            'stats_heading_highlight' => 'Stats Heading Highlight',
            'stats_subtext' => 'Stats Subtext',
            'stat_countries' => 'Countries Count',
            'stat_satisfaction' => 'Satisfaction Rate',
        ];

        foreach ($fields as $field => $label) {
            $oldValue = $oldValues[$field] ?? null;
            $newValue = $request->$field ?? null;
            if ($oldValue != $newValue) {
                $changes[] = $label;
            }
        }

        // Log: Stats updated
        $logMessage = 'Stats section updated';
        if (!empty($changes)) {
            $logMessage .= '. Changes: ' . implode(', ', $changes);
        } else {
            $logMessage .= '. No changes made';
        }
        $this->log('update', 'stats', $logMessage);

        return back()->with('success', 'Stats section updated successfully.');
    }
}
