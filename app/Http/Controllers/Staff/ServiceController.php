<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreServiceRequest;
use App\Models\Service;
use App\Services\Cms\ServiceContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(private readonly ServiceContentService $serviceContentService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Service::class);

        $services = Service::query()->with('author')->orderBy('order')->paginate(15);

        return view('staff.services.index', compact('services'));
    }

    public function create(): View
    {
        $this->authorize('create', Service::class);

        return view('staff.services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $service = $this->serviceContentService->create($request->user(), $request->validated(), $request->slug());

        return redirect()->route('staff.services.index')->with('status', "\"{$service->title}\" saved.");
    }

    public function edit(Service $service): View
    {
        $this->authorize('update', $service);

        return view('staff.services.edit', compact('service'));
    }

    public function update(StoreServiceRequest $request, Service $service): RedirectResponse
    {
        $this->authorize('update', $service);

        $this->serviceContentService->update($service, $request->validated());

        return redirect()->route('staff.services.index')->with('status', 'Service updated.');
    }
}