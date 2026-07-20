{{-- resources/views/staff/categories/index.blade.php --}}
<x-layouts.app :title="'Categories — Crafting Colons'">
    <section class="section max-w-2xl">
        <h1 class="font-display text-2xl font-semibold">Categories</h1>
        <p class="mt-1 text-sm text-ink-400">Used to organize Articles and News.</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.categories.store') }}" class="card mt-6 flex flex-col gap-3 p-6 sm:flex-row sm:items-end">
            @csrf
            <div class="flex-1">
                <label class="mb-1 block text-sm text-ink-300">Name</label>
                <input type="text" name="name" placeholder="e.g. Engineering" required
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
            </div>
            <div>
                <label class="mb-1 block text-sm text-ink-300">Applies to</label>
                <select name="type" class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach (\App\Enums\CategoryType::cases() as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">Add</button>
        </form>

        <div class="card mt-6 divide-y divide-ink-800">
            @forelse ($categories as $category)
                <div x-data="{ editing: false }" class="px-6 py-3">
                    <div x-show="!editing" class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white">{{ $category->name }}</p>
                            <p class="text-xs text-ink-500">{{ $category->type->label() }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button @click="editing = true" class="text-xs text-ink-400 hover:underline">Rename</button>
                            <form method="POST" action="{{ route('staff.categories.destroy', $category) }}">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-400 hover:underline">Delete</button>
                            </form>
                        </div>
                    </div>
                    <form x-show="editing" x-cloak method="POST" action="{{ route('staff.categories.update', $category) }}"
                          class="flex items-center gap-2">
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $category->name }}" required
                            class="flex-1 rounded-lg border border-ink-700 bg-ink-800 px-3 py-1.5 text-sm text-white">
                        <input type="hidden" name="type" value="{{ $category->type->value }}">
                        <button type="submit" class="btn-primary !px-3 !py-1.5 text-xs">Save</button>
                        <button type="button" @click="editing = false" class="text-xs text-ink-400 hover:underline">Cancel</button>
                    </form>
                </div>
            @empty
                <p class="px-6 py-4 text-sm text-ink-500">No categories yet — add one above.</p>
            @endforelse
        </div>
    </section>
</x-layouts.app>