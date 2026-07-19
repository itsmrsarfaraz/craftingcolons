<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreNewsRequest;
use App\Models\News;
use App\Services\Cms\NewsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(private readonly NewsService $newsService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', News::class);

        $news = News::query()->with('author')->latest()->paginate(15);

        return view('staff.news.index', compact('news'));
    }

    public function create(): View
    {
        $this->authorize('create', News::class);

        $categories = \App\Models\Category::where('type', \App\Enums\CategoryType::News)->get();

        return view('staff.news.create', compact('categories'));
    }

    public function store(StoreNewsRequest $request): RedirectResponse
    {
        $this->newsService->create($request->user(), $request->validated(), $request->slug());

        return redirect()->route('staff.news.index')->with('status', 'News item saved.');
    }

    public function edit(News $news): View
    {
        $this->authorize('update', $news);

        $categories = \App\Models\Category::where('type', \App\Enums\CategoryType::News)->get();
        $news->load('categories');

        return view('staff.news.edit', compact('news', 'categories'));
    }

    public function update(StoreNewsRequest $request, News $news): RedirectResponse
    {
        $this->authorize('update', $news);

        $this->newsService->update($news, $request->validated());

        return redirect()->route('staff.news.index')->with('status', 'News item updated.');
    }
}