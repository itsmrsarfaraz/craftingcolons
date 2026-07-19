<x-layouts.site :title="$jobPosting->title.' — Crafting Colons'">
    <section class="section max-w-2xl">
        <div data-reveal>
            <a href="{{ route('careers.index') }}" class="text-sm text-ink-400 hover:text-white">← All roles</a>
            <h1 class="mt-4 font-display text-3xl font-semibold sm:text-4xl">{{ $jobPosting->title }}</h1>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="rounded-full border border-ink-700 px-3 py-1 text-xs text-ink-300">{{ $jobPosting->employment_type->label() }}</span>
                <span class="rounded-full border border-ink-700 px-3 py-1 text-xs text-ink-300">{{ $jobPosting->department }}</span>
                <span class="rounded-full border border-ink-700 px-3 py-1 text-xs text-ink-300">{{ $jobPosting->location ?? 'Remote' }}</span>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="prose prose-invert mt-8 max-w-none" data-reveal data-reveal-delay="1">
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

        <div class="card mt-10 p-6" data-reveal data-reveal-delay="2">
            @auth
                @if (auth()->user()->hasRole('applicant') && $jobPosting->isOpen())
                    <form method="POST" action="{{ route('applicant.applications.store', $jobPosting->slug) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm text-ink-300">Select CV / Document</label>
                            <select name="applicant_document_id" required
                                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                                @foreach (auth()->user()->applicantDocuments as $doc)
                                    <option value="{{ $doc->id }}">{{ $doc->type->label() }} — {{ $doc->original_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm text-ink-300">Cover Letter (optional)</label>
                            <textarea name="cover_letter" rows="4"
                                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white"></textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full sm:w-auto">Submit Application</button>
                    </form>
                @elseif (! $jobPosting->isOpen())
                    <p class="text-ink-400">This position is no longer accepting applications.</p>
                @endif
            @else
                <div class="text-center">
                    <p class="text-ink-400">Log in as an applicant to apply for this role.</p>
                    <a href="{{ route('login') }}" class="btn-primary mt-4">Log In to Apply</a>
                </div>
            @endauth
        </div>
    </section>
</x-layouts.site>