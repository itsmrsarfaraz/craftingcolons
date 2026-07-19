<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            'Laravel', 'PHP', 'Python', 'Flask', 'FastAPI', 'JavaScript',
            'Tailwind CSS', 'Alpine.js', 'MySQL', 'PostgreSQL', 'Redis',
            'AWS', 'Docker', 'ESP32', 'RFID', 'REST API',
        ];

        foreach ($technologies as $name) {
            Technology::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}