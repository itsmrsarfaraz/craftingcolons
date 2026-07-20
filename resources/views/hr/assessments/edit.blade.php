<x-layouts.app :title="'Edit — '.$jobPosting->title">
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $assessment->title }}</h1>
            <p class="text-ink-400 text-sm mt-1">
                {{ $assessment->duration_minutes }} min · Passing: {{ $assessment->passing_marks }}% ·
                Total marks: {{ $assessment->total_marks }}
            </p>
        </div>

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

        <div class="space-y-3">
            @foreach ($assessment->questions as $question)
                <div x-data="{ editing: false }" class="bg-ink-900 border border-ink-800 rounded-xl p-4">
                    <div x-show="!editing">
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase tracking-wide text-ink-400">
                                {{ $question->type->label() }} · {{ $question->marks }} marks
                            </span>
                            <div class="flex gap-3">
                                <button @click="editing = true" class="text-xs underline">Edit</button>
                                <form method="POST" action="{{ route('hr.questions.destroy', [$assessment, $question]) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-400 underline">Remove</button>
                                </form>
                            </div>
                        </div>
                        <p class="mt-2">{{ $question->prompt }}</p>

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
                            class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">{{ $question->prompt }}</textarea>

                        <input type="number" name="marks" value="{{ $question->marks }}" min="1" required
                            class="w-24 rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">

                        @if ($question->type->usesOptions())
                            @foreach ($question->options as $index => $option)
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="options[{{ $index }}][is_correct]" value="1" {{ $option->is_correct ? 'checked' : '' }}
                                        class="rounded border-ink-700 bg-ink-800">
                                    <input type="text" name="options[{{ $index }}][label]" value="{{ $option->label }}"
                                        class="flex-1 rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                                </div>
                            @endforeach
                        @endif

                        <div class="flex gap-2">
                            <button type="submit" class="text-xs bg-white text-ink-950 rounded-lg px-3 py-1.5">Save</button>
                            <button type="button" @click="editing = false" class="text-xs underline text-ink-400">Cancel</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- Add question form --}}
        <div x-data="{ type: 'mcq' }" class="bg-ink-900 border border-ink-800 rounded-2xl p-6 space-y-4">
            <h2 class="text-lg font-semibold">Add Question</h2>

            <form method="POST" action="{{ route('hr.questions.store', $assessment) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm text-ink-300 mb-1">Type</label>
                    <select name="type" x-model="type"
                        class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                        @foreach (\App\Enums\QuestionType::cases() as $qType)
                            <option value="{{ $qType->value }}">{{ $qType->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-ink-300 mb-1">Prompt</label>
                    <textarea name="prompt" rows="3" required
                        class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-ink-300 mb-1">Marks</label>
                        <input type="number" name="marks" value="1" min="1" required
                            class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                    </div>
                    <div x-show="type === 'coding'">
                        <label class="block text-sm text-ink-300 mb-1">Language</label>
                        <input type="text" name="language" placeholder="e.g. php, python"
                            class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                    </div>
                </div>

                <div x-show="['mcq', 'true_false', 'multiple_select'].includes(type)" class="space-y-2">
                    <label class="block text-sm text-ink-300 mb-1">Options</label>
                    <template x-for="i in 4" :key="i">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" :name="'options['+(i-1)+'][is_correct]'" value="1"
                                class="rounded border-ink-700 bg-ink-800">
                            <input type="text" :name="'options['+(i-1)+'][label]'" placeholder="Option text"
                                class="flex-1 rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                        </div>
                    </template>
                </div>

                <button type="submit"
                    class="bg-white text-ink-950 font-medium rounded-lg px-4 py-2 hover:bg-ink-200 transition">
                    Add Question
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>