/* BiteBiMuv — particles.js v3.0 (Canvas-based GPU accelerated) */
(function () {
  'use strict';

  function initParticles(container) {
    if (!container) return;
    var canvas  = document.createElement('canvas');
    canvas.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;pointer-events:none;';
    container.style.position = container.style.position || 'relative';
    container.appendChild(canvas);

    var ctx = canvas.getContext('2d');
    var W, H, particles;

    var CONFIG = {
      count:     window.innerWidth < 768 ? 25 : 50,
      minSize:   1,
      maxSize:   3.5,
      minSpeed:  0.3,
      maxSpeed:  1.2,
      colors:    ['rgba(255,217,61,', 'rgba(232,67,90,', 'rgba(255,255,255,'],
      maxAlpha:  0.6,
      connect:   true,
      connectDist: 100,
    };

    function resize() {
      W = canvas.width  = container.offsetWidth;
      H = canvas.height = container.offsetHeight;
    }

    function createParticle() {
      var color = CONFIG.colors[Math.floor(Math.random() * CONFIG.colors.length)];
      return {
        x:  Math.random() * W,
        y:  Math.random() * H,
        vx: (Math.random() - 0.5) * CONFIG.maxSpeed,
        vy: -(Math.random() * (CONFIG.maxSpeed - CONFIG.minSpeed) + CONFIG.minSpeed),
        r:  Math.random() * (CONFIG.maxSize - CONFIG.minSize) + CONFIG.minSize,
        a:  Math.random() * CONFIG.maxAlpha,
        color: color,
      };
    }

    function init() {
      resize();
      particles = [];
      for (var i = 0; i < CONFIG.count; i++) particles.push(createParticle());
    }

    function draw() {
      ctx.clearRect(0, 0, W, H);

      particles.forEach(function(p) {
        p.x += p.vx;
        p.y += p.vy;
        if (p.y < -10)   p.y = H + 10;
        if (p.x < -10)   p.x = W + 10;
        if (p.x > W + 10) p.x = -10;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = p.color + p.a + ')';
        ctx.fill();
      });

      if (CONFIG.connect) {
        for (var i = 0; i < particles.length; i++) {
          for (var j = i + 1; j < particles.length; j++) {
            var dx   = particles[i].x - particles[j].x;
            var dy   = particles[i].y - particles[j].y;
            var dist = Math.sqrt(dx*dx + dy*dy);
            if (dist < CONFIG.connectDist) {
              ctx.beginPath();
              ctx.moveTo(particles[i].x, particles[i].y);
              ctx.lineTo(particles[j].x, particles[j].y);
              ctx.strokeStyle = 'rgba(255,255,255,' + (0.08 * (1 - dist / CONFIG.connectDist)) + ')';
              ctx.lineWidth = 0.5;
              ctx.stroke();
            }
          }
        }
      }

      requestAnimationFrame(draw);
    }

    var resizeObs = window.ResizeObserver
      ? new ResizeObserver(resize)
      : null;
    if (resizeObs) resizeObs.observe(container);
    else window.addEventListener('resize', resize);

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    init();
    draw();
  }

  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.bbm-hero__particles, [data-particles]').forEach(initParticles);
  });

  window.BBMParticles = { init: initParticles };
})();
