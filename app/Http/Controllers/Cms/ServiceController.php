<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::published()->get();

        return view('cms.services.index', compact('services'));
    }

    public function show(Service $service): View
    {
        $this->authorize('view', $service);

        return view('cms.services.show', compact('service'));
    }
}