<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Traits\LogsActivity; // ← Add this

class ContactMessageController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);

        // Log: Viewed contact messages
        $this->log('view', 'contact_messages', 'Viewed contact messages list');

        return view('pages.admin.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $wasUnread = !$contactMessage->is_read;

        if ($wasUnread) {
            $contactMessage->update(['is_read' => true]);
        }

        // Log: Viewed contact message details
        $this->log('view_details', 'contact_messages',
            'Viewed contact message from ' . $contactMessage->name .
            ' (Email: ' . $contactMessage->email . ')' .
            ($wasUnread ? ' - Marked as read' : '')
        );

        return view('pages.admin.contact-messages.show', compact('contactMessage'));
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $name = $contactMessage->name;
        $email = $contactMessage->email;

        $contactMessage->delete();

        // Log: Deleted contact message
        $this->log('delete', 'contact_messages',
            'Deleted contact message from ' . $name . ' (Email: ' . $email . ')'
        );

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
