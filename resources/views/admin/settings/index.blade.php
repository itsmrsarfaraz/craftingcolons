<x-layouts.app :title="'Settings — Crafting Colons'">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold">Settings</h1>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="card border border-ink-800 rounded-2xl divide-y divide-ink-800">
            @foreach ($settings as $setting)
                <div class="px-6 py-4 flex items-center justify-between">
                    <span class="text-sm font-mono">{{ $setting->key }}</span>
                    <span class="text-sm text-ink-400">{{ $setting->value }}</span>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}"
              class="bg-ink-900 border border-ink-800 rounded-2xl p-6 space-y-3">
            @csrf
            <input type="text" name="key" placeholder="e.g. assessment.max_violations_allowed_default" required
                class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
            <input type="text" name="value" placeholder="Value" required
                class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
            <select name="type" class="w-full rounded-lg bg-ink-800 border border-ink-700 text-white px-3 py-2">
                <option value="string">String</option>
                <option value="integer">Integer</option>
                <option value="boolean">Boolean</option>
                <option value="json">JSON</option>
            </select>
            <button type="submit"
                class="btn-primary font-medium rounded-lg px-4 py-2 hover:bg-ink-200 transition">
                Save Setting
            </button>
        </form>
    </div>
</x-layouts.app>