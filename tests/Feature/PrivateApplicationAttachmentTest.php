<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Admin;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PrivateApplicationAttachmentTest extends TestCase
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

    private function applicationWithAttachment(User $user): Application
    {
        $program = Program::create([
            'name' => 'Test Program',
            'country' => 'Test Country',
        ]);

        return Application::create([
            'user_id' => $user->id,
            'program_id' => $program->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '1234567890',
            'form_answers' => [
                'passport' => [
                    'label' => 'Passport',
                    'value' => 'applications/attachments/private-passport.pdf',
                    'original_name' => 'passport.pdf',
                    'mime_type' => 'application/pdf',
                    'is_file' => true,
                    'store_in_system' => true,
                ],
            ],
        ]);
    }

    public function test_admin_can_download_a_private_application_attachment(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('applications/attachments/private-passport.pdf', 'private-document');
        $application = $this->applicationWithAttachment(User::factory()->create());

        $response = $this->withSession([
            'admin_id' => 1,
            'admin_name' => 'Admin',
        ])->get(route('admin.applications.attachments.download', [$application, 'passport']));

        $response->assertOk();
        $response->assertDownload('passport.pdf');
        $response->assertHeader('Cache-Control', 'max-age=0, no-store, private');
    }

    public function test_guest_cannot_download_an_admin_application_attachment(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('applications/attachments/private-passport.pdf', 'private-document');
        $application = $this->applicationWithAttachment(User::factory()->create());

        $this->get(route('admin.applications.attachments.download', [$application, 'passport']))
            ->assertRedirect(route('admin.login'));
    }

    public function test_student_can_only_download_their_own_attachment(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('applications/attachments/private-passport.pdf', 'private-document');
        $owner = User::factory()->create();
        $otherStudent = User::factory()->create();
        $application = $this->applicationWithAttachment($owner);
        $route = route('student.applications.attachments.download', [$application, 'passport']);

        $this->actingAs($owner)->get($route)->assertDownload('passport.pdf');
        $this->actingAs($otherStudent)->get($route)->assertForbidden();
    }

    public function test_unapproved_or_missing_paths_are_not_downloadable(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $application = $this->applicationWithAttachment($user);
        $answers = $application->form_answers;
        $answers['passport']['value'] = '../private-passport.pdf';
        $application->update(['form_answers' => $answers]);

        $this->withSession(['admin_id' => 1])
            ->get(route('admin.applications.attachments.download', [$application, 'passport']))
            ->assertNotFound();
    }

    public function test_legacy_public_attachments_can_be_migrated_to_private_storage(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        Storage::disk('public')->put('applications/attachments/private-passport.pdf', 'legacy-document');
        Storage::disk('public')->put('applications/attachments/orphaned-document.pdf', 'orphaned-document');
        $application = $this->applicationWithAttachment(User::factory()->create());

        Artisan::call('applications:files-to-private');

        Storage::disk('local')->assertExists('applications/attachments/private-passport.pdf');
        Storage::disk('local')->assertExists('applications/attachments/orphaned-document.pdf');
        Storage::disk('public')->assertMissing('applications/attachments/private-passport.pdf');
        Storage::disk('public')->assertMissing('applications/attachments/orphaned-document.pdf');
        $this->assertSame(
            'local',
            $application->fresh()->form_answers['passport']['storage_disk']
        );
    }
}
