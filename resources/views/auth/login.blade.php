<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <title>Login — Crafting Colons</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center">
    <div class="w-full max-w-sm bg-neutral-900 border border-neutral-800 rounded-2xl p-8 shadow-xl">
        <h1 class="text-2xl font-semibold text-white mb-1">Welcome back</h1>
        <p class="text-neutral-400 text-sm mb-6">Sign in to Crafting Colons</p>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-400 bg-red-950/50 border border-red-900 rounded-lg px-4 py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-neutral-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/20">
            </div>
            <div>
                <label class="block text-sm text-neutral-300 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg bg-neutral-800 border border-neutral-700 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/20">
            </div>
            <label class="flex items-center gap-2 text-sm text-neutral-400">
                <input type="checkbox" name="remember" class="rounded border-neutral-700 bg-neutral-800">
                Remember me
            </label>
            <button type="submit"
                class="w-full bg-white text-neutral-950 font-medium rounded-lg py-2 hover:bg-neutral-200 transition">
                Sign in
            </button>
        </form>

        <p class="mt-6 text-sm text-neutral-400 text-center">
            No account? <a href="{{ route('register') }}" class="text-white underline">Register</a>
        </p>
    </div>
</body>
</html>