/**
 * BiteBiMuv - Etkileşimli Gülümseyen Yüz (Smiley Face)
 * Kaydırma ve fare hareketine duyarlı interaktif maskot
 */
(function () {
  'use strict';

  // Her smiley instance'i bağımsızça yönetilir
  var smileys = [];

  // Yapılandırma
  var CONFIG = {
    pupilMaxTravel:  4.5,   // SVG birimiyle max gözbebeği hareketi
    pupilLerpFactor: 0.10,  // Gözbebeği yuvarlaştırma hızı (0-1)
    blinkMinDelay:   2500,  // ms
    blinkMaxDelay:   5500,  // ms
    blinkDuration:   120,   // ms
    smileBaseY:      68,    // Taban smile Y
    smileMaxY:       6,     // Ek smile Y (tam scroll)
    smileCtrlBase:   78,    // Control point
    smileCtrlMax:    18,    // Control point artışı
    browRaise:       6,     // Max kaş yükselişi (px)
    cheekFadeSpeed:  0.04,  // Yanak solma hızı
  };

  /**
   * Bir smiley yüzü başlat
   */
  function initSmiley(svgEl) {
    var id = svgEl.id || 'bbm-smiley';
    var prefix = id + '-';

    var parts = {
      leftPupil:  svgEl.querySelector('#' + prefix + 'left-pupil'),
      rightPupil: svgEl.querySelector('#' + prefix + 'right-pupil'),
      leftLid:    svgEl.querySelector('#' + prefix + 'left-lid'),
      rightLid:   svgEl.querySelector('#' + prefix + 'right-lid'),
      leftBrow:   svgEl.querySelector('#' + prefix + 'left-brow'),
      rightBrow:  svgEl.querySelector('#' + prefix + 'right-brow'),
      smile:      svgEl.querySelector('#' + prefix + 'smile'),
      teeth:      svgEl.querySelector('#' + prefix + 'teeth'),
      leftCheek:  svgEl.querySelector('#' + prefix + 'left-cheek'),
      rightCheek: svgEl.querySelector('#' + prefix + 'right-cheek'),
    };

    // Göz merkezleri (SVG koordinatında)
    var eyeCenter = { L: { x: 33, y: 38 }, R: { x: 67, y: 38 } };

    var state = {
      targetL:    { x: 33, y: 38 },
      targetR:    { x: 67, y: 38 },
      currentL:   { x: 33, y: 38 },
      currentR:   { x: 67, y: 38 },
      isBlinking: false,
      blinkTimer: null,
      cheekOpacity: 0,
      cheekTarget:  0,
      scrollProg:   0,
      animFrame:    null,
    };

    // =====  SVG güçlendirme: eyelid başlangıç konumunu ayarla =====
    function resetLids() {
      if (parts.leftLid) {
        parts.leftLid.setAttribute('ry', '8.5');
        parts.leftLid.setAttribute('cy', '29');
      }
      if (parts.rightLid) {
        parts.rightLid.setAttribute('ry', '8.5');
        parts.rightLid.setAttribute('cy', '29');
      }
    }
    resetLids();

    // ===== Göz kırpma =====
    function blink() {
      if (state.isBlinking) return;
      state.isBlinking = true;

      function closeLid(el) {
        if (!el) return;
        el.style.transition = 'cy ' + CONFIG.blinkDuration * 0.5 + 'ms ease, ry ' + CONFIG.blinkDuration * 0.5 + 'ms ease';
        el.setAttribute('cy', '38');
        el.setAttribute('ry', '10.5');
      }
      function openLid(el) {
        if (!el) return;
        el.style.transition = 'cy ' + CONFIG.blinkDuration * 0.5 + 'ms ease, ry ' + CONFIG.blinkDuration * 0.5 + 'ms ease';
        el.setAttribute('cy', '29');
        el.setAttribute('ry', '8.5');
      }

      closeLid(parts.leftLid);
      closeLid(parts.rightLid);

      setTimeout(function () {
        openLid(parts.leftLid);
        openLid(parts.rightLid);
        setTimeout(function () { state.isBlinking = false; }, CONFIG.blinkDuration * 0.5 + 50);
      }, CONFIG.blinkDuration);
    }

    function scheduleBlink() {
      var delay = CONFIG.blinkMinDelay + Math.random() * (CONFIG.blinkMaxDelay - CONFIG.blinkMinDelay);
      state.blinkTimer = setTimeout(function () {
        blink();
        scheduleBlink();
      }, delay);
    }

    // ===== Mouse takip =====
    function onMouseMove(e) {
      var rect = svgEl.getBoundingClientRect();
      if (rect.width === 0) return;

      var cx = rect.left + rect.width / 2;
      var cy = rect.top  + rect.height / 2;
      var dx = e.clientX - cx;
      var dy = e.clientY - cy;

      var angle = Math.atan2(dy, dx);
      var dist  = Math.sqrt(dx * dx + dy * dy);

      // Ekran uzaklığına göre normalize et
      var normalized = Math.min(CONFIG.pupilMaxTravel, dist / (rect.width * 0.3) * CONFIG.pupilMaxTravel);

      state.targetL.x = eyeCenter.L.x + Math.cos(angle) * normalized;
      state.targetL.y = eyeCenter.L.y + Math.sin(angle) * normalized;
      state.targetR.x = eyeCenter.R.x + Math.cos(angle) * normalized;
      state.targetR.y = eyeCenter.R.y + Math.sin(angle) * normalized;
    }

    // Touch desteği
    function onTouchMove(e) {
      if (e.touches.length > 0) {
        onMouseMove({ clientX: e.touches[0].clientX, clientY: e.touches[0].clientY });
      }
    }

    // ===== Scroll takip =====
    function onScroll() {
      var maxScroll = document.documentElement.scrollHeight - window.innerHeight;
      state.scrollProg = maxScroll > 0 ? Math.max(0, Math.min(1, window.scrollY / maxScroll)) : 0;
      updateSmile();
      updateBrows();
    }

    function updateSmile() {
      if (!parts.smile) return;
      var p = state.scrollProg;
      var bY  = CONFIG.smileBaseY + p * CONFIG.smileMaxY;
      var cY  = CONFIG.smileCtrlBase + p * CONFIG.smileCtrlMax;
      parts.smile.setAttribute('d', 'M 28 ' + bY.toFixed(1) + ' Q 50 ' + cY.toFixed(1) + ' 72 ' + bY.toFixed(1));

      if (parts.teeth) {
        var teethOp = Math.max(0, (p - 0.65) * 2.86);
        parts.teeth.setAttribute('opacity', teethOp.toFixed(2));
      }
    }

    function updateBrows() {
      if (!parts.leftBrow || !parts.rightBrow) return;
      var raise = state.scrollProg * CONFIG.browRaise;
      parts.leftBrow.setAttribute('d', 'M 24 ' + (24 - raise).toFixed(1) + ' Q 33 ' + (19 - raise).toFixed(1) + ' 42 ' + (24 - raise).toFixed(1));
      parts.rightBrow.setAttribute('d', 'M 58 ' + (24 - raise).toFixed(1) + ' Q 67 ' + (19 - raise).toFixed(1) + ' 76 ' + (24 - raise).toFixed(1));
    }

    // ===== Hover: yanak pembe =====
    function onMouseEnter() { state.cheekTarget = 1; }
    function onMouseLeave() { state.cheekTarget = 0; }

    // ===== Ana animasyon döngüsü (RAF) =====
    function animate() {
      var lf = CONFIG.pupilLerpFactor;

      // Gözbebeği lerp
      state.currentL.x += (state.targetL.x - state.currentL.x) * lf;
      state.currentL.y += (state.targetL.y - state.currentL.y) * lf;
      state.currentR.x += (state.targetR.x - state.currentR.x) * lf;
      state.currentR.y += (state.targetR.y - state.currentR.y) * lf;

      if (parts.leftPupil && parts.rightPupil) {
        parts.leftPupil.setAttribute('cx', state.currentL.x.toFixed(2));
        parts.leftPupil.setAttribute('cy', state.currentL.y.toFixed(2));
        parts.rightPupil.setAttribute('cx', state.currentR.x.toFixed(2));
        parts.rightPupil.setAttribute('cy', state.currentR.y.toFixed(2));
      }

      // Yanak lerp
      state.cheekOpacity += (state.cheekTarget - state.cheekOpacity) * CONFIG.cheekFadeSpeed;
      var co = state.cheekOpacity.toFixed(2);
      if (parts.leftCheek)  parts.leftCheek.setAttribute('opacity', co);
      if (parts.rightCheek) parts.rightCheek.setAttribute('opacity', co);

      state.animFrame = requestAnimationFrame(animate);
    }

    // ===== Olayları bağla =====
    document.addEventListener('mousemove', onMouseMove, { passive: true });
    document.addEventListener('touchmove', onTouchMove, { passive: true });
    window.addEventListener('scroll', onScroll, { passive: true });
    svgEl.addEventListener('mouseenter', onMouseEnter);
    svgEl.addEventListener('mouseleave', onMouseLeave);

    // Başlat
    onScroll();
    scheduleBlink();
    state.animFrame = requestAnimationFrame(animate);

    smileys.push({ el: svgEl, state: state });
  }

  // ===== Tüm smiley elemanlarını başlat =====
  function initAll() {
    var els = document.querySelectorAll('.bbm-smiley');
    if (els.length === 0) return;
    els.forEach(function (el) { initSmiley(el); });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }

  // Genel API
  window.BBMSmiley = { init: initAll, initEl: initSmiley, instances: smileys };

})();
