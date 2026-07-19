<x-layouts.site :title="'Our Work — Crafting Colons'">
    <section class="section">
        <div class="text-center" data-reveal>
            <span class="eyebrow">Portfolio</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">Our Work</h1>
            <p class="mx-auto mt-3 max-w-lg text-ink-400">Projects and platforms we've built.</p>
        </div>

        <div class="mt-8 flex flex-wrap justify-center gap-2" data-reveal data-reveal-delay="1">
            <a href="{{ route('projects.index') }}"
               class="rounded-full px-4 py-1.5 text-xs font-medium {{ ! request('type') ? 'bg-brand-500 text-ink-950' : 'bg-ink-800 text-ink-300' }}">
                All
            </a>
            @foreach ($types as $type)
                <a href="{{ route('projects.index', ['type' => $type->value]) }}"
                   class="rounded-full px-4 py-1.5 text-xs font-medium {{ request('type') === $type->value ? 'bg-brand-500 text-ink-950' : 'bg-ink-800 text-ink-300' }}">
                    {{ $type->label() }}
                </a>
            @endforeach
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($projects as $i => $project)
                <a href="{{ route('projects.show', $project->slug) }}"
                   class="card card-hover group overflow-hidden" data-reveal data-reveal-delay="{{ min($i, 4) }}">
                    <div class="aspect-[4/3] overflow-hidden bg-ink-800">
                        @if ($project->featuredImage())
                            <img src="{{ $project->featuredImage()->url() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $project->title }}">
                        @else
                            <div class="flex h-full items-center justify-center text-ink-600">
                                <span class="font-display text-2xl">{{ Str::limit($project->title, 1, '') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <span class="text-xs font-medium uppercase tracking-wide text-brand-400">{{ $project->project_type->label() }}</span>
                        <h2 class="mt-1 font-semibold text-white">{{ $project->title }}</h2>
                        <p class="mt-2 text-sm text-ink-400">{{ Str::limit($project->summary, 90) }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($project->technologies->take(3) as $tech)
                                <span class="rounded-full bg-ink-800 px-2 py-1 text-xs text-ink-300">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </a>
            @empty
                <div class="card p-10 text-center text-ink-500 sm:col-span-2 lg:col-span-3">No projects in this category yet.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $projects->links() }}</div>
    </section>
</x-layouts.site>