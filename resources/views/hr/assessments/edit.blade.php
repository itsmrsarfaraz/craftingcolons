<x-layouts.app :title="$assessment->title">
    <div class="mx-auto max-w-3xl">
        <h1 class="font-display text-2xl font-semibold text-white">{{ $assessment->title }}</h1>
        <p class="mt-1 text-sm text-ink-400">
            {{ $assessment->duration_minutes }} min · Passing: {{ $assessment->passing_marks }}% ·
            Total marks: {{ $assessment->total_marks }}
        </p>

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

        <div class="mt-6 space-y-3">
            @foreach ($assessment->questions as $question)
                <div x-data="{ editing: false }" class="card p-4">
                    <div x-show="!editing">
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-wide text-ink-500">
                                {{ $question->type->label() }} · {{ $question->marks }} marks
                            </span>
                            <div class="flex gap-3">
                                <button @click="editing = true" class="text-xs text-ink-300 hover:underline">Edit</button>
                                <form method="POST" action="{{ route('hr.questions.destroy', [$assessment, $question]) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-400 hover:underline">Remove</button>
                                </form>
                            </div>
                        </div>
                        <p class="mt-2 text-white">{{ $question->prompt }}</p>

                        @if ($question->type->usesOptions())
                            <ul class="mt-3 space-y-1 text-sm">
                                @foreach ($question->options as $option)
                                    <li class="{{ $option->is_correct ? 'text-emerald-400' : 'text-ink-400' }}">
                                        {{ $option->is_correct ? '✓' : '○' }} {{ $option->label }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <form x-show="editing" x-cloak method="POST" action="{{ route('hr.questions.update', [$assessment, $question]) }}" class="space-y-3">
                        @csrf @method('PUT')
                        <input type="hidden" name="type" value="{{ $question->type->value }}">

                        <textarea name="prompt" rows="2" required
                            class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ $question->prompt }}</textarea>

                        <input type="number" name="marks" value="{{ $question->marks }}" min="1" required
                            class="w-24 rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

                        @if ($question->type->usesOptions())
                            @foreach ($question->options as $index => $option)
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="options[{{ $index }}][is_correct]" value="1" {{ $option->is_correct ? 'checked' : '' }}
                                        class="rounded border-ink-700 bg-ink-800">
                                    <input type="text" name="options[{{ $index }}][label]" value="{{ $option->label }}"
                                        class="flex-1 rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                                </div>
                            @endforeach
                        @endif

                        <div class="flex gap-2">
                            <button type="submit" class="btn-primary !px-3 !py-1.5 text-xs">Save</button>
                            <button type="button" @click="editing = false" class="text-xs text-ink-400 hover:underline">Cancel</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>

        <div x-data="{ type: 'mcq' }" class="card mt-6 space-y-4 p-6">
            <h2 class="text-lg font-semibold text-white">Add Question</h2>

            <form method="POST" action="{{ route('hr.questions.store', $assessment) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="mb-1 block text-sm text-ink-300">Type</label>
                    <select name="type" x-model="type" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                        @foreach (\App\Enums\QuestionType::cases() as $qType)
                            <option value="{{ $qType->value }}">{{ $qType->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-ink-300">Prompt</label>
                    <textarea name="prompt" rows="3" required
                        class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm text-ink-300">Marks</label>
                        <input type="number" name="marks" value="1" min="1" required
                            class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    </div>
                    <div x-show="type === 'coding'">
                        <label class="mb-1 block text-sm text-ink-300">Language</label>
                        <input type="text" name="language" placeholder="e.g. php, python"
                            class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    </div>
                </div>

                <div x-show="['mcq', 'true_false', 'multiple_select'].includes(type)" class="space-y-2">
                    <label class="mb-1 block text-sm text-ink-300">Options</label>
                    <template x-for="i in 4" :key="i">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" :name="'options['+(i-1)+'][is_correct]'" value="1"
                                class="rounded border-ink-700 bg-ink-800">
                            <input type="text" :name="'options['+(i-1)+'][label]'" placeholder="Option text"
                                class="flex-1 rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                        </div>
                    </template>
                </div>

                <button type="submit" class="btn-primary">Add Question</button>
            </form>
        </div>
    </div>
</x-layouts.app>