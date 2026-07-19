<?php

namespace App\Services\Search;

use App\Models\Article;
use App\Models\Event;
use App\Models\JobPosting;
use App\Models\News;
use App\Models\Project;
use Illuminate\Support\Collection;

class GlobalSearchService
{
    private const RESULTS_PER_TYPE = 5;

    public function search(string $query): Collection
    {
        $query = trim($query);

        if (mb_strlen($query) < 2) {
            return collect();
        }

        return collect([
            'jobs' => $this->searchJobs($query),
            'articles' => $this->searchArticles($query),
            'projects' => $this->searchProjects($query),
            'events' => $this->searchEvents($query),
            'news' => $this->searchNews($query),
        ])->filter(fn (Collection $results) => $results->isNotEmpty());
    }

    private function searchJobs(string $term): Collection
    {
        return JobPosting::query()
            ->where('status', 'published')
            ->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")
                ->orWhere('department', 'like', "%{$term}%"))
            ->limit(self::RESULTS_PER_TYPE)
            ->get()
            ->map(fn (JobPosting $job) => [
                'title' => $job->title,
                'excerpt' => $job->department,
                'url' => route('careers.show', $job->slug),
            ]);
    }

    private function searchArticles(string $term): Collection
    {
        return Article::published()
            ->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%"))
            ->limit(self::RESULTS_PER_TYPE)
            ->get()
            ->map(fn (Article $article) => [
                'title' => $article->title,
                'excerpt' => $article->excerpt ?? str($article->body)->limit(100),
                'url' => route('articles.show', $article->slug),
            ]);
    }

    private function searchProjects(string $term): Collection
    {
        return Project::published()
            ->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")
                ->orWhere('summary', 'like', "%{$term}%")
                ->orWhere('client_name', 'like', "%{$term}%"))
            ->limit(self::RESULTS_PER_TYPE)
            ->get()
            ->map(fn (Project $project) => [
                'title' => $project->title,
                'excerpt' => $project->summary,
                'url' => route('projects.show', $project->slug),
            ]);
    }

    private function searchEvents(string $term): Collection
    {
        return Event::published()
            ->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%"))
            ->limit(self::RESULTS_PER_TYPE)
            ->get()
            ->map(fn (Event $event) => [
                'title' => $event->title,
                'excerpt' => $event->starts_at->format('M j, Y'),
                'url' => route('events.show', $event->slug),
            ]);
    }

    private function searchNews(string $term): Collection
    {
        return News::published()
            ->where(fn ($q) => $q
                ->where('title', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%"))
            ->limit(self::RESULTS_PER_TYPE)
            ->get()
            ->map(fn (News $news) => [
                'title' => $news->title,
                'excerpt' => $news->excerpt ?? str($news->body)->limit(100),
                'url' => route('news.show', $news->slug),
            ]);
    }
}