<x-layouts.site :title="'Reset Password — Crafting Colons'">
    <div class="mx-auto flex min-h-[70vh] max-w-sm items-center px-4">
        <div class="card w-full p-8">
            <h1 class="font-display text-2xl font-semibold text-white">Forgot your password?</h1>
            <p class="mt-2 text-sm text-ink-400">Enter your email and we'll send you a reset link.</p>

            @if (session('status'))
                <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
                @csrf
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
                <button type="submit" class="btn-primary w-full">Send Reset Link</button>
            </form>

            <p class="mt-6 text-center text-sm text-ink-400">
                <a href="{{ route('login') }}" class="text-brand-400 hover:underline">← Back to login</a>
            </p>
        </div>
    </div>
</x-layouts.site>