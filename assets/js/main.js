/* BiteBiMuv — main.js v3.0 */
(function () {
  'use strict';

  /* ===== HEADER ===== */
  function initHeader() {
    var header = document.querySelector('.bbm-header');
    if (!header) return;
    var nav  = document.querySelector('.bbm-nav');
    var burger = document.getElementById('bbm-hamburger');

    window.addEventListener('scroll', function() {
      header.classList.toggle('is-scrolled', window.scrollY > 20);
    }, { passive: true });

    if (burger && nav) {
      burger.addEventListener('click', function() {
        var open = nav.classList.toggle('is-open');
        burger.classList.toggle('is-active', open);
        burger.setAttribute('aria-expanded', open);
        document.body.style.overflow = open ? 'hidden' : '';
      });
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && nav.classList.contains('is-open')) {
          nav.classList.remove('is-open');
          burger.classList.remove('is-active');
          burger.setAttribute('aria-expanded', 'false');
          document.body.style.overflow = '';
        }
      });
      document.addEventListener('click', function(e) {
        if (nav.classList.contains('is-open') && !nav.contains(e.target) && !burger.contains(e.target)) {
          nav.classList.remove('is-open');
          burger.classList.remove('is-active');
          burger.setAttribute('aria-expanded', 'false');
          document.body.style.overflow = '';
        }
      });
    }
  }

  /* ===== SCROLL PROGRESS ===== */
  function initScrollProgress() {
    var bar = document.getElementById('bbm-scroll-progress');
    if (!bar) return;
    window.addEventListener('scroll', function() {
      var dh = document.documentElement.scrollHeight - window.innerHeight;
      bar.style.width = (dh > 0 ? (window.scrollY / dh * 100) : 0) + '%';
    }, { passive: true });
  }

  /* ===== SCROLL TOP ===== */
  function initScrollTop() {
    var btn = document.getElementById('bbm-scroll-top');
    if (!btn) return;
    window.addEventListener('scroll', function() {
      btn.classList.toggle('is-visible', window.scrollY > 400);
    }, { passive: true });
    btn.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ===== FLOATING SMILEY ===== */
  function initFloatingSmiley() {
    var el = document.getElementById('bbm-floating-smiley');
    if (!el) return;
    window.addEventListener('scroll', function() {
      el.classList.toggle('is-visible', window.scrollY > 600);
    }, { passive: true });
    el.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ===== SEARCH ===== */
  function initSearch() {
    var panel  = document.getElementById('bbm-search-panel');
    var toggle = document.getElementById('bbm-search-toggle');
    var close  = document.getElementById('bbm-search-close');
    var input  = panel && panel.querySelector('.bbm-live-search-input');
    if (!panel || !toggle) return;
    var open = function() {
      panel.classList.add('is-open');
      panel.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      if (input) setTimeout(function() { input.focus(); }, 200);
    };
    var closePanel = function() {
      panel.classList.remove('is-open');
      panel.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    };
    toggle.addEventListener('click', open);
    if (close) close.addEventListener('click', closePanel);
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closePanel();
      if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); open(); }
    });
  }

  /* ===== AOS (Intersection Observer) ===== */
  function initAOS() {
    if (!window.IntersectionObserver) return;
    var els = document.querySelectorAll('[data-aos]');
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var delay = entry.target.dataset.aosDelay || 0;
          setTimeout(function() {
            entry.target.classList.add('is-animated');
          }, parseInt(delay));
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    els.forEach(function(el) { obs.observe(el); });
  }

  /* ===== COUNTERS ===== */
  function initCounters() {
    if (!window.IntersectionObserver) return;
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (!entry.isIntersecting) return;
        obs.unobserve(entry.target);
        animateCounter(entry.target);
      });
    }, { threshold: 0.5 });
    document.querySelectorAll('.bbm-counter[data-target]').forEach(function(el) { obs.observe(el); });
  }

  function animateCounter(el) {
    var raw     = el.dataset.target;
    var suffix  = (el.dataset.suffix || '') + (el.dataset.unit || '');
    var target  = parseFloat(raw) || 0;
    var isFloat = raw.includes('.');
    var dur     = 1800;
    var start   = null;
    var ease    = function(t) { return 1 - Math.pow(1 - t, 4); }; // easeOutQuart
    requestAnimationFrame(function step(ts) {
      if (!start) start = ts;
      var prog = Math.min((ts - start) / dur, 1);
      var val  = target * ease(prog);
      el.textContent = (isFloat ? val.toFixed(1) : Math.floor(val)) + suffix;
      if (prog < 1) requestAnimationFrame(step);
      else { el.textContent = target + suffix; el.classList.add('is-counting'); }
    });
  }

  /* ===== CONTACT FORM ===== */
  function initContactForm() {
    var form = document.getElementById('bbm-contact-form');
    if (!form) return;
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      var btn  = form.querySelector('[type=submit]');
      var data = new FormData(form);
      data.append('action', 'bbm_contact');
      data.append('nonce',  window.BBM ? BBM.nonce : '');
      setLoading(btn, true);
      fetch(window.BBM ? BBM.ajaxUrl : '/wp-admin/admin-ajax.php', {
        method: 'POST', body: data
      }).then(function(r) { return r.json(); }).then(function(resp) {
        showFormMsg(form, resp.success, resp.data && resp.data.message || '');
        if (resp.success) { form.reset(); cheerSmileys(); }
      }).catch(function() {
        showFormMsg(form, false, window.BBM && BBM.i18n ? BBM.i18n.error : 'Hata oluştu.');
      }).finally(function() { setLoading(btn, false); });
    });
  }

  /* ===== EVENT REGISTER ===== */
  function initEventRegister() {
    var modal   = document.getElementById('bbm-event-register-modal');
    var overlay = modal && modal.closest('.bbm-modal-overlay');
    if (!modal) return;
    document.querySelectorAll('[data-event-register]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id    = btn.dataset.eventRegister;
        var title = btn.dataset.eventTitle || '';
        var inp   = modal.querySelector('input[name="event_id"]');
        var h     = modal.querySelector('.bbm-modal__title');
        if (inp) inp.value = id;
        if (h)   h.textContent = title || 'Etkinliğe Kayıt';
        if (overlay) overlay.classList.add('is-open');
      });
    });
    modal.querySelectorAll('.bbm-modal__close, [data-modal-close]').forEach(function(c) {
      c.addEventListener('click', function() { if (overlay) overlay.classList.remove('is-open'); });
    });
    if (overlay) overlay.addEventListener('click', function(e) {
      if (e.target === overlay) overlay.classList.remove('is-open');
    });

    var regForm = document.getElementById('bbm-event-register-form');
    if (regForm) {
      regForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var btn  = regForm.querySelector('[type=submit]');
        var data = new FormData(regForm);
        data.append('action', 'bbm_event_register');
        data.append('nonce',  window.BBM ? BBM.nonce : '');
        setLoading(btn, true);
        fetch(window.BBM ? BBM.ajaxUrl : '/wp-admin/admin-ajax.php', {
          method: 'POST', body: data
        }).then(function(r) { return r.json(); }).then(function(resp) {
          showFormMsg(regForm, resp.success, resp.data && resp.data.message || '');
          if (resp.success) { regForm.reset(); cheerSmileys(); }
        }).finally(function() { setLoading(btn, false); });
      });
    }
  }

  /* ===== MEMBERSHIP FORM ===== */
  function initMembershipForm() {
    var form = document.getElementById('bbm-membership-form');
    if (!form) return;
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      var btn  = form.querySelector('[type=submit]');
      var data = new FormData(form);
      data.append('action', 'bbm_membership');
      data.append('nonce',  window.BBM ? BBM.nonce : '');
      setLoading(btn, true);
      fetch(window.BBM ? BBM.ajaxUrl : '/wp-admin/admin-ajax.php', {
        method: 'POST', body: data
      }).then(function(r) { return r.json(); }).then(function(resp) {
        showFormMsg(form, resp.success, resp.data && resp.data.message || '');
        if (resp.success) { form.reset(); cheerSmileys(); }
      }).catch(function() {
        showFormMsg(form, false, window.BBM && BBM.i18n ? BBM.i18n.error : 'Hata oluştu.');
      }).finally(function() { setLoading(btn, false); });
    });
  }

  /* ===== NEWSLETTER ===== */
  function initNewsletter() {
    document.querySelectorAll('.bbm-newsletter-form').forEach(function(form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        var btn  = form.querySelector('[type=submit]');
        var data = new FormData(form);
        data.append('action', 'bbm_newsletter');
        data.append('nonce',  window.BBM ? BBM.nonce : '');
        setLoading(btn, true);
        fetch(window.BBM ? BBM.ajaxUrl : '/wp-admin/admin-ajax.php', {
          method: 'POST', body: data
        }).then(function(r) { return r.json(); }).then(function(resp) {
          showFormMsg(form, resp.success, resp.data && resp.data.message || '');
          if (resp.success) { form.reset(); showToast('🎊 ' + (resp.data && resp.data.message || 'Abone oldunuz!'), 'success'); }
        }).finally(function() { setLoading(btn, false); });
      });
    });
  }

  /* ===== GALLERY LIGHTBOX ===== */
  function initGallery() {
    var lightbox = document.getElementById('bbm-lightbox');
    var lbImg    = lightbox && lightbox.querySelector('img');
    if (!lightbox || !lbImg) return;

    document.querySelectorAll('.bbm-gallery-item').forEach(function(item) {
      item.addEventListener('click', function() {
        var img = item.querySelector('img');
        if (!img) return;
        lbImg.src = img.src.replace(/-\d+x\d+\./, '.');
        lbImg.alt = img.alt;
        lightbox.classList.add('is-open');
        document.body.style.overflow = 'hidden';
      });
    });

    var close = lightbox.querySelector('.bbm-lightbox__close');
    var closeLb = function() {
      lightbox.classList.remove('is-open');
      document.body.style.overflow = '';
    };
    if (close) close.addEventListener('click', closeLb);
    lightbox.addEventListener('click', function(e) { if (e.target === lightbox) closeLb(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeLb(); });
  }

  /* ===== SHARE BUTTONS ===== */
  function initShareButtons() {
    document.querySelectorAll('.bbm-share-btn--copy').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var url = btn.dataset.url || window.location.href;
        if (navigator.clipboard) {
          navigator.clipboard.writeText(url).then(function() {
            showToast('✅ ' + (window.BBM && BBM.i18n ? BBM.i18n.copied : 'Kopyalandı!'));
          });
        } else {
          var ta = document.createElement('textarea');
          ta.value = url;
          document.body.appendChild(ta);
          ta.select();
          document.execCommand('copy');
          document.body.removeChild(ta);
          showToast('✅ Kopyalandı!');
        }
      });
    });
  }

  /* ===== SMOOTH SCROLL ===== */
  function initSmoothScroll() {
    var header = document.querySelector('.bbm-header');
    document.querySelectorAll('a[href^="#"]').forEach(function(a) {
      a.addEventListener('click', function(e) {
        var id = a.getAttribute('href').slice(1);
        var target = document.getElementById(id);
        if (!target) return;
        e.preventDefault();
        var offset = header ? header.offsetHeight : 72;
        var top    = target.getBoundingClientRect().top + window.scrollY - offset - 16;
        window.scrollTo({ top: top, behavior: 'smooth' });
      });
    });
  }

  /* ===== PLAN SELECT ===== */
  function initPlanSelect() {
    document.querySelectorAll('.bbm-plan__btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var plan = btn.closest('.bbm-plan');
        var name = plan && plan.querySelector('.bbm-plan__name');
        var sel  = document.querySelector('#bbm-membership-form select[name="membership_type"]');
        if (sel && name) {
          sel.value = btn.dataset.plan || name.textContent.toLowerCase();
          var form = document.getElementById('bbm-membership-form');
          if (form) form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      });
    });
  }

  /* ===== TIMELINE ANIMATION ===== */
  function initTimeline() {
    if (!window.IntersectionObserver) return;
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'none';
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });
    document.querySelectorAll('.bbm-timeline-item').forEach(function(item, i) {
      item.style.opacity = '0';
      item.style.transform = 'translateY(30px)';
      item.style.transition = 'opacity 0.6s ease ' + (i * 0.12) + 's, transform 0.6s ease ' + (i * 0.12) + 's';
      obs.observe(item);
    });
  }

  /* ===== HELPERS ===== */
  function setLoading(btn, loading) {
    if (!btn) return;
    btn.disabled = loading;
    btn.classList.toggle('bbm-btn--loading', loading);
    var txt = btn.querySelector('.bbm-btn-text');
    if (txt) txt.style.opacity = loading ? '0' : '1';
  }

  function showFormMsg(form, success, msg) {
    var existing = form.querySelector('.bbm-form-success, .bbm-form-error');
    if (existing) existing.remove();
    var div = document.createElement('div');
    div.className = success ? 'bbm-form-success' : 'bbm-form-error';
    div.textContent = (success ? '✅ ' : '❌ ') + msg;
    form.appendChild(div);
    if (success) setTimeout(function() { div.remove(); }, 6000);
  }

  function showToast(msg, type) {
    var t = document.getElementById('bbm-toast');
    if (!t) {
      t = document.createElement('div');
      t.id = 'bbm-toast';
      t.className = 'bbm-toast';
      document.body.appendChild(t);
    }
    t.className = 'bbm-toast bbm-toast--' + (type || 'accent');
    t.textContent = msg;
    requestAnimationFrame(function() {
      requestAnimationFrame(function() { t.classList.add('is-visible'); });
    });
    clearTimeout(t._timer);
    t._timer = setTimeout(function() { t.classList.remove('is-visible'); }, 3500);
  }

  function cheerSmileys() {
    if (window.BBMSmiley) BBMSmiley.cheer();
    document.querySelectorAll('.bbm-smiley').forEach(function(el) {
      el.classList.add('bbm-animate-tada');
      setTimeout(function() { el.classList.remove('bbm-animate-tada'); }, 1100);
    });
  }

  /* ===== NAV FALLBACK ===== */
  function initNavFallback() {
    var nav = document.getElementById('bbm-nav-fallback');
    if (!nav || nav.children.length > 0) return;
    var items = [
      { label: 'Ana Sayfa',    url: '/' },
      { label: 'Etkinlikler', url: '/etkinlikler/' },
      { label: 'Projeler',    url: '/projeler/' },
      { label: 'Duyurular',  url: '/duyurular/' },
      { label: 'İletişim',    url: '/#iletisim' },
    ];
    items.forEach(function(it) {
      var li = document.createElement('li');
      li.className = 'menu-item';
      var a  = document.createElement('a');
      a.href = it.url;
      a.textContent = it.label;
      li.appendChild(a);
      nav.appendChild(li);
    });
  }

  /* ===== INIT ===== */
  document.addEventListener('DOMContentLoaded', function () {
    initHeader();
    initScrollProgress();
    initScrollTop();
    initFloatingSmiley();
    initSearch();
    initAOS();
    initCounters();
    initContactForm();
    initEventRegister();
    initMembershipForm();
    initNewsletter();
    initGallery();
    initShareButtons();
    initSmoothScroll();
    initPlanSelect();
    initTimeline();
    initNavFallback();
  });

  window.BBM_UI = {
    showToast:   showToast,
    cheerSmileys:cheerSmileys,
  };
})();
