<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Feature;
use App\Models\SuccessStory;
use App\Models\Partner;
use App\Traits\LogsActivity; // ← Add this

class WebsiteManageController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index()
    {
        $counts = [
            'services' => Service::count(),
            'features' => Feature::count(),
            'success_stories' => SuccessStory::count(),
            'partners' => Partner::count(),
        ];

        // Log: Viewed website management dashboard
        $this->log('view_website_manage', 'website',
            'Viewed website management dashboard. Stats: ' .
            'Services: ' . $counts['services'] . ', ' .
            'Features: ' . $counts['features'] . ', ' .
            'Success Stories: ' . $counts['success_stories'] . ', ' .
            'Partners: ' . $counts['partners']
        );

        return view('pages.admin.website.index', compact('counts'));
    }
}
