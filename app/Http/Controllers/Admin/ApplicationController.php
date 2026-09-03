<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\LogsActivity; // ← Add this
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Application;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusMail;
use App\Mail\AgentActivityAlertMail;

class ApplicationController extends Controller
{
    use LogsActivity; // ← Add this trait

    public function index(Request $request)
    {
        $query = Application::with(['program', 'user']);

        // Filter by program
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(15)->withQueryString();
        $programs = Program::ordered()->get();

        // Log: Viewed applications list
        $logMessage = 'Viewed applications list';
        if ($request->filled('status')) {
            $logMessage .= ' (Filter: status=' . $request->status . ')';
        }
        if ($request->filled('program_id')) {
            $logMessage .= ' (Filter: program=' . $request->program_id . ')';
        }
        if ($request->filled('search')) {
            $logMessage .= ' (Search: ' . $request->search . ')';
        }
        $this->log('view', 'applications', $logMessage);

        return view('pages.admin.applications.index', compact('applications', 'programs'));
    }

    public function export(Request $request)
    {
        $query = Application::with(['program', 'user']);

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->get();
        $count = $applications->count();

        // Log: Export applications
        $logMessage = 'Exported ' . $count . ' applications';
        if ($request->filled('status')) {
            $logMessage .= ' (Status: ' . $request->status . ')';
        }
        if ($request->filled('program_id')) {
            $logMessage .= ' (Program ID: ' . $request->program_id . ')';
        }
        $this->log('export', 'applications', $logMessage);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Applications');

        $headers = [
            'Application ID',
            'Linked User Email',
            'Applicant Name',
            'Applicant Email',
            'Applicant Phone',
            'Program',
            'Program Country',
            'Status',
            'Submitted At',
            'Updated At',
            'Consent Accepted',
            'Consent Accepted At',
            'Consent Text',
            'User Account Linked',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        foreach ($applications as $application) {
            $sheet->fromArray([
                'APP-' . str_pad($application->id, 5, '0', STR_PAD_LEFT),
                optional($application->user)->email,
                $application->name,
                $application->email,
                $application->phone,
                optional($application->program)->name,
                optional($application->program)->country,
                $application->status,
                optional($application->created_at)->format('Y-m-d H:i:s'),
                optional($application->updated_at)->format('Y-m-d H:i:s'),
                $application->consent_accepted ? 'Yes' : 'No',
                optional($application->consent_accepted_at)->format('Y-m-d H:i:s'),
                $application->consent_text,
                $application->user ? 'Yes' : 'No',
            ], null, "A{$row}");

            $row++;
        }

        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'applications_export_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            if (ob_get_level()) {
                ob_end_clean();
            }
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
            'Pragma' => 'public',
            'Expires' => '0',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    protected function formatExportValue(mixed $value): string
    {
        if (is_null($value) || $value === '') {
            return '';
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }

        return (string) $value;
    }

    public function show(Application $application)
    {
        $application->load(['program', 'user']);
        $program = $application->program;

        // Log: Viewed application details
        $this->log('view_details', 'applications', 'Viewed application #APP-' . str_pad($application->id, 5, '0', STR_PAD_LEFT) . ' - ' . $application->name);

        // Fetch sections and fields to group form answers
        $sections = $program->formSections()->with(['fields' => function ($q) {
            $q->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        $groupedAnswers = [];
        $formAnswers = $application->form_answers ?: [];

        // Group form builder fields by their section
        foreach ($sections as $section) {
            $sectionAnswers = [];
            foreach ($section->fields as $field) {
                $key = $field->field_key;
                if (isset($formAnswers[$key])) {
                    $sectionAnswers[] = [
                        'field_key' => $key,
                        'label'   => $field->label,
                        'value'   => $formAnswers[$key]['value'] ?? '—',
                        'is_file' => $formAnswers[$key]['is_file'] ?? false,
                        'store_in_system' => $formAnswers[$key]['store_in_system'] ?? true,
                    ];
                    // Remove so we can track leftover/deleted fields
                    unset($formAnswers[$key]);
                }
            }
            if (!empty($sectionAnswers)) {
                $groupedAnswers[] = [
                    'section_name' => $section->name,
                    'answers'      => $sectionAnswers,
                ];
            }
        }

        // Leftover fields (e.g. if field/section was deleted after submission)
        if (!empty($formAnswers)) {
            $leftovers = [];
            foreach ($formAnswers as $key => $ans) {
                $leftovers[] = [
                    'field_key' => $key,
                    'label'   => $ans['label'] ?? $key,
                    'value'   => $ans['value'] ?? '—',
                    'is_file' => $ans['is_file'] ?? false,
                    'store_in_system' => $ans['store_in_system'] ?? true,
                ];
            }
            $groupedAnswers[] = [
                'section_name' => 'Additional Info (Unassigned)',
                'answers'      => $leftovers,
            ];
        }

        return view('pages.admin.applications.show', compact('application', 'groupedAnswers'));
    }

    public function downloadAttachment(Application $application, string $fieldKey)
    {
        $attachment = $application->storedAttachment($fieldKey);

        abort_unless($attachment && Storage::disk('local')->exists($attachment['path']), 404);

        $this->log(
            'download_attachment',
            'applications',
            'Downloaded attachment from application #APP-' . str_pad($application->id, 5, '0', STR_PAD_LEFT)
        );

        return Storage::disk('local')->download($attachment['path'], $attachment['name'], [
            'Cache-Control' => 'private, no-store, max-age=0',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function updateStatus(Request $request, Application $application)
    {
        $validStatuses = [
            'pending',
            'received',
            'university_applied',
            'tuition_fee_confirmed',
            'visa_applied',
            'visa_granted',
            'visa_rejected',
            'studying',
            'refund_complete',
        ];

        $request->validate([
            'status' => 'required|string|in:' . implode(',', $validStatuses),
        ]);

        $statusLabels = [
            'pending'                => 'Pending',
            'received'               => 'Received',
            'university_applied'     => 'University Applied',
            'tuition_fee_confirmed'  => 'Tuition Fee Confirmed',
            'visa_applied'           => 'Visa Applied',
            'visa_granted'           => 'Visa Granted',
            'visa_rejected'          => 'Visa Rejected',
            'studying'               => 'Studying',
            'refund_complete'        => 'Refund Complete',
        ];

        // DEBUG — confirm method is called
        // \Log::info('updateStatus called', [
        //     'application_id' => $application->id,
        //     'email'          => $application->email,
        //     'new_status'     => $request->status,
        // ]);

        $oldStatus = $application->status;
        $newStatus = $request->status;

        $application->update([
            'status' => $newStatus,
        ]);

        // Log: Status update
        $this->log(
            'status_update',
            'applications',
            'Application #APP-' . str_pad($application->id, 5, '0', STR_PAD_LEFT) .
                ' status changed from ' . $oldStatus . ' to ' . $newStatus .
                ' - ' . $application->name
        );

        // Notify the configured Admin recipient about every application update.
        $adminEmail = SiteSetting::applicationRecipientEmail();
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new AgentActivityAlertMail(
                    agentName: 'Admin',
                    actionTitle: 'Application Updated',
                    description: 'Application status was updated in the Admin portal.',
                    details: [
                        'Application ID' => 'APP-' . str_pad($application->id, 5, '0', STR_PAD_LEFT),
                        'Applicant' => $application->name,
                        'Previous Status' => $oldStatus,
                        'New Status' => $statusLabels[$newStatus] ?? ucfirst($newStatus),
                    ]
                ));
            } catch (\Exception $e) {
                \Log::error('Admin application activity email failed: ' . $e->getMessage());
            }
        }

        // Send status email to student's login email — skip 'studying' status
        //  if ($newStatus !== 'studying' && !empty($application->email)) {
        if ($newStatus !== 'studying' && !empty($application->user?->email)) {
            try {
                //   Mail::to($application->email)    for application mail
                Mail::to($application->user->email)
                    ->send(new ApplicationStatusMail(
                        $application,
                        $statusLabels[$newStatus] ?? ucfirst($newStatus)
                    ));
            } catch (\Exception $e) {
                // Mail failure should not block the status update
                \Log::error('ApplicationStatusMail failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Application status updated successfully!');
    }
}
