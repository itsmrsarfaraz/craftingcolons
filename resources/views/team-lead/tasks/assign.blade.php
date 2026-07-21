<x-layouts.app :title="'Assign Task — Crafting Colons'">
    <div class="mx-auto max-w-2xl">
        <h1 class="font-display text-2xl font-semibold text-white">Assign a Task</h1>
        <p class="mt-1 text-sm text-ink-400">Assign new work to someone on your team.</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if ($teamMembers->isEmpty())
            <div class="card mt-6 p-8 text-center text-ink-400">
                No one currently reports to you — ask HR to set your team's reporting structure.
            </div>
        @else
            <form method="POST" action="{{ route('team-lead.tasks.assign.store') }}" class="card mt-6 space-y-4 p-6">
                @csrf

                <div>
                    <label class="mb-1 block text-sm text-ink-300">Assign to</label>
                    <select name="employee_id" required class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                        <option value="">— Select team member —</option>
                        @foreach ($teamMembers as $member)
                            <option value="{{ $member->id }}" {{ old('employee_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->user->name }} · {{ $member->designation }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="text" name="title" value="{{ old('title') }}" placeholder="Task title" required
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">

                <textarea name="description" rows="3" placeholder="Description (optional)"
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">{{ old('description') }}</textarea>

                <input type="date" name="due_date" value="{{ old('due_date') }}"
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

                <button type="submit" class="btn-primary">Assign Task</button>
            </form>
        @endif
    </div>
</x-layouts.app>