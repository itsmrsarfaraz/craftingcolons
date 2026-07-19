<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Enums\ProjectType;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::published()->with('technologies', 'media')->latest('published_at');

        if ($type = $request->query('type')) {
            $query->where('project_type', $type);
        }

        $projects = $query->paginate(9);
        $types = ProjectType::cases();

        return view('cms.projects.index', compact('projects', 'types'));
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load('author', 'technologies', 'results', 'media', 'tags');

        return view('cms.projects.show', compact('project'));
    }
}