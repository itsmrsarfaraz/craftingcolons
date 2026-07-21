<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RebrandSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_includes_lucide_script(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('lucide@latest', false);
    }

    public function test_home_page_includes_the_logo_mark_svg(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('viewBox="0 0 500 500"', false);
    }

    public function test_home_page_includes_theme_toggle(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('Toggle light/dark mode', false);
    }

    public function test_home_page_includes_code_window_illustration(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('deploy.php');
    }
}