@php
    $user = auth()->user();
@endphp

<aside
    x-show="sidebarOpen || window.innerWidth >= 1024"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    class="fixed inset-y-0 left-0 z-50 flex w-64 shrink-0 flex-col border-r border-ink-800 bg-ink-900 lg:static lg:z-auto lg:flex lg:translate-x-0"
>
    <div class="flex items-center gap-2 px-6 py-5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-500 font-display text-sm font-bold text-ink-950">CC</span>
        <span class="font-display text-base font-semibold text-white">Crafting Colons</span>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-4 pb-6">
        @php
            $link = fn ($route, $label, $icon) => [
                'active' => request()->routeIs($route.'*'),
                'url' => \Illuminate\Support\Facades\Route::has($route) ? route($route) : '#',
                'label' => $label,
                'icon' => $icon,
            ];

            $renderGroup = function (string $heading, array $items) {
                return compact('heading', 'items');
            };

            $groups = [];

            if ($user->hasRole('applicant')) {
                $groups[] = $renderGroup('Applicant', [
                    $link('applicant.dashboard', 'Overview', '🏠'),
                    $link('applicant.profile.edit', 'Profile & CV', '📄'),
                    $link('applicant.applications.index', 'My Applications', '📋'),
                ]);
            }

            if ($user->hasRole('employee') || $user->hasRole('intern')) {
                $groups[] = $renderGroup('My Work', [
                    $link('employee.dashboard', 'Overview', '🏠'),
                    $link('employee.attendance.index', 'Attendance', '⏱️'),
                    $link('employee.tasks.index', 'Tasks', '✅'),
                    $link('announcements.feed', 'Announcements', '📣'),
                ]);
            }

            if ($user->hasRole('team-lead')) {
                $groups[] = $renderGroup('Team Lead', [
                    $link('team-lead.tasks.review', 'Review Tasks', '🔍'),
                ]);
            }

            if ($user->hasRole('hr') || $user->hasRole('admin')) {
                $groups[] = $renderGroup('Recruitment', [
                    $link('hr.jobs.index', 'Job Postings', '💼'),
                ]);
            }

            if ($user->hasRole('staff') || $user->hasRole('admin')) {
                $groups[] = $renderGroup('Content', [
                    $link('staff.articles.index', 'Articles', '📰'),
                    $link('staff.news.index', 'News', '📢'),
                    $link('staff.events.index', 'Events', '📅'),
                    $link('staff.projects.index', 'Portfolio', '🗂️'),
                    $link('staff.services.index', 'Services', '🛠️'),
                    $link('staff.categories.index', 'Categories', '🏷️'),
                ]);
            }

            if ($user->hasRole('admin')) {
                $groups[] = $renderGroup('Administration', [
                    $link('admin.activity-logs.index', 'Activity Log', '📊'),
                    $link('admin.settings.index', 'Settings', '⚙️'),
                    $link('admin.users.index', 'Users', '👥'),
                ]);
            }
        @endphp

        @foreach ($groups as $group)
            <div>
                <p class="px-3 text-xs font-semibold uppercase tracking-wide text-ink-500">{{ $group['heading'] }}</p>
                <div class="mt-2 space-y-0.5">
                    @foreach ($group['items'] as $item)
                        <a href="{{ $item['url'] }}"
                           class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition {{ $item['active'] ? 'bg-brand-500/10 font-medium text-brand-400' : 'text-ink-300 hover:bg-ink-800 hover:text-white' }}">
                            <span class="text-base">{{ $item['icon'] }}</span>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>
</aside>