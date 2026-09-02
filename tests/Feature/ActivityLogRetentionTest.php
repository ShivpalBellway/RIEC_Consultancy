<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ActivityLogRetentionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secure-password',
            'is_active' => true,
        ]);
    }

    private function createLogAt(string $description, $createdAt): ActivityLog
    {
        $log = ActivityLog::create([
            'admin_name' => 'Admin',
            'action' => 'test',
            'module' => 'test',
            'description' => $description,
            'ip_address' => '127.0.0.1',
        ]);

        ActivityLog::whereKey($log->id)->update(['created_at' => $createdAt]);

        return $log->fresh();
    }

    public function test_cleanup_deletes_only_logs_older_than_365_days(): void
    {
        $oldLog = $this->createLogAt('older than retention', now()->subDays(366));
        $protectedLog = $this->createLogAt('inside retention', now()->subDays(364));
        $boundaryLog = $this->createLogAt('retention boundary', now()->subDays(365)->addMinute());

        $response = $this->withSession([
            'admin_id' => 1,
            'admin_name' => 'Admin',
        ])->post(route('admin.activity-logs.delete-old'), [
            'confirmation' => 'yes',
            'days' => 1,
        ]);

        $response->assertSessionHas(
            'success',
            '1 activity logs older than 365 days have been deleted.'
        );
        $this->assertDatabaseMissing('activity_logs', ['id' => $oldLog->id]);
        $this->assertDatabaseHas('activity_logs', ['id' => $protectedLog->id]);
        $this->assertDatabaseHas('activity_logs', ['id' => $boundaryLog->id]);
        $this->assertDatabaseHas('activity_logs', ['action' => 'delete_old_attempt']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'delete_old']);
    }

    public function test_cleanup_without_confirmation_is_denied_and_logged(): void
    {
        $oldLog = $this->createLogAt('must remain after denied request', now()->subDays(500));

        $response = $this->withSession([
            'admin_id' => 1,
            'admin_name' => 'Admin',
        ])->post(route('admin.activity-logs.delete-old'));

        $response->assertSessionHas(
            'error',
            'Activity logs must be retained for a minimum of 365 days. Confirm deletion to remove only logs older than one year.'
        );
        $this->assertDatabaseHas('activity_logs', ['id' => $oldLog->id]);
        $this->assertDatabaseHas('activity_logs', ['action' => 'delete_old_attempt']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'delete_old_denied']);
    }

    public function test_clear_all_route_is_removed_and_ui_explains_retention(): void
    {
        $this->assertFalse(Route::has('admin.activity-logs.clear'));

        $this->withSession([
            'admin_id' => 1,
            'admin_name' => 'Admin',
        ])->get(route('admin.activity-logs.index'))
            ->assertOk()
            ->assertDontSee('Clear All')
            ->assertSee('Minimum 365-day retention is mandatory');
    }
}
