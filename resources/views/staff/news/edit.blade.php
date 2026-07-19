<x-layouts.site :title="'Edit: '.$news->title">
    <section class="section max-w-2xl">
        <h1 class="font-display text-2xl font-semibold">Edit News Item</h1>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.news.update', $news) }}" class="card mt-6 space-y-4 p-6">
            @csrf
            @method('PUT')

            <input type="text" name="title" value="{{ old('title', $news->title) }}" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <textarea name="excerpt" rows="2"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('excerpt', $news->excerpt) }}</textarea>

            <textarea name="body" rows="8" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('body', $news->body) }}</textarea>

            <div>
                <label class="mb-1 block text-sm text-ink-300">Categories</label>
                <select name="categories[]" multiple class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $news->categories->contains($category) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="meta_title" value="{{ old('meta_title', $news->meta_title) }}" maxlength="60"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <input type="text" name="meta_description" value="{{ old('meta_description', $news->meta_description) }}" maxlength="160"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <select name="status" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                @foreach (\App\Enums\NewsStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ $news->status === $status ? 'selected' : '' }}>{{ $status->label() }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </section>
</x-layouts.site>