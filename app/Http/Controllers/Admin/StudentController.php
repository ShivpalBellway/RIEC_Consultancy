<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\AgentNotification;
use App\Mail\AgentStudentUpdateMail;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    use LogsActivity;

    /* ─── Status label map ─── */
    private array $statusLabels = [
        'submitted'           => 'Submitted',
        'under_review'        => 'Under Review',
        'university_assigned' => 'University Assigned',
        'offer_letter'        => 'Offer Letter Phase',
        'visa'                => 'Visa Phase',
        'completed'           => 'Completed / Enrolled',
    ];

    /* ─────────────────────────────────────────────────────────────
     | INDEX
     |───────────────────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $query = Student::with(['agent', 'documents']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('passport_number', 'like', "%{$search}%")
                  ->orWhere('university_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('agent')) {
            $query->where('agent_id', $request->agent);
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        $this->log('view', 'students', 'Viewed Agent Students list');

        return view('pages.admin.students.index', compact('students'));
    }

    /* ─────────────────────────────────────────────────────────────
     | SHOW
     |───────────────────────────────────────────────────────────── */
    public function show(Student $student)
    {
        $student->load(['agent', 'documents']);
        $mandatoryTypes = StudentDocument::allDocumentTypes();

        $this->log('view', 'students', "Viewed student details for {$student->full_name}");

        return view('pages.admin.students.show', compact('student', 'mandatoryTypes'));
    }

    /* ─────────────────────────────────────────────────────────────
     | UPDATE UNIVERSITY
     |───────────────────────────────────────────────────────────── */
    public function updateUniversity(Request $request, Student $student)
    {
        $request->validate([
            'university_name' => ['required', 'string', 'max:255'],
        ]);

        $newUni = $request->university_name;
        $student->update(['university_name' => $newUni]);

        // ── In-app notification ──
        if ($student->agent) {
            AgentNotification::create([
                'agent_id' => $student->agent_id,
                'type'     => 'university_assigned',
                'title'    => 'University Assigned / Updated',
                'message'  => "Admin assigned university '{$newUni}' to student {$student->full_name}.",
                'link'     => route('agent.students.show', $student->id),
            ]);

            // ── Email to Agent ──
            $this->sendAgentEmail(
                agent: $student->agent,
                actionType: 'university_assigned',
                actionTitle: '🏛️ University Assigned',
                studentName: $student->full_name,
                message: "Great news! A university has been assigned to your student {$student->full_name}. They are now in the University Assigned phase of their application.",
                portalLink: route('agent.students.show', $student->id),
                details: [
                    'Student Name'        => $student->full_name,
                    'Assigned University' => $newUni,
                    'Application Phase'   => 'University Assigned',
                ]
            );
        }

        $this->log('update_university', 'students', "Updated university for {$student->full_name} to {$newUni}");

        return back()
            ->with('active_tab', 'status')
            ->with('success', "University assigned successfully: {$newUni}");
    }

    /* ─────────────────────────────────────────────────────────────
     | UPDATE STATUS
     |───────────────────────────────────────────────────────────── */
    public function updateStatus(Request $request, Student $student)
    {
        $request->validate([
            'status' => ['required', 'in:submitted,under_review,university_assigned,offer_letter,visa,completed'],
        ]);

        $newStatus      = $request->status;
        $newStatusLabel = $this->statusLabels[$newStatus] ?? ucfirst(str_replace('_', ' ', $newStatus));

        $student->update(['status' => $newStatus]);

        // ── In-app notification ──
        if ($student->agent) {
            AgentNotification::create([
                'agent_id' => $student->agent_id,
                'type'     => 'status_updated',
                'title'    => 'Application Status Updated',
                'message'  => "Status for student {$student->full_name} updated to {$newStatusLabel}",
                'link'     => route('agent.students.show', $student->id),
            ]);

            // ── Email to Agent ──
            $this->sendAgentEmail(
                agent: $student->agent,
                actionType: 'status_updated',
                actionTitle: '📋 Application Status Updated',
                studentName: $student->full_name,
                message: "The application status for your student {$student->full_name} has been updated by the REIAC Global admin team. Please log into your portal to view the details and any next steps.",
                portalLink: route('agent.students.show', $student->id),
                details: [
                    'Student Name'   => $student->full_name,
                    'New Status'     => $newStatusLabel,
                    'Updated By'     => 'REIAC Global Admin',
                    'Date & Time'    => now()->format('d M Y, h:i A'),
                ]
            );
        }

        $this->log('update_status', 'students', "Updated status for {$student->full_name} to {$newStatus}");

        return back()
            ->with('active_tab', 'status')
            ->with('success', "Application status updated to: {$newStatusLabel}");
    }

    /* ─────────────────────────────────────────────────────────────
     | UPLOAD OFFER LETTER (ADMIN ONLY)
     |───────────────────────────────────────────────────────────── */
    public function uploadOfferLetter(Request $request, Student $student)
    {
        $request->validate([
            'offer_letter_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ]);

        $file     = $request->file('offer_letter_file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('student_documents/offer_letters', 'public');

        // Check if offer letter already exists for this student
        $existing = StudentDocument::where('student_id', $student->id)
            ->where('document_type', 'offer_letter')
            ->first();

        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
            $existing->update([
                'document_name' => $fileName,
                'file_path'     => $filePath,
                'file_size'     => $file->getSize(),
                'mime_type'     => $file->getClientMimeType(),
                'status'        => 'verified',
                'admin_comment' => null,
            ]);
            $document = $existing;
        } else {
            $document = StudentDocument::create([
                'student_id'    => $student->id,
                'agent_id'      => $student->agent_id,
                'document_type' => 'offer_letter',
                'document_name' => $fileName,
                'file_path'     => $filePath,
                'file_size'     => $file->getSize(),
                'mime_type'     => $file->getClientMimeType(),
                'is_mandatory'  => true,
                'status'        => 'verified',
            ]);
        }

        // Notify Agent
        if ($student->agent) {
            AgentNotification::create([
                'agent_id' => $student->agent_id,
                'type'     => 'offer_letter_uploaded',
                'title'    => 'Official Offer Letter Uploaded',
                'message'  => "Admin uploaded official Offer Letter for student {$student->full_name}.",
                'link'     => route('agent.students.show', $student->id),
            ]);

            $this->sendAgentEmail(
                agent: $student->agent,
                actionType: 'status_updated',
                actionTitle: '📜 Official Offer Letter Available',
                studentName: $student->full_name,
                message: "Great news! REIAC Global admin team has uploaded the official Offer Letter for student {$student->full_name}. You can view and download it directly from your agent portal.",
                portalLink: route('agent.students.show', $student->id),
                details: [
                    'Student Name'  => $student->full_name,
                    'Document Name' => $fileName,
                    'Uploaded By'   => 'REIAC Global Admin',
                ]
            );
        }

        $this->log('upload_offer_letter', 'students', "Admin uploaded official offer letter for {$student->full_name}");

        return back()
            ->with('active_tab', 'documents')
            ->with('success', "Official Offer Letter uploaded successfully.");
    }

    /* ─────────────────────────────────────────────────────────────
     | VERIFY DOCUMENT
     |───────────────────────────────────────────────────────────── */
    public function verifyDocument(Request $request, StudentDocument $document)
    {
        $document->update([
            'status'        => 'verified',
            'admin_comment' => null,
        ]);

        $student = $document->student;

        // ── In-app notification ──
        if ($student?->agent) {
            AgentNotification::create([
                'agent_id' => $student->agent_id,
                'type'     => 'document_verified',
                'title'    => 'Document Verified',
                'message'  => "Document '{$document->document_type_name}' for student {$student->full_name} has been verified by Admin.",
                'link'     => route('agent.students.show', $student->id),
            ]);

            // ── Email to Agent ──
            $this->sendAgentEmail(
                agent: $student->agent,
                actionType: 'document_verified',
                actionTitle: '✅ Document Verified',
                studentName: $student->full_name,
                message: "We're pleased to inform you that a document submitted for your student {$student->full_name} has been successfully reviewed and verified by our admin team.",
                portalLink: route('agent.students.show', $student->id),
                details: [
                    'Student Name'  => $student->full_name,
                    'Document Type' => $document->document_type_name,
                    'Status'        => '✅ Verified',
                    'Verified On'   => now()->format('d M Y, h:i A'),
                ]
            );
        }

        $this->log('verify_document', 'students', "Verified document '{$document->document_type_name}' for student {$student?->full_name}");

        return back()
            ->with('active_tab', 'documents')
            ->with('success', "Document '{$document->document_type_name}' verified successfully.");
    }

    /* ─────────────────────────────────────────────────────────────
     | REJECT DOCUMENT
     |───────────────────────────────────────────────────────────── */
    public function rejectDocument(Request $request, StudentDocument $document)
    {
        $request->validate([
            'admin_comment' => ['required', 'string', 'max:500'],
        ]);

        $document->update([
            'status'        => 'rejected',
            'admin_comment' => $request->admin_comment,
        ]);

        $student = $document->student;

        // ── In-app notification ──
        if ($student?->agent) {
            AgentNotification::create([
                'agent_id' => $student->agent_id,
                'type'     => 'document_rejected',
                'title'    => 'Document Rejected – Action Required',
                'message'  => "Document '{$document->document_type_name}' for student {$student->full_name} was rejected. Reason: {$request->admin_comment}",
                'link'     => route('agent.students.show', $student->id),
            ]);

            // ── Email to Agent ──
            $this->sendAgentEmail(
                agent: $student->agent,
                actionType: 'document_rejected',
                actionTitle: '❌ Document Review Required',
                studentName: $student->full_name,
                message: "A document submitted for student {$student->full_name} requires your attention. Please log into your portal and re-upload the document with corrections as indicated below.",
                portalLink: route('agent.students.show', $student->id),
                details: [
                    'Student Name'    => $student->full_name,
                    'Document Type'   => $document->document_type_name,
                    'Status'          => '❌ Rejected',
                    'Reason / Comment' => $request->admin_comment,
                    'Action Required' => 'Please re-upload the corrected document.',
                ]
            );
        }

        $this->log('reject_document', 'students', "Rejected document '{$document->document_type_name}' for student {$student?->full_name}");

        return back()
            ->with('active_tab', 'documents')
            ->with('error_msg', "Document '{$document->document_type_name}' rejected. Agent has been notified.");
    }

    /* ─────────────────────────────────────────────────────────────
     | PRIVATE: Send email to agent (silently catch errors)
     |───────────────────────────────────────────────────────────── */
    private function sendAgentEmail(
        $agent,
        string $actionType,
        string $actionTitle,
        string $studentName,
        string $message,
        string $portalLink,
        array  $details = []
    ): void {
        try {
            Mail::to($agent->email)->send(new AgentStudentUpdateMail(
                agentName:   $agent->name,
                actionType:  $actionType,
                actionTitle: $actionTitle,
                studentName: $studentName,
                message:     $message,
                portalLink:  $portalLink,
                details:     $details
            ));
        } catch (\Exception $e) {
            Log::error("Failed to send agent update email to {$agent->email}: " . $e->getMessage());
        }
    }
}
