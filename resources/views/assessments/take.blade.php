<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>{{ $attempt->assessment->title }} — Assessment</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full text-white"
      x-data="assessmentTaker({
          attemptId: {{ $attempt->id }},
          remainingSeconds: {{ $attempt->remainingSeconds() }},
          csrfToken: '{{ csrf_token() }}',
          answerUrl: '{{ route('assessments.answer', $attempt) }}',
          submitUrl: '{{ route('assessments.submit', $attempt) }}',
      })"
      x-init="startTimer()">

    <div class="sticky top-0 bg-neutral-950/95 border-b border-neutral-800 px-6 py-4 flex items-center justify-between z-10">
        <div>
            <h1 class="font-semibold">{{ $attempt->assessment->title }}</h1>
            <p class="text-xs text-neutral-500" x-show="saving">Saving...</p>
            <p class="text-xs text-emerald-500" x-show="!saving && lastSaved" x-cloak>Saved</p>
        </div>
        <div class="text-lg font-mono" :class="remainingSeconds < 60 ? 'text-red-400' : 'text-white'">
            <span x-text="formattedTime"></span>
        </div>
    </div>

    <div class="max-w-2xl mx-auto py-8 px-4 space-y-6">
        @foreach ($questions as $index => $question)
            @php $existing = $existingAnswers->get($question->id); @endphp
            <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6" data-question-id="{{ $question->id }}">
                <p class="text-sm text-neutral-500 mb-2">Question {{ $index + 1 }} of {{ $questions->count() }} · {{ $question->marks }} marks</p>
                <p class="mb-4">{{ $question->prompt }}</p>

                @if ($question->type->usesOptions())
                    <div class="space-y-2">
                        @foreach ($question->options as $option)
                            <label class="flex items-center gap-2 bg-neutral-800/60 rounded-lg px-4 py-2 cursor-pointer">
                                <input
                                    type="{{ $question->type->value === 'multiple_select' ? 'checkbox' : 'radio' }}"
                                    name="q{{ $question->id }}"
                                    value="{{ $option->id }}"
                                    {{ $existing && in_array($option->id, $existing->selected_option_ids ?? []) ? 'checked' : '' }}
                                    @change="saveAnswer({{ $question->id }}, this)"
                                    class="rounded border-neutral-700 bg-neutral-800">
                                {{ $option->label }}
                            </label>
                        @endforeach
                    </div>
                @elseif (in_array($question->type->value, ['short_answer', 'long_answer']))
                    <textarea
                        rows="{{ $question->type->value === 'long_answer' ? 6 : 2 }}"
                        @input.debounce.800ms="saveTextAnswer({{ $question->id }}, this.value)"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2"
                    >{{ $existing->text_answer ?? '' }}</textarea>
                @elseif (in_array($question->type->value, ['file_upload', 'coding']))
                    <input type="file" @change="saveFileAnswer({{ $question->id }}, this.files[0])"
                        class="text-sm text-neutral-300">
                    @if ($existing?->file_path)
                        <p class="text-xs text-emerald-400 mt-2">File uploaded</p>
                    @endif
                @endif
            </div>
        @endforeach

        <button @click="confirmSubmit()"
            class="w-full bg-white text-neutral-950 font-medium rounded-lg py-3 hover:bg-neutral-200 transition">
            Submit Assessment
        </button>
    </div>

    <script>
        function assessmentTaker({ attemptId, remainingSeconds, csrfToken, answerUrl, submitUrl }) {
            return {
                remainingSeconds,
                saving: false,
                lastSaved: false,
                get formattedTime() {
                    const m = Math.floor(this.remainingSeconds / 60);
                    const s = this.remainingSeconds % 60;
                    return `${m}:${s.toString().padStart(2, '0')}`;
                },
                startTimer() {
                    setInterval(() => {
                        if (this.remainingSeconds > 0) {
                            this.remainingSeconds--;
                        } else {
                            this.submit(true);
                        }
                    }, 1000);
                },
                async post(body) {
                    this.saving = true;
                    const response = await fetch(answerUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body,
                    });
                    this.saving = false;
                    this.lastSaved = true;
                    if (response.ok) {
                        const data = await response.json();
                        this.remainingSeconds = data.remaining_seconds;
                    }
                },
                saveAnswer(questionId, input) {
                    const isCheckbox = input.type === 'checkbox';
                    const form = new FormData();
                    form.append('question_id', questionId);
                    if (isCheckbox) {
                        document.querySelectorAll(`input[name="q${questionId}"]:checked`)
                            .forEach(el => form.append('selected_option_ids[]', el.value));
                    } else {
                        form.append('selected_option_ids[]', input.value);
                    }
                    this.post(form);
                },
                saveTextAnswer(questionId, value) {
                    const form = new FormData();
                    form.append('question_id', questionId);
                    form.append('text_answer', value);
                    this.post(form);
                },
                saveFileAnswer(questionId, file) {
                    const form = new FormData();
                    form.append('question_id', questionId);
                    form.append('file', file);
                    this.post(form);
                },
                confirmSubmit() {
                    if (confirm('Submit your assessment? You cannot make changes after submitting.')) {
                        this.submit(false);
                    }
                },
                submit() {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = submitUrl;
                    form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
                    document.body.appendChild(form);
                    form.submit();
                },
            };
        }
    </script>
</body>
</html>