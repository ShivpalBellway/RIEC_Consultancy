<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServicePageController extends Controller
{
    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $services = Service::where('status', 1)
            ->where('id', '!=', $service->id)
            ->orderBy('sort_order', 'asc')
            ->take(3)
            ->get();

        return view('pages.web.services.show', compact('service', 'services'));
    }

    public function index()
    {
        $services = Service::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('pages.web.services.index', compact('services'));
    }
}
