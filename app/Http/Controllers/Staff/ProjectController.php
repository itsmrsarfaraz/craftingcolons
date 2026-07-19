<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreProjectRequest;
use App\Models\Project;
use App\Models\Technology;
use App\Services\Cms\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projectService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::query()->with('author')->latest()->paginate(15);

        return view('staff.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        $technologies = Technology::orderBy('name')->get();

        return view('staff.projects.create', compact('technologies'));
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $this->projectService->create(
            $request->user(),
            $request->validated() + ['featured_image' => $request->file('featured_image')],
            $request->slug()
        );

        return redirect()->route('staff.projects.index')->with('status', "\"{$project->title}\" saved.");
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        $technologies = Technology::orderBy('name')->get();
        $project->load('technologies', 'results');

        return view('staff.projects.edit', compact('project', 'technologies'));
    }

    public function update(StoreProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $this->projectService->update(
            $project,
            $request->validated() + ['featured_image' => $request->file('featured_image')]
        );

        return redirect()->route('staff.projects.index')->with('status', 'Project updated.');
    }
}