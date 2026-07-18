<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Event — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">New Event</h1>

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.events.store') }}" enctype="multipart/form-data"
              class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-4">
            @csrf

            {{-- Title --}}
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Title" required
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">

            {{-- Excerpt --}}
            <textarea name="excerpt" placeholder="Short excerpt" rows="2"
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">{{ old('excerpt') }}</textarea>

            {{-- Body --}}
            <textarea name="body" placeholder="Event body" rows="10" required
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">{{ old('body') }}</textarea>

            {{-- Date & Time Settings --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Starts At</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Ends At</label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" required
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
            </div>

            {{-- Location Settings --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="block text-sm text-neutral-300 mb-1">Location / URL</label>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="Physical address or meeting link" required
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
                <div class="flex items-center h-[42px]">
                    <label class="inline-flex items-center cursor-pointer text-sm text-neutral-300">
                        <input type="checkbox" name="is_virtual" value="1" {{ old('is_virtual') ? 'checked' : '' }} class="rounded bg-neutral-800 border-neutral-700 text-white focus:ring-0 mr-2">
                        Virtual Event
                    </label>
                </div>
            </div>

            {{-- Capacity & Logistics --}}
            <div>
                <label class="block text-sm text-neutral-300 mb-1">Max Attendees (Leave blank for unlimited)</label>
                <input type="number" name="max_attendees" value="{{ old('max_attendees') }}" min="1" placeholder="e.g., 100"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            {{-- Categories --}}
            <div>
                <label class="block text-sm text-neutral-300 mb-1">Categories</label>
                <select name="categories[]" multiple class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tags --}}
            <input type="text" name="tags" value="{{ old('tags') }}" placeholder="Tags, comma separated"
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">

            {{-- Image Upload --}}
            <div>
                <label class="block text-sm text-neutral-300 mb-1">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*" class="text-sm text-neutral-300">
            </div>

            {{-- SEO Metadata --}}
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Meta title (max 60 chars)" maxlength="60"
                    class="rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                <input type="text" name="meta_description" value="{{ old('meta_description') }}" placeholder="Meta description (max 160 chars)" maxlength="160"
                    class="rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            {{-- Status Selection --}}
            <select name="status" class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                @foreach (\App\Enums\EventStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ old('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 hover:bg-neutral-200 transition">
                Save Event
            </button>
        </form>
    </div>
</body>
</html>