/**
 * Wanderly — Travel Planner AI
 * Main JavaScript File
 *
 * Features:
 * - Dark mode toggle with localStorage persistence
 * - Toast notification system
 * - Sticky navbar with scroll effects
 * - Mobile menu toggle
 * - User dropdown menu
 * - Scroll-triggered animations (IntersectionObserver)
 * - Hero section parallax blobs
 * - Page transition loading bar
 * - Form enhancements
 * - Auto-dismiss toasts
 */

'use strict';

// Import Firebase auth handler
import('./auth-google.js').catch(err => console.debug('Firebase auth not loaded:', err));

/* ============================================================
   1. THEME / DARK MODE
   ============================================================ */
const ThemeManager = {
  storageKey: 'wanderly_theme',
  html: document.documentElement,
  toggleBtn: null,
  icon: null,

  init() {
    this.toggleBtn = document.getElementById('themeToggle');
    this.icon      = document.getElementById('themeIcon');

    // Load saved preference, fallback to system preference
    const saved = localStorage.getItem(this.storageKey);
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    let theme = saved || (prefersDark ? 'dark' : 'light');

    // If we're on the landing page (hero exists or root path), force dark mode visually
    const isHome = document.querySelector('.hero') !== null || window.location.pathname === '/';
    if (isHome) {
      // Apply dark theme only for the homepage view without overwriting stored preference
      this.html.setAttribute('data-theme', 'dark');
      this.updateIcon('dark');
    } else {
      this.apply(theme);
    }

    if (this.toggleBtn) {
      this.toggleBtn.addEventListener('click', () => this.toggle());
    }

    // Listen for OS theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem(this.storageKey)) {
        this.apply(e.matches ? 'dark' : 'light');
      }
    });
  },

  apply(theme) {
    this.html.setAttribute('data-theme', theme);
    localStorage.setItem(this.storageKey, theme);
    this.updateIcon(theme);
  },

  toggle() {
    const current = this.html.getAttribute('data-theme');
    this.apply(current === 'dark' ? 'light' : 'dark');

    // Small bounce animation on button
    if (this.toggleBtn) {
      this.toggleBtn.style.transform = 'scale(0.85) rotate(30deg)';
      setTimeout(() => { this.toggleBtn.style.transform = ''; }, 200);
    }
  },

  updateIcon(theme) {
    if (!this.icon) return;
    this.icon.className = theme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
  }
};

/* ============================================================
   2. TOAST NOTIFICATION SYSTEM
   ============================================================ */
const ToastManager = {
  container: null,
  autoHideDelay: 4000,

  init() {
    this.container = document.getElementById('toastContainer');

    // Auto-dismiss toasts loaded from server session
    document.querySelectorAll('.toast[data-auto-dismiss]').forEach(toast => {
      this.showToast(toast);
    });

    // Delegate close button clicks
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('toast-close')) {
        this.dismiss(e.target.closest('.toast'));
      }
    });
  },

  showToast(toastEl) {
    // Schedule auto-dismiss
    const timer = setTimeout(() => this.dismiss(toastEl), this.autoHideDelay);
    toastEl.dataset.timerId = timer;

    // Pause timer on hover
    toastEl.addEventListener('mouseenter', () => clearTimeout(timer));
    toastEl.addEventListener('mouseleave', () => {
      const newTimer = setTimeout(() => this.dismiss(toastEl), 2000);
      toastEl.dataset.timerId = newTimer;
    });
  },

  dismiss(toastEl) {
    if (!toastEl || toastEl.classList.contains('hiding')) return;
    clearTimeout(toastEl.dataset.timerId);
    toastEl.classList.add('hiding');
    toastEl.addEventListener('animationend', () => toastEl.remove(), { once: true });
  },

  /**
   * Programmatically show a toast (JS-triggered)
   * @param {string} message
   * @param {'success'|'info'|'error'} type
   */
  show(message, type = 'info') {
    if (!this.container) return;

    const icons = { success: 'check-circle-fill', info: 'info-circle-fill', error: 'exclamation-triangle-fill' };
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.setAttribute('data-auto-dismiss', '');
    toast.innerHTML = `
      <i class="bi bi-${icons[type] || icons.info}"></i>
      <span>${message}</span>
      <button class="toast-close">&times;</button>
    `;
    this.container.appendChild(toast);
    this.showToast(toast);
  }
};

/* ============================================================
   3. NAVBAR
   ============================================================ */
const NavbarManager = {
  nav: null,
  mobileBtn: null,
  navLinks: null,
  userMenuBtn: null,
  userMenu: null,
  lastScrollY: 0,

  init() {
    this.nav         = document.getElementById('mainNav');
    this.mobileBtn   = document.getElementById('mobileMenuBtn');
    this.navLinks    = document.getElementById('navLinks');
    this.userMenuBtn = document.getElementById('userMenuBtn');
    this.userMenu    = document.querySelector('.user-menu');

    // Scroll effect
    window.addEventListener('scroll', () => this.handleScroll(), { passive: true });
    this.handleScroll();

    // Mobile menu toggle
    if (this.mobileBtn) {
      this.mobileBtn.addEventListener('click', () => this.toggleMobileMenu());
    }

    // User dropdown
    if (this.userMenuBtn) {
      this.userMenuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.userMenu.classList.toggle('open');
      });
    }

    // Close dropdowns on outside click
    document.addEventListener('click', () => {
      if (this.userMenu) this.userMenu.classList.remove('open');
      if (this.navLinks) this.navLinks.classList.remove('mobile-open');
    });

    // Prevent propagation inside menus
    if (this.userMenu) this.userMenu.addEventListener('click', e => e.stopPropagation());
  },

  handleScroll() {
    const scrollY = window.scrollY;
    if (this.nav) {
      this.nav.classList.toggle('scrolled', scrollY > 20);
    }
    this.lastScrollY = scrollY;
  },

  toggleMobileMenu() {
    if (!this.navLinks) return;
    this.navLinks.classList.toggle('mobile-open');

    // Animate hamburger icon
    const spans = this.mobileBtn.querySelectorAll('span');
    const isOpen = this.navLinks.classList.contains('mobile-open');
    if (isOpen) {
      spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
      spans[1].style.opacity   = '0';
      spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
    } else {
      spans.forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
    }
  }
};

/* ============================================================
   4. SCROLL ANIMATIONS (IntersectionObserver)
   ============================================================ */
const AnimationManager = {
  init() {
    // Add fade-in class to animatable elements
    const selectors = [
      '.feature-card',
      '.step-card',
      '.trip-type-card',
      '.stat-card',
      '.trip-card-full',
      '.trip-card',
      '.item-card',
      '.itin-day',
      '.activity-item-card'
    ];

    const elements = document.querySelectorAll(selectors.join(', '));
    elements.forEach(el => el.classList.add('fade-in'));

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    elements.forEach(el => observer.observe(el));

    // Staggered delay for grid items
    document.querySelectorAll('.features-grid .feature-card, .stats-grid .stat-card, .trips-grid .trip-card-full').forEach((el, i) => {
      el.style.transitionDelay = `${i * 60}ms`;
    });
  }
};

/* ============================================================
   5. SMOOTH SCROLL for anchor links
   ============================================================ */
const SmoothScrollManager = {
  init() {
    const scrollToHash = (hash, smooth = true) => {
      if (!hash || hash === '#') return;

      const target = document.querySelector(hash);
      if (!target) return;

      const offset = 80; // navbar height
      const top = target.getBoundingClientRect().top + window.scrollY - offset;

      window.scrollTo({ top, behavior: smooth ? 'smooth' : 'auto' });
    };

    document.querySelectorAll('a[href^="#"], a[href*="#features"], a[href*="#how-it-works"]').forEach(link => {
      link.addEventListener('click', function (e) {
        const href = this.getAttribute('href') || '';
        const hash = href.includes('#') ? `#${href.split('#').pop()}` : href;

        if (!hash || hash === '#') return;

        const target = document.querySelector(hash);
        if (target) {
          e.preventDefault();
          scrollToHash(hash, true);
          history.replaceState(null, '', hash);
        }
      });
    });

    // If the page loads with a hash (for example /#features from another page),
    // animate to the target after the DOM is ready.
    if (window.location.hash) {
      const initialHash = window.location.hash;
      window.scrollTo({ top: 0, behavior: 'auto' });
      window.requestAnimationFrame(() => {
        setTimeout(() => scrollToHash(initialHash, true), 60);
      });
    }
  }
};

/* ============================================================
   6. LOADING BAR (page transitions)
   ============================================================ */
const LoadingBar = {
  bar: null,
  resetTimer: null,

  init() {
    // Create bar
    this.bar = document.createElement('div');
    this.bar.id = 'loadingBar';
    this.bar.style.cssText = `
      position: fixed; top: 0; left: 0; height: 3px; width: 0;
      background: linear-gradient(90deg, #4361ee, #f72585);
      z-index: 9999; transition: width 0.3s ease; border-radius: 0 2px 2px 0;
      box-shadow: 0 0 8px rgba(67,97,238,0.5);
    `;
    document.body.appendChild(this.bar);

    // Reset the bar when a page is restored from the back/forward cache.
    window.addEventListener('pageshow', () => this.complete(), { passive: true });

    // Trigger on all non-anchor form submits and navigation links
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', () => this.start());
    });

    document.querySelectorAll('a:not([href^="#"]):not([href^="javascript"]):not([target="_blank"])').forEach(link => {
      link.addEventListener('click', () => {
        if (!link.href || link.href === window.location.href) return;
        this.start();
      });
    });
  },

  start() {
    this.bar.style.width = '70%';
    this.bar.style.opacity = '1';
    // Complete on next page load
    window.addEventListener('load', () => this.complete(), { once: true });
  },

  complete() {
    if (!this.bar) return;

    clearTimeout(this.resetTimer);
    this.bar.style.width = '100%';
    this.resetTimer = setTimeout(() => {
      this.bar.style.opacity = '0';
      setTimeout(() => { this.bar.style.width = '0'; }, 300);
    }, 300);
  }
};

/* ============================================================
   7. HERO BLOB PARALLAX
   ============================================================ */
const ParallaxManager = {
  init() {
    const blobs = document.querySelectorAll('.blob, .floating-element');
    if (!blobs.length) return;

    window.addEventListener('mousemove', (e) => {
      const x = (e.clientX / window.innerWidth - 0.5) * 20;
      const y = (e.clientY / window.innerHeight - 0.5) * 20;

      blobs.forEach((blob, i) => {
        const factor = (i % 3 + 1) * 0.4;
        blob.style.transform = `translate(${x * factor}px, ${y * factor}px)`;
      });
    }, { passive: true });
  }
};

/* ============================================================
   8. FORM ENHANCEMENTS
   ============================================================ */
const FormManager = {
  init() {
    // Auto-resize textareas
    document.querySelectorAll('textarea.form-input').forEach(textarea => {
      textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 300) + 'px';
      });
    });

    // Confirm delete actions
    document.querySelectorAll('form[data-confirm]').forEach(form => {
      form.addEventListener('submit', function (e) {
        if (!confirm(this.dataset.confirm)) e.preventDefault();
      });
    });

    // Mark active nav link based on current URL
    const path = window.location.pathname;
    document.querySelectorAll('.nav-links a').forEach(link => {
      if (link.getAttribute('href') === path) {
        link.classList.add('active');
      }
    });

    // Date field: prevent end_date before start_date
    const startDate = document.getElementById('start_date');
    const endDate   = document.getElementById('end_date');
    if (startDate && endDate) {
      startDate.addEventListener('change', () => {
        if (endDate.value && endDate.value < startDate.value) {
          endDate.value = startDate.value;
        }
        endDate.min = startDate.value;
      });
    }

    // Character counter for textareas with maxlength
    document.querySelectorAll('textarea[maxlength]').forEach(ta => {
      const max = parseInt(ta.getAttribute('maxlength'));
      const counter = document.createElement('small');
      counter.className = 'char-counter';
      counter.style.cssText = 'display:block; text-align:right; font-size:0.75rem; color:var(--text-muted); margin-top:4px;';
      ta.parentNode.insertBefore(counter, ta.nextSibling);

      const update = () => {
        const remaining = max - ta.value.length;
        counter.textContent = `${ta.value.length} / ${max}`;
        counter.style.color = remaining < 50 ? 'var(--warning)' : 'var(--text-muted)';
      };
      ta.addEventListener('input', update);
      update();
    });
  }
};

/* ============================================================
   9. DASHBOARD: Greeting
   ============================================================ */
const GreetingManager = {
  init() {
    const greetingEl = document.querySelector('.greeting');
    if (!greetingEl) return;

    const hour = new Date().getHours();
    let greeting = 'Good evening';
    if (hour < 12) greeting = 'Good morning';
    else if (hour < 17) greeting = 'Good afternoon';

    greetingEl.textContent = greeting;
  }
};

/* ============================================================
   10. COUNTER ANIMATION (stats)
   ============================================================ */
const CounterManager = {
  init() {
    const counters = document.querySelectorAll('.stat-value');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          this.animate(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
  },

  animate(el) {
    const text  = el.textContent.trim();
    const prefix = text.match(/^[^0-9]*/)[0];   // e.g. "$"
    const suffix = text.match(/[^0-9]*$/)[0];    // e.g. "K+"
    const num   = parseFloat(text.replace(/[^0-9.]/g, ''));

    if (isNaN(num) || num === 0) return;

    const duration = 1000;
    const start    = performance.now();

    const step = (now) => {
      const elapsed  = now - start;
      const progress = Math.min(elapsed / duration, 1);
      // Ease out cubic
      const eased    = 1 - Math.pow(1 - progress, 3);
      const current  = Math.round(eased * num);

      el.textContent = prefix + current.toLocaleString() + suffix;

      if (progress < 1) requestAnimationFrame(step);
      else el.textContent = text; // restore original
    };

    requestAnimationFrame(step);
  }
};

/* ============================================================
   11. TRIP CARD HOVER EFFECTS
   ============================================================ */
const CardEffects = {
  init() {
    document.querySelectorAll('.trip-card-full, .feature-card, .stat-card').forEach(card => {
      card.addEventListener('mousemove', function (e) {
        const rect   = this.getBoundingClientRect();
        const x      = e.clientX - rect.left;
        const y      = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const rotateX = ((y - centerY) / centerY) * -4;
        const rotateY = ((x - centerX) / centerX) * 4;

        this.style.transform = `perspective(600px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
      });

      card.addEventListener('mouseleave', function () {
        this.style.transform = '';
      });
    });
  }
};

/* ============================================================
   12. ACTIVE TAB PERSISTENCE (Trip Detail)
   ============================================================ */
const TabManager = {
  storageKey: 'wanderly_active_tab',

  init() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    if (!tabBtns.length) return;

    // Restore last active tab
    const saved = sessionStorage.getItem(this.storageKey);
    if (saved) {
      const savedBtn = document.querySelector(`.tab-btn[data-tab="${saved}"]`);
      if (savedBtn) savedBtn.click();
    }

    // Save on tab change
    tabBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        sessionStorage.setItem(this.storageKey, btn.dataset.tab);
      });
    });
  }
};

/* ============================================================
   13. COPY TO CLIPBOARD (booking references)
   ============================================================ */
const ClipboardManager = {
  init() {
    document.querySelectorAll('.booking-ref strong, [data-copy]').forEach(el => {
      el.style.cursor = 'pointer';
      el.title = 'Click to copy';

      el.addEventListener('click', async () => {
        const text = el.dataset.copy || el.textContent;
        try {
          await navigator.clipboard.writeText(text);
          ToastManager.show(`Copied: ${text}`, 'success');
        } catch {
          // Fallback
          const range = document.createRange();
          range.selectNode(el);
          window.getSelection().removeAllRanges();
          window.getSelection().addRange(range);
          document.execCommand('copy');
          ToastManager.show('Copied to clipboard!', 'success');
        }
      });
    });
  }
};

/* ============================================================
   14. SEARCH INPUT DEBOUNCE (Trips Index)
   ============================================================ */
const SearchManager = {
  init() {
    const searchInput = document.querySelector('.search-input');
    if (!searchInput) return;

    let debounceTimer;
    searchInput.addEventListener('input', function () {
      clearTimeout(debounceTimer);
      // Only auto-submit if input is cleared (for clearing search)
      if (this.value === '') {
        debounceTimer = setTimeout(() => {
          this.closest('form')?.submit();
        }, 300);
      }
    });

    // Submit on Enter
    searchInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        clearTimeout(debounceTimer);
        this.closest('form')?.submit();
      }
    });
  }
};

/* ============================================================
   15. PRINT STYLES HELPER (Itinerary)
   ============================================================ */
const PrintManager = {
  init() {
    window.addEventListener('beforeprint', () => {
      document.querySelectorAll('.itin-day.is-past').forEach(d => d.style.opacity = '1');
    });
    window.addEventListener('afterprint', () => {
      document.querySelectorAll('.itin-day.is-past').forEach(d => d.style.opacity = '');
    });
  }
};

/* ============================================================
   16. KEYBOARD SHORTCUTS
   ============================================================ */
const ShortcutManager = {
  init() {
    document.addEventListener('keydown', (e) => {
      // Esc — close dropdowns / mobile menu
      if (e.key === 'Escape') {
        document.querySelectorAll('.tcf-dropdown.open, .user-menu.open').forEach(el => el.classList.remove('open'));
        const navLinks = document.getElementById('navLinks');
        if (navLinks) navLinks.classList.remove('mobile-open');
      }

      // Ctrl/Cmd + K — focus search (if on trips page)
      if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        const search = document.querySelector('.search-input');
        if (search) { e.preventDefault(); search.focus(); search.select(); }
      }
    });
  }
};

/* ============================================================
   BOOT — Initialize all managers on DOM ready
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
  ThemeManager.init();
  ToastManager.init();
  NavbarManager.init();
  AnimationManager.init();
  SmoothScrollManager.init();
  LoadingBar.init();
  ParallaxManager.init();
  FormManager.init();
  GreetingManager.init();
  CounterManager.init();
  CardEffects.init();
  TabManager.init();
  ClipboardManager.init();
  SearchManager.init();
  PrintManager.init();
  ShortcutManager.init();

  // Restore scroll position after form submission
  if (window.history.scrollRestoration) {
    window.history.scrollRestoration = 'manual';
  }

  console.log('%c✈ Wanderly Travel Planner', 'color:#1a2744;font-size:1.1rem;font-weight:bold;');
  console.log('%cPremium AI Travel Planning — Ready!', 'color:#e8804a;');
});
