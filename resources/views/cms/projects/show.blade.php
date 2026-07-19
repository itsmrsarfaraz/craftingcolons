<x-layouts.site :title="$project->seoTitle().' — Crafting Colons'" :description="$project->seoDescription()">
    <section class="section max-w-2xl">
        @if ($project->featuredImage())
            <div class="overflow-hidden rounded-2xl" data-reveal>
                <img src="{{ $project->featuredImage()->url() }}" class="w-full" alt="{{ $project->title }}">
            </div>
        @endif

        <div class="mt-8" data-reveal data-reveal-delay="1">
            <span class="eyebrow">{{ $project->project_type->label() }}</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">{{ $project->title }}</h1>
            @if ($project->client_name)
                <p class="mt-2 text-sm text-ink-400">Client: {{ $project->client_name }}</p>
            @endif
            <p class="mt-4 text-lg text-ink-300">{{ $project->summary }}</p>
        </div>

        @if ($project->results->isNotEmpty())
            <div class="mt-8 grid grid-cols-3 gap-4" data-reveal data-reveal-delay="2">
                @foreach ($project->results as $result)
                    <div class="card p-4 text-center">
                        <p class="font-display text-2xl font-semibold text-white">{{ $result->metric_value }}</p>
                        <p class="mt-1 text-xs text-ink-500">{{ $result->metric_label }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="prose prose-invert mt-10 max-w-none" data-reveal data-reveal-delay="3">
            {!! nl2br(e($project->body)) !!}
        </div>

        <div class="mt-8 flex flex-wrap gap-2 border-t border-ink-800 pt-6" data-reveal data-reveal-delay="4">
            @foreach ($project->technologies as $tech)
                <span class="rounded-full bg-ink-800 px-3 py-1 text-xs text-ink-300">{{ $tech->name }}</span>
            @endforeach
        </div>

        @if ($project->project_url)
            <a href="{{ $project->project_url }}" target="_blank" rel="noopener" class="btn-primary mt-8">
                Visit Project →
            </a>
        @endif
    </section>
</x-layouts.site>