<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqPageController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.web.faqs', compact('faqs'));
    }
}
