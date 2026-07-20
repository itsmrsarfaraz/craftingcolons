<x-layouts.app :title="'New User — Crafting Colons'">
    <div class="mx-auto max-w-xl">
        <h1 class="font-display text-2xl font-semibold text-white">Create Staff Account</h1>
        <p class="mt-1 text-sm text-ink-400">Directly create an HR, Staff, Team Lead, Employee, or Admin account.</p>

        @if ($errors->any())
            <div class="mt-4 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" class="card mt-6 space-y-4 p-6">
            @csrf

            <input type="text" name="name" value="{{ old('name') }}" placeholder="Full name" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">

            <select name="role" class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white">
                <option value="hr">HR</option>
                <option value="staff">Staff</option>
                <option value="team-lead">Team Lead</option>
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
            </select>

            <p class="text-xs text-ink-500">A secure temporary password will be generated automatically.</p>

            <button type="submit" class="btn-primary">Create Account</button>
        </form>
    </div>
</x-layouts.app>