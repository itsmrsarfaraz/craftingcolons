<x-layouts.site :title="'Edit: '.$article->title">
    <section class="section max-w-2xl">
        <h1 class="font-display text-2xl font-semibold">Edit Article</h1>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.articles.update', $article) }}" enctype="multipart/form-data"
              class="card mt-6 space-y-4 p-6">
            @csrf
            @method('PUT')

            <input type="text" name="title" value="{{ old('title', $article->title) }}" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <textarea name="excerpt" rows="2"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('excerpt', $article->excerpt) }}</textarea>

            <textarea name="body" rows="10" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('body', $article->body) }}</textarea>

            <div>
                <label class="mb-1 block text-sm text-ink-300">Categories</label>
                <select name="categories[]" multiple class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $article->categories->contains($category) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <input type="text" name="tags" value="{{ old('tags', $article->tags->pluck('name')->join(', ')) }}" placeholder="Tags, comma separated"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <div>
                <label class="mb-1 block text-sm text-ink-300">Featured Image</label>
                @if ($article->featuredImage())
                    <img src="{{ $article->featuredImage()->url() }}" class="mb-2 h-32 w-full rounded-lg object-cover">
                @endif
                <input type="file" name="featured_image" accept="image/*" class="text-sm text-ink-400">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="meta_title" value="{{ old('meta_title', $article->meta_title) }}" maxlength="60"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <input type="text" name="meta_description" value="{{ old('meta_description', $article->meta_description) }}" maxlength="160"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <div x-data="{ status: '{{ old('status', $article->status->value) }}' }">
                <div x-show="status === 'scheduled'" x-cloak class="mb-4">
                    <label class="mb-1 block text-sm text-ink-300">Publish Date &amp; Time</label>
                    <input type="datetime-local" name="published_at"
                        value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                </div>

                <select name="status" x-model="status" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach (\App\Enums\ArticleStatus::cases() as $articleStatus)
                        <option value="{{ $articleStatus->value }}" {{ $article->status === $articleStatus ? 'selected' : '' }}>
                            {{ $articleStatus->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </section>
</x-layouts.site>