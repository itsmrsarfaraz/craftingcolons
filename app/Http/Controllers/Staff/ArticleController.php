<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Enums\CategoryType;
use App\Services\Cms\ArticleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(private readonly ArticleService $articleService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Article::class);

        $articles = Article::query()->with('author')->latest()->paginate(15);

        return view('staff.articles.index', compact('articles'));
    }

    public function create(): View
    {
        $this->authorize('create', Article::class);

        $categories = Category::where('type', CategoryType::Article)->get();

        return view('staff.articles.create', compact('categories'));
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $article = $this->articleService->create(
            $request->user(),
            $request->validated() + ['featured_image' => $request->file('featured_image')],
            $request->slug()
        );

        return redirect()->route('staff.articles.index')->with('status', "\"{$article->title}\" saved.");
    }

    public function edit(Article $article): View
    {
        $this->authorize('update', $article);

        $categories = Category::where('type', CategoryType::Article)->get();

        return view('staff.articles.edit', compact('article', 'categories'));
    }

    public function update(StoreArticleRequest $request, Article $article): RedirectResponse
    {
        $this->authorize('update', $article);

        $this->articleService->update(
            $article,
            $request->validated() + ['featured_image' => $request->file('featured_image')]
        );

        return redirect()->route('staff.articles.index')->with('status', 'Article updated.');
    }
}