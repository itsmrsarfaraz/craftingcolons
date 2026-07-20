<x-layouts.app :title="'Tasks — Crafting Colons'">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">Tasks Awaiting Review</h1>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-3">
            @forelse ($tasks as $task)
                <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6">
                    <p class="font-medium">{{ $task->title }}</p>
                    <p class="text-xs text-neutral-500">{{ $task->employee->user->name }}</p>
                    @if ($task->description)
                        <p class="text-sm text-neutral-400 mt-2">{{ $task->description }}</p>
                    @endif

                    @if ($task->reports->isNotEmpty())
                        <div class="mt-3 text-xs text-neutral-500 space-y-1">
                            @foreach ($task->reports->take(3) as $report)
                                <p><span class="text-neutral-400">{{ $report->report_date->format('M j') }}:</span> {{ $report->summary }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex gap-2 mt-4">
                        <form method="POST" action="{{ route('team-lead.tasks.approve', $task) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs bg-emerald-900/40 text-emerald-400 border border-emerald-900 rounded-lg px-3 py-1.5">
                                Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('team-lead.tasks.request-changes', $task) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs border border-neutral-700 rounded-lg px-3 py-1.5 hover:bg-neutral-800">
                                Request Changes
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-neutral-500 text-sm">No tasks awaiting review.</p>
            @endforelse
        </div>

        {{ $tasks->links() }}
    </div>
</x-layouts.app>