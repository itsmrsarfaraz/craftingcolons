<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Event;
use App\Models\JobPosting;
use App\Models\News;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect();

        $urls->push(['loc' => route('home'), 'priority' => '1.0']);
        $urls->push(['loc' => route('careers.index'), 'priority' => '0.8']);
        $urls->push(['loc' => route('articles.index'), 'priority' => '0.7']);
        $urls->push(['loc' => route('news.index'), 'priority' => '0.6']);
        $urls->push(['loc' => route('events.index'), 'priority' => '0.6']);
        $urls->push(['loc' => route('projects.index'), 'priority' => '0.7']);

        JobPosting::where('status', 'published')->get()->each(
            fn ($job) => $urls->push([
                'loc' => route('careers.show', $job->slug),
                'lastmod' => $job->updated_at->toAtomString(),
                'priority' => '0.6',
            ])
        );

        Article::published()->get()->each(
            fn ($article) => $urls->push([
                'loc' => route('articles.show', $article->slug),
                'lastmod' => $article->updated_at->toAtomString(),
                'priority' => '0.5',
            ])
        );

        News::published()->get()->each(
            fn ($news) => $urls->push([
                'loc' => route('news.show', $news->slug),
                'lastmod' => $news->updated_at->toAtomString(),
                'priority' => '0.5',
            ])
        );

        Event::published()->get()->each(
            fn ($event) => $urls->push([
                'loc' => route('events.show', $event->slug),
                'lastmod' => $event->updated_at->toAtomString(),
                'priority' => '0.5',
            ])
        );

        Project::published()->get()->each(
            fn ($project) => $urls->push([
                'loc' => route('projects.show', $project->slug),
                'lastmod' => $project->updated_at->toAtomString(),
                'priority' => '0.6',
            ])
        );

        return response()
            ->view('seo.sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}