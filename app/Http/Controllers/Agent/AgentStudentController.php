<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Services\AgentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentStudentController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        $query = Student::where('agent_id', $agent->id)->withCount('documents');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('passport_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->latest()->paginate(10)->withQueryString();

        return view('pages.agent.students.index', compact('students'));
    }

    public function create()
    {
        return view('pages.agent.students.create');
    }

    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $data = $request->validate([
            'first_name'            => ['required', 'string', 'max:100'],
            'last_name'             => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:255'],
            'phone'                 => ['nullable', 'string', 'max:50'],
            'passport_number'       => ['nullable', 'string', 'max:100'],
            'date_of_birth'         => ['nullable', 'date'],
            'gender'                => ['nullable', 'string', 'max:20'],
            'nationality'           => ['nullable', 'string', 'max:100'],

            // Korean Address Section
            'korean_address'        => ['nullable', 'string'],
            'korean_city'           => ['nullable', 'string', 'max:100'],
            'korean_postal_code'    => ['nullable', 'string', 'max:30'],
            'korean_contact_number' => ['nullable', 'string', 'max:50'],
        ]);

        $student = Student::create([
            'agent_id'              => $agent->id,
            'first_name'            => $data['first_name'],
            'last_name'             => $data['last_name'],
            'email'                 => $data['email'],
            'phone'                 => $data['phone'] ?? null,
            'passport_number'       => $data['passport_number'] ?? null,
            'date_of_birth'         => $data['date_of_birth'] ?? null,
            'gender'                => $data['gender'] ?? null,
            'nationality'           => $data['nationality'] ?? null,
            'korean_address'        => $data['korean_address'] ?? null,
            'korean_city'           => $data['korean_city'] ?? null,
            'korean_postal_code'    => $data['korean_postal_code'] ?? null,
            'korean_contact_number' => $data['korean_contact_number'] ?? null,
            'university_name'       => null, // Set only by Admin
            'status'                => 'submitted',
        ]);

        // Automated Email Alert to Admin & Audit Log
        AgentNotificationService::notifyAdminAndLog(
            agentName: $agent->name,
            action: 'student_created',
            module: 'agent_students',
            description: "Agent created student record: {$student->full_name}",
            details: [
                'Student Name'   => $student->full_name,
                'Email'          => $student->email,
                'Passport No.'   => $student->passport_number ?? 'N/A',
                'Korean Address' => $student->korean_address ?? 'N/A',
            ]
        );

        return redirect()->route('agent.students.show', $student)
            ->with('success', 'Student added successfully! You can now upload mandatory documents.');
    }

    public function show(Student $student)
    {
        $agent = Auth::guard('agent')->user();
        if ($student->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $documents = StudentDocument::where('student_id', $student->id)->get();
        $mandatoryTypes = StudentDocument::mandatoryDocumentTypes();

        return view('pages.agent.students.show', compact('student', 'documents', 'mandatoryTypes'));
    }

    public function edit(Student $student)
    {
        $agent = Auth::guard('agent')->user();
        if ($student->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($student->status === 'completed') {
            return back()->with('error', 'Completed applications cannot be edited.');
        }

        return view('pages.agent.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $agent = Auth::guard('agent')->user();
        if ($student->agent_id !== $agent->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($student->status === 'completed') {
            return back()->with('error', 'Completed applications cannot be edited.');
        }

        $data = $request->validate([
            'first_name'            => ['required', 'string', 'max:100'],
            'last_name'             => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:255'],
            'phone'                 => ['nullable', 'string', 'max:50'],
            'passport_number'       => ['nullable', 'string', 'max:100'],
            'date_of_birth'         => ['nullable', 'date'],
            'gender'                => ['nullable', 'string', 'max:20'],
            'nationality'           => ['nullable', 'string', 'max:100'],

            // Korean Address Section
            'korean_address'        => ['nullable', 'string'],
            'korean_city'           => ['nullable', 'string', 'max:100'],
            'korean_postal_code'    => ['nullable', 'string', 'max:30'],
            'korean_contact_number' => ['nullable', 'string', 'max:50'],
        ]);

        $addressChanged = ($student->korean_address !== $data['korean_address'] ||
                           $student->korean_city !== $data['korean_city'] ||
                           $student->korean_postal_code !== $data['korean_postal_code'] ||
                           $student->korean_contact_number !== $data['korean_contact_number']);

        $student->update($data);

        // Automated Email Alert to Admin & Audit Log
        $action = $addressChanged ? 'address_updated' : 'student_updated';
        $description = $addressChanged
            ? "Agent updated Korean Address for student: {$student->full_name}"
            : "Agent updated profile for student: {$student->full_name}";

        AgentNotificationService::notifyAdminAndLog(
            agentName: $agent->name,
            action: $action,
            module: 'agent_students',
            description: $description,
            details: [
                'Student Name'   => $student->full_name,
                'Korean Address' => $student->korean_address ?? 'N/A',
                'Korean City'    => $student->korean_city ?? 'N/A',
                'Postal Code'    => $student->korean_postal_code ?? 'N/A',
                'Contact Phone'  => $student->korean_contact_number ?? 'N/A',
            ]
        );

        return redirect()->route('agent.students.show', $student)
            ->with('success', 'Student details updated successfully.');
    }
}
