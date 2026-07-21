<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function teamLeadWithReport(): array
    {
        $lead = User::factory()->create();
        $lead->assignRole('team-lead');

        $empUser = User::factory()->create();
        $empUser->assignRole('employee');
        $employee = Employee::create([
            'user_id' => $empUser->id,
            'employee_code' => 'CC-0001',
            'employment_type' => 'full_time',
            'joined_at' => now(),
            'reports_to' => $lead->id,
        ]);

        return [$lead, $employee];
    }

    public function test_team_lead_can_assign_a_task_to_their_own_report(): void
    {
        Notification::fake();
        [$lead, $employee] = $this->teamLeadWithReport();

        $response = $this->actingAs($lead)->post(route('team-lead.tasks.assign.store'), [
            'employee_id' => $employee->id,
            'title' => 'Fix the login bug',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'employee_id' => $employee->id,
            'assigned_by' => $lead->id,
            'title' => 'Fix the login bug',
        ]);
        Notification::assertSentTo($employee->user, TaskAssignedNotification::class);
    }

    public function test_team_lead_cannot_assign_a_task_to_someone_outside_their_team(): void
    {
        [$lead, ] = $this->teamLeadWithReport();

        $otherLead = User::factory()->create();
        $otherLead->assignRole('team-lead');
        $otherEmpUser = User::factory()->create();
        $otherEmpUser->assignRole('employee');
        $unrelatedEmployee = Employee::create([
            'user_id' => $otherEmpUser->id,
            'employee_code' => 'CC-0002',
            'employment_type' => 'full_time',
            'joined_at' => now(),
            'reports_to' => $otherLead->id,
        ]);

        $response = $this->actingAs($lead)->post(route('team-lead.tasks.assign.store'), [
            'employee_id' => $unrelatedEmployee->id,
            'title' => 'Sneaky assignment',
        ]);

        $response->assertSessionHasErrors('employee_id');
        $this->assertDatabaseMissing('tasks', ['title' => 'Sneaky assignment']);
    }

    public function test_assigned_task_appears_on_the_employees_task_list_with_assigner_name(): void
    {
        [$lead, $employee] = $this->teamLeadWithReport();

        $this->actingAs($lead)->post(route('team-lead.tasks.assign.store'), [
            'employee_id' => $employee->id,
            'title' => 'Ship the release',
        ]);

        $response = $this->actingAs($employee->user)->get(route('employee.tasks.review'));

        $response->assertSee('Ship the release');
        $response->assertSee('Assigned by '.$lead->name);
    }

    public function test_assign_form_shows_only_the_team_leads_own_reports(): void
    {
        [$lead, $employee] = $this->teamLeadWithReport();

        $response = $this->actingAs($lead)->get(route('team-lead.tasks.assign'));

        $response->assertOk();
        $response->assertSee($employee->user->name);
    }
}