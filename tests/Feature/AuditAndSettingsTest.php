<?php

namespace Tests\Feature;

use App\Enums\JobPostingStatus;
use App\Enums\SettingType;
use App\Models\JobPosting;
use App\Models\User;
use App\Services\Settings\SettingsService;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditAndSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function hr(): User
    {
        $user = User::factory()->create();
        $user->assignRole('hr');

        return $user;
    }

    public function test_publishing_a_job_posting_creates_an_activity_log_entry(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id, 'status' => JobPostingStatus::Draft]);

        $this->actingAs($hr)->patch(route('hr.jobs.publish', $posting));

        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => (new JobPosting())->getMorphClass(),
            'subject_id' => $posting->id,
            'action' => 'status_changed',
        ]);
    }

    public function test_editing_an_unrelated_field_does_not_create_a_status_changed_log(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);

        $posting->update(['description' => 'Updated description text only.']);

        $this->assertDatabaseMissing('activity_logs', [
            'subject_id' => $posting->id,
            'action' => 'status_changed',
        ]);
    }

    public function test_settings_service_stores_and_retrieves_typed_values(): void
    {
        $settings = app(SettingsService::class);

        $settings->set('assessment.max_violations_allowed_default', 5, SettingType::Integer);

        $this->assertSame(5, $settings->get('assessment.max_violations_allowed_default'));
    }

    public function test_settings_service_returns_default_when_key_does_not_exist(): void
    {
        $settings = app(SettingsService::class);

        $this->assertSame(3, $settings->get('nonexistent.key', 3));
    }

    public function test_only_admin_can_view_activity_logs(): void
    {
        $hr = $this->hr();

        $response = $this->actingAs($hr)->get(route('admin.activity-logs.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_and_update_settings(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'key' => 'attendance.late_grace_minutes',
            'value' => '20',
            'type' => 'integer',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', ['key' => 'attendance.late_grace_minutes', 'value' => '20']);
    }
}