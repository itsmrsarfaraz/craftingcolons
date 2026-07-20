<x-layouts.app :title="'Edit: '.$event->title">
    <section class="section max-w-2xl">
        <h1 class="font-display text-2xl font-semibold">Edit Event</h1>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.events.update', $event) }}" class="card mt-6 space-y-4 p-6">
            @csrf
            @method('PUT')

            <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <textarea name="description" rows="4" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('description', $event->description) }}</textarea>

            <label class="flex items-center gap-2 text-sm text-ink-300">
                <input type="checkbox" name="is_virtual" value="1" {{ old('is_virtual', $event->is_virtual) ? 'checked' : '' }}
                    class="rounded border-ink-700 bg-ink-800">
                Virtual event
            </label>

            <input type="text" name="location" value="{{ old('location', $event->location) }}" placeholder="Location (if in-person)"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-sm text-ink-300">Starts At</label>
                    <input type="datetime-local" name="starts_at"
                        value="{{ old('starts_at', $event->starts_at->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm text-ink-300">Ends At</label>
                    <input type="datetime-local" name="ends_at"
                        value="{{ old('ends_at', $event->ends_at->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                </div>
            </div>

            <input type="number" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}" min="1" placeholder="Max attendees (optional)"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <div>
                <label class="mb-1 block text-sm text-ink-300">Categories</label>
                <select name="categories[]" multiple class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $event->categories->contains($category) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <select name="status" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                @foreach (\App\Enums\EventStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ $event->status === $status ? 'selected' : '' }}>{{ $status->label() }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </section>
</x-layouts.app>