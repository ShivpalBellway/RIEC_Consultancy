<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Services\AgentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgentDocumentController extends Controller
{
    public function upload(Request $request, Student $student)
    {
        $agent = Auth::guard('agent')->user();

        if ($student->agent_id !== $agent->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'document_type' => ['required', 'string'],
            'file'          => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB
        ]);

        $file = $request->file('file');
        $documentType = $request->input('document_type');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $fileSize = $file->getSize();

        // Store file in public disk
        $path = $file->store('student_documents', 'public');

        // Check if mandatory
        $mandatoryTypes = array_keys(StudentDocument::mandatoryDocumentTypes());
        $isMandatory = in_array($documentType, $mandatoryTypes);

        // Delete existing document of same type if re-uploading
        $existing = StudentDocument::where('student_id', $student->id)
            ->where('document_type', $documentType)
            ->first();

        if ($existing) {
            if ($existing->file_path && Storage::disk('public')->exists($existing->file_path)) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $existing->delete();
        }

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
            $uploadedCount = count(array_intersect($mandatoryKeys, $uploadedTypes));
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
                    'url'           => Storage::url($doc->file_path),
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

    public function requestRemoval(Request $request, StudentDocument $document)
    {
        $agent = Auth::guard('agent')->user();

        if ($document->agent_id !== $agent->id) {
            return back()->with('error', 'Unauthorized action.');
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
