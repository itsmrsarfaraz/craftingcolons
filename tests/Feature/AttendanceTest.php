<?php

namespace Tests\Feature;

use App\Enums\AttendanceStatus;
use App\Models\Employee;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function employee(): User
    {
        $user = User::factory()->create();
        $user->assignRole('employee');
        Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'CC-0001',
            'employment_type' => 'full_time',
            'joined_at' => now(),
        ]);

        return $user->fresh();
    }

    public function test_employee_can_clock_in_on_time(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(9, 5));
        $user = $this->employee();

        $response = $this->actingAs($user)->post(route('employee.attendance.clock-in'));

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $user->employee->id,
            'status' => AttendanceStatus::Present->value,
        ]);
    }

    public function test_employee_clocking_in_after_grace_period_is_marked_late(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(9, 45));
        $user = $this->employee();

        $this->actingAs($user)->post(route('employee.attendance.clock-in'));

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $user->employee->id,
            'status' => AttendanceStatus::Late->value,
        ]);
    }

    public function test_employee_cannot_clock_in_twice_the_same_day(): void
    {
        $user = $this->employee();
        $this->actingAs($user)->post(route('employee.attendance.clock-in'));

        $response = $this->actingAs($user)->post(route('employee.attendance.clock-in'));

        $response->assertSessionHasErrors('attendance');
        $this->assertDatabaseCount('attendances', 1);
    }

    public function test_employee_can_clock_out_after_clocking_in(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(9, 0));
        $user = $this->employee();
        $this->actingAs($user)->post(route('employee.attendance.clock-in'));

        Carbon::setTestNow(Carbon::today()->setTime(17, 0));
        $response = $this->actingAs($user)->post(route('employee.attendance.clock-out'));

        $response->assertRedirect();
        $this->assertNotNull($user->employee->attendances()->first()->clock_out);
    }

    public function test_clocking_out_early_marks_half_day(): void
    {
        Carbon::setTestNow(Carbon::today()->setTime(9, 0));
        $user = $this->employee();
        $this->actingAs($user)->post(route('employee.attendance.clock-in'));

        Carbon::setTestNow(Carbon::today()->setTime(11, 0)); // only 2 hours
        $this->actingAs($user)->post(route('employee.attendance.clock-out'));

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $user->employee->id,
            'status' => AttendanceStatus::HalfDay->value,
        ]);
    }
}