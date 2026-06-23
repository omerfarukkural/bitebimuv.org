/**
 * BBM Leaflet Map – Dernek Konum Haritası
 * Bite Bi Muv Derneği Teması v4.0
 */
;(function () {
    'use strict';

    function initMap() {
        const container = document.getElementById('bbm-leaflet-map');
        if (!container) return;

        const lat   = parseFloat(container.dataset.lat)   || 41.0082;
        const lng   = parseFloat(container.dataset.lng)   || 28.9784;
        const title = container.dataset.title || 'Dernek';

        if (typeof L === 'undefined') {
            console.warn('[BBM Map] Leaflet not loaded');
            return;
        }

        // Prevent double init
        if (container._bbmMapInit) return;
        container._bbmMapInit = true;

        const map = L.map(container, {
            center:          [lat, lng],
            zoom:            15,
            scrollWheelZoom: false,
            attributionControl: true,
        });

        // OpenStreetMap tile layer (no API key required)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);

        // Custom marker icon
        const markerIcon = L.divIcon({
            className: 'bbm-map-marker',
            html: `<div class="bbm-map-marker-inner">
                       <svg width="32" height="40" viewBox="0 0 32 40" fill="none">
                           <path d="M16 0C7.163 0 0 7.163 0 16c0 10 16 24 16 24s16-14 16-24C32 7.163 24.837 0 16 0z" fill="#6366f1"/>
                           <circle cx="16" cy="16" r="8" fill="white"/>
                           <circle cx="16" cy="16" r="5" fill="#6366f1"/>
                       </svg>
                   </div>`,
            iconSize:   [32, 40],
            iconAnchor: [16, 40],
            popupAnchor:[0, -40],
        });

        const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
        marker.bindPopup(`
            <div style="font-family: -apple-system, sans-serif; min-width: 160px; text-align: center;">
                <strong style="font-size:14px; color:#1f2937">${escapeHtml(title)}</strong>
            </div>
        `, { offset: [0, -5] });

        // Open popup on load
        setTimeout(() => marker.openPopup(), 400);

        // Fix map size after CSS transitions
        setTimeout(() => map.invalidateSize(), 500);

        // Enable scroll wheel on click
        map.on('click', () => map.scrollWheelZoom.enable());
        map.on('mouseout', () => map.scrollWheelZoom.disable());
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Lazy init via IntersectionObserver for performance
    function lazyInitMap() {
        const container = document.getElementById('bbm-leaflet-map');
        if (!container) return;

        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        initMap();
                        obs.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '200px' });
            obs.observe(container);
        } else {
            initMap();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', lazyInitMap);
    } else {
        lazyInitMap();
    }

    window.BBMMap = { init: initMap };

})();
