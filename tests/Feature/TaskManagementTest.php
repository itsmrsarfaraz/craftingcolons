<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Employee;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function employeeWithManager(): array
    {
        $manager = User::factory()->create();
        $manager->assignRole('team-lead');

        $empUser = User::factory()->create();
        $empUser->assignRole('employee');
        $employee = Employee::create([
            'user_id' => $empUser->id,
            'employee_code' => 'CC-0001',
            'employment_type' => 'full_time',
            'joined_at' => now(),
            'reports_to' => $manager->id,
        ]);

        return [$empUser->fresh(), $manager, $employee];
    }

    public function test_employee_can_create_a_task_and_move_it_to_in_progress(): void
    {
        [$empUser, , ] = $this->employeeWithManager();

        $this->actingAs($empUser)->post(route('employee.tasks.store'), ['title' => 'Fix bug']);
        $task = $empUser->employee->tasks()->first();

        $response = $this->actingAs($empUser)->patch(route('employee.tasks.status', $task), [
            'status' => TaskStatus::InProgress->value,
        ]);

        $response->assertRedirect();
        $this->assertEquals(TaskStatus::InProgress, $task->fresh()->status);
    }

    public function test_employee_cannot_self_complete_a_task_skipping_review(): void
    {
        [$empUser, , ] = $this->employeeWithManager();
        $this->actingAs($empUser)->post(route('employee.tasks.store'), ['title' => 'Fix bug']);
        $task = $empUser->employee->tasks()->first();
        $task->update(['status' => TaskStatus::InProgress]);

        $response = $this->actingAs($empUser)->patch(route('employee.tasks.status', $task), [
            'status' => TaskStatus::Completed->value,
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertEquals(TaskStatus::InProgress, $task->fresh()->status);
    }

    public function test_employee_can_submit_a_daily_report(): void
    {
        [$empUser, , ] = $this->employeeWithManager();
        $this->actingAs($empUser)->post(route('employee.tasks.store'), ['title' => 'Fix bug']);
        $task = $empUser->employee->tasks()->first();

        $response = $this->actingAs($empUser)->post(route('employee.tasks.reports.store', $task), [
            'summary' => 'Investigated the root cause today.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('task_reports', ['task_id' => $task->id]);
    }

    public function test_the_assignees_manager_can_approve_a_task_in_review(): void
    {
        [$empUser, $manager, ] = $this->employeeWithManager();
        $this->actingAs($empUser)->post(route('employee.tasks.store'), ['title' => 'Fix bug']);
        $task = $empUser->employee->tasks()->first();
        $task->update(['status' => TaskStatus::Review]);

        $response = $this->actingAs($manager)->patch(route('team-lead.tasks.approve', $task));

        $response->assertRedirect();
        $this->assertEquals(TaskStatus::Completed, $task->fresh()->status);
        $this->assertEquals($manager->id, $task->fresh()->reviewed_by);
    }

    public function test_a_different_team_leads_manager_cannot_approve_someone_elses_task(): void
    {
        [$empUser, , ] = $this->employeeWithManager();
        $this->actingAs($empUser)->post(route('employee.tasks.store'), ['title' => 'Fix bug']);
        $task = $empUser->employee->tasks()->first();
        $task->update(['status' => TaskStatus::Review]);

        $unrelatedTeamLead = User::factory()->create();
        $unrelatedTeamLead->assignRole('team-lead');

        $response = $this->actingAs($unrelatedTeamLead)->patch(route('team-lead.tasks.approve', $task));

        $response->assertForbidden();
        $this->assertEquals(TaskStatus::Review, $task->fresh()->status);
    }
}