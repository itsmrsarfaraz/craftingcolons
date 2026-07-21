<x-layouts.app :title="'Users — Crafting Colons'">
    <div class="mx-auto max-w-4xl">
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-semibold text-white">Users</h1>
            <a href="{{ route('admin.users.create') }}" class="btn-primary !px-4 !py-2 text-sm">+ New Staff Account</a>
        </div>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="card mt-6 divide-y divide-ink-800">
            @foreach ($users as $user)
                <div x-data="{ editing: false }" class="flex items-center justify-between px-6 py-4">
                    <div>
                        <p class="font-medium text-white">{{ $user->name }}</p>
                        <p class="text-xs text-ink-500">{{ $user->email }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex gap-1">
                            @foreach ($user->roles as $role)
                                <span class="rounded-full border border-ink-700 px-2 py-1 text-xs text-ink-300">{{ $role->name }}</span>
                            @endforeach
                        </div>
                        <button @click="editing = true" x-show="!editing" class="text-xs text-brand-400 hover:underline">Change Role</button>

                        <form x-show="editing" x-cloak method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="role" class="rounded-lg border border-ink-700 bg-ink-800 px-2 py-1 text-xs text-white">
                                @foreach (['admin', 'hr', 'staff', 'team-lead', 'employee', 'intern', 'applicant'] as $slug)
                                    <option value="{{ $slug }}" {{ $user->roles->contains('slug', $slug) ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('-', ' ', $slug)) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="text-xs text-brand-400 hover:underline">Save</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $users->links() }}</div>
    </div>
</x-layouts.app>