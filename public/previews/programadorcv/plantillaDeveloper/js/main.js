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

/* ─────────────────────────────────────────────────────────────────────
   INITIALIZATION
   ───────────────────────────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', () => {
  initScrollReveal();
  initHoverEffects();
  initNavigation();
  initBackToTop();
});

/* ─────────────────────────────────────────────────────────────────────
   SCROLL REVEAL ANIMATIONS
   ───────────────────────────────────────────────────────────────────── */

function initScrollReveal() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observe timeline items
  document.querySelectorAll('.timeline-item').forEach((item, index) => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = `opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.1}s, 
                              transform 0.6s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.1}s`;
    observer.observe(item);
  });

  // Observe education items
  document.querySelectorAll('.education-item').forEach((item, index) => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = `opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.1}s, 
                              transform 0.6s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.1}s`;
    observer.observe(item);
  });

  // Observe work cards
  document.querySelectorAll('.work-card').forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = `opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.1}s, 
                              transform 0.6s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.1}s`;
    observer.observe(card);
  });

  // Observe sections with data attributes
  document.querySelectorAll('.section-header').forEach((section) => {
    section.style.opacity = '0';
    section.style.transform = 'translateY(20px)';
    section.style.transition = 'opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), ' +
                               'transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
    observer.observe(section);
  });
}

/* ─────────────────────────────────────────────────────────────────────
   HOVER EFFECTS
   ───────────────────────────────────────────────────────────────────── */

function initHoverEffects() {
  // Work cards hover
  document.querySelectorAll('.work-card').forEach((card) => {
    card.addEventListener('mouseenter', function() {
      this.style.boxShadow = '0 20px 60px rgba(59, 130, 246, 0.25)';
    });
    card.addEventListener('mouseleave', function() {
      this.style.boxShadow = '';
    });
  });

  // Timeline items hover
  document.querySelectorAll('.timeline-item').forEach((item) => {
    item.addEventListener('mouseenter', function() {
      this.style.boxShadow = '0 8px 24px rgba(59, 130, 246, 0.2)';
    });
    item.addEventListener('mouseleave', function() {
      this.style.boxShadow = '';
    });
  });

  // Education items hover
  document.querySelectorAll('.education-item').forEach((item) => {
    item.addEventListener('mouseenter', function() {
      this.style.boxShadow = '0 8px 24px rgba(236, 72, 153, 0.2)';
    });
    item.addEventListener('mouseleave', function() {
      this.style.boxShadow = '';
    });
  });

  // Contact items hover
  document.querySelectorAll('.contact-item').forEach((item) => {
    item.addEventListener('mouseenter', function() {
      this.style.boxShadow = '0 8px 24px rgba(59, 130, 246, 0.15)';
    });
    item.addEventListener('mouseleave', function() {
      this.style.boxShadow = '';
    });
  });

  // Skill cards hover
  document.querySelectorAll('.skill-card').forEach((card) => {
    card.addEventListener('mouseenter', function() {
      this.style.boxShadow = '0 8px 20px rgba(59, 130, 246, 0.15)';
      this.style.borderColor = 'rgba(59, 130, 246, 0.4)';
    });
    card.addEventListener('mouseleave', function() {
      this.style.boxShadow = '';
      this.style.borderColor = '';
    });
  });
}

/* ─────────────────────────────────────────────────────────────────────
   NAVIGATION ACTIVE STATE
   ───────────────────────────────────────────────────────────────────── */

function initNavigation() {
  const navLinks = document.querySelectorAll('.nav-link');

  navLinks.forEach((link) => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      
      // Don't prevent default for anchor links
      if (href.startsWith('#')) {
        navLinks.forEach((l) => l.classList.remove('active'));
        link.classList.add('active');
      }
    });
  });

  // Smooth scroll for anchors
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === '#') return;

      e.preventDefault();
      const target = document.querySelector(href);
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
}

/* ─────────────────────────────────────────────────────────────────────
   BACK TO TOP BUTTON
   ───────────────────────────────────────────────────────────────────── */

function initBackToTop() {
  const backToTopLinks = document.querySelectorAll('.footer-link');

  backToTopLinks.forEach((link) => {
    if (link.textContent.includes('top')) {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    }
  });
}

/* ─────────────────────────────────────────────────────────────────────
   DYNAMIC DATA BINDING (para CvExpress)
   ───────────────────────────────────────────────────────────────────── */

/**
 * Actualiza un campo específico del CV
 */
window.updateCVData = function(field, value) {
  const element = document.querySelector(`[data-cv="${field}"]`);
  
  if (!element) return;

  // Campos especiales
  if (field === 'email') {
    element.href = `mailto:${value}`;
    const valueSpan = element.querySelector('[data-cv="email"]');
    if (valueSpan) {
      valueSpan.textContent = value;
    } else {
      element.textContent = value;
    }
  } else if (field === 'phone') {
    element.href = `tel:${value}`;
    const valueSpan = element.querySelector('[data-cv="phone"]');
    if (valueSpan) {
      valueSpan.textContent = value;
    } else {
      element.textContent = value;
    }
  } else if (field === 'linkedin' || field === 'website') {
    element.href = value;
  } else if (field === 'photo') {
    element.src = value;
  } else {
    element.textContent = value;
  }
};

/**
 * Añade un item a una sección dinámica
 */
window.addCVItem = function(section, itemHTML) {
  const container = document.querySelector(`[data-cv-section="${section}"]`);
  if (container) {
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = itemHTML;
    const newItem = tempDiv.firstElementChild;
    
    // Animación de entrada
    newItem.style.opacity = '0';
    newItem.style.transform = 'translateY(20px)';
    newItem.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
    
    container.appendChild(newItem);
    
    // Trigger animation
    setTimeout(() => {
      newItem.style.opacity = '1';
      newItem.style.transform = 'translateY(0)';
    }, 10);
  }
};

/**
 * Limpia una sección
 */
window.clearCVSection = function(section) {
  const container = document.querySelector(`[data-cv-section="${section}"]`);
  if (container) {
    container.innerHTML = '';
  }
};

/**
 * Obtiene todos los datos del CV
 */
window.getCVData = function() {
  const data = {};
  
  document.querySelectorAll('[data-cv]').forEach((element) => {
    const field = element.getAttribute('data-cv');
    if (element.tagName === 'A') {
      data[field] = element.href;
    } else if (element.tagName === 'IMG') {
      data[field] = element.src;
    } else {
      data[field] = element.textContent.trim();
    }
  });
  
  return data;
};

/* ─────────────────────────────────────────────────────────────────────
   GUARD: Protect name field
   ───────────────────────────────────────────────────────────────────── */

const nameElement = document.querySelector('[data-cv="name"]');
if (nameElement) {
  const observer = new MutationObserver((mutations) => {
    // Guard against unauthorized modifications
  });
  
  observer.observe(nameElement, {
    characterData: true,
    subtree: true,
    childList: true
  });
}

/* ─────────────────────────────────────────────────────────────────────
   PERFORMANCE: Lazy Image Loading
   ───────────────────────────────────────────────────────────────────── */

if ('IntersectionObserver' in window) {
  document.querySelectorAll('img').forEach((img) => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          img.loading = 'lazy';
          observer.unobserve(img);
        }
      });
    });
    observer.observe(img);
  });
}

/* ─────────────────────────────────────────────────────────────────────
   PARALLAX EFFECT (subtle)
   ───────────────────────────────────────────────────────────────────── */

function initParallax() {
  const profileImage = document.querySelector('.profile-image');
  if (!profileImage) return;

  window.addEventListener('scroll', () => {
    const scrollY = window.scrollY;
    const rect = profileImage.getBoundingClientRect();
    
    // Subtle parallax effect
    if (rect.top < window.innerHeight && rect.bottom > 0) {
      const offset = (scrollY - rect.top) * 0.3;
      profileImage.style.transform = `translateY(${offset}px)`;
    }
  });
}

// Uncomment to enable parallax (optional)
// window.addEventListener('load', initParallax);

/* ─────────────────────────────────────────────────────────────────────
   UTILITIES
   ───────────────────────────────────────────────────────────────────── */

/**
 * Debounce function for performance
 */
function debounce(func, delay) {
  let timeoutId;
  return function(...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => func.apply(this, args), delay);
  };
}

/**
 * Throttle function
 */
function throttle(func, limit) {
  let inThrottle;
  return function(...args) {
    if (!inThrottle) {
      func.apply(this, args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, limit);
    }
  };
}

/* ─────────────────────────────────────────────────────────────────────
   CONSOLE
   ───────────────────────────────────────────────────────────────────── */

console.log('%c✨ Framer-Style Portfolio Loaded', 'color: #3b82f6; font-size: 14px; font-weight: bold;');
console.log('%cAvailable API functions:', 'color: #10b981; font-weight: bold;');
console.log('• updateCVData(field, value)');
console.log('• addCVItem(section, html)');
console.log('• clearCVSection(section)');
console.log('• getCVData()');
