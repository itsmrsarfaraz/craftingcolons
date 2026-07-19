<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->can('publish-articles') || auth()->user()->hasRole('admin'), 403);

        $categories = Category::orderBy('type')->orderBy('name')->get();

        return view('staff.categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::firstOrCreate(
            ['slug' => $request->slug(), 'type' => $request->validated('type')],
            ['name' => $request->validated('name')]
        );

        return back()->with('status', 'Category created.');
    }

    public function update(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        abort_unless(auth()->user()->can('publish-articles') || auth()->user()->hasRole('admin'), 403);

        $category->update([
            'name' => $request->validated('name'),
            'slug' => $request->slug(),
            'type' => $request->validated('type'),
        ]);

        return back()->with('status', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        abort_unless(auth()->user()->can('publish-articles') || auth()->user()->hasRole('admin'), 403);

        $category->delete();

        return back()->with('status', 'Category deleted.');
    }
}