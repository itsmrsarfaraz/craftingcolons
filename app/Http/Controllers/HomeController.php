<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'services' => Service::where('is_active', true)->orderBy('position')->get(),
            'projects' => Project::where('is_active', true)->orderBy('position')->get(),
        ]);
    }
}