<?php

namespace Database\Factories;

use App\Enums\EmploymentType;
use App\Enums\JobPostingStatus;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostingFactory extends Factory
{
    protected $model = JobPosting::class;

    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'slug' => fake()->unique()->slug(),
            'department' => fake()->word(),
            'employment_type' => EmploymentType::FullTime,
            'location' => 'Islamabad, Pakistan',
            'description' => fake()->paragraph(),
            'status' => JobPostingStatus::Draft,
            'assessment_required' => false,
            'created_by' => User::factory(),
        ];
    }
}