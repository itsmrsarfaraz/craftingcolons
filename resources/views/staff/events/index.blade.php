<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events — Crafting Colons</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full text-white py-12 px-4">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Events</h1>
            <a href="{{ route('staff.events.create') }}"
               class="bg-white text-neutral-950 font-medium rounded-lg px-4 py-2 text-sm hover:bg-neutral-200 transition">
                + New Event
            </a>
        </div>

        @if (session('status'))
            <div class="text-sm text-emerald-400 bg-emerald-950/40 border border-emerald-900 rounded-lg px-4 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl divide-y divide-neutral-800 overflow-hidden">
            @forelse ($events as $event)
                <a href="{{ route('staff.events.edit', $event) }}"
                   class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4 hover:bg-neutral-800/40 transition">
                    
                    {{-- Left Side: Main Details --}}
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-medium text-neutral-100">{{ $event->title }}</p>
                            
                            @if($event->is_virtual)
                                <span class="text-[10px] font-semibold uppercase tracking-wider bg-sky-950 text-sky-400 border border-sky-900 rounded px-1.5 py-0.5">
                                    Virtual
                                </span>
                            @else
                                <span class="text-[10px] font-semibold uppercase tracking-wider bg-amber-950 text-amber-400 border border-amber-900 rounded px-1.5 py-0.5">
                                    In-Person
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-x-3 gap-y-1 text-xs text-neutral-400 flex-wrap">
                            <span>By {{ $event->author->name }}</span>
                            <span class="text-neutral-600">&bull;</span>
                            
                            {{-- Date Handling (Assumes starts_at and ends_at are carbon instances) --}}
                            <span>
                                {{ $event->starts_at->format('M j, Y g:i A') }} - {{ $event->ends_at->format('g:i A') }}
                            </span>
                        </div>
                    </div>

                    {{-- Right Side: Capacity and Pipeline Status --}}
                    <div class="flex items-center justify-between sm:justify-end gap-4 shrink-0">
                        {{-- Attendance Counter --}}
                        <div class="text-right text-xs text-neutral-400">
                            <span class="font-medium text-neutral-200">
                                {{ $event->attendees_count ?? 0 }}
                            </span> 
                            / 
                            <span>
                                {{ $event->max_attendees ?? '∞' }}
                            </span>
                            <p class="text-[10px] text-neutral-500 uppercase tracking-wider">Booked</p>
                        </div>

                        {{-- Status Badge --}}
                        <span class="text-xs uppercase tracking-wide bg-neutral-800 border border-neutral-700 rounded-full px-3 py-1 font-medium text-neutral-300">
                            {{ $event->status->label() }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="px-6 py-12 text-center text-neutral-500 text-sm">
                    No events have been created yet.
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $events->links() }}
        </div>
    </div>
</body>
</html>