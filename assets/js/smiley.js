/* BiteBiMuv Smiley Engine v3.0 */
(function () {
  'use strict';

  var CONFIG = {
    pupilMaxTravel: 5,
    pupilLerpFactor: 0.10,
    blinkMinDelay: 2000,
    blinkMaxDelay: 6000,
    blinkDuration: 100,
    smileBaseY: 68,
    smileMaxY: 10,
    smileCtrlBase: 78,
    smileCtrlMax: 20,
    browRaise: 7,
    cheekFadeSpeed: 0.05,
    teethFadeSpeed: 0.04,
  };

  var EMOTIONS = {
    happy:     { smileAmp: 0.5,  browRaise: 0,    cheek: 0.3, teeth: 0.5, pupilSize: 1.0 },
    excited:   { smileAmp: 1.0,  browRaise: -5,   cheek: 0.8, teeth: 1.0, pupilSize: 1.15, bounce: true },
    surprised: { smileAmp: 0.2,  browRaise: -10,  cheek: 0.0, teeth: 0.0, pupilSize: 1.3, wideEye: true },
    thinking:  { smileAmp: -0.2, browRaise: -4,   cheek: 0.0, teeth: 0.0, pupilSize: 0.9, tilt: true },
    sleeping:  { smileAmp: 0.1,  browRaise: 2,    cheek: 0.0, teeth: 0.0, pupilSize: 0.0, closed: true },
    sad:       { smileAmp: -1.0, browRaise: 4,    cheek: 0.0, teeth: 0.0, pupilSize: 0.85 },
    wink:      { smileAmp: 0.7,  browRaise: -3,   cheek: 0.5, teeth: 0.7, pupilSize: 1.0, wink: true },
  };

  function lerp(a, b, t) { return a + (b - a) * t; }
  function clamp(v, min, max) { return Math.min(max, Math.max(min, v)); }
  function rand(min, max) { return Math.random() * (max - min) + min; }

  function parseParts(svg, prefix) {
    var get = function(id) { return svg.getElementById(id) || svg.querySelector('#' + prefix + '-' + id.replace(prefix + '-', '')); };
    return {
      leftPupil:   svg.getElementById(prefix + '-left-pupil'),
      rightPupil:  svg.getElementById(prefix + '-right-pupil'),
      leftLid:     svg.getElementById(prefix + '-left-lid'),
      rightLid:    svg.getElementById(prefix + '-right-lid'),
      leftBrow:    svg.getElementById(prefix + '-left-brow'),
      rightBrow:   svg.getElementById(prefix + '-right-brow'),
      leftCheek:   svg.getElementById(prefix + '-left-cheek'),
      rightCheek:  svg.getElementById(prefix + '-right-cheek'),
      leftEye:     svg.getElementById(prefix + '-left-eye'),
      rightEye:    svg.getElementById(prefix + '-right-eye'),
      leftShine:   svg.getElementById(prefix + '-left-shine'),
      rightShine:  svg.getElementById(prefix + '-right-shine'),
      smile:       svg.getElementById(prefix + '-smile'),
      teeth:       svg.getElementById(prefix + '-teeth'),
    };
  }

  function initSmiley(svg) {
    var id     = svg.id || 'bbm';
    var parts  = parseParts(svg, id);
    if (!parts.smile) return;

    var rect    = svg.getBoundingClientRect();
    var cx      = rect.left + rect.width / 2;
    var cy      = rect.top  + rect.height / 2;

    // State
    var targetLX = 0, targetLY = 0, targetRX = 0, targetRY = 0;
    var curLX = 0, curLY = 0, curRX = 0, curRY = 0;
    var scrollRatio  = 0;
    var cheekOpacity = 0, teethOpacity = 0;
    var targetCheek  = 0, targetTeeth  = 0;
    var isBlinking   = false;
    var winkLeft     = false;
    var isHovering   = false;
    var currentEmotion = 'happy';
    var emotionTarget  = 0.5;
    var emotionCur     = 0.5;
    var browOffsetCur  = 0, browOffsetTarget = 0;
    var originalSmile  = parts.smile ? parts.smile.getAttribute('d') : '';
    var originalLBrow  = parts.leftBrow ? parts.leftBrow.getAttribute('d') : '';
    var originalRBrow  = parts.rightBrow ? parts.rightBrow.getAttribute('d') : '';

    // ---- Blinking ----
    function scheduleBlink() {
      var delay = rand(CONFIG.blinkMinDelay, CONFIG.blinkMaxDelay);
      setTimeout(function() {
        if (currentEmotion !== 'sleeping') doBlink();
        scheduleBlink();
      }, delay);
    }

    function doBlink(leftOnly) {
      if (isBlinking) return;
      isBlinking = true;
      var close = function(lid, eye, pupil) {
        if (!lid) return;
        lid.setAttribute('ry', '16');
        lid.setAttribute('cy', eye ? eye.getAttribute('cy') : '88');
        if (pupil) pupil.setAttribute('opacity', '0');
      };
      var open = function(lid, pupil) {
        if (!lid) return;
        lid.setAttribute('ry', '0');
        if (pupil) pupil.setAttribute('opacity', '1');
        isBlinking = false;
      };
      if (!leftOnly) close(parts.leftLid,  parts.leftEye,  parts.leftPupil);
      close(parts.rightLid, parts.rightEye, parts.rightPupil);
      setTimeout(function() {
        if (!leftOnly) open(parts.leftLid,  parts.leftPupil);
        open(parts.rightLid, parts.rightPupil);
      }, CONFIG.blinkDuration);
    }

    // ---- Smile path builder ----
    function buildSmile(amp) {
      var baseY  = 126 - amp * CONFIG.smileMaxY;
      var ctrlY  = 126 + amp * CONFIG.smileCtrlMax;
      if (amp < 0) {
        baseY = 130 - amp * 6;
        ctrlY = 120 + amp * 15;
      }
      return 'M72,' + baseY + ' Q100,' + ctrlY + ' 128,' + baseY;
    }

    // ---- Brow path builder ----
    function buildBrow(side, raise) {
      if (side === 'left')  return 'M58,' + (74 + raise) + ' Q72,' + (68 + raise) + ' 86,' + (72 + raise);
      return 'M114,' + (72 + raise) + ' Q128,' + (68 + raise) + ' 142,' + (74 + raise);
    }

    // ---- Scroll → emotion ----
    function onScroll() {
      var dh   = document.documentElement.scrollHeight - window.innerHeight;
      scrollRatio = dh > 0 ? Math.min(1, window.scrollY / dh) : 0;

      if (scrollRatio > 0.85)       setEmotion('excited');
      else if (scrollRatio > 0.55)  setEmotion('happy');
      else if (scrollRatio > 0.25)  setEmotion('thinking');
      else                          setEmotion('happy');
    }

    // ---- Set emotion ----
    function setEmotion(name) {
      if (currentEmotion === name) return;
      currentEmotion = name;
      var e = EMOTIONS[name];
      emotionTarget     = e.smileAmp;
      browOffsetTarget  = e.browRaise;
      targetCheek       = e.cheek;
      targetTeeth       = e.teeth;
      if (e.wink) { winkLeft = true; setTimeout(function(){ winkLeft=false; }, 800); }
      if (e.surprised) doBlink();
    }

    // ---- Mouse tracking ----
    function onMouseMove(e) {
      rect = svg.getBoundingClientRect();
      cx   = rect.left + rect.width / 2;
      cy   = rect.top  + rect.height / 2;
      var dx = (e.clientX - cx) / (rect.width  * 0.5);
      var dy = (e.clientY - cy) / (rect.height * 0.5);
      dx = clamp(dx, -1, 1);
      dy = clamp(dy, -1, 1);
      targetLX = dx * CONFIG.pupilMaxTravel;
      targetLY = dy * CONFIG.pupilMaxTravel;
      targetRX = dx * CONFIG.pupilMaxTravel;
      targetRY = dy * CONFIG.pupilMaxTravel;
    }

    // ---- Hover ----
    svg.addEventListener('mouseenter', function() {
      isHovering = true;
      setEmotion('excited');
    });
    svg.addEventListener('mouseleave', function() {
      isHovering = false;
      setEmotion('happy');
    });

    window.addEventListener('mousemove', onMouseMove, { passive: true });
    window.addEventListener('scroll',    onScroll,    { passive: true });
    scheduleBlink();
    onScroll();

    // ---- RAF loop ----
    function tick() {
      curLX = lerp(curLX, targetLX, CONFIG.pupilLerpFactor);
      curLY = lerp(curLY, targetLY, CONFIG.pupilLerpFactor);
      curRX = lerp(curRX, targetRX, CONFIG.pupilLerpFactor);
      curRY = lerp(curRY, targetRY, CONFIG.pupilLerpFactor);
      emotionCur    = lerp(emotionCur,    emotionTarget,   0.04);
      browOffsetCur = lerp(browOffsetCur, browOffsetTarget, 0.06);
      cheekOpacity  = lerp(cheekOpacity,  targetCheek,     CONFIG.cheekFadeSpeed);
      teethOpacity  = lerp(teethOpacity,  targetTeeth,     CONFIG.teethFadeSpeed);

      // Pupils
      if (parts.leftPupil) {
        parts.leftPupil.setAttribute('cx',  72  + curLX);
        parts.leftPupil.setAttribute('cy',  90  + curLY);
      }
      if (parts.rightPupil) {
        parts.rightPupil.setAttribute('cx', 128 + curRX);
        parts.rightPupil.setAttribute('cy', 90  + curRY);
      }
      // Shines follow pupils
      if (parts.leftShine) {
        parts.leftShine.setAttribute('transform', 'translate(' + (69 + curLX) + ',' + (87 + curLY) + ')');
      }
      if (parts.rightShine) {
        parts.rightShine.setAttribute('transform', 'translate(' + (125 + curRX) + ',' + (87 + curRY) + ')');
      }
      // Smile
      if (parts.smile) parts.smile.setAttribute('d', buildSmile(emotionCur));
      // Teeth
      if (parts.teeth) parts.teeth.setAttribute('opacity', Math.max(0, Math.min(1, teethOpacity)));
      // Cheeks
      if (parts.leftCheek)  parts.leftCheek.setAttribute('opacity',  Math.max(0, Math.min(1, cheekOpacity)));
      if (parts.rightCheek) parts.rightCheek.setAttribute('opacity', Math.max(0, Math.min(1, cheekOpacity)));
      // Brows
      if (parts.leftBrow)  parts.leftBrow.setAttribute('d',  buildBrow('left',  browOffsetCur));
      if (parts.rightBrow) parts.rightBrow.setAttribute('d', buildBrow('right', browOffsetCur));
      // Wink
      if (winkLeft && parts.leftLid && !isBlinking) {
        parts.leftLid.setAttribute('ry', '16');
        if (parts.leftPupil) parts.leftPupil.setAttribute('opacity', '0');
      } else if (!isBlinking && parts.leftLid) {
        parts.leftLid.setAttribute('ry', '0');
        if (parts.leftPupil) parts.leftPupil.setAttribute('opacity', '1');
      }

      requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);

    // Public API on the svg element
    svg._bbmSetEmotion = setEmotion;
    svg._bbmCheer     = function() { setEmotion('excited'); };
    svg._bbmSad       = function() { setEmotion('sad'); };
  }

  function initAll() {
    document.querySelectorAll('.bbm-smiley').forEach(function(el) {
      initSmiley(el);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }

  window.BBMSmiley = {
    init:       initAll,
    initOne:    initSmiley,
    emotions:   Object.keys(EMOTIONS),
    setEmotion: function(name) {
      document.querySelectorAll('.bbm-smiley').forEach(function(el) {
        if (el._bbmSetEmotion) el._bbmSetEmotion(name);
      });
    },
    cheer: function() { window.BBMSmiley.setEmotion('excited'); },
    sad:   function() { window.BBMSmiley.setEmotion('sad'); },
  };
})();
