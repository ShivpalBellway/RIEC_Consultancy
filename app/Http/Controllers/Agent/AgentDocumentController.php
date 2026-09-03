<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Services\AgentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AgentDocumentController extends Controller
{
    public function upload(Request $request, Student $student)
    {
        $agent = Auth::guard('agent')->user();

        if ($student->agent_id !== $agent->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'document_type' => ['required', Rule::in(array_keys(StudentDocument::agentMandatoryDocumentTypes()))],
            'file'          => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB
        ]);

        $file = $request->file('file');
        $documentType = $request->input('document_type');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $fileSize = $file->getSize();

        // Delete existing document of same type if re-uploading
        $existing = StudentDocument::where('student_id', $student->id)
            ->where('document_type', $documentType)
            ->first();

        if ($existing) {
            if ($existing->status !== 'rejected') {
                $message = 'This document can only be replaced after Admin rejects it.';

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }

                return back()->with('error', $message);
            }

            if ($existing->file_path && Storage::disk('public')->exists($existing->file_path)) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $existing->delete();
        }

        // Store only after replacement rules pass, so rejected attempts do not leave orphan files.
        $path = $file->store('student_documents', 'public');
        $isMandatory = true;

        $doc = StudentDocument::create([
            'student_id'             => $student->id,
            'agent_id'               => $agent->id,
            'document_type'          => $documentType,
            'document_name'          => $originalName,
            'file_path'              => $path,
            'file_size'              => $fileSize,
            'mime_type'              => $mimeType,
            'is_mandatory'           => $isMandatory,
            'status'                 => 'uploaded',
            'removal_request_status' => 'none',
        ]);

        // Automated Admin Email Dispatch & Audit Log
        AgentNotificationService::notifyAdminAndLog(
            agentName: $agent->name,
            action: 'document_uploaded',
            module: 'agent_documents',
            description: "Agent uploaded document '{$doc->document_type_name}' for student: {$student->full_name}",
            details: [
                'Student Name'  => $student->full_name,
                'Document Type' => $doc->document_type_name,
                'File Name'     => $originalName,
                'File Size'     => number_format($fileSize / 1024, 2) . ' KB',
            ]
        );

        if ($request->wantsJson() || $request->ajax()) {
            $student->refresh();
            $allDocs = StudentDocument::where('student_id', $student->id)->get();
            $uploadedTypes = $allDocs->pluck('document_type')->toArray();
            $mandatoryKeys = array_keys(StudentDocument::mandatoryDocumentTypes());
            $uploadedCount = $allDocs
                ->whereIn('document_type', $mandatoryKeys)
                ->where('status', '!=', 'rejected')
                ->pluck('document_type')
                ->unique()
                ->count();
            $totalCount = count($mandatoryKeys);
            $percentage = round(($uploadedCount / max($totalCount, 1)) * 100);

            return response()->json([
                'success' => true,
                'message' => "'{$doc->document_type_name}' uploaded successfully!",
                'document' => [
                    'id'            => $doc->id,
                    'type'          => $doc->document_type,
                    'type_name'     => $doc->document_type_name,
                    'name'          => $doc->document_name,
                    'url'           => Storage::disk('public')->url($doc->file_path),
                    'uploaded_at'   => $doc->created_at->format('d M Y, h:i A'),
                    'status'        => ucfirst($doc->status),
                ],
                'uploaded_count' => $uploadedCount,
                'total_count'    => $totalCount,
                'percentage'     => $percentage,
            ]);
        }

        return back()->with('success', "'{$doc->document_type_name}' uploaded successfully.");
    }

    public function uploadBatch(Request $request, Student $student)
    {
        $agent = Auth::guard('agent')->user();

        if ($student->agent_id !== $agent->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $allowedTypes = StudentDocument::agentMandatoryDocumentTypes();
        $request->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $files = $request->file('files', []);
        foreach ($files as $documentType => $file) {
            if (!array_key_exists($documentType, $allowedTypes)) {
                return response()->json(['success' => false, 'message' => 'Invalid document type selected.'], 422);
            }

            $existing = StudentDocument::where('student_id', $student->id)
                ->where('document_type', $documentType)
                ->first();
            if ($existing && $existing->status !== 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => "{$allowedTypes[$documentType]} is already uploaded and awaiting review.",
                ], 422);
            }
        }

        $uploadedDocuments = [];
        foreach ($files as $documentType => $file) {
            $existing = StudentDocument::where('student_id', $student->id)
                ->where('document_type', $documentType)
                ->first();
            if ($existing) {
                if ($existing->file_path && Storage::disk('public')->exists($existing->file_path)) {
                    Storage::disk('public')->delete($existing->file_path);
                }
                $existing->delete();
            }

            $document = StudentDocument::create([
                'student_id' => $student->id,
                'agent_id' => $agent->id,
                'document_type' => $documentType,
                'document_name' => $file->getClientOriginalName(),
                'file_path' => $file->store('student_documents', 'public'),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
                'is_mandatory' => true,
                'status' => 'uploaded',
                'removal_request_status' => 'none',
            ]);
            $uploadedDocuments[] = $document;
        }

        AgentNotificationService::notifyAdminAndLog(
            agentName: $agent->name,
            action: 'documents_uploaded',
            module: 'agent_documents',
            description: "Agent uploaded " . count($uploadedDocuments) . " documents for student: {$student->full_name}",
            details: [
                'Student Name' => $student->full_name,
                'Documents' => collect($uploadedDocuments)->map(fn ($document) => $document->document_type_name)->implode(', '),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => count($uploadedDocuments) . ' document(s) uploaded successfully. One Admin notification was sent.',
        ]);
    }

    public function submitForReview(Request $request, Student $student)
    {
        $agent = Auth::guard('agent')->user();

        if ($student->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($student->status !== 'submitted') {
            return back()->with('error', 'This student is already in the review or a later phase.');
        }

        $mandatoryTypes = array_keys(StudentDocument::agentMandatoryDocumentTypes());
        $uploadedTypes = StudentDocument::where('student_id', $student->id)
            ->whereIn('document_type', $mandatoryTypes)
            ->where('status', '!=', 'rejected')
            ->pluck('document_type')
            ->unique()
            ->all();
        $missingTypes = array_diff($mandatoryTypes, $uploadedTypes);

        if ($missingTypes) {
            $missingLabels = array_map(
                fn (string $type) => StudentDocument::agentMandatoryDocumentTypes()[$type],
                $missingTypes
            );

            return back()->with('error', 'Upload all mandatory documents before submitting: ' . implode(', ', $missingLabels));
        }

        $student->update(['status' => 'under_review']);

        AgentNotificationService::notifyAdminAndLog(
            agentName: $agent->name,
            action: 'documents_submitted_for_review',
            module: 'agent_documents',
            description: "Agent submitted all mandatory documents for review for student: {$student->full_name}",
            details: [
                'Student Name' => $student->full_name,
                'Documents' => count($mandatoryTypes),
            ]
        );

        return redirect()->route('agent.students.show', $student)
            ->with('success', 'All mandatory documents submitted for Admin review.');
    }

    public function requestRemoval(Request $request, StudentDocument $document)
    {
        $agent = Auth::guard('agent')->user();

        if ($document->agent_id !== $agent->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($document->status === 'verified') {
            return back()->with('error', 'Verified documents cannot be removed.');
        }

        if ($document->removal_request_status === 'requested') {
            return back()->with('error', 'A removal request is already pending Admin review.');
        }

        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $document->update([
            'removal_request_status' => 'requested',
            'removal_request_reason' => $request->input('reason'),
            'removal_requested_at'   => now(),
        ]);

        // Notify Admin of Removal Request & Log
        AgentNotificationService::notifyAdminAndLog(
            agentName: $agent->name,
            action: 'removal_request',
            module: 'agent_documents',
            description: "Agent requested removal for document '{$document->document_type_name}' (Student: {$document->student?->full_name})",
            details: [
                'Student Name'   => $document->student?->full_name,
                'Document Type'  => $document->document_type_name,
                'File Name'      => $document->document_name,
                'Removal Reason' => $request->input('reason'),
            ]
        );

        return back()->with('success', 'Document removal request submitted successfully. Admin will review your request.');
    }
}
