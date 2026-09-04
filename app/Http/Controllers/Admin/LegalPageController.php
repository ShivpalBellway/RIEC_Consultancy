<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class LegalPageController extends Controller
{
    use LogsActivity;

    public function edit()
    {
        $privacyPolicy = LegalPage::contentFor('privacy-policy');
        $termsConditions = LegalPage::contentFor('terms-conditions');

        return view('pages.admin.legal-pages.edit', compact('privacyPolicy', 'termsConditions'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'page' => 'required|in:privacy-policy,terms-conditions',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        LegalPage::updateOrCreate(
            ['slug' => $data['page']],
            ['title' => $data['title'], 'content' => $data['content']]
        );

        $this->log('update', 'legal_pages', 'Admin updated ' . $data['page']);

        return back()->with('success', $data['title'] . ' updated successfully.');
    }
}