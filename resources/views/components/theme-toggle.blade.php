<button
    x-data="{ light: localStorage.getItem('theme') === 'light' }"
    x-init="$watch('light', value => {
        document.documentElement.classList.toggle('light', value);
        document.documentElement.classList.toggle('dark', !value);
        localStorage.setItem('theme', value ? 'light' : 'dark');
    }); document.documentElement.classList.toggle('light', light); document.documentElement.classList.toggle('dark', !light)"
    @click="light = !light"
    class="rounded-lg p-2 text-slate-300 transition hover:bg-slate-800 hover:text-white"
    aria-label="Toggle light/dark mode"
>
    <i data-lucide="sun" class="h-4 w-4" x-show="light" x-cloak></i>
    <i data-lucide="moon" class="h-4 w-4" x-show="!light" x-cloak></i>
</button>