<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\SiteSetting;
use App\Models\Admin;
use App\Mail\AgentActivityAlertMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AgentNotificationService
{
    /**
     * Send email alert to Admin and log the agent activity.
     */
    public static function notifyAdminAndLog(
        string $agentName,
        string $action,
        string $module,
        string $description,
        array $details = []
    ): void {
        // 1. Audit Log Entry
        try {
            ActivityLog::create([
                'admin_name'  => "Agent: {$agentName}",
                'action'      => $action,
                'module'      => $module,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'created_at'  => now(),
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to write activity log: " . $e->getMessage());
        }

        // 2. Automated Admin Email Dispatch
        try {
            $adminEmail = SiteSetting::applicationRecipientEmail();

            if (!$adminEmail) {
                // Fallback to first active admin
                $adminEmail = Admin::where('is_active', true)->value('email');
            }

            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AgentActivityAlertMail(
                    agentName: $agentName,
                    actionTitle: ucfirst(str_replace('_', ' ', $action)),
                    description: $description,
                    details: $details
                ));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send agent activity email to admin: " . $e->getMessage());
        }
    }
}
