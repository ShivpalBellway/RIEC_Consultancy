<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentDocument;
use App\Models\AgentNotification;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDocumentRemovalController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $requests = StudentDocument::with(['student', 'agent'])
            ->where('removal_request_status', 'requested')
            ->latest('removal_requested_at')
            ->paginate(15);

        $this->log('view', 'document_removals', 'Viewed document removal requests');

        return view('pages.admin.document_removals.index', compact('requests'));
    }

    public function approve(Request $request, StudentDocument $document)
    {
        $agent = $document->agent;
        $docName = $document->document_type_name;
        $studentName = $document->student?->full_name;

        // Delete file from disk
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Delete record
        $document->delete();

        // In-app notification to Agent
        if ($agent) {
            AgentNotification::create([
                'agent_id' => $agent->id,
                'type'     => 'removal_approved',
                'title'    => 'Document Removal Approved',
                'message'  => "Admin approved the removal request for '{$docName}' (Student: {$studentName}). The document has been removed.",
                'link'     => route('agent.students.show', $document->student_id),
            ]);
        }

        $this->log('approve_removal', 'document_removals', "Approved document removal for '{$docName}' (Student: {$studentName})");

        return back()->with('success', "Removal request approved. The document has been removed.");
    }

    public function reject(Request $request, StudentDocument $document)
    {
        $request->validate([
            'admin_comment' => ['nullable', 'string', 'max:500'],
        ]);

        $document->update([
            'removal_request_status' => 'rejected',
            'admin_comment'          => $request->input('admin_comment'),
        ]);

        $agent = $document->agent;
        $docName = $document->document_type_name;
        $studentName = $document->student?->full_name;

        // In-app notification to Agent
        if ($agent) {
            AgentNotification::create([
                'agent_id' => $agent->id,
                'type'     => 'removal_rejected',
                'title'    => 'Document Removal Rejected',
                'message'  => "Admin rejected the removal request for '{$docName}' (Student: {$studentName}). Reason: " . ($request->input('admin_comment') ?? 'No reason provided.'),
                'link'     => route('agent.students.show', $document->student_id),
            ]);
        }

        $this->log('reject_removal', 'document_removals', "Rejected document removal for '{$docName}' (Student: {$studentName})");

        return back()->with('success', "Removal request rejected.");
    }
}
