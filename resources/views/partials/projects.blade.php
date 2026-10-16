<div class="mt-8 grid md:grid-cols-2 gap-5" id="grid">
  @foreach ($projects as $p)
    <article class="card p-6" data-cat="{{ $p->categories }}" data-title="{{ $p->title }}"
             data-tag="{{ $p->tagline }}" data-det="{{ $p->details }}" data-kind="{{ $p->kind }}">
      <span class="badge">{{ $p->kind }}</span>
      <h3 class="mt-3 font-mono font-bold text-lg text-white">{{ $p->title }}</h3>
      <p class="text-cyan text-sm mt-1">{{ $p->tagline }}</p>
      <p class="mt-3 text-sm text-gray-300">{{ $p->details }}</p>
      <button class="btn mt-4 text-emerald text-sm underline preview">[ Inspect artifact ]</button>
    </article>
  @endforeach
</div>
