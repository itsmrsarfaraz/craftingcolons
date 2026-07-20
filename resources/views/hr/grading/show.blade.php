<x-layouts.app :title="'Grade Attempt — Crafting Colons'">
    <div class="mx-auto max-w-2xl">
        <h1 class="font-display text-2xl font-semibold text-white">{{ $attempt->candidate->name }}</h1>
        <p class="mt-1 text-sm text-ink-400">{{ $attempt->assessment->title }}</p>
        @if (! is_null($attempt->score))
            <p class="mt-2 text-sm">
                Score: <span class="font-mono text-white">{{ $attempt->score }}%</span> ·
                <span class="{{ $attempt->passed ? 'text-emerald-400' : 'text-red-400' }}">{{ $attempt->passed ? 'Passed' : 'Failed' }}</span>
            </p>
        @else
            <p class="mt-2 text-sm text-amber-400">Awaiting manual grading</p>
        @endif

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.grading.store', $attempt) }}" class="mt-6 space-y-4">
            @csrf

            @foreach ($attempt->answers as $answer)
                @php $question = $answer->question; @endphp
                <div class="card p-6">
                    <p class="mb-2 text-xs uppercase tracking-wide text-ink-500">
                        {{ $question->type->label() }} · Max {{ $question->marks }} marks
                    </p>
                    <p class="mb-3 text-white">{{ $question->prompt }}</p>

                    @if ($question->type->usesOptions())
                        <ul class="mb-3 space-y-1 text-sm">
                            @foreach ($question->options as $option)
                                @php $selected = in_array($option->id, $answer->selected_option_ids ?? []); @endphp
                                <li class="{{ $selected ? 'font-medium text-white' : 'text-ink-500' }}">
                                    {{ $selected ? '☑' : '☐' }} {{ $option->label }}
                                    @if ($option->is_correct) <span class="text-xs text-emerald-400">(correct)</span> @endif
                                </li>
                            @endforeach
                        </ul>
                        <p class="text-xs text-ink-500">Auto-graded: {{ $answer->marks_awarded }} / {{ $question->marks }}</p>
                        <input type="hidden" name="grades[{{ $loop->parent->index }}][answer_id]" value="{{ $answer->id }}">
                        <input type="hidden" name="grades[{{ $loop->parent->index }}][marks_awarded]" value="{{ $answer->marks_awarded }}">
                    @else
                        @if (in_array($question->type->value, ['file_upload', 'coding']))
                            @if ($answer->file_path)
                                <p class="mb-3 text-sm text-emerald-400">File submitted.</p>
                            @else
                                <p class="mb-3 text-sm text-ink-500">No file submitted.</p>
                            @endif
                        @else
                            <p class="mb-3 whitespace-pre-wrap rounded-lg bg-ink-800/60 p-3 text-sm text-ink-200">{{ $answer->text_answer ?: '(no answer submitted)' }}</p>
                        @endif

                        <div class="flex items-center gap-2">
                            <label class="text-sm text-ink-300">Marks awarded:</label>
                            <input type="number" name="grades[{{ $loop->index }}][marks_awarded]"
                                value="{{ old("grades.{$loop->index}.marks_awarded", $answer->marks_awarded) }}"
                                min="0" max="{{ $question->marks }}" required
                                class="w-20 rounded-lg border border-ink-700 bg-ink-800 px-3 py-1 text-white">
                            <span class="text-xs text-ink-500">/ {{ $question->marks }}</span>
                        </div>
                        <input type="hidden" name="grades[{{ $loop->index }}][answer_id]" value="{{ $answer->id }}">
                    @endif
                </div>
            @endforeach

            <button type="submit" class="btn-primary">Save Grades</button>
        </form>
    </div>
</x-layouts.app>