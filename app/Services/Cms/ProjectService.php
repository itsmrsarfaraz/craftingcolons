<?php

namespace App\Services\Cms;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class ProjectService
{
    public function create(User $author, array $data, string $slug): Project
    {
        $project = Project::create([
            'author_id' => $author->id,
            'title' => $data['title'],
            'slug' => $slug,
            'client_name' => $data['client_name'] ?? null,
            'project_type' => $data['project_type'],
            'summary' => $data['summary'],
            'body' => $data['body'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
            'project_url' => $data['project_url'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);

        $project->technologies()->sync($data['technologies'] ?? []);
        $this->syncResults($project, $data['results'] ?? []);

        if (isset($data['featured_image'])) {
            $this->attachFeaturedImage($project, $data['featured_image']);
        }

        return $project;
    }

    public function update(Project $project, array $data): Project
    {
        $project->update([
            'title' => $data['title'],
            'client_name' => $data['client_name'] ?? null,
            'project_type' => $data['project_type'],
            'summary' => $data['summary'],
            'body' => $data['body'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? ($project->published_at ?? now()) : null,
            'project_url' => $data['project_url'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);

        $project->technologies()->sync($data['technologies'] ?? []);
        $this->syncResults($project, $data['results'] ?? []);

        if (isset($data['featured_image'])) {
            $this->attachFeaturedImage($project, $data['featured_image']);
        }

        return $project->fresh();
    }

    /**
     * Full replace-on-save for results: simpler and safer than diffing
     * individual rows, and results are few enough per project (typically
     * 3-6) that this is cheap.
     */
    private function syncResults(Project $project, array $results): void
    {
        $project->results()->delete();

        foreach ($results as $index => $result) {
            $project->results()->create([
                'metric_label' => $result['metric_label'],
                'metric_value' => $result['metric_value'],
                'order' => $index,
            ]);
        }
    }

    private function attachFeaturedImage(Project $project, UploadedFile $file): void
    {
        $project->media()->where('collection', 'featured')->delete();

        $path = $file->store('projects/featured', 'public');

        $project->media()->create([
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'collection' => 'featured',
        ]);
    }
}