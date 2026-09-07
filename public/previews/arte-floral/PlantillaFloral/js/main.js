/* ── LIVE PREVIEW FILL (CvExpress dashboard) ── */
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

/* =========================================================
   MAIN.JS — Vanilla JS, sin librerías externas
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
  setYear();
  initHeaderScroll();
  initMobileNav();
  initSmoothScroll();
  initActiveNavOnScroll();
  initScrollReveal();
  initSkillBars();
  generateFlowers('flowersLeft', 6);
  generateFlowers('flowersRight', 6);
});

/* ---------------------------------------------------------
   Año actual en el footer
   --------------------------------------------------------- */
function setYear() {
  const yearEl = document.getElementById('year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();
}

/* ---------------------------------------------------------
   Header: sombra/borde al hacer scroll
   --------------------------------------------------------- */
function initHeaderScroll() {
  const header = document.getElementById('header');
  if (!header) return;

  const toggleScrolled = () => {
    header.classList.toggle('is-scrolled', window.scrollY > 10);
  };

  toggleScrolled();
  window.addEventListener('scroll', toggleScrolled, { passive: true });
}

/* ---------------------------------------------------------
   Menú de navegación en móvil
   --------------------------------------------------------- */
function initMobileNav() {
  const toggle = document.getElementById('navToggle');
  const nav = document.getElementById('nav');
  if (!toggle || !nav) return;

  toggle.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(isOpen));
  });

  // Cierra el menú al pulsar un enlace (útil en móvil)
  nav.querySelectorAll('[data-nav]').forEach((link) => {
    link.addEventListener('click', () => {
      nav.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    });
  });
}

/* ---------------------------------------------------------
   Scroll suave al hacer clic en la navegación
   --------------------------------------------------------- */
function initSmoothScroll() {
  const links = document.querySelectorAll('a[href^="#"]');
  const header = document.getElementById('header');

  links.forEach((link) => {
    link.addEventListener('click', (e) => {
      const targetId = link.getAttribute('href');
      const target = document.querySelector(targetId);
      if (!target) return;

      e.preventDefault();
      const headerHeight = header ? header.offsetHeight : 0;
      const targetPosition = target.getBoundingClientRect().top + window.scrollY - headerHeight + 1;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    });
  });
}

/* ---------------------------------------------------------
   Clase "active" en el nav según la sección visible
   --------------------------------------------------------- */
function initActiveNavOnScroll() {
  const sections = document.querySelectorAll('main section[id]');
  const navLinks = document.querySelectorAll('.nav__link');
  if (!sections.length || !navLinks.length) return;

  const setActive = (id) => {
    navLinks.forEach((link) => {
      link.classList.toggle('active', link.getAttribute('href') === `#${id}`);
    });
  };

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          setActive(entry.target.id);
        }
      });
    },
    {
      rootMargin: '-45% 0px -50% 0px',
      threshold: 0
    }
  );

  sections.forEach((section) => observer.observe(section));
}

/* ---------------------------------------------------------
   Animaciones de aparición al hacer scroll (Intersection Observer)
   --------------------------------------------------------- */
function initScrollReveal() {
  const revealEls = document.querySelectorAll('.reveal');
  if (!revealEls.length) return;

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          // Pequeño retraso escalonado para un efecto más natural
          setTimeout(() => {
            entry.target.classList.add('is-visible');
          }, index * 60);
          obs.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.15,
      rootMargin: '0px 0px -60px 0px'
    }
  );

  revealEls.forEach((el) => observer.observe(el));
}

/* ---------------------------------------------------------
   Barras de habilidades: rellenan al entrar en el viewport
   --------------------------------------------------------- */
function initSkillBars() {
  const bars = document.querySelectorAll('.skill-bar__fill');
  if (!bars.length) return;

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const target = entry.target;
          const width = target.getAttribute('data-width') || '0';
          target.style.width = `${width}%`;
          obs.unobserve(target);
        }
      });
    },
    { threshold: 0.4 }
  );

  bars.forEach((bar) => observer.observe(bar));
}

/* ---------------------------------------------------------
   Generación de flores decorativas (CSS + JS)
   Crea varios tallos con hojas y una corola de pétalos que
   se mecen suavemente, como si el viento las moviera.
   --------------------------------------------------------- */
function generateFlowers(containerId, count) {
  const container = document.getElementById(containerId);
  if (!container) return;

  const fragment = document.createDocumentFragment();

  for (let i = 0; i < count; i++) {
    const flower = document.createElement('div');
    flower.className = 'flower';

    // Variación aleatoria para un movimiento orgánico, no uniforme
    const height = 90 + Math.random() * 90;
    const leftPos = (i / count) * 100 + (Math.random() * 6 - 3);
    const duration = 4.5 + Math.random() * 3;
    const delay = Math.random() * 3;
    const hueShift = Math.random() > 0.5 ? 1 : -1;

    flower.style.left = `${leftPos}%`;
    flower.style.height = `${height}px`;
    flower.style.animationDuration = `${duration}s`;
    flower.style.animationDelay = `${delay}s`;

    const stem = document.createElement('div');
    stem.className = 'flower__stem';
    stem.style.height = `${height}px`;

    // Un par de hojas a distinta altura del tallo
    const leaf1 = document.createElement('div');
    leaf1.className = 'flower__leaf';
    leaf1.style.top = `${height * 0.4}px`;
    leaf1.style.left = hueShift > 0 ? '2px' : '-20px';
    leaf1.style.transform = hueShift > 0 ? 'rotate(20deg)' : 'rotate(-20deg) scaleX(-1)';

    const leaf2 = document.createElement('div');
    leaf2.className = 'flower__leaf';
    leaf2.style.top = `${height * 0.68}px`;
    leaf2.style.left = hueShift > 0 ? '-20px' : '2px';
    leaf2.style.transform = hueShift > 0 ? 'rotate(-20deg) scaleX(-1)' : 'rotate(20deg)';

    stem.appendChild(leaf1);
    stem.appendChild(leaf2);

    const bloom = document.createElement('div');
    bloom.className = 'flower__bloom';

    // Cinco pétalos distribuidos en círculo
    const petalCount = 5;
    for (let p = 0; p < petalCount; p++) {
      const petal = document.createElement('div');
      petal.className = 'flower__petal';
      const angle = (360 / petalCount) * p;
      petal.style.transform = `translate(-50%, -100%) rotate(${angle}deg)`;
      bloom.appendChild(petal);
    }

    const center = document.createElement('div');
    center.className = 'flower__center';
    bloom.appendChild(center);

    stem.appendChild(bloom);
    flower.appendChild(stem);
    fragment.appendChild(flower);
  }

  container.appendChild(fragment);
}
