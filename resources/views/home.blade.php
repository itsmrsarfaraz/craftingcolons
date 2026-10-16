<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Crafting Colons | Software Agency &amp; Technology Solutions</title>
<meta name="description" content="Crafting Colons builds web applications, desktop software, IoT integrations and AI agent pipelines.">

<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;600&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

<!-- HEADER -->
<header class="sticky top-0 z-40 bg-bg/90 backdrop-blur border-b border-indigo/30">
  <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
    <a href="#home" class="font-mono font-bold text-lg text-cyan">&lt;Crafting<span class="colon">:</span>Colons /&gt;</a>
    <nav class="hidden lg:flex gap-5 font-mono text-sm" aria-label="Main">
      <a class="hover:text-cyan" href="#home">[01. Base (Home)]</a>
      <a class="hover:text-cyan" href="#services">[02. Skill Tree (Services)]</a>
      <a class="hover:text-cyan" href="#projects">[03. Quest Log (Projects)]</a>
      <a class="hover:text-cyan" href="#about">[04. Guild Stats (About)]</a>
      <a class="hover:text-cyan" href="#contact">[05. Contact]</a>
    </nav>
    <div class="flex items-center gap-3">
      <div class="hidden sm:block border border-emerald/50 px-3 py-1 font-mono text-xs" title="Click to gain XP" id="xpWidget" role="button" tabindex="0">
        <div class="flex justify-between gap-4 text-emerald"><span>Level <b id="lvl">99</b></span><span>Full-Stack Architects</span></div>
        <div class="h-1.5 bg-gray-800 mt-1"><div id="xpBar" class="h-full bg-emerald transition-all duration-500" style="width:72%"></div></div>
      </div>
      <button id="menuBtn" class="lg:hidden border border-cyan text-cyan px-3 py-1 font-mono" aria-expanded="false" aria-controls="mobileNav">MENU</button>
    </div>
  </div>
  <nav id="mobileNav" class="hidden lg:hidden border-t border-indigo/30 bg-panel px-4 py-3 flex-col gap-3 font-mono text-sm" aria-label="Mobile">
    <a href="#home">[01. Base (Home)]</a><a href="#services">[02. Skill Tree (Services)]</a><a href="#projects">[03. Quest Log (Projects)]</a><a href="#about">[04. Guild Stats (About)]</a><a href="#contact">[05. Contact]</a>
    <div class="text-emerald text-xs pt-1">Level 99 | Full-Stack Architects</div>
  </nav>
</header>

<main>
<!-- HERO -->
<section id="home" class="max-w-7xl mx-auto px-4 py-16 lg:py-24 grid lg:grid-cols-2 gap-10 items-center">
  <div>
    <span class="badge">[STATUS: READY FOR NEW QUESTS]</span>
    <h1 class="pix text-2xl sm:text-3xl lg:text-4xl leading-relaxed mt-5 text-white">Crafting Code. <span class="text-cyan">Architecting Solutions.</span></h1>
    <p class="mt-6 text-gray-300 max-w-xl leading-relaxed">We build high-performance web applications, desktop software, IoT hardware integrations, and intelligent AI agent pipelines. Turn complex code into seamless digital experiences.</p>
    <div class="mt-8 flex flex-wrap gap-4">
      <a href="#contact" id="ctaMain" class="btn bg-emerald text-bg px-6 py-3 glow-c hover:shadow-[0_0_28px_#10B981]">[ Start A Project / Summon Guild ]</a>
      <a href="#projects" class="btn border border-cyan text-cyan px-6 py-3 hover:bg-cyan/10">[ Explore Quest Log ]</a>
    </div>
  </div>
  <div class="card glow-c rounded-md overflow-hidden" aria-label="Terminal preview">
    <div class="flex items-center gap-2 px-4 py-2 bg-black/40 border-b border-indigo/30">
      <span class="w-3 h-3 rounded-full bg-red-500"></span><span class="w-3 h-3 rounded-full bg-yellow-400"></span><span class="w-3 h-3 rounded-full bg-emerald"></span>
      <span class="ml-3 font-mono text-xs text-gray-400">guild@craftingcolons:~</span>
    </div>
    <pre class="font-mono text-sm p-5 min-h-[190px] whitespace-pre-wrap text-emerald"><span id="typed" class="cursor"></span></pre>
  </div>
</section>

<!-- STATS -->
<section class="max-w-7xl mx-auto px-4 pb-16" aria-label="Power-up metrics">
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="card p-5 text-center"><div class="pix text-xl text-cyan"><span class="count" data-to="100" data-suffix="%+">0</span></div><p class="mt-3 text-sm text-gray-300">Code Quality &amp; Test Coverage</p></div>
    <div class="card p-5 text-center"><div class="pix text-xl text-emerald"><span class="count" data-to="24" data-suffix="/7">0</span></div><p class="mt-3 text-sm text-gray-300">System Reliability &amp; Maintenance</p></div>
    <div class="card p-5 text-center"><div class="pix text-xl text-indigo"><span class="count" data-to="4" data-suffix="-Tier">0</span></div><p class="mt-3 text-sm text-gray-300">Tech Stack Mastery (Web, Desktop, IoT, AI)</p></div>
    <div class="card p-5 text-center"><div class="pix text-xl text-cyan"><span class="count" data-to="99.9" data-suffix="%" data-dec="1">0</span></div><p class="mt-3 text-sm text-gray-300">Client Quest Satisfaction Rate</p></div>
  </div>
</section>

<!-- SERVICES -->
<section id="services" class="max-w-7xl mx-auto px-4 py-16">
  <h2 class="pix text-xl text-white">SELECT YOUR QUEST</h2>
  <p class="mt-4 text-gray-400">Choose a specialized path for your software development lifecycle.</p>
  @include('partials.services')
  <div id="questInfo" class="mt-5 border border-dashed border-emerald/50 p-4 font-mono text-sm text-emerald min-h-[3.5rem]">&gt; Select a quest to inspect it.</div>
</section>

<!-- PROJECTS -->
<section id="projects" class="max-w-7xl mx-auto px-4 py-16">
  <h2 class="pix text-xl text-white">UNLOCKED ARTIFACTS</h2>
  <p class="mt-4 text-gray-400">Recent projects delivered and quests completed by Crafting Colons.</p>
  <div class="mt-6 flex flex-wrap gap-3" role="tablist" id="filters">
    <button class="tab on" data-f="all">[ All ]</button>
    <button class="tab" data-f="web">[ Web ]</button>
    <button class="tab" data-f="desktop">[ Desktop ]</button>
    <button class="tab" data-f="edu">[ Education/Tools ]</button>
  </div>
  @include('partials.projects')
</section>

<!-- ABOUT -->
<section id="about" class="max-w-7xl mx-auto px-4 py-16">
  <h2 class="pix text-xl text-white">THE ARCHITECTS</h2>
  <p class="mt-4 text-gray-400">Engineered with precision, built for performance.</p>
  <div class="mt-8 grid lg:grid-cols-2 gap-8">
    <p class="text-gray-300 leading-relaxed max-w-prose">Crafting Colons is a modern software engineering studio dedicated to crafting robust digital solutions across web, desktop, IoT, and AI infrastructure. From custom software products to automated institutional portals, we turn complex logic into intuitive, high-performance software.</p>
    <ul class="space-y-3 font-mono text-sm">
      <li class="card p-4">⚡ Clean, Scalable Architecture</li>
      <li class="card p-4">🛠️ Cross-Platform Mastery (Web, Desktop, Hardware)</li>
      <li class="card p-4">🤖 Modern AI-Driven Engineering</li>
    </ul>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="max-w-3xl mx-auto px-4 py-16">
  <h2 class="pix text-xl text-white leading-relaxed">READY TO INITIATE A NEW QUEST?</h2>
  <p class="mt-4 text-gray-400">Send your signal directly to the Crafting Colons team.</p>
  <form id="form" class="mt-8 card glow-c p-6 space-y-5 font-mono text-sm" novalidate>
    <div><label for="name" class="block mb-1 text-cyan">[ Adventurer / Client Name ]</label>
      <input id="name" required class="w-full bg-bg border border-indigo/50 p-3 focus:border-cyan outline-none" type="text"></div>
    <div><label for="email" class="block mb-1 text-cyan">[ Transmission / Email Address ]</label>
      <input id="email" required class="w-full bg-bg border border-indigo/50 p-3 focus:border-cyan outline-none" type="email"></div>
    <div><label for="type" class="block mb-1 text-cyan">[ Quest Type ]</label>
      <select id="type" class="w-full bg-bg border border-indigo/50 p-3 focus:border-cyan outline-none">
        <option>Web Development</option><option>Desktop App</option><option>IoT/Hardware</option><option>AI Integration</option><option>Custom Software</option>
      </select></div>
    <div><label for="msg" class="block mb-1 text-cyan">[ Quest Briefing / Message ]</label>
      <textarea id="msg" rows="5" required class="w-full bg-bg border border-indigo/50 p-3 focus:border-cyan outline-none"></textarea></div>
    <button type="submit" class="btn w-full bg-cyan text-bg py-3 hover:shadow-[0_0_28px_#06B6D4]">[ SUBMIT TRANSMISSION ]</button>
    <p id="status" role="status" class="min-h-[1.25rem]"></p>
  </form>
</section>
</main>

<!-- FOOTER -->
<footer class="border-t border-indigo/30 mt-8">
  <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col md:flex-row gap-4 md:items-center md:justify-between font-mono text-sm">
    <a href="#home" class="font-bold text-cyan">&lt;Crafting<span class="colon">:</span>Colons /&gt;</a>
    <p class="text-gray-400">© 2026 Crafting Colons. All rights reserved. Built for high performance.</p>
    <p class="text-emerald">[ SERVER: ONLINE | SYSTEM HEALTH: 100% ]</p>
  </div>
</footer>

<!-- MODAL -->
<div id="modal" class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="mTitle">
  <div class="card active max-w-lg w-full p-6">
    <span class="badge" id="mKind"></span>
    <h3 id="mTitle" class="mt-3 font-mono font-bold text-xl text-white"></h3>
    <p id="mTag" class="text-cyan text-sm mt-1"></p>
    <p id="mDet" class="mt-4 text-gray-300 text-sm"></p>
    <div class="mt-6 flex gap-3">
      <a href="#contact" id="mCta" class="btn bg-emerald text-bg px-4 py-2 text-sm">[ Start a similar quest ]</a>
      <button id="mClose" class="btn border border-cyan text-cyan px-4 py-2 text-sm">[ Close ]</button>
    </div>
  </div>
</div>

<script>
const $ = (s, r = document) => r.querySelector(s), $$ = (s, r = document) => [...r.querySelectorAll(s)];

// Mobile nav
const menuBtn = $('#menuBtn'), mobileNav = $('#mobileNav');
menuBtn.addEventListener('click', () => {
  const open = mobileNav.classList.toggle('hidden') === false;
  mobileNav.classList.toggle('flex', open);
  menuBtn.setAttribute('aria-expanded', open);
});
$$('#mobileNav a').forEach(a => a.addEventListener('click', () => { mobileNav.classList.add('hidden'); mobileNav.classList.remove('flex'); menuBtn.setAttribute('aria-expanded', false); }));

// XP widget
let xp = 72;
function gainXp() {
  xp += 7;
  if (xp >= 100) { xp -= 100; $('#lvl').textContent = +$('#lvl').textContent + 1; }
  $('#xpBar').style.width = xp + '%';
}
$('#xpWidget').addEventListener('click', gainXp);
$('#xpWidget').addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); gainXp(); } });

// Typing terminal
const lines = [
  'CraftingColons.init();',
  '// Launching digital experiences...',
  '> loading modules: web, desktop, iot, ai',
  '> status: READY FOR NEW QUESTS'
];
const typed = $('#typed'); let li = 0, ci = 0, out = '';
function type() {
  if (li >= lines.length) return;
  if (ci < lines[li].length) { out += lines[li][ci++]; typed.textContent = out; setTimeout(type, 45); }
  else { out += '\n'; li++; ci = 0; setTimeout(type, 350); }
}
type();

// Counters
const io = new IntersectionObserver(es => es.forEach(e => {
  if (!e.isIntersecting) return;
  const el = e.target, to = +el.dataset.to, dec = +(el.dataset.dec || 0), suf = el.dataset.suffix || '';
  const t0 = performance.now();
  (function step(t) {
    const p = Math.min((t - t0) / 1200, 1);
    el.textContent = (to * p).toFixed(dec) + (p === 1 ? suf : '');
    if (p < 1) requestAnimationFrame(step);
  })(t0);
  io.unobserve(el);
}), { threshold: .5 });
$$('.count').forEach(c => io.observe(c));

// Quests
const info = {
  'Web Development': 'Quest I accepted. Typical loot: SaaS platforms, REST APIs, multi-tenant dashboards.',
  'Desktop App': 'Quest II accepted. Typical loot: offline-first POS, inventory and reporting tools.',
  'IoT/Hardware': 'Quest III accepted. Typical loot: sensor networks, camera nodes, vending automation.',
  'AI Integration': 'Quest IV accepted. Typical loot: local LLM deployments, agent pipelines, automation.'
};
$$('.quest').forEach(q => q.addEventListener('click', () => {
  $$('.quest').forEach(o => { o.classList.remove('active'); o.setAttribute('aria-pressed', false); });
  q.classList.add('active'); q.setAttribute('aria-pressed', true);
  $('#questInfo').textContent = '> ' + info[q.dataset.type];
  $('#type').value = q.dataset.type; gainXp();
}));

// Filters
$$('#filters .tab').forEach(t => t.addEventListener('click', () => {
  $$('#filters .tab').forEach(o => o.classList.remove('on')); t.classList.add('on');
  $$('#grid article').forEach(a => a.classList.toggle('hidden', t.dataset.f !== 'all' && !a.dataset.cat.split(' ').includes(t.dataset.f)));
}));

// Modal
const modal = $('#modal');
const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
$$('.preview').forEach(b => b.addEventListener('click', () => {
  const d = b.closest('article').dataset;
  $('#mKind').textContent = d.kind; $('#mTitle').textContent = d.title;
  $('#mTag').textContent = d.tag; $('#mDet').textContent = d.det;
  modal.classList.remove('hidden'); modal.classList.add('flex'); $('#mClose').focus();
}));
$('#mClose').addEventListener('click', closeModal);
$('#mCta').addEventListener('click', closeModal);
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

// CTA pulse
$('#ctaMain').addEventListener('click', e => { e.currentTarget.classList.remove('pulse'); void e.currentTarget.offsetWidth; e.currentTarget.classList.add('pulse'); });

// Contact form (front-end only: no backend yet)
$('#form').addEventListener('submit', e => {
  e.preventDefault();
  const s = $('#status'), n = $('#name').value.trim(), m = $('#email').value.trim(), g = $('#msg').value.trim();
  if (!n || !/^\S+@\S+\.\S+$/.test(m) || !g) { s.className = 'text-red-400'; s.textContent = 'Transmission failed: enter your name, a valid email, and a message.'; return; }
  s.className = 'text-emerald'; s.textContent = 'Transmission sent. The guild will reply to ' + m + ' soon.';
  e.target.reset(); gainXp();
});
</script>
</body>
</html>