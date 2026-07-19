<div x-data="globalSearch()" class="relative" @click.outside="open = false">
    <input
        type="text"
        x-model="query"
        @input.debounce.300ms="search()"
        @focus="open = query.length >= 2"
        placeholder="Search..."
        class="w-64 rounded-lg bg-neutral-800 border border-neutral-700 text-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/20"
    >

    <div x-show="open" x-cloak
         class="absolute mt-2 w-96 max-h-96 overflow-y-auto bg-neutral-900 border border-neutral-800 rounded-xl shadow-xl z-50">
        <template x-if="loading">
            <p class="text-xs text-neutral-500 p-4">Searching...</p>
        </template>

        <template x-if="!loading && Object.keys(results).length === 0 && query.length >= 2">
            <p class="text-xs text-neutral-500 p-4">No results found.</p>
        </template>

        <template x-for="(items, type) in results" :key="type">
            <div class="p-3 border-b border-neutral-800 last:border-0">
                <p class="text-xs uppercase tracking-wide text-neutral-500 mb-2" x-text="type"></p>
                <template x-for="item in items" :key="item.url">
                    <a :href="item.url" class="block py-1.5 text-sm hover:text-neutral-300">
                        <span x-text="item.title"></span>
                    </a>
                </template>
            </div>
        </template>
    </div>
</div>

<script>
    function globalSearch() {
        return {
            query: '',
            results: {},
            loading: false,
            open: false,
            async search() {
                if (this.query.length < 2) {
                    this.open = false;
                    return;
                }
                this.loading = true;
                this.open = true;
                const response = await fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(this.query)}`);
                this.results = await response.json();
                this.loading = false;
            },
        };
    }
</script>