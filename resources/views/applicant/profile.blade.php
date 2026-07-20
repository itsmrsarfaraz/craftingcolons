<x-layouts.app :title="'My Profile — Crafting Colons'">
    <div class="mx-auto max-w-2xl space-y-8">
        <div>
            <h1 class="font-display text-2xl font-semibold text-white">My Profile</h1>
            <p class="text-sm text-ink-400">Keep this up to date before applying for a role.</p>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('applicant.profile.update') }}" class="card space-y-4 p-6">
            @csrf
            @method('PUT')

            <input type="text" name="headline" value="{{ old('headline', $profile?->headline) }}" placeholder="e.g. Backend Engineer"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="phone" value="{{ old('phone', $profile?->phone) }}" placeholder="Phone"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <input type="text" name="city" value="{{ old('city', $profile?->city) }}" placeholder="City"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <input type="text" name="address" value="{{ old('address', $profile?->address) }}" placeholder="Address"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $profile?->portfolio_url) }}" placeholder="https://your-portfolio.com"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <textarea name="bio" rows="4" placeholder="Bio"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('bio', $profile?->bio) }}</textarea>

            <button type="submit" class="btn-primary">Save Profile</button>
        </form>

        <div class="card space-y-4 p-6">
            <h2 class="text-lg font-semibold text-white">Documents</h2>

            <ul class="space-y-2">
                @forelse ($documents as $document)
                    <li class="flex items-center justify-between rounded-lg bg-ink-800/60 px-4 py-2 text-sm">
                        <div>
                            <span class="font-medium text-white">{{ $document->type->label() }}</span>
                            <span class="ml-2 text-ink-400">{{ $document->original_name }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('applicant.documents.download', $document) }}" class="text-brand-400 hover:underline">Download</a>
                            <form method="POST" action="{{ route('applicant.documents.destroy', $document) }}">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:underline">Delete</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <p class="text-sm text-ink-500">No documents uploaded yet.</p>
                @endforelse
            </ul>

            <form method="POST" action="{{ route('applicant.documents.store') }}" enctype="multipart/form-data"
                  class="flex flex-col gap-3 border-t border-ink-800 pt-4 sm:flex-row sm:items-end">
                @csrf
                <div>
                    <label class="mb-1 block text-sm text-ink-300">Type</label>
                    <select name="type" class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                        @foreach (\App\Enums\DocumentType::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="mb-1 block text-sm text-ink-300">File (PDF/DOC, max 5MB)</label>
                    <input type="file" name="file" required class="w-full text-sm text-ink-300">
                </div>
                <button type="submit" class="btn-primary">Upload</button>
            </form>
        </div>
    </div>
</x-layouts.app>