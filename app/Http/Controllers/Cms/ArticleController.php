<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::published()->with('author', 'categories', 'tags')->latest('published_at');

        if ($category = $request->query('category')) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $category));
        }

        if ($tag = $request->query('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tag));
        }

        $articles = $query->paginate(9);

        return view('cms.articles.index', compact('articles'));
    }

    public function show(Article $article): View
    {
        $this->authorize('view', $article);

        $article->load('author', 'categories', 'tags', 'media');

        return view('cms.articles.show', compact('article'));
    }
}