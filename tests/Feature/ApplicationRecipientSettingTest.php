<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationRecipientSettingTest extends TestCase
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

    public function test_admin_can_update_the_application_recipient_email(): void
    {
        SiteSetting::create([]);

        $response = $this->withSession([
            'admin_id' => 1,
            'admin_name' => 'Admin',
        ])->post(route('admin.site.settings.update'), [
            'application_recipient_email' => 'applications@example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('site_settings', [
            'application_recipient_email' => 'applications@example.com',
        ]);
    }

    public function test_application_recipient_must_be_a_valid_email(): void
    {
        $setting = SiteSetting::create([
            'application_recipient_email' => 'valid@example.com',
        ]);

        $response = $this->withSession(['admin_id' => 1])
            ->from(route('admin.site.settings.edit'))
            ->post(route('admin.site.settings.update'), [
                'application_recipient_email' => 'not-an-email',
            ]);

        $response->assertSessionHasErrors('application_recipient_email');
        $this->assertSame('valid@example.com', $setting->fresh()->application_recipient_email);
    }

    public function test_configured_environment_recipient_is_used_as_a_fallback(): void
    {
        config(['mail.application_recipient' => 'fallback@example.com']);

        $this->assertSame('fallback@example.com', SiteSetting::applicationRecipientEmail());

        SiteSetting::create([
            'application_recipient_email' => 'admin@example.com',
        ]);

        $this->assertSame('admin@example.com', SiteSetting::applicationRecipientEmail());
    }
}
