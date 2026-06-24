/**
 * extras.js - Ek bileşen JavaScript'leri v3.0
 * FAQ akordeon, topbar, üyelik formu planı seçimi, etkinlik kayıt
 * galeri lightbox geliştirme, topbar temizleme, breadcrumb
 *
 * @package bitebimuv-dernek
 */

(function() {
    'use strict';

    // =========================================
    // FAQ AKORDEONu
    // =========================================
    function initFAQ() {
        document.querySelectorAll('.bbm-faq-item__question').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var expanded = this.getAttribute('aria-expanded') === 'true';
                var answerId = this.getAttribute('aria-controls');
                var answer   = document.getElementById(answerId);
                if (!answer) return;

                // Tüm diğer FAQ'ları kapat
                document.querySelectorAll('.bbm-faq-item__question').forEach(function(other) {
                    if (other !== btn) {
                        other.setAttribute('aria-expanded', 'false');
                        var otherId = other.getAttribute('aria-controls');
                        var otherAns = document.getElementById(otherId);
                        if (otherAns) otherAns.hidden = true;
                    }
                });

                // Bu FAQ'ı aç/kapat
                var isNowOpen = !expanded;
                this.setAttribute('aria-expanded', isNowOpen ? 'true' : 'false');
                answer.hidden = !isNowOpen;
            });
        });
    }

    // =========================================
    // TOPBAR KAPATMA
    // =========================================
    function initTopbar() {
        var topbar = document.getElementById('bbm-topbar');
        if (!topbar) return;

        // Cookie kontrolü
        if (document.cookie.indexOf('bbm_topbar_closed=1') !== -1) {
            topbar.style.display = 'none';
            return;
        }

        var closeBtn = topbar.querySelector('.bbm-topbar__close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                topbar.style.transition = 'max-height 0.3s ease, opacity 0.3s ease';
                topbar.style.maxHeight = '0';
                topbar.style.overflow  = 'hidden';
                topbar.style.opacity   = '0';
                setTimeout(function() { topbar.style.display = 'none'; }, 350);
                // 1 gün boyunca kapalı tut
                document.cookie = 'bbm_topbar_closed=1; max-age=86400; path=/; SameSite=Lax';
            });
        }
    }

    // =========================================
    // ETKİNLİK KART OVERLAY BADGELERİ
    // =========================================
    function initEventCardBadges() {
        document.querySelectorAll('.bbm-event-card__overlay-badge').forEach(function(badge) {
            var card = badge.closest('.bbm-card');
            if (card) {
                badge.style.display = 'inline-flex';
            }
        });
    }

    // =========================================
    // SAYFA YÜKLEME PROGRESS ANIMASYONU
    // =========================================
    function initPageLoading() {
        // Sayfa tamamen yüklendiğinde progress bar'ı kaldır
        window.addEventListener('load', function() {
            document.documentElement.classList.add('bbm-loaded');
        });
    }

    // =========================================
    // ÜYELİK PLAN SEÇİMİ (FORM ENTEGRASYONu)
    // =========================================
    function initMembershipPlanSync() {
        var planBtns       = document.querySelectorAll('.bbm-plan-select-btn');
        var typeInput      = document.getElementById('bbm-membership-type');
        var typeSelect     = document.getElementById('bbm-mem-type-select');
        var formWrap       = document.getElementById('bbm-membership-form-wrap');
        var selectedPlanEl = document.querySelector('.bbm-membership-selected-plan');

        if (!planBtns.length) return;

        planBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var planId   = this.dataset.planId;
                var planName = this.dataset.planName;

                // Gizli input güncelle
                if (typeInput) typeInput.value = planId;

                // Select güncelle
                if (typeSelect) typeSelect.value = planId;

                // Plana göre formu scroll ile göster
                if (formWrap) {
                    var headerH = document.getElementById('bbm-header');
                    var offset  = headerH ? headerH.offsetHeight + 24 : 80;
                    var top     = formWrap.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: top, behavior: 'smooth' });
                }

                // Seçili plan göstergesi
                if (selectedPlanEl) selectedPlanEl.textContent = planName;

                // Tüm plan select butonlarından aktif sınıfını kaldır
                planBtns.forEach(function(b) {
                    b.classList.remove('is-selected');
                });
                this.classList.add('is-selected');

                // Toast
                if (window.BBM_UI && window.BBM_UI.showToast) {
                    window.BBM_UI.showToast(
                        (window.BBM && window.BBM.i18n && window.BBM.i18n.planSelected)
                            ? window.BBM.i18n.planSelected.replace('%s', planName)
                            : planName + ' planı seçildi',
                        'success'
                    );
                }
            });
        });
    }

    // =========================================
    // GALERİ LİGHTBOX (GELİŞTİRİLMİŞ)
    // =========================================
    function initGalleryLightbox() {
        var lightbox    = document.getElementById('bbm-lightbox');
        if (!lightbox) return;

        var overlay     = document.getElementById('bbm-lightbox-overlay');
        var img         = document.getElementById('bbm-lightbox-img');
        var caption     = document.getElementById('bbm-lightbox-caption');
        var closeBtn    = document.getElementById('bbm-lightbox-close');
        var prevBtn     = document.getElementById('bbm-lightbox-prev');
        var nextBtn     = document.getElementById('bbm-lightbox-next');
        var galleryData = [];
        var currentIdx  = 0;

        // Galeri verilerini yükle
        var dataEl = document.getElementById('bbm-gallery-data');
        if (dataEl) {
            try {
                galleryData = JSON.parse(dataEl.textContent);
            } catch(e) {}
        }

        function openLightbox(idx) {
            if (!galleryData[idx]) return;
            currentIdx = idx;
            var item = galleryData[idx];
            img.src = item.src || item.full || '';
            img.alt = item.alt || '';
            if (caption) caption.textContent = item.caption || '';
            lightbox.hidden = false;
            document.body.style.overflow = 'hidden';
            img.focus();

            // Önceki/sonraki butonları gizle/göster
            if (prevBtn) prevBtn.style.display = idx === 0 ? 'none' : '';
            if (nextBtn) nextBtn.style.display = idx === galleryData.length - 1 ? 'none' : '';
        }

        function closeLightbox() {
            lightbox.hidden = true;
            document.body.style.overflow = '';
        }

        // Tetikleyiciler
        document.addEventListener('click', function(e) {
            var trigger = e.target.closest('.bbm-gallery-trigger');
            if (!trigger) return;
            e.preventDefault();
            var idx = parseInt(trigger.dataset.galleryIndex, 10) || 0;
            openLightbox(idx);
        });

        if (overlay) overlay.addEventListener('click', closeLightbox);
        if (closeBtn) closeBtn.addEventListener('click', closeLightbox);

        if (prevBtn) prevBtn.addEventListener('click', function() {
            if (currentIdx > 0) openLightbox(currentIdx - 1);
        });

        if (nextBtn) nextBtn.addEventListener('click', function() {
            if (currentIdx < galleryData.length - 1) openLightbox(currentIdx + 1);
        });

        // Klavye desteği
        document.addEventListener('keydown', function(e) {
            if (lightbox.hidden) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft' && currentIdx > 0) openLightbox(currentIdx - 1);
            if (e.key === 'ArrowRight' && currentIdx < galleryData.length - 1) openLightbox(currentIdx + 1);
        });

        // Dokunmatik kaydırma
        var touchStartX = null;
        lightbox.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].clientX;
        }, { passive: true });

        lightbox.addEventListener('touchend', function(e) {
            if (touchStartX === null) return;
            var diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) {
                if (diff > 0 && currentIdx < galleryData.length - 1) openLightbox(currentIdx + 1);
                else if (diff < 0 && currentIdx > 0) openLightbox(currentIdx - 1);
            }
            touchStartX = null;
        }, { passive: true });
    }

    // =========================================
    // ETKİNLİK KAYIT MODAL (HEADER REG BUTONU)
    // =========================================
    function initEventRegisterBtns() {
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.bbm-event-register-btn');
            if (!btn) return;

            var modal   = document.getElementById('bbm-event-modal');
            var title   = document.getElementById('bbm-event-modal-title');
            var eventId = document.getElementById('bbm-event-register-id');

            if (!modal) return;

            var eventTitle = btn.dataset.eventTitle || '';
            if (title && eventTitle) {
                title.textContent = window.BBM && window.BBM.i18n && window.BBM.i18n.registerFor
                    ? window.BBM.i18n.registerFor + ': ' + eventTitle
                    : 'Kayıt: ' + eventTitle;
            }

            if (eventId) eventId.value = btn.dataset.eventId || '';

            modal.hidden = false;
            document.body.style.overflow = 'hidden';

            // İlk inputa odaklan
            var firstInput = modal.querySelector('input:not([type=hidden])');
            if (firstInput) setTimeout(function() { firstInput.focus(); }, 100);
        });
    }

    // =========================================
    // PRO: BÜLTEN FORMU DOĞRULAMA
    // =========================================
    function initNewsletterValidation() {
        var form = document.getElementById('bbm-newsletter-form');
        if (!form) return;

        var emailInput = document.getElementById('bbm-newsletter-email');
        if (!emailInput) return;

        emailInput.addEventListener('blur', function() {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = 'rgba(239,68,68,0.7)';
                this.setAttribute('aria-invalid', 'true');
            } else {
                this.style.borderColor = '';
                this.removeAttribute('aria-invalid');
            }
        });
    }

    // =========================================
    // HEADER SCROLL DARK
    // =========================================
    function initHeaderScrollDark() {
        var header = document.getElementById('bbm-header');
        if (!header) return;

        var ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    if (window.scrollY > 50) {
                        header.classList.add('is-scrolled');
                    } else {
                        header.classList.remove('is-scrolled');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    // =========================================
    // BAŞLATMA
    // =========================================
    function init() {
        initFAQ();
        initTopbar();
        initEventCardBadges();
        initPageLoading();
        initMembershipPlanSync();
        initGalleryLightbox();
        initEventRegisterBtns();
        initNewsletterValidation();
        initHeaderScrollDark();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Public API
    window.BBM_Extras = {
        initFAQ: initFAQ,
        initGalleryLightbox: initGalleryLightbox,
    };

})();
