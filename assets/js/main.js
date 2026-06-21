/**
 * BiteBiMuv Dernek - Ana JavaScript
 * Header, scroll, sayac, form, modal ve diğer genel işlevseller
 */
(function () {
  'use strict';

  // ===================================================
  // Yardımcı fonksiyonlar
  // ===================================================
  function qs(sel, ctx)   { return (ctx || document).querySelector(sel); }
  function qsa(sel, ctx)  { return Array.from((ctx || document).querySelectorAll(sel)); }
  function addClass(el, c)    { if (el) el.classList.add(c); }
  function removeClass(el, c) { if (el) el.classList.remove(c); }
  function toggleClass(el, c) { if (el) el.classList.toggle(c); }
  function show(el)  { if (el) el.hidden = false; }
  function hide(el)  { if (el) el.hidden = true; }
  function $on(el, ev, fn, opts) { if (el) el.addEventListener(ev, fn, opts || false); }
  function debounce(fn, ms) {
    var t;
    return function () { clearTimeout(t); t = setTimeout(fn.bind(this, arguments), ms); };
  }

  // ===================================================
  // Başlangıç
  // ===================================================
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
    initNewsletter();
    initParticles();
    initSmoothScroll();
    initModal();
    initFocusNavigation();
  });

  // ===================================================
  // 1. Sticky Header & Hamburger
  // ===================================================
  function initHeader() {
    var header    = qs('#bbm-header');
    var hamburger = qs('#bbm-hamburger');
    var nav       = qs('#bbm-nav');
    if (!header) return;

    // Scroll'da header stilleri
    var lastY = 0;
    window.addEventListener('scroll', function () {
      var y = window.scrollY;
      if (y > 20) {
        addClass(header, 'is-scrolled');
      } else {
        removeClass(header, 'is-scrolled');
      }
      lastY = y;
    }, { passive: true });

    // Hamburger
    if (hamburger && nav) {
      $on(hamburger, 'click', function () {
        var isOpen = hamburger.getAttribute('aria-expanded') === 'true';
        hamburger.setAttribute('aria-expanded', String(!isOpen));
        toggleClass(hamburger, 'is-open');
        toggleClass(nav, 'is-open');
        document.body.style.overflow = isOpen ? '' : 'hidden';
      });

      // Dışarı tıklayınca kapat
      $on(document, 'click', function (e) {
        if (nav.classList.contains('is-open') && !nav.contains(e.target) && !hamburger.contains(e.target)) {
          hamburger.setAttribute('aria-expanded', 'false');
          removeClass(hamburger, 'is-open');
          removeClass(nav, 'is-open');
          document.body.style.overflow = '';
        }
      });

      // Esc tuşu
      $on(document, 'keydown', function (e) {
        if (e.key === 'Escape' && nav.classList.contains('is-open')) {
          hamburger.click();
        }
      });
    }
  }

  // ===================================================
  // 2. Scroll Progress Bar
  // ===================================================
  function initScrollProgress() {
    var bar = qs('#bbm-scroll-progress');
    if (!bar) return;
    window.addEventListener('scroll', function () {
      var maxScroll = document.documentElement.scrollHeight - window.innerHeight;
      var pct = maxScroll > 0 ? (window.scrollY / maxScroll * 100) : 0;
      bar.style.width = pct.toFixed(1) + '%';
    }, { passive: true });
  }

  // ===================================================
  // 3. Scroll-to-top butonu
  // ===================================================
  function initScrollTop() {
    var btn = qs('#bbm-scroll-top');
    if (!btn) return;
    show(btn);
    btn.style.opacity = '0';
    btn.style.transform = 'translateY(20px)';

    window.addEventListener('scroll', function () {
      if (window.scrollY > 400) {
        btn.style.opacity = '1';
        btn.style.transform = 'translateY(0)';
        addClass(btn, 'is-visible');
      } else {
        btn.style.opacity = '0';
        btn.style.transform = 'translateY(20px)';
        removeClass(btn, 'is-visible');
      }
    }, { passive: true });

    $on(btn, 'click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ===================================================
  // 4. Köşe floating smiley
  // ===================================================
  function initFloatingSmiley() {
    var fs = qs('#bbm-floating-smiley');
    if (!fs) return;
    show(fs);
    fs.style.opacity = '0';
    fs.style.transform = 'scale(0.8)';
    fs.style.transition = 'all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';

    window.addEventListener('scroll', function () {
      if (window.scrollY > 600) {
        fs.style.opacity = '1';
        fs.style.transform = 'scale(1)';
      } else {
        fs.style.opacity = '0';
        fs.style.transform = 'scale(0.8)';
      }
    }, { passive: true });

    $on(fs, 'click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ===================================================
  // 5. Arama paneli
  // ===================================================
  function initSearch() {
    var toggle = qs('#bbm-search-toggle');
    var panel  = qs('#bbm-search-panel');
    var close  = qs('#bbm-search-close');
    if (!toggle || !panel) return;

    $on(toggle, 'click', function () {
      show(panel);
      var input = panel.querySelector('input');
      if (input) input.focus();
    });
    $on(close, 'click', function () { hide(panel); });
    $on(document, 'keydown', function (e) {
      if (e.key === 'Escape') hide(panel);
    });
    $on(document, 'click', function (e) {
      if (!panel.hidden && !panel.contains(e.target) && e.target !== toggle) {
        hide(panel);
      }
    });
  }

  // ===================================================
  // 6. AOS-like scroll animasyonları
  // ===================================================
  function initAOS() {
    var elements = qsa('[data-aos]');
    if (elements.length === 0) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          addClass(entry.target, 'is-animated');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

    elements.forEach(function (el) { observer.observe(el); });
  }

  // ===================================================
  // 7. Sayac animasyonu
  // ===================================================
  function initCounters() {
    var counters = qsa('.bbm-counter');
    if (counters.length === 0) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el     = entry.target;
        var target = parseInt(el.getAttribute('data-target') || '0', 10);
        var suffix = el.textContent.replace(/[0-9]/g, '').trim();
        var start  = 0;
        var duration = 1800;
        var startTime = null;

        function step(timestamp) {
          if (!startTime) startTime = timestamp;
          var progress = Math.min((timestamp - startTime) / duration, 1);
          // easeOutQuart
          var eased = 1 - Math.pow(1 - progress, 4);
          var current = Math.floor(eased * target);
          el.textContent = current + suffix;
          if (progress < 1) requestAnimationFrame(step);
          else el.textContent = target + suffix;
        }

        requestAnimationFrame(step);
        observer.unobserve(el);
      });
    }, { threshold: 0.5 });

    counters.forEach(function (el) { observer.observe(el); });
  }

  // ===================================================
  // 8. İletişim formu (AJAX)
  // ===================================================
  function initContactForm() {
    var form    = qs('#bbm-contact-form');
    var msgBox  = qs('#bbm-contact-message');
    var submitBtn = qs('#bbm-contact-submit');
    if (!form) return;

    $on(form, 'submit', function (e) {
      e.preventDefault();
      var data = new FormData(form);

      // Buton
      var btnText = submitBtn.querySelector('.bbm-btn__text');
      var btnLoad = submitBtn.querySelector('.bbm-btn__loading');
      if (btnText) hide(btnText);
      if (btnLoad) show(btnLoad);
      submitBtn.disabled = true;

      // Mesaj temizle
      if (msgBox) { msgBox.className = 'bbm-form-message'; msgBox.textContent = ''; }

      fetch((window.bbmData && window.bbmData.ajaxUrl) || '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: data,
        credentials: 'same-origin',
      })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res.success) {
            showMsg(msgBox, res.data, 'success');
            form.reset();
            // Smiley'i mutlu yap!
            cheerUpSmiley();
          } else {
            showMsg(msgBox, res.data || 'Hata oluştu.', 'error');
          }
        })
        .catch(function () {
          showMsg(msgBox, 'Bağlantı hatası. Lütfen tekrar deneyin.', 'error');
        })
        .finally(function () {
          if (btnText) show(btnText);
          if (btnLoad) hide(btnLoad);
          submitBtn.disabled = false;
        });
    });
  }

  function showMsg(el, text, type) {
    if (!el) return;
    el.textContent = text;
    el.className = 'bbm-form-message is-' + type;
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function cheerUpSmiley() {
    // Gönderim başarılı olunca smiley zıplayıp güler
    var smileys = document.querySelectorAll('.bbm-smiley');
    smileys.forEach(function (s) {
      s.style.transition = 'transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
      s.style.transform = 'scale(1.2) rotate(5deg)';
      setTimeout(function () {
        s.style.transform = 'scale(1) rotate(-3deg)';
        setTimeout(function () {
          s.style.transform = '';
        }, 300);
      }, 300);
    });
  }

  // ===================================================
  // 9. Etkinlik Kayıt Modal
  // ===================================================
  function initEventRegister() {
    var modal      = qs('#bbm-event-register-modal');
    var backdrop   = modal ? modal.querySelector('.bbm-modal__backdrop') : null;
    var closeBtn   = modal ? modal.querySelector('.bbm-modal__close') : null;
    var form       = qs('#bbm-event-register-form');
    var msgBox     = qs('#bbm-event-form-message');
    if (!modal) return;

    // Kayıt butonları
    qsa('[data-event-register]').forEach(function (btn) {
      $on(btn, 'click', function (e) {
        e.preventDefault();
        var eventId = btn.getAttribute('data-event-register');
        var idInput = modal.querySelector('#bbm-event-id');
        if (idInput) idInput.value = eventId;
        show(modal);
        document.body.style.overflow = 'hidden';
        var firstInput = modal.querySelector('input:not([type=hidden])');
        if (firstInput) setTimeout(function () { firstInput.focus(); }, 100);
      });
    });

    function closeModal() {
      hide(modal);
      document.body.style.overflow = '';
      if (form) form.reset();
      if (msgBox) { msgBox.className = 'bbm-form-message'; msgBox.textContent = ''; }
    }

    if (closeBtn)  $on(closeBtn, 'click', closeModal);
    if (backdrop)  $on(backdrop, 'click', closeModal);
    $on(document, 'keydown', function (e) { if (e.key === 'Escape') closeModal(); });

    if (form) {
      $on(form, 'submit', function (e) {
        e.preventDefault();
        var data = new FormData(form);
        var submitBtn = form.querySelector('[type=submit]');
        if (submitBtn) submitBtn.disabled = true;

        fetch((window.bbmData && window.bbmData.ajaxUrl) || '/wp-admin/admin-ajax.php', {
          method: 'POST', body: data, credentials: 'same-origin',
        })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            if (res.success) {
              showMsg(msgBox, res.data, 'success');
              form.reset();
              cheerUpSmiley();
              setTimeout(closeModal, 3000);
            } else {
              showMsg(msgBox, res.data || 'Hata.', 'error');
            }
          })
          .catch(function () { showMsg(msgBox, 'Bağlantı hatası.', 'error'); })
          .finally(function () { if (submitBtn) submitBtn.disabled = false; });
      });
    }
  }

  // ===================================================
  // 10. Modal (Genel)
  // ===================================================
  function initModal() {
    // Backdrop tıklama ve Esc ile tüm modalları kapat
    qsa('.bbm-modal__backdrop').forEach(function (bd) {
      $on(bd, 'click', function () {
        var modal = bd.closest('.bbm-modal');
        if (modal) hide(modal);
      });
    });
  }

  // ===================================================
  // 11. Bültene abone ol (basit demo)
  // ===================================================
  function initNewsletter() {
    var form = qs('#bbm-newsletter-form');
    if (!form) return;
    $on(form, 'submit', function (e) {
      e.preventDefault();
      var emailInput = form.querySelector('[name=email]');
      if (!emailInput || !emailInput.value) return;
      // Basit feedback
      var btn = form.querySelector('[type=submit]');
      if (btn) {
        var orig = btn.textContent;
        btn.textContent = '✓ Abone Oldunuz!';
        btn.disabled = true;
        btn.style.background = '#6BCB77';
        setTimeout(function () {
          btn.textContent = orig;
          btn.disabled = false;
          btn.style.background = '';
          form.reset();
        }, 3000);
      }
    });
  }

  // ===================================================
  // 12. Hero partiçüller
  // ===================================================
  function initParticles() {
    var container = qs('#bbm-particles');
    if (!container) return;

    var colors = ['rgba(232,67,90,0.4)', 'rgba(255,217,61,0.4)', 'rgba(107,203,119,0.3)', 'rgba(255,255,255,0.3)'];
    var count = window.innerWidth < 768 ? 8 : 16;

    for (var i = 0; i < count; i++) {
      var particle = document.createElement('div');
      particle.className = 'bbm-particle';
      var size = 4 + Math.random() * 12;
      particle.style.cssText = [
        'width:' + size + 'px',
        'height:' + size + 'px',
        'background:' + colors[Math.floor(Math.random() * colors.length)],
        'left:' + (Math.random() * 100) + '%',
        'top:' + (Math.random() * 100) + '%',
        '--duration:' + (4 + Math.random() * 8) + 's',
        '--delay:' + (Math.random() * -8) + 's',
        '--opacity:' + (0.2 + Math.random() * 0.5),
      ].join(';');
      container.appendChild(particle);
    }
  }

  // ===================================================
  // 13. Smooth scroll (anchor linkler)
  // ===================================================
  function initSmoothScroll() {
    qsa('a[href^="#"]').forEach(function (a) {
      $on(a, 'click', function (e) {
        var href = a.getAttribute('href');
        if (href === '#') return;
        var target = document.querySelector(href);
        if (!target) return;
        e.preventDefault();
        var headerH = (qs('#bbm-header') || {}).offsetHeight || 72;
        var top = target.getBoundingClientRect().top + window.scrollY - headerH - 20;
        window.scrollTo({ top: top, behavior: 'smooth' });
        // Mobil menüyse kapat
        var nav = qs('#bbm-nav');
        var hamburger = qs('#bbm-hamburger');
        if (nav && nav.classList.contains('is-open') && hamburger) hamburger.click();
      });
    });
  }

  // ===================================================
  // 14. Klavye odak navigasyonu
  // ===================================================
  function initFocusNavigation() {
    // Tab tuşuyla gezinirken focus halkalarını göster
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Tab') {
        document.body.classList.add('user-is-tabbing');
      }
    });
    document.addEventListener('mousedown', function () {
      document.body.classList.remove('user-is-tabbing');
    });
  }

  // ===================================================
  // Nav fallback: Menü yoksa basit link listesi göster
  // ===================================================
  window.bbm_nav_fallback = function () {
    var pages = [['Anasayfa','/'],['Hakkımızda','/hakkimizda'],['Etkinlikler','/etkinlikler'],['Haberler','/haberler'],['İletişim','/#iletisim']];
    var ul = document.createElement('ul');
    ul.className = 'bbm-nav__menu';
    pages.forEach(function (p) {
      var li = document.createElement('li');
      var a  = document.createElement('a');
      a.href = p[1]; a.textContent = p[0];
      li.appendChild(a); ul.appendChild(li);
    });
    var nav = document.getElementById('bbm-primary-menu');
    if (nav) nav.parentNode.replaceChild(ul, nav);
    else {
      var n = document.getElementById('bbm-nav');
      if (n) n.appendChild(ul);
    }
  };

})();
