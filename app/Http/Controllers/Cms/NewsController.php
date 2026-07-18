<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::published()->with('author')->latest('published_at')->paginate(9);

        return view('cms.news.index', compact('news'));
    }

    public function show(News $news): View
    {
        $this->authorize('view', $news);

        return view('cms.news.show', compact('news'));
    }
}