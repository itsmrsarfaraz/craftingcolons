<?php

namespace App\Services\Cms;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ArticleService
{
    public function create(User $author, array $data, string $slug): Article
    {
        $article = Article::create([
            'author_id' => $author->id,
            'title' => $data['title'],
            'slug' => $slug,
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : ($data['published_at'] ?? null),
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'canonical_url' => $data['canonical_url'] ?? null,
        ]);

        $this->syncCategories($article, $data['categories'] ?? []);
        $this->syncTags($article, $data['tags'] ?? '');

        if (isset($data['featured_image'])) {
            $this->attachFeaturedImage($article, $data['featured_image']);
        }

        return $article;
    }

    public function update(Article $article, array $data): Article
    {
        $article->update([
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? ($article->published_at ?? now()) : ($data['published_at'] ?? null),
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'canonical_url' => $data['canonical_url'] ?? null,
        ]);

        $this->syncCategories($article, $data['categories'] ?? []);
        $this->syncTags($article, $data['tags'] ?? '');

        if (isset($data['featured_image'])) {
            $this->attachFeaturedImage($article, $data['featured_image']);
        }

        return $article->fresh();
    }

    private function syncCategories(Article $article, array $categoryIds): void
    {
        $article->categories()->sync($categoryIds);
    }

    /**
     * Accepts a comma-separated string, creates any tags that don't
     * already exist (find-or-create by slug), and syncs the relation.
     */
    private function syncTags(Article $article, string $tagString): void
    {
        $names = collect(explode(',', $tagString))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique();

        $tagIds = $names->map(function (string $name) {
            return Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            )->id;
        });

        $article->tags()->sync($tagIds);
    }

    private function attachFeaturedImage(Article $article, UploadedFile $file): void
    {
        $article->media()->where('collection', 'featured')->delete();

        $path = $file->store('articles/featured', 'public');

        $article->media()->create([
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'collection' => 'featured',
        ]);
    }
}