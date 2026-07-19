<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('roles', fn ($q) => $q->where('slug', 'admin'))->first();

        if (! $admin) {
            return;
        }

        $services = [
            ['icon' => '💻', 'title' => 'Web Development', 'short_description' => 'Laravel-powered platforms, dashboards, and internal tools built to scale.'],
            ['icon' => '📱', 'title' => 'Mobile Apps', 'short_description' => 'Native-feel mobile experiences connected to real backend systems.'],
            ['icon' => '🎨', 'title' => 'Product Design', 'short_description' => 'UI/UX that looks premium and converts, not just wireframes.'],
            ['icon' => '🎓', 'title' => 'Tech Training', 'short_description' => 'Internship and community programs in dev, design, and photography.'],
        ];

        foreach ($services as $i => $data) {
            Service::firstOrCreate(
                ['title' => $data['title']],
                [
                    'author_id' => $admin->id,
                    'slug' => Str::slug($data['title']),
                    'icon' => $data['icon'],
                    'short_description' => $data['short_description'],
                    'body' => $data['short_description'],
                    'status' => 'published',
                    'published_at' => now(),
                    'order' => $i,
                ]
            );
        }
    }
}