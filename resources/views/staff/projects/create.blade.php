<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Project — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">New Project</h1>

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.projects.store') }}" enctype="multipart/form-data"
              class="bg-neutral-900 border border-neutral-800 rounded-2xl p-6 space-y-4">
            @csrf

            {{-- Title --}}
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Project Title" required
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">

            {{-- Excerpt --}}
            <textarea name="excerpt" placeholder="Short description / Excerpt" rows="2"
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">{{ old('excerpt') }}</textarea>

            {{-- Body --}}
            <textarea name="body" placeholder="Detailed project case study body" rows="10" required
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">{{ old('body') }}</textarea>

            {{-- Project Logistics --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Client Name</label>
                    <input type="text" name="client_name" value="{{ old('client_name') }}" placeholder="e.g., Acme Corp" required
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-neutral-300 mb-1">Project Type</label>
                    <input type="text" name="project_type" value="{{ old('project_type') }}" placeholder="e.g., E-Commerce Mobile App" required
                        class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                </div>
            </div>

            {{-- Project URL --}}
            <div>
                <label class="block text-sm text-neutral-300 mb-1">Project URL</label>
                <input type="url" name="project_url" value="{{ old('project_url') }}" placeholder="https://example.com"
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            {{-- Technologies Used Multi-Select --}}
            <div>
                <label class="block text-sm text-neutral-300 mb-1">Technologies Used</label>
                <select name="technologies[]" multiple class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2 min-h-[120px]">
                    @foreach ($technologies as $tech)
                        <option value="{{ $tech->id }}" {{ in_array($tech->id, old('technologies', [])) ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-neutral-500 mt-1">Hold Down Ctrl (Cmd on Mac) to select multiple items.</p>
            </div>

            {{-- Repeatable Project Results Section --}}
            <div class="border border-neutral-800 rounded-xl p-4 bg-neutral-950/40 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-neutral-300">Project Key Results</label>
                    <button type="button" id="add-result-row" 
                        class="text-xs bg-neutral-800 border border-neutral-700 hover:bg-neutral-700 px-2 py-1 rounded text-neutral-200 transition">
                        + Add Metric Row
                    </button>
                </div>
                
                <div id="results-container" class="space-y-2">
                    @if(old('results'))
                        @foreach(old('results') as $index => $result)
                            <div class="grid grid-cols-12 gap-2 result-row">
                                <div class="col-span-7">
                                    <input type="text" name="results[{{ $index }}][metric]" value="{{ $result['metric'] ?? '' }}" placeholder="Metric Label (e.g. Conversion Rate Increase)" class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white text-sm px-3 py-1.5">
                                </div>
                                <div class="col-span-4">
                                    <input type="text" name="results[{{ $index }}][value]" value="{{ $result['value'] ?? '' }}" placeholder="Value (e.g. +42%)" class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white text-sm px-3 py-1.5">
                                </div>
                                <div class="col-span-1 flex items-center justify-center">
                                    <button type="button" onclick="this.closest('.result-row').remove()" class="text-red-400 hover:text-red-300 text-sm">&times;</button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Baseline Single Row Layout Default --}}
                        <div class="grid grid-cols-12 gap-2 result-row">
                            <div class="col-span-7">
                                <input type="text" name="results[0][metric]" placeholder="Metric Label (e.g. Load Time Reduction)" class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white text-sm px-3 py-1.5">
                            </div>
                            <div class="col-span-4">
                                <input type="text" name="results[0][value]" placeholder="Value (e.g. 60%)" class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white text-sm px-3 py-1.5">
                            </div>
                            <div class="col-span-1 flex items-center justify-center">
                                <button type="button" onclick="this.closest('.result-row').remove()" class="text-red-400 hover:text-red-300 text-sm">&times;</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tags --}}
            <input type="text" name="tags" value="{{ old('tags') }}" placeholder="Tags, comma separated"
                class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">

            {{-- Featured Image Upload --}}
            <div>
                <label class="block text-sm text-neutral-300 mb-1">Featured Case Study Image</label>
                <input type="file" name="featured_image" accept="image/*" class="text-sm text-neutral-300">
            </div>

            {{-- SEO Metadata --}}
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Meta title (max 60 chars)" maxlength="60"
                    class="rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                <input type="text" name="meta_description" value="{{ old('meta_description') }}" placeholder="Meta description (max 160 chars)" maxlength="160"
                    class="rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
            </div>

            {{-- Project Status --}}
            <select name="status" class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2">
                @foreach (\App\Enums\ProjectStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ old('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 hover:bg-neutral-200 transition">
                Save Project
            </button>
        </form>
    </div>

    {{-- Script context block managing the dynamic repeatable metric inputs --}}
    <script>
        document.getElementById('add-result-row').addEventListener('click', function() {
            const container = document.getElementById('results-container');
            const rowCount = container.getElementsByClassName('result-row').length;
            
            const newRow = document.createElement('div');
            newRow.className = 'grid grid-cols-12 gap-2 result-row';
            newRow.innerHTML = `
                <div class="col-span-7">
                    <input type="text" name="results[${rowCount}][metric]" placeholder="Metric Label" class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white text-sm px-3 py-1.5">
                </div>
                <div class="col-span-4">
                    <input type="text" name="results[${rowCount}][value]" placeholder="Value" class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white text-sm px-3 py-1.5">
                </div>
                <div class="col-span-1 flex items-center justify-center">
                    <button type="button" onclick="this.closest('.result-row').remove()" class="text-red-400 hover:text-red-300 text-sm">&times;</button>
                </div>
            `;
            container.appendChild(newRow);
        });
    </script>
</body>
</html>