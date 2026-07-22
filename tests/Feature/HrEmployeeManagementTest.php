<?php

namespace Tests\Feature;

use App\Enums\EmployeeStatus;
use App\Models\Employee;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HrEmployeeManagementTest extends TestCase
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

    private function employee(): Employee
    {
        $empUser = User::factory()->create();
        $empUser->assignRole('employee');

        return Employee::create([
            'user_id' => $empUser->id,
            'employee_code' => 'CC-0001',
            'employment_type' => 'full_time',
            'department' => 'Engineering',
            'joined_at' => now(),
        ]);
    }

    public function test_hr_can_view_the_employee_directory(): void
    {
        $hr = $this->hr();
        $employee = $this->employee();

        $response = $this->actingAs($hr)->get(route('hr.employees.index'));

        $response->assertOk();
        $response->assertSee($employee->user->name);
    }

    public function test_hr_can_filter_employees_by_department(): void
    {
        $hr = $this->hr();
        $engineeringEmp = $this->employee();

        $designEmpUser = User::factory()->create();
        $designEmpUser->assignRole('employee');
        Employee::create([
            'user_id' => $designEmpUser->id, 'employee_code' => 'CC-0002',
            'employment_type' => 'full_time', 'department' => 'Design', 'joined_at' => now(),
        ]);

        $response = $this->actingAs($hr)->get(route('hr.employees.index', ['department' => 'Engineering']));

        $response->assertSee($engineeringEmp->user->name);
        $response->assertDontSee($designEmpUser->name);
    }

    public function test_hr_can_update_an_employees_department_and_manager(): void
    {
        $hr = $this->hr();
        $employee = $this->employee();
        $manager = User::factory()->create();
        $manager->assignRole('team-lead');

        $response = $this->actingAs($hr)->put(route('hr.employees.update', $employee), [
            'department' => 'Product',
            'designation' => 'Senior Engineer',
            'reports_to' => $manager->id,
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'department' => 'Product',
            'reports_to' => $manager->id,
        ]);
    }

    public function test_marking_an_employee_terminated_creates_an_activity_log_entry(): void
    {
        $hr = $this->hr();
        $employee = $this->employee();

        $this->actingAs($hr)->put(route('hr.employees.update', $employee), [
            'department' => $employee->department,
            'status' => EmployeeStatus::Terminated->value,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'subject_id' => $employee->id,
            'action' => 'status_changed',
        ]);
    }

    public function test_an_employee_cannot_be_set_as_their_own_manager(): void
    {
        $hr = $this->hr();
        $employee = $this->employee();

        $response = $this->actingAs($hr)->put(route('hr.employees.update', $employee), [
            'department' => 'Engineering',
            'status' => 'active',
            'reports_to' => $employee->user_id,
        ]);

        $response->assertSessionHasErrors('reports_to');
    }

    public function test_non_hr_cannot_access_the_employee_directory(): void
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $response = $this->actingAs($applicant)->get(route('hr.employees.index'));

        $response->assertForbidden();
    }
}