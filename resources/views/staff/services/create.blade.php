<x-layouts.site :title="'New Service — Crafting Colons'">
    <section class="section max-w-2xl">
        <h1 class="font-display text-2xl font-semibold">New Service</h1>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.services.store') }}" class="card mt-6 space-y-4 p-6">
            @csrf

            <input type="text" name="title" placeholder="Service title" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">

            <input type="text" name="icon" placeholder="Icon (emoji, e.g. 💻)" maxlength="10"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">

            <textarea name="short_description" placeholder="Short description (shown on cards)" rows="2" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500"></textarea>

            <textarea name="body" placeholder="Full service page content" rows="8" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500"></textarea>

            <div class="grid grid-cols-2 gap-4">
                <input type="number" name="order" placeholder="Display order" min="0"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
                <select name="status" class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach (\App\Enums\ServiceStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary">Save Service</button>
        </form>
    </section>
</x-layouts.site>