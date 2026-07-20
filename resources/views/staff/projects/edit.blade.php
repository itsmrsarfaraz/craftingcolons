<x-layouts.app :title="'Edit: '.$project->title">
    <section class="section max-w-2xl">
        <h1 class="font-display text-2xl font-semibold">Edit Project</h1>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('staff.projects.update', $project) }}" enctype="multipart/form-data"
              class="card mt-6 space-y-4 p-6">
            @csrf
            @method('PUT')

            <input type="text" name="title" value="{{ old('title', $project->title) }}" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <input type="text" name="client_name" value="{{ old('client_name', $project->client_name) }}" placeholder="Client name (optional)"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <select name="project_type" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                @foreach (\App\Enums\ProjectType::cases() as $type)
                    <option value="{{ $type->value }}" {{ $project->project_type === $type ? 'selected' : '' }}>
                        {{ $type->label() }}
                    </option>
                @endforeach
            </select>

            <textarea name="summary" rows="2" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('summary', $project->summary) }}</textarea>

            <textarea name="body" rows="10" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">{{ old('body', $project->body) }}</textarea>

            <input type="url" name="project_url" value="{{ old('project_url', $project->project_url) }}" placeholder="Live project URL (optional)"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <div>
                <label class="mb-1 block text-sm text-ink-300">Technologies</label>
                <select name="technologies[]" multiple class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                    @foreach ($technologies as $tech)
                        <option value="{{ $tech->id }}" {{ $project->technologies->contains($tech) ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm text-ink-300">Results / Metrics</label>
                <div x-data="{ results: {{ Illuminate\Support\Js::from($project->results->map(fn ($r) => ['metric_label' => $r->metric_label, 'metric_value' => $r->metric_value])->values()->all() ?: [['metric_label' => '', 'metric_value' => '']]) }} }" class="space-y-2">
                    <template x-for="(result, index) in results" :key="index">
                        <div class="flex gap-2">
                            <input type="text" :name="'results['+index+'][metric_label]'" x-model="result.metric_label" placeholder="Label (e.g. Load Time)"
                                class="flex-1 rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                            <input type="text" :name="'results['+index+'][metric_value]'" x-model="result.metric_value" placeholder="Value (e.g. 1.2s)"
                                class="flex-1 rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                            <button type="button" @click="results.splice(index, 1)" class="text-xs text-red-400">✕</button>
                        </div>
                    </template>
                    <button type="button" @click="results.push({ metric_label: '', metric_value: '' })" class="text-xs text-brand-400 hover:underline">
                        + Add result
                    </button>
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm text-ink-300">Featured Image</label>
                @if ($project->featuredImage())
                    <img src="{{ $project->featuredImage()->url() }}" class="mb-2 h-32 w-full rounded-lg object-cover">
                @endif
                <input type="file" name="featured_image" accept="image/*" class="text-sm text-ink-400">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="meta_title" value="{{ old('meta_title', $project->meta_title) }}" maxlength="60"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <input type="text" name="meta_description" value="{{ old('meta_description', $project->meta_description) }}" maxlength="160"
                    class="rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
            </div>

            <select name="status" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                @foreach (\App\Enums\ProjectStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ $project->status === $status ? 'selected' : '' }}>{{ $status->label() }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </section>
</x-layouts.app>