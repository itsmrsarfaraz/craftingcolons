<x-layouts.app :title="'Services — Crafting Colons'">
    <section class="section max-w-3xl">
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-semibold">Services</h1>
            <a href="{{ route('staff.services.create') }}" class="btn-primary !px-4 !py-2 text-sm">+ New Service</a>
        </div>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="card mt-6 divide-y divide-ink-800">
            @foreach ($services as $service)
                <a href="{{ route('staff.services.edit', $service) }}" class="flex items-center justify-between px-6 py-4 hover:bg-ink-800/40">
                    <div class="flex items-center gap-3">
                        @if ($service->icon)<span class="text-xl">{{ $service->icon }}</span>@endif
                        <p class="font-medium text-white">{{ $service->title }}</p>
                    </div>
                    <span class="rounded-full border border-ink-700 px-3 py-1 text-xs text-ink-300">{{ $service->status->label() }}</span>
                </a>
            @endforeach
        </div>

        {{ $services->links() }}
    </section>
</x-layouts.app>