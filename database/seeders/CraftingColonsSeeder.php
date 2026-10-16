<?php
namespace Database\Seeders;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Seeder;

class CraftingColonsSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['Quest I: Web & SaaS Architecture', 'Enterprise-grade full-stack web applications, scalable APIs, and modern responsive frontends.',
                ['Laravel', 'PHP', 'Tailwind CSS', 'Alpine.js', 'React', 'MySQL'], 'Web Development',
                'Quest I accepted. Typical loot: SaaS platforms, REST APIs, multi-tenant dashboards.'],
            ['Quest II: Desktop Application Development', 'Robust, high-speed Windows desktop applications and Point-of-Sale (POS) systems engineered for offline resilience and performance.',
                ['C#', '.NET', 'WPF', 'Entity Framework Core', 'SQLite'], 'Desktop App',
                'Quest II accepted. Typical loot: offline-first POS, inventory and reporting tools.'],
            ['Quest III: Smart IoT & Embedded Hardware', 'Connecting the physical and digital worlds using microcontrollers, sensor arrays, automated vending systems, and circuit design.',
                ['ESP32', 'ESP32-CAM', 'Arduino', 'C++', 'Sensor Automation'], 'IoT/Hardware',
                'Quest III accepted. Typical loot: sensor networks, camera nodes, vending automation.'],
            ['Quest IV: AI Workflows & Automation', 'Custom AI pipelines, local LLM deployments, autonomous agents, and intelligence integration.',
                ['LangGraph', 'Ollama', 'Qwen', 'NVIDIA NIM APIs', 'Python'], 'AI Integration',
                'Quest IV accepted. Typical loot: local LLM deployments, agent pipelines, automation.'],
        ];
        foreach ($services as $i => [$title, $description, $badges, $type, $info]) {
            Service::updateOrCreate(['title' => $title], [
                'position' => $i + 1, 'description' => $description, 'badges' => $badges,
                'quest_type' => $type, 'info' => $info,
            ]);
        }

        $projects = [
            ['SeeHostels.com', 'Web / SaaS', 'web', 'Hostel Bed Reservation & Property Management Platform',
                'Seamless bed bookings, real-time availability, dynamic property dashboard, and automated booking notifications.'],
            ['CraftingPOS', 'Desktop / Retail', 'desktop', 'Desktop Point of Sale & Inventory Engine',
                'Fast, offline-first WPF desktop software designed for retail businesses with barcode scanning, receipts, and analytical reporting.'],
            ['SmartTimetable', 'Education / Desktop Tool', 'edu desktop', 'Rule-Based Academic Schedule Generator',
                'Automated timetable generation application tailored for educational institutions, solving teacher and room conflict matrices.'],
            ['Skolevo', 'Web / Platform', 'web edu', 'Next-Gen Educational Portal & LMS Platform',
                'Modern learning management platform for schools and colleges, handling student progress, schedules, and administrative workflows.'],
        ];
        foreach ($projects as $i => [$title, $kind, $cats, $tagline, $details]) {
            Project::updateOrCreate(['title' => $title], [
                'position' => $i + 1, 'kind' => $kind, 'categories' => $cats,
                'tagline' => $tagline, 'details' => $details,
            ]);
        }
    }
}
