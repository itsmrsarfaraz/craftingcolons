<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>{{ $jobPosting->title }} — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $jobPosting->title }}</h1>
            <p class="text-neutral-400 text-sm mt-1">
                {{ $jobPosting->employment_type->label() }} · {{ $jobPosting->department }} · {{ $jobPosting->location ?? 'Remote' }}
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

        <div class="prose prose-invert max-w-none">
            <h3>Description</h3>
            <p>{{ $jobPosting->description }}</p>

            @if ($jobPosting->responsibilities)
                <h3>Responsibilities</h3>
                <p>{{ $jobPosting->responsibilities }}</p>
            @endif

            @if ($jobPosting->requirements)
                <h3>Requirements</h3>
                <p>{{ $jobPosting->requirements }}</p>
            @endif
        </div>

        @auth
            @if (auth()->user()->hasRole('applicant') && $jobPosting->isOpen())
                <form method="POST" action="{{ route('applicant.applications.store', $jobPosting->slug) }}"
                      class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-neutral-300 mb-1">Select CV / Document</label>
                        <select name="applicant_document_id" required
                            class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                            @foreach (auth()->user()->applicantDocuments as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->type->label() }} — {{ $doc->original_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-neutral-300 mb-1">Cover Letter (optional)</label>
                        <textarea name="cover_letter" rows="4"
                            class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2"></textarea>
                    </div>
                    <button type="submit"
                        class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 hover:bg-neutral-200 transition">
                        Submit Application
                    </button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="underline text-sm">Log in to apply</a>
        @endauth
    </div>
</body>
</html>