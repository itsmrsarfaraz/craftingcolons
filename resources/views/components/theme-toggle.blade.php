<button
    x-data="{ dark: localStorage.getItem('theme') !== 'light' }"
    x-init="$watch('dark', value => {
        document.documentElement.classList.toggle('dark', value);
        localStorage.setItem('theme', value ? 'dark' : 'light');
    }); document.documentElement.classList.toggle('dark', dark)"
    @click="dark = !dark"
    class="rounded-lg p-2 hover:bg-neutral-800 transition"
    aria-label="Toggle dark mode"
>
    <span x-show="dark" x-cloak>☀️</span>
    <span x-show="!dark" x-cloak>🌙</span>
</button>