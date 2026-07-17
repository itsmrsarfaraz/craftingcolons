<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>My Profile — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-8">
        <div>
            <h1 class="text-2xl font-semibold">My Profile</h1>
            <p class="text-neutral-400 text-sm">Keep this up to date before applying for a role.</p>
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

        {{-- Profile fields --}}
        <form method="POST" action="{{ route('applicant.profile.update') }}"
              class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Headline</label>
                <input type="text" name="headline" value="{{ old('headline', $profile?->headline) }}"
                    placeholder="e.g. Backend Engineer"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $profile?->phone) }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $profile?->city) }}"
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Address</label>
                <input type="text" name="address" value="{{ old('address', $profile?->address) }}"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Portfolio URL</label>
                <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $profile?->portfolio_url) }}"
                    placeholder="https://..."
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            <div>
                <label class="block text-sm text-neutral-300 mb-1">Bio</label>
                <textarea name="bio" rows="4"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">{{ old('bio', $profile?->bio) }}</textarea>
            </div>

            <button type="submit"
                class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 hover:bg-neutral-200 transition">
                Save Profile
            </button>
        </form>

        {{-- Documents --}}
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-4">
            <h2 class="text-lg font-semibold">Documents</h2>

            <ul class="space-y-2">
                @forelse ($documents as $document)
                    <li class="flex items-center justify-between bg-neutral-800/60 rounded-lg px-4 py-2 text-sm">
                        <div>
                            <span class="font-medium">{{ $document->type->label() }}</span>
                            <span class="text-neutral-400 ml-2">{{ $document->original_name }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('applicant.documents.download', $document) }}" class="underline">Download</a>
                            <form method="POST" action="{{ route('applicant.documents.destroy', $document) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-400 underline">Delete</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <p class="text-neutral-500 text-sm">No documents uploaded yet.</p>
                @endforelse
            </ul>

            <form method="POST" action="{{ route('applicant.documents.store') }}" enctype="multipart/form-data"
                  class="flex items-end gap-3 pt-4 border-t border-neutral-800">
                @csrf
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Type</label>
                    <select name="type" class="rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                        @foreach (\App\Enums\DocumentType::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm text-neutral-300 mb-1">File (PDF/DOC, max 5MB)</label>
                    <input type="file" name="file" required
                        class="w-full text-sm text-neutral-300">
                </div>
                <button type="submit"
                    class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 hover:bg-neutral-200 transition">
                    Upload
                </button>
            </form>
        </div>
    </div>
</body>
</html>