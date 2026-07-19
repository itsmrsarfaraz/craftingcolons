<?php

namespace Database\Seeders;

use App\Models\Stat;
use Illuminate\Database\Seeder;

class StatSeeder extends Seeder
{
    public function run(): void
    {
        $stats = [
            ['label' => 'Projects Delivered', 'value' => '100+', 'order' => 1],
            ['label' => 'Founded', 'value' => '2024', 'order' => 2],
            ['label' => 'Based In', 'value' => 'Islamabad', 'order' => 3],
        ];

        foreach ($stats as $stat) {
            Stat::firstOrCreate(['label' => $stat['label']], $stat);
        }
    }
}