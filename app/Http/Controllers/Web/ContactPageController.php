<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::first();
        return view('pages.web.contactUs', compact('setting'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($data);

        return redirect()->route('contact')
            ->with('success', 'Your message has been sent successfully. We will get back to you soon!');
    }
}
