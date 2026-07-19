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
}