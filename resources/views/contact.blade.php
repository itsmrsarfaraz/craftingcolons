<x-layouts.site :title="'Contact — Crafting Colons'">
    <section class="section max-w-lg">
        <div class="text-center" data-reveal>
            <span class="eyebrow">Get in touch</span>
            <h1 class="mt-2 font-display text-3xl font-semibold sm:text-4xl">Let's talk</h1>
            <p class="mx-auto mt-3 text-ink-400">Have a project, a question, or just want to say hi?</p>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-lg border border-emerald-900 bg-emerald-950/40 px-4 py-2 text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 space-y-1 rounded-lg border border-red-900 bg-red-950/50 px-4 py-2 text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="card mt-8 space-y-4 p-6" data-reveal data-reveal-delay="1">
            @csrf
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Your email" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject (optional)"
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">
            <textarea name="message" rows="5" placeholder="Your message" required
                class="w-full rounded-lg border border-ink-700 bg-ink-800 px-3 py-2 text-white placeholder:text-ink-500">{{ old('message') }}</textarea>
            <button type="submit" class="btn-primary w-full">Send Message</button>
        </form>
    </section>
</x-layouts.site>