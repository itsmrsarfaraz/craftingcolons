<x-layouts.app :title="'Grading — '.$jobPosting->title">
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $attempt->candidate->name }}</h1>
            <p class="text-ink-400 text-sm">{{ $attempt->assessment->title }}</p>
            @if (! is_null($attempt->score))
                <p class="text-sm mt-2">
                    Score: <span class="font-mono">{{ $attempt->score }}%</span> ·
                    <span class="{{ $attempt->passed ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $attempt->passed ? 'Passed' : 'Failed' }}
                    </span>
                </p>
            @else
                <p class="text-sm text-amber-400 mt-2">Awaiting manual grading</p>
            @endif
        </div>

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('hr.grading.store', $attempt) }}" class="space-y-4">
            @csrf

            @foreach ($attempt->answers as $answer)
                @php $question = $answer->question; @endphp
                <div class="bg-ink-900 border border-ink-800 rounded-2xl p-6">
                    <p class="text-xs text-ink-500 uppercase tracking-wide mb-2">
                        {{ $question->type->label() }} · Max {{ $question->marks }} marks
                    </p>
                    <p class="mb-3">{{ $question->prompt }}</p>

                    @if ($question->type->usesOptions())
                        <ul class="text-sm space-y-1 mb-3">
                            @foreach ($question->options as $option)
                                @php $selected = in_array($option->id, $answer->selected_option_ids ?? []); @endphp
                                <li class="{{ $selected ? 'text-white font-medium' : 'text-ink-500' }}">
                                    {{ $selected ? '☑' : '☐' }} {{ $option->label }}
                                    @if ($option->is_correct) <span class="text-emerald-400 text-xs">(correct)</span> @endif
                                </li>
                            @endforeach
                        </ul>
                        <p class="text-xs text-ink-500">Auto-graded: {{ $answer->marks_awarded }} / {{ $question->marks }}</p>
                        <input type="hidden" name="grades[{{ $loop->parent->index }}][answer_id]" value="{{ $answer->id }}">
                        <input type="hidden" name="grades[{{ $loop->parent->index }}][marks_awarded]" value="{{ $answer->marks_awarded }}">
                    @else
                        @if ($question->type->value === 'file_upload' || $question->type->value === 'coding')
                            @if ($answer->file_path)
                                <p class="text-sm text-emerald-400 mb-3">File submitted (download not shown here — wire to Storage in a future pass).</p>
                            @else
                                <p class="text-sm text-ink-500 mb-3">No file submitted.</p>
                            @endif
                        @else
                            <p class="text-sm bg-ink-800/60 rounded-lg p-3 mb-3 whitespace-pre-wrap">{{ $answer->text_answer ?: '(no answer submitted)' }}</p>
                        @endif

                        <div class="flex items-center gap-2">
                            <label class="text-sm text-ink-300">Marks awarded:</label>
                            <input type="number" name="grades[{{ $loop->index }}][marks_awarded]"
                                value="{{ old("grades.{$loop->index}.marks_awarded", $answer->marks_awarded) }}"
                                min="0" max="{{ $question->marks }}" required
                                class="w-20 rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-1">
                            <span class="text-xs text-ink-500">/ {{ $question->marks }}</span>
                        </div>
                        <input type="hidden" name="grades[{{ $loop->index }}][answer_id]" value="{{ $answer->id }}">
                    @endif
                </div>
            @endforeach

            <button type="submit"
                class="bg-white text-ink-950 font-medium rounded-lg px-4 py-2 hover:bg-ink-200 transition">
                Save Grades
            </button>
        </form>
    </div>
</x-layouts.app>