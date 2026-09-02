<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ActivityLogController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('admin_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Get total count before pagination
        $totalCount = $query->count();

        // Paginate with 15 per page
        $logs = $query->latest('created_at')->paginate(15)->withQueryString();
        $actions = ActivityLog::distinct('action')->pluck('action');
        $modules = ActivityLog::distinct('module')->pluck('module');

        // Get today's logs count
        $todayLogs = ActivityLog::whereDate('created_at', today())->count();

        $this->log('view_activity_logs', 'activity_log', 'Admin viewed activity logs');

        return view('pages.admin.activity-logs.index', compact('logs', 'actions', 'modules', 'totalCount', 'todayLogs'));
    }

    public function show(ActivityLog $activityLog)
    {
        // Get previous log for reference
        $activityLog->previous_log = ActivityLog::where('id', '<', $activityLog->id)
            ->latest('id')
            ->first();

        $this->log('view_activity_log_detail', 'activity_log',
            'Admin viewed activity log detail: ' . $activityLog->id
        );

        return view('pages.admin.activity-logs.show', compact('activityLog'));
    }

    public function deleteOld(Request $request)
    {
        $retentionDays = 365;
        $this->log(
            'delete_old_attempt',
            'activity_log',
            'Admin attempted to delete activity logs older than the mandatory 365-day retention period'
        );

        if (!$request->boolean('confirmation')) {
            $this->log(
                'delete_old_denied',
                'activity_log',
                'Activity log deletion was denied because confirmation was not provided'
            );

            return redirect()->route('admin.activity-logs.index')
                ->with('error', 'Activity logs must be retained for a minimum of 365 days. Confirm deletion to remove only logs older than one year.');
        }

        try {
            $cutoff = now()->subDays($retentionDays);
            $count = ActivityLog::where('created_at', '<', $cutoff)->delete();

            $this->log(
                'delete_old',
                'activity_log',
                'Admin completed the 365-day retention cleanup. Deleted logs: ' . $count
            );

            $message = $count > 0
                ? $count . ' activity logs older than 365 days have been deleted.'
                : 'No activity logs are eligible for deletion. Logs are retained for a minimum of 365 days.';

            return redirect()->route('admin.activity-logs.index')
                             ->with('success', $message);

        } catch (\Exception $e) {
            $this->log(
                'delete_old_failed',
                'activity_log',
                'The 365-day retention cleanup failed'
            );

            return redirect()->route('admin.activity-logs.index')
                             ->with('error', 'Unable to delete eligible logs. Activity logs newer than 365 days remain protected.');
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $query = $this->getFilteredQuery($request);
            $logs = $query->latest('created_at')->get();
            $count = $logs->count();

            $this->log('export_excel', 'activity_log', 'Admin exported ' . $count . ' activity logs as Excel');

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Activity Logs');

            $headers = ['#', 'Admin Name', 'Action', 'Module', 'Description', 'IP Address', 'Created At'];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $column++;
            }

            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1A2F5E'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ];
            $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

            $row = 2;
            foreach ($logs as $index => $log) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $log->admin_name);
                $sheet->setCellValue('C' . $row, $log->action);
                $sheet->setCellValue('D' . $row, $log->module);
                $sheet->setCellValue('E' . $row, $log->description);
                $sheet->setCellValue('F' . $row, $log->ip_address);
                $sheet->setCellValue('G' . $row, $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '');
                $row++;
            }

            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            $sheet->getColumnDimension('E')->setWidth(50);

            $writer = new Xlsx($spreadsheet);
            $fileName = 'activity_logs_' . date('Y-m-d_H-i-s') . '.xlsx';

            return response()->streamDownload(function () use ($writer) {
                if (ob_get_level()) {
                    ob_end_clean();
                }
                $writer->save('php://output');
            }, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'max-age=0, no-cache, must-revalidate',
                'Pragma' => 'public',
                'Expires' => '0',
            ]);

        } catch (\Exception $e) {
            \Log::error('Excel Export error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export: ' . $e->getMessage());
        }
    }

    public function exportCsv(Request $request)
    {
        try {
            $query = $this->getFilteredQuery($request);
            $logs = $query->latest('created_at')->get();
            $count = $logs->count();

            $this->log('export_csv', 'activity_log', 'Admin exported ' . $count . ' activity logs as CSV');

            $fileName = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0, no-cache, must-revalidate',
                'Pragma' => 'public',
                'Expires' => '0',
            ];

            $callback = function() use ($logs) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($handle, ['#', 'Admin Name', 'Action', 'Module', 'Description', 'IP Address', 'Created At']);

                foreach ($logs as $index => $log) {
                    fputcsv($handle, [
                        $index + 1,
                        $log->admin_name,
                        $log->action,
                        $log->module,
                        $log->description,
                        $log->ip_address,
                        $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '',
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('CSV Export error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export: ' . $e->getMessage());
        }
    }

    private function getFilteredQuery($request)
    {
        $query = ActivityLog::query();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('admin_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function stats()
    {
        try {
            $today = now()->toDateString();
            $weekAgo = now()->subDays(7)->toDateString();

            $stats = [
                'total_logs' => ActivityLog::count(),
                'today_logs' => ActivityLog::whereDate('created_at', $today)->count(),
                'week_logs' => ActivityLog::whereDate('created_at', '>=', $weekAgo)->count(),
                'unique_actions' => ActivityLog::distinct('action')->count(),
                'unique_modules' => ActivityLog::distinct('module')->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
