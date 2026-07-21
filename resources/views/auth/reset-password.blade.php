<x-layouts.site :title="'Set New Password — Crafting Colons'">
    <div class="mx-auto flex min-h-[70vh] max-w-sm items-center px-4">
        <div class="card w-full p-8">
            <h1 class="font-display text-2xl font-semibold text-white">Set a new password</h1>

            @if ($errors->any())
                <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="email" name="email" value="{{ old('email', $email) }}" placeholder="Email" required
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
                <input type="password" name="password" placeholder="New password" required
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
                <input type="password" name="password_confirmation" placeholder="Confirm new password" required
                    class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
                <button type="submit" class="btn-primary w-full">Reset Password</button>
            </form>
        </div>
    </div>
</x-layouts.site>