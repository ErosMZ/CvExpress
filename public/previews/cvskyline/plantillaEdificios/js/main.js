/* ─── LIVE PREVIEW FILL ─────────────────────────────────────────────── */
(function () {
  if (location.search.indexOf('live=1') === -1) return;
  var raw = localStorage.getItem('cv_preview_live');
  if (!raw) return;
  try { _fillLive(JSON.parse(raw)); } catch (e) {}
  function _fillLive(d) {
    if (d.fields) Object.keys(d.fields).forEach(function (f) {
      var v = d.fields[f]; if (!v) return;
      document.querySelectorAll('[data-cv="' + f + '"]').forEach(function (el) {
        if (el.tagName === 'IMG') el.src = v; else el.textContent = v;
      });
    });
    if (d.photo) document.querySelectorAll('[data-cv="photo"]').forEach(function (el) {
      if (el.tagName === 'IMG') { el.src = d.photo; el.style.display = 'block'; }
    });
    if (d.sections) Object.keys(d.sections).forEach(function (t) {
      document.querySelectorAll('[data-cv-section="' + t + '"]').forEach(function (el) {
        el.innerHTML = d.sections[t];
      });
    });
    if (d.sectionVisibility) Object.keys(d.sectionVisibility).forEach(function (t) {
      document.querySelectorAll('[data-cv-section-wrap="' + t + '"]').forEach(function (el) {
        el.style.display = d.sectionVisibility[t] ? '' : 'none';
      });
    });
  }
})();

/* ============================================================
   Ciudad CV — main.js
   ============================================================ */

/* ── MODALES ── */
function om(id) {
  document.getElementById('m-' + id).classList.add('open');
  document.body.style.overflow = 'hidden';
  if (id === 'skills') {
    setTimeout(() => {
      document.querySelectorAll('#skg .skb').forEach(b => {
        b.style.width = b.dataset.w + '%';
      });
    }, 120);
  }
}

function cm(id) {
  document.getElementById('m-' + id).classList.remove('open');
  document.body.style.overflow = '';
  if (id === 'skills') {
    document.querySelectorAll('#skg .skb').forEach(b => {
      b.style.width = '0%';
    });
  }
}

function cio(e, id) {
  if (e.target === e.currentTarget) cm(id);
}

/* Cerrar con Escape */
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.ov.open').forEach(m => {
      m.classList.remove('open');
      document.body.style.overflow = '';
    });
  }
});

/* ── HINT DE SCROLL ── */
const scene = document.getElementById('scene');
const hint  = document.querySelector('.scroll-hint');
let hintHidden = false;

scene.addEventListener('scroll', () => {
  if (!hintHidden && scene.scrollLeft > 30) {
    hint.style.opacity = '0';
    hintHidden = true;
  }
}, { passive: true });

/* ── ARRASTRAR PARA SCROLL (desktop) ── */
let isDown = false, startX, scrollLeft;

scene.addEventListener('mousedown', e => {
  isDown = true;
  scene.style.cursor = 'grabbing';
  startX = e.pageX - scene.offsetLeft;
  scrollLeft = scene.scrollLeft;
});
scene.addEventListener('mouseleave', () => {
  isDown = false;
  scene.style.cursor = 'default';
});
scene.addEventListener('mouseup', () => {
  isDown = false;
  scene.style.cursor = 'default';
});
scene.addEventListener('mousemove', e => {
  if (!isDown) return;
  e.preventDefault();
  const x    = e.pageX - scene.offsetLeft;
  const walk = (x - startX) * 1.5;
  scene.scrollLeft = scrollLeft - walk;
});