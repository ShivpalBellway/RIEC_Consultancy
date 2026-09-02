<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageSubmittedMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $contactMessage = ContactMessage::create($data);

        try {
            Mail::to('shivpalbellway@gmail.com')->send(new ContactMessageSubmittedMail($contactMessage));
        } catch (\Exception $e) {
            logger()->error('Failed to send contact message email: ' . $e->getMessage());
        }

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
