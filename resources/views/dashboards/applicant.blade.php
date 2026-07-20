<x-layouts.app :title="'Dashboard — Crafting Colons'">
    <div class="mx-auto max-w-3xl">
        <h1 class="font-display text-2xl font-semibold text-white">Welcome, {{ auth()->user()->name }}</h1>
        <p class="mt-1 text-sm text-ink-400">Keep your profile current and track your applications.</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('applicant.profile.edit') }}" class="card card-hover p-6">
                <span class="text-2xl">📄</span>
                <p class="mt-3 font-semibold text-white">Profile & CV</p>
                <p class="mt-1 text-sm text-ink-400">Update your details and documents.</p>
            </a>
            <a href="{{ route('applicant.applications.index') }}" class="card card-hover p-6">
                <span class="text-2xl">📋</span>
                <p class="mt-3 font-semibold text-white">My Applications</p>
                <p class="mt-1 text-sm text-ink-400">Track status across all your applications.</p>
            </a>
        </div>

        <div class="mt-6">
            <a href="{{ route('careers.index') }}" class="btn-primary">Browse Open Roles</a>
        </div>
    </div>
</x-layouts.app>