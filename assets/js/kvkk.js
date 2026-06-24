/* BiteBiMuv — kvkk.js (Çerez Banner) v3.0 */
(function () {
  'use strict';

  var COOKIE_KEY   = 'bbm_kvkk';
  var ACCEPTED_VAL = 'accepted';
  var DECLINED_VAL = 'declined';
  var BANNER_ID    = 'bbm-kvkk-banner';

  function getCookie(name) {
    var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
    return m ? decodeURIComponent(m[1]) : null;
  }

  function setCookie(name, value, days) {
    var d = new Date();
    d.setTime(d.getTime() + days * 86400000);
    document.cookie = name + '=' + encodeURIComponent(value)
      + '; expires=' + d.toUTCString()
      + '; path=/; SameSite=Lax';
  }

  function hideBanner(banner) {
    banner.classList.remove('is-visible');
    setTimeout(function() { banner.style.display = 'none'; }, 500);
  }

  function initBanner() {
    var banner = document.getElementById(BANNER_ID);
    if (!banner) return;

    // Already answered
    if (getCookie(COOKIE_KEY)) {
      banner.style.display = 'none';
      return;
    }

    // Show after 1.5s delay
    setTimeout(function() { banner.classList.add('is-visible'); }, 1500);

    var accept  = document.getElementById('bbm-kvkk-accept');
    var decline = document.getElementById('bbm-kvkk-decline');
    var close   = document.getElementById('bbm-kvkk-close');

    if (accept) {
      accept.addEventListener('click', function() {
        setCookie(COOKIE_KEY, ACCEPTED_VAL, 365);
        hideBanner(banner);
        // Fire consent event for analytics
        document.dispatchEvent(new CustomEvent('bbm:kvkk:accepted'));
      });
    }
    if (decline) {
      decline.addEventListener('click', function() {
        setCookie(COOKIE_KEY, DECLINED_VAL, 30);
        hideBanner(banner);
        document.dispatchEvent(new CustomEvent('bbm:kvkk:declined'));
      });
    }
    if (close) {
      close.addEventListener('click', function() {
        hideBanner(banner);
      });
    }
  }

  document.addEventListener('DOMContentLoaded', initBanner);
  window.BBMKVKK = { getCookie: function() { return getCookie(COOKIE_KEY); } };
})();
