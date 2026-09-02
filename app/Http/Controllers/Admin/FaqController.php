<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $faqs = Faq::orderBy('sort_order', 'asc')->paginate(10);

        // Log: Viewed FAQs
        $this->log('view', 'faqs', 'Viewed FAQs list');

        return view('pages.admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        // Log: Opened FAQ creation form
        $this->log('create_form', 'faqs', 'Opened FAQ creation form');

        return view('pages.admin.faqs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        Faq::create($data);

        $this->log('created', 'faqs', 'Added FAQ: ' . $data['question']);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        // Log: Opened FAQ edit form
        $this->log('edit_form', 'faqs', 'Opened edit form for FAQ: ' . $faq->question);

        return view('pages.admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $oldQuestion = $faq->question;

        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        $faq->update($data);

        $logMessage = 'Updated FAQ';
        if ($oldQuestion != $data['question']) {
            $logMessage .= ' from "' . $oldQuestion . '" to "' . $data['question'] . '"';
        } else {
            $logMessage .= ': ' . $data['question'];
        }
        $this->log('updated', 'faqs', $logMessage);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $question = $faq->question;
        $faq->delete();

        $this->log('deleted', 'faqs', 'Deleted FAQ: ' . $question);

        return back()->with('success', 'FAQ deleted successfully.');
    }

    public function toggleStatus(Request $request, Faq $faq)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $faq->update(['is_active' => $request->is_active]);

        // Log: Toggled FAQ status
        $status = $request->is_active ? 'activated' : 'deactivated';
        $this->log('toggled', 'faqs', $status . ' FAQ: ' . $faq->question);

        return response()->json([
            'success' => true,
            'message' => 'FAQ status updated successfully.',
            'is_active' => $request->is_active
        ]);
    }
}
