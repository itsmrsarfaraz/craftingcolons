const $ = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => [...r.querySelectorAll(s)];

// ---------- Mobile menu ----------
const menuBtn = $('#menuBtn'), mobileNav = $('#mobileNav');
function setMenu(open) {
  mobileNav.classList.toggle('hidden', !open);
  mobileNav.classList.toggle('flex', open);
  menuBtn.setAttribute('aria-expanded', open);
}
menuBtn.addEventListener('click', () => setMenu(mobileNav.classList.contains('hidden')));
$$('#mobileNav a').forEach(a => a.addEventListener('click', () => setMenu(false)));

// ---------- XP widget ----------
let xp = 72;
function gainXp() {
  xp += 7;
  if (xp >= 100) { xp -= 100; $('#lvl').textContent = +$('#lvl').textContent + 1; }
  $('#xpBar').style.width = xp + '%';
}
const xpWidget = $('#xpWidget');
xpWidget.addEventListener('click', gainXp);
xpWidget.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); gainXp(); } });

// ---------- Typing terminal ----------
const lines = [
  'CraftingColons.init();',
  '// Launching digital experiences...',
  '> loading modules: web, desktop, iot, ai',
  '> status: READY FOR NEW QUESTS'
];
const typed = $('#typed');
let li = 0, ci = 0, out = '';
(function type() {
  if (li >= lines.length) return;
  if (ci < lines[li].length) { out += lines[li][ci++]; typed.textContent = out; setTimeout(type, 45); }
  else { out += '\n'; li++; ci = 0; setTimeout(type, 350); }
})();

// ---------- Animated counters ----------
const io = new IntersectionObserver(entries => entries.forEach(e => {
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

// ---------- Quests (services from the database) ----------
$$('.quest').forEach(q => q.addEventListener('click', () => {
  $$('.quest').forEach(o => { o.classList.remove('active'); o.setAttribute('aria-pressed', false); });
  q.classList.add('active'); q.setAttribute('aria-pressed', true);
  $('#questInfo').textContent = '> ' + q.dataset.info;
  $('#type').value = q.dataset.type;
  gainXp();
}));

// ---------- Project filters ----------
$$('#filters .tab').forEach(t => t.addEventListener('click', () => {
  $$('#filters .tab').forEach(o => o.classList.remove('on'));
  t.classList.add('on');
  $$('#grid article').forEach(a => {
    const show = t.dataset.f === 'all' || a.dataset.cat.split(' ').includes(t.dataset.f);
    a.classList.toggle('hidden', !show);
  });
}));

// ---------- Project modal ----------
const modal = $('#modal');
const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
$$('.preview').forEach(b => b.addEventListener('click', () => {
  const d = b.closest('article').dataset;
  $('#mKind').textContent = d.kind;
  $('#mTitle').textContent = d.title;
  $('#mTag').textContent = d.tag;
  $('#mDet').textContent = d.det;
  modal.classList.remove('hidden'); modal.classList.add('flex');
  $('#mClose').focus();
}));
$('#mClose').addEventListener('click', closeModal);
$('#mCta').addEventListener('click', closeModal);
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

// ---------- CTA pulse ----------
$('#ctaMain').addEventListener('click', e => {
  const b = e.currentTarget;
  b.classList.remove('pulse'); void b.offsetWidth; b.classList.add('pulse');
});

// ---------- Contact form -> Laravel ----------
const form = $('#form');
form.addEventListener('submit', async e => {
  e.preventDefault();
  const s = $('#status');
  s.className = 'text-cyan'; s.textContent = 'Sending transmission...';
  try {
    const res = await fetch(form.dataset.action, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        name: $('#name').value,
        email: $('#email').value,
        quest_type: $('#type').value,
        message: $('#msg').value
      })
    });
    const data = await res.json();
    if (!res.ok) throw new Error(Object.values(data.errors || { e: [data.message] }).flat()[0]);
    s.className = 'text-emerald'; s.textContent = data.message;
    form.reset(); gainXp();
  } catch (err) {
    s.className = 'text-red-400'; s.textContent = 'Transmission failed: ' + err.message;
  }
});
