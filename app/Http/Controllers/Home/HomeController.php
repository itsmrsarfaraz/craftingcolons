<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Event;
use App\Models\JobPosting;
use App\Models\News;
use App\Models\Project;
use App\Models\Service;
use App\Models\Stat;
use App\Models\Technology;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'stats' => Stat::ordered()->get(),
            'testimonials' => Testimonial::active()->get(),
            'technologies' => Technology::orderBy('name')->limit(12)->get(),
            'latestJobs' => JobPosting::where('status', 'published')
                ->where(fn ($q) => $q->whereNull('deadline')->orWhere('deadline', '>=', now()))
                ->latest()->limit(3)->get(),
            'latestArticles' => Article::published()->latest('published_at')->limit(3)->get(),
            'latestNews' => News::published()->latest('published_at')->limit(3)->get(),
            'upcomingEvents' => Event::published()->where('starts_at', '>=', now())->orderBy('starts_at')->limit(3)->get(),
            'featuredProjects' => Project::published()->with('media', 'technologies')->latest('published_at')->limit(3)->get(),
            'services' => Service::published()->limit(4)->get(),
        ]);
    }
}