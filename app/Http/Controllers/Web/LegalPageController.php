<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;

class LegalPageController extends Controller
{
    public function show(string $slug)
    {
        abort_unless(in_array($slug, ['privacy-policy', 'terms-conditions'], true), 404);

        $page = LegalPage::contentFor($slug);

        return view('pages.web.legal-page', compact('page'));
    }
}