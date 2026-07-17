<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>{{ $assessment->title }} — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $assessment->title }}</h1>
            <p class="text-neutral-400 text-sm mt-1">
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
                <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs uppercase tracking-wide text-neutral-400">
                            {{ $question->type->label() }} · {{ $question->marks }} marks
                        </span>
                        <form method="POST" action="{{ route('hr.questions.destroy', [$assessment, $question]) }}">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 underline">Remove</button>
                        </form>
                    </div>
                    <p class="mt-2">{{ $question->prompt }}</p>

                    @if ($question->type->usesOptions())
                        <ul class="mt-3 space-y-1 text-sm">
                            @foreach ($question->options as $option)
                                <li class="{{ $option->is_correct ? 'text-emerald-400' : 'text-neutral-400' }}">
                                    {{ $option->is_correct ? '✓' : '○' }} {{ $option->label }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Add question form --}}
        <div x-data="{ type: 'mcq' }" class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-4">
            <h2 class="text-lg font-semibold">Add Question</h2>

            <form method="POST" action="{{ route('hr.questions.store', $assessment) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Type</label>
                    <select name="type" x-model="type"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                        @foreach (\App\Enums\QuestionType::cases() as $qType)
                            <option value="{{ $qType->value }}">{{ $qType->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Prompt</label>
                    <textarea name="prompt" rows="3" required
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-neutral-300 mb-1">Marks</label>
                        <input type="number" name="marks" value="1" min="1" required
                            class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                    </div>
                    <div x-show="type === 'coding'">
                        <label class="block text-sm text-neutral-300 mb-1">Language</label>
                        <input type="text" name="language" placeholder="e.g. php, python"
                            class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                    </div>
                </div>

                <div x-show="['mcq', 'true_false', 'multiple_select'].includes(type)" class="space-y-2">
                    <label class="block text-sm text-neutral-300 mb-1">Options</label>
                    <template x-for="i in 4" :key="i">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" :name="'options['+(i-1)+'][is_correct]'" value="1"
                                class="rounded border-neutral-700 bg-neutral-800">
                            <input type="text" :name="'options['+(i-1)+'][label]'" placeholder="Option text"
                                class="flex-1 rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                        </div>
                    </template>
                </div>

                <button type="submit"
                    class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 hover:bg-neutral-200 transition">
                    Add Question
                </button>
            </form>
        </div>
    </div>
</body>
</html>