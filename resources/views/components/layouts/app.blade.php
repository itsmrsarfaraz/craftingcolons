<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js" defer onload="lucide.createIcons()"></script>
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="h-full bg-ink-950" x-data="{ sidebarOpen: false }">
    <div class="flex h-full">
        @include('partials.app-sidebar')

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/60 lg:hidden"></div>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex items-center justify-between border-b border-ink-800 bg-ink-950 px-4 py-3 lg:px-8">
                <button @click="sidebarOpen = true" class="text-white lg:hidden" aria-label="Open menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="hidden text-sm text-ink-400 lg:block">
                    {{ now()->format('l, F j, Y') }}
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('notifications.index') }}" class="relative text-ink-300 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @php $unread = auth()->user()->unreadNotifications()->count() @endphp
                        @if ($unread > 0)
                            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-brand-500 text-[10px] font-bold text-ink-950">
                                {{ $unread > 9 ? '9+' : $unread }}
                            </span>
                        @endif
                    </a>

                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-500/20 text-sm font-semibold text-brand-400">
                            {{ Str::substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="hidden text-sm text-white sm:block">{{ auth()->user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-ink-400 hover:text-white">Logout</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>