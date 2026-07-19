<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>{{ $project->seoTitle() }} — Crafting Colons</title>
    <meta name="description" content="{{ $project->seoDescription() }}">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-8">
        @if ($project->featuredImage())
            <img src="{{ $project->featuredImage()->url() }}" class="rounded-2xl w-full" alt="{{ $project->title }}">
        @endif

        <div>
            <span class="text-xs uppercase tracking-wide text-neutral-500">{{ $project->project_type->label() }}</span>
            <h1 class="text-3xl font-semibold mt-1">{{ $project->title }}</h1>
            @if ($project->client_name)
                <p class="text-sm text-neutral-400 mt-1">Client: {{ $project->client_name }}</p>
            @endif
            <p class="text-neutral-400 mt-3">{{ $project->summary }}</p>
        </div>

        @if ($project->results->isNotEmpty())
            <div class="grid grid-cols-3 gap-4">
                @foreach ($project->results as $result)
                    <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-4 text-center">
                        <p class="text-2xl font-semibold">{{ $result->metric_value }}</p>
                        <p class="text-xs text-neutral-500 mt-1">{{ $result->metric_label }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="prose prose-invert max-w-none">
            {!! nl2br(e($project->body)) !!}
        </div>

        <div class="flex gap-2 flex-wrap pt-4 border-t border-neutral-800">
            @foreach ($project->technologies as $tech)
                <span class="text-xs bg-neutral-800 rounded-full px-3 py-1">{{ $tech->name }}</span>
            @endforeach
        </div>

        @if ($project->project_url)
            <a href="{{ $project->project_url }}" target="_blank" rel="noopener"
               class="inline-block bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 hover:bg-neutral-200 transition">
                Visit Project →
            </a>
        @endif
    </div>
</body>
</html>