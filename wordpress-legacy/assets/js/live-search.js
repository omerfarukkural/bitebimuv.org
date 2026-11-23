/* BiteBiMuv — live-search.js v3.0 */
(function () {
  'use strict';

  var TYPE_ICONS = {
    'Haber':    '📰',
    'Sayfa':    '📄',
    'Etkinlik': '📅',
    'Duyuru':   '📢',
    'Proje':    '🚀',
    'Galeri':   '🖼️',
  };

  var debounceTimer = null;
  var currentXhr    = null;

  function debounce(fn, delay) {
    return function() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(fn, delay);
    };
  }

  function escapeHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function renderResults(results, container, input) {
    if (!results.length) {
      container.innerHTML = '<div class="bbm-live-search-empty" style="padding:1.5rem;text-align:center;color:var(--bbm-gray-400);font-size:.85rem;">' +
        (window.BBM && BBM.i18n ? BBM.i18n.noResults : 'Sonuç bulunamadı.') + '</div>';
      container.classList.add('has-results');
      return;
    }
    var html = '';
    results.forEach(function(r) {
      var icon  = TYPE_ICONS[r.type] || '📌';
      var thumb = r.thumb
        ? '<img class="bbm-live-search-result__thumb" src="' + escapeHtml(r.thumb) + '" alt="" loading="lazy">'
        : '<div class="bbm-live-search-result__thumb-placeholder">' + icon + '</div>';
      html += '<a href="' + escapeHtml(r.url) + '" class="bbm-live-search-result">' +
        thumb +
        '<div class="bbm-live-search-result__info">' +
          '<div class="bbm-live-search-result__title">' + escapeHtml(r.title) + '</div>' +
          (r.excerpt ? '<div class="bbm-live-search-result__excerpt">' + escapeHtml(r.excerpt) + '</div>' : '') +
        '</div>' +
        '<span class="bbm-live-search-result__type">' + escapeHtml(r.type) + '</span>' +
        '</a>';
    });
    container.innerHTML = html;
    container.classList.add('has-results');
  }

  function doSearch(input, container) {
    var term = input.value.trim();
    if (term.length < 2) {
      container.classList.remove('has-results');
      container.innerHTML = '';
      return;
    }

    if (currentXhr) currentXhr.abort();

    var xhr = new XMLHttpRequest();
    currentXhr = xhr;
    var data = 'action=bbm_live_search&term=' + encodeURIComponent(term) +
      '&nonce=' + (window.BBM ? BBM.nonce : '');

    xhr.open('POST', window.BBM ? BBM.ajaxUrl : '/wp-admin/admin-ajax.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status !== 200) return;
      try {
        var resp = JSON.parse(xhr.responseText);
        if (resp.success) renderResults(resp.data, container, input);
      } catch(e) {}
    };
    xhr.send(data);
  }

  function initLiveSearch() {
    var input     = document.querySelector('.bbm-live-search-input');
    var container = document.querySelector('.bbm-live-search-results');
    if (!input || !container) return;

    var search = debounce(function() { doSearch(input, container); }, 280);
    input.addEventListener('input', search);

    // Close on click outside
    document.addEventListener('click', function(e) {
      if (!input.contains(e.target) && !container.contains(e.target)) {
        container.classList.remove('has-results');
      }
    });

    // Keyboard navigation
    input.addEventListener('keydown', function(e) {
      var items = container.querySelectorAll('.bbm-live-search-result');
      if (!items.length) return;
      var active = container.querySelector('.bbm-live-search-result:focus');
      var idx    = Array.prototype.indexOf.call(items, active);
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        var next = items[idx + 1] || items[0];
        next.focus();
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        var prev = items[idx - 1] || items[items.length - 1];
        prev.focus();
      } else if (e.key === 'Escape') {
        container.classList.remove('has-results');
        input.blur();
      }
    });
  }

  document.addEventListener('DOMContentLoaded', initLiveSearch);
})();
