<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Our Work — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-4xl mx-auto space-y-8">
        <div>
            <h1 class="text-3xl font-semibold">Our Work</h1>
            <p class="text-neutral-400 mt-1">Projects and platforms we've built.</p>
        </div>

        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('projects.index') }}"
               class="text-xs rounded-full px-3 py-1.5 {{ ! request('type') ? 'bg-white text-neutral-950' : 'bg-neutral-800 text-neutral-300' }}">
                All
            </a>
            @foreach ($types as $type)
                <a href="{{ route('projects.index', ['type' => $type->value]) }}"
                   class="text-xs rounded-full px-3 py-1.5 {{ request('type') === $type->value ? 'bg-white text-neutral-950' : 'bg-neutral-800 text-neutral-300' }}">
                    {{ $type->label() }}
                </a>
            @endforeach
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            @foreach ($projects as $project)
                <a href="{{ route('projects.show', $project->slug) }}"
                   class="block bg-neutral-900 border border-neutral-800 rounded-2xl overflow-hidden hover:border-neutral-700 transition">
                    @if ($project->featuredImage())
                        <img src="{{ $project->featuredImage()->url() }}" class="w-full h-40 object-cover" alt="{{ $project->title }}">
                    @endif
                    <div class="p-6">
                        <span class="text-xs uppercase tracking-wide text-neutral-500">{{ $project->project_type->label() }}</span>
                        <h2 class="text-lg font-semibold mt-1">{{ $project->title }}</h2>
                        <p class="text-sm text-neutral-400 mt-2">{{ Str::limit($project->summary, 100) }}</p>
                        <div class="flex gap-2 flex-wrap mt-3">
                            @foreach ($project->technologies->take(4) as $tech)
                                <span class="text-xs bg-neutral-800 rounded-full px-2 py-1">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{ $projects->links() }}
    </div>
</body>
</html>