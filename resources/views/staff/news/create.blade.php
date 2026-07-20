<x-layouts.app :title="'New News Item — Crafting Colons'">
    <section class="section max-w-2xl">
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-semibold">New News Item</h1>
            <a href="{{ route('staff.categories.index') }}" class="btn-secondary !px-4 !py-2 text-xs">Manage Categories</a>
        </div>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.news.store') }}" class="card mt-6 space-y-4 p-6">
            @csrf

            <input type="text" name="title" value="{{ old('title') }}" placeholder="Title" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">

            <textarea name="excerpt" placeholder="Short excerpt" rows="2"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">{{ old('excerpt') }}</textarea>

            <textarea name="body" placeholder="News body" rows="8" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">{{ old('body') }}</textarea>

            <div>
                <label class="mb-1 block text-sm text-ink-300">Categories</label>
                <select name="categories[]" multiple class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Meta title (max 60 chars)" maxlength="60"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
                <input type="text" name="meta_description" value="{{ old('meta_description') }}" placeholder="Meta description (max 160 chars)" maxlength="160"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
            </div>

            <select name="status" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                @foreach (\App\Enums\NewsStatus::cases() as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-primary">Save News Item</button>
        </form>
    </section>
</x-layouts.app>