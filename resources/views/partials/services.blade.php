<div class="mt-8 grid md:grid-cols-2 gap-5" id="quests">
  @foreach ($services as $s)
    <button class="card quest p-6 text-left" aria-pressed="false"
            data-type="{{ $s->quest_type }}" data-info="{{ $s->info }}">
      <h3 class="font-mono font-bold text-cyan">{{ $s->title }}</h3>
      <p class="mt-3 text-gray-300 text-sm">{{ $s->description }}</p>
      <div class="mt-4 flex flex-wrap gap-2">
        @foreach ($s->badges as $b)<span class="badge">{{ $b }}</span>@endforeach
      </div>
    </button>
  @endforeach
</div>
