/* BiteBiMuv — dark-mode.js v3.0 */
(function () {
  'use strict';

  var COOKIE_KEY = 'bbm_dark_mode';
  var CLASS      = 'bbm-dark';
  var TRANS_CLASS= 'bbm-dark-transition';

  function getCookie(name) {
    var match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
  }

  function setCookie(name, value, days) {
    var expires = '';
    if (days) {
      var d = new Date();
      d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
      expires = '; expires=' + d.toUTCString();
    }
    document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/; SameSite=Lax';
  }

  function isDark() {
    var cookie = getCookie(COOKIE_KEY);
    if (cookie !== null) return cookie === '1';
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  function applyMode(dark, animate) {
    if (animate) {
      document.body.classList.add(TRANS_CLASS);
      setTimeout(function() { document.body.classList.remove(TRANS_CLASS); }, 400);
    }
    if (dark) {
      document.documentElement.classList.add(CLASS);
      document.body.classList.add(CLASS);
    } else {
      document.documentElement.classList.remove(CLASS);
      document.body.classList.remove(CLASS);
    }
    updateToggleIcon(dark);
    setCookie(COOKIE_KEY, dark ? '1' : '0', 365);
  }

  function updateToggleIcon(dark) {
    var btn = document.getElementById('bbm-darkmode-toggle');
    if (!btn) return;
    var sun  = btn.querySelector('.bbm-darkmode-icon-sun');
    var moon = btn.querySelector('.bbm-darkmode-icon-moon');
    var label = dark ? (window.BBM && window.BBM.i18n ? BBM.i18n.lightMode : 'Aydınlık Mod') : (window.BBM && window.BBM.i18n ? BBM.i18n.darkMode : 'Karanlık Mod');
    btn.setAttribute('aria-label', label);
    btn.setAttribute('title', label);
  }

  function toggleDarkMode() {
    applyMode(!isDark(), true);
  }

  // Apply on page load without animation
  applyMode(isDark(), false);

  document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('bbm-darkmode-toggle');
    if (btn) btn.addEventListener('click', toggleDarkMode);

    // System preference change
    if (window.matchMedia) {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        if (getCookie(COOKIE_KEY) === null) applyMode(e.matches, true);
      });
    }
  });

  window.BBMDarkMode = { toggle: toggleDarkMode, isDark: isDark, apply: applyMode };
})();
