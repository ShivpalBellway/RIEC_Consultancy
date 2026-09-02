<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Partner;

class AboutPageController extends Controller
{
    public function index()
    {
        $about = AboutUs::getContent();
        $partners = Partner::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('pages.web.aboutUs', compact('about', 'partners'));
    }
}
