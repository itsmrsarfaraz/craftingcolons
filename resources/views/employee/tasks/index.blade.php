<x-layouts.app :title="'Tasks — Crafting Colons'">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">My Tasks</h1>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('employee.tasks.store') }}"
              class="bg-ink-900 border border-ink-800 rounded-2xl p-6 space-y-3">
            @csrf
            <input type="text" name="title" placeholder="New task title" required
                class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
            <textarea name="description" placeholder="Description (optional)" rows="2"
                class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2"></textarea>
            <div class="flex items-center gap-3">
                <input type="date" name="due_date"
                    class="rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                <button type="submit"
                    class="bg-white text-ink-950 font-medium rounded-lg px-4 py-2 text-sm hover:bg-ink-200">
                    Add Task
                </button>
            </div>
        </form>

        <div class="space-y-3">
            @foreach ($tasks as $task)
                <div class="bg-ink-900 border border-ink-800 rounded-2xl p-6" x-data="{ open: false }">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $task->title }}</p>
                            <p class="text-xs text-ink-500">
                                {{ $task->due_date?->format('M j, Y') ?? 'No due date' }}
                                @if ($task->assignedBy)
                                    · Assigned by {{ $task->assignedBy->name }}
                                @endif
                            </p>
                        </div>
                        <span class="text-xs uppercase tracking-wide bg-ink-800 rounded-full px-3 py-1">
                            {{ $task->status->label() }}
                        </span>
                    </div>

                    @if ($task->description)
                        <p class="text-sm text-ink-400 mt-2">{{ $task->description }}</p>
                    @endif

                    <div class="flex gap-2 mt-4">
                        @foreach ($task->status->allowedNextStatuses() as $next)
                            <form method="POST" action="{{ route('employee.tasks.status', $task) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $next->value }}">
                                <button class="text-xs border border-ink-700 rounded-lg px-3 py-1 hover:bg-ink-800">
                                    Move to {{ $next->label() }}
                                </button>
                            </form>
                        @endforeach
                        <button @click="open = !open" class="text-xs underline text-ink-400">
                            Daily Report
                        </button>
                    </div>

                    <div x-show="open" x-cloak class="mt-4 pt-4 border-t border-ink-800">
                        <form method="POST" action="{{ route('employee.tasks.reports.store', $task) }}" enctype="multipart/form-data" class="space-y-2">
                            @csrf
                            <textarea name="summary" placeholder="What did you do today?" rows="2" required
                                class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2 text-sm"></textarea>
                            <input type="file" name="evidence" class="text-xs text-ink-400">
                            <button type="submit" class="text-xs bg-white text-ink-950 rounded-lg px-3 py-1.5">
                                Submit
                            </button>
                        </form>

                        @foreach ($task->reports as $report)
                            <div class="text-xs text-ink-500 mt-3 pt-3 border-t border-ink-800/60">
                                <span class="text-ink-400">{{ $report->report_date->format('M j') }}:</span>
                                {{ $report->summary }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{ $tasks->links() }}
    </div>
</x-layouts.app>