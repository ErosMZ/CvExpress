/* ═══════════════════════════════════════════════
   CvExpress — main.js
   • Header shrink on scroll
   • Mobile nav toggle
   • Scroll-reveal animations
   • Active nav highlight
   NOTA: NO modifica elementos con data-cv (los gestiona el dashboard).
   ═══════════════════════════════════════════════ */

(function () {
  'use strict';

  /* ─── HEADER SCROLL ──────────────────────────── */
  const header = document.getElementById('site-header');

  function onScroll() {
    if (window.scrollY > 40) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
    updateActiveNav();
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll(); // estado inicial

  /* ─── HAMBURGER / NAV MÓVIL ──────────────────── */
  const hamburger = document.getElementById('hamburger');
  const nav = document.querySelector('.header-nav');

  if (hamburger && nav) {
    hamburger.addEventListener('click', function () {
      const isOpen = nav.classList.toggle('open');
      hamburger.classList.toggle('open', isOpen);
      hamburger.setAttribute('aria-expanded', isOpen);
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    // Cerrar al hacer click en un enlace
    nav.querySelectorAll('.nav-link').forEach(function (link) {
      link.addEventListener('click', function () {
        nav.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', false);
        document.body.style.overflow = '';
      });
    });

    // Cerrar al hacer click fuera
    document.addEventListener('click', function (e) {
      if (nav.classList.contains('open') &&
          !nav.contains(e.target) &&
          !hamburger.contains(e.target)) {
        nav.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', false);
        document.body.style.overflow = '';
      }
    });
  }

  /* ─── ACTIVE NAV LINK ────────────────────────── */
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link[href^="#"]');

  function updateActiveNav() {
    let current = '';
    const scrollY = window.scrollY + 120;

    sections.forEach(function (section) {
      if (section.offsetTop <= scrollY) {
        current = section.getAttribute('id');
      }
    });

    navLinks.forEach(function (link) {
      link.style.borderBottomColor = '';
      link.style.color = '';
      if (link.getAttribute('href') === '#' + current) {
        link.style.borderBottomColor = 'var(--c-amber)';
        link.style.color = 'var(--c-charcoal)';
      }
    });
  }

  /* ─── SCROLL REVEAL ──────────────────────────── */
  // Añadir clase .reveal a elementos que deben animarse
  const revealTargets = [
    '.about-grid',
    '.about-contact-card',
    '.timeline-item',
    '.education-item',
    '.languages-card',
    '.section-label',
    '.section-title',
  ];

  revealTargets.forEach(function (selector) {
    document.querySelectorAll(selector).forEach(function (el) {
      // No revelar elementos que ya tienen animación propia en el hero
      if (!el.closest('.hero')) {
        el.classList.add('reveal');
      }
    });
  });

  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry, i) {
        if (entry.isIntersecting) {
          // Stagger ligero para grupos
          setTimeout(function () {
            entry.target.classList.add('visible');
          }, i * 80);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
  );

  document.querySelectorAll('.reveal').forEach(function (el) {
    observer.observe(el);
  });

  /* ─── SMOOTH SCROLL (fallback para Safari antiguo) ── */
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const top = target.getBoundingClientRect().top + window.scrollY - 64;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  /* ─── GUARD: no tocar elementos data-cv ─────────
     El dashboard inyecta datos en data-cv y data-cv-section.
     Este archivo NO debe interferir con esos valores.
     Solo se mencionan aquí como recordatorio para futuros devs.
  ─────────────────────────────────────────────── */

})();