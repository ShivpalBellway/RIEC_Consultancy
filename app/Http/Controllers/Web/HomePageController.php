<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Service;
use App\Models\SuccessStory;
use App\Models\Partner;
use App\Models\Faq;
use App\Models\Blog;
use App\Models\ProcessStep;
use App\Models\Application;

class HomePageController extends Controller
{
    public function index()
    {
        // Get active features
        $features = Feature::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Get active services
        $services = Service::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Get active success stories
        $successStories = SuccessStory::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Get active partners
        $partners = Partner::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Get recent blogs for homepage
        $recentBlogs = Blog::where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get FAQ for homepage (if needed)
        $faqs = Faq::where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->limit(6)
            ->get();

        $processSteps = ProcessStep::where('status', 1)->orderBy('sort_order')->get();

        // Auto stats
        $statAdmissions = Application::count();
        $statPartners   = Partner::where('status', 1)->count();

        return view('pages.web.home', compact(
            'features', 'services', 'successStories', 'partners',
            'recentBlogs', 'faqs', 'processSteps',
            'statAdmissions', 'statPartners'
        ));
    }
}
