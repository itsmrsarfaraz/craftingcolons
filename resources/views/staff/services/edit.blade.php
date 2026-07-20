<x-layouts.app :title="'Edit: '.$service->title">
    <section class="section max-w-2xl">
        <h1 class="font-display text-2xl font-semibold">Edit Service</h1>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.services.update', $service) }}" class="card mt-6 space-y-4 p-6">
            @csrf
            @method('PUT')

            <input type="text" name="title" value="{{ old('title', $service->title) }}" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <input type="text" name="icon" value="{{ old('icon', $service->icon) }}" placeholder="Icon (emoji)" maxlength="10"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <textarea name="short_description" rows="2" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('short_description', $service->short_description) }}</textarea>

            <textarea name="body" rows="8" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('body', $service->body) }}</textarea>

            <div class="grid grid-cols-2 gap-4">
                <input type="number" name="order" value="{{ old('order', $service->order) }}" min="0"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <select name="status" class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach (\App\Enums\ServiceStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ $service->status === $status ? 'selected' : '' }}>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="meta_title" value="{{ old('meta_title', $service->meta_title) }}" maxlength="60"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <input type="text" name="meta_description" value="{{ old('meta_description', $service->meta_description) }}" maxlength="160"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </section>
</x-layouts.app>