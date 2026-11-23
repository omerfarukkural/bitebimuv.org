/**
 * BBM Events Calendar – Aylık Grid Takvim
 * Bite Bi Muv Derneği Teması v4.0
 */
;(function () {
    'use strict';

    const TR_MONTHS = [
        'Ocak','Şubat','Mart','Nisan','Mayıs','Haziran',
        'Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'
    ];
    const TR_DAYS_SHORT = ['Pzt','Sal','Çar','Per','Cum','Cmt','Paz'];

    let currentYear, currentMonth;
    let allEvents = [];

    // ── Init ──────────────────────────────────────────────────────────────────

    function init() {
        const dataEl = document.getElementById('bbm-events-data');
        if (!dataEl) return;

        try {
            allEvents = JSON.parse(dataEl.textContent || '[]');
        } catch(e) {
            allEvents = [];
        }

        const now = new Date();
        currentYear  = now.getFullYear();
        currentMonth = now.getMonth(); // 0-indexed

        // View toggle
        document.querySelectorAll('.bbm-view-btn').forEach(btn => {
            btn.addEventListener('click', () => switchView(btn.dataset.view));
        });

        // Filter tabs
        document.querySelectorAll('.bbm-filter-tab').forEach(tab => {
            tab.addEventListener('click', () => filterEvents(tab.dataset.filter));
        });

        // Category filter
        const catFilter = document.getElementById('bbm-event-cat-filter');
        if (catFilter) catFilter.addEventListener('change', filterByCategory);

        // Calendar navigation
        const prevBtn = document.getElementById('bbm-cal-prev');
        const nextBtn = document.getElementById('bbm-cal-next');
        if (prevBtn) prevBtn.addEventListener('click', () => { shiftMonth(-1); renderCalendar(); });
        if (nextBtn) nextBtn.addEventListener('click', () => { shiftMonth(1);  renderCalendar(); });

        renderCalendar();
    }

    // ── View Switching ─────────────────────────────────────────────────────────

    function switchView(view) {
        document.querySelectorAll('.bbm-view-btn').forEach(b => b.classList.remove('active'));
        document.querySelector(`.bbm-view-btn[data-view="${view}"]`)?.classList.add('active');

        const grid = document.getElementById('bbm-events-grid');
        const cal  = document.getElementById('bbm-events-calendar');

        if (view === 'calendar') {
            if (grid) grid.hidden = true;
            if (cal)  { cal.hidden = false; renderCalendar(); }
        } else {
            if (grid) { grid.hidden = false; applyViewClass(grid, view); }
            if (cal)  cal.hidden = true;
        }
    }

    function applyViewClass(container, view) {
        container.classList.remove('is-list-view', 'is-grid-view');
        container.classList.add(view === 'list' ? 'is-list-view' : 'is-grid-view');
    }

    // ── Filter Events ─────────────────────────────────────────────────────────

    function filterEvents(filter) {
        document.querySelectorAll('.bbm-filter-tab').forEach(t => t.classList.remove('active'));
        document.querySelector(`.bbm-filter-tab[data-filter="${filter}"]`)?.classList.add('active');

        const cards = document.querySelectorAll('.bbm-event-card[data-status]');
        cards.forEach(card => {
            const status = card.dataset.status;
            const show = !filter || filter === 'all' ||
                (filter === 'upcoming' && status === 'upcoming') ||
                (filter === 'past' && status === 'past');
            card.style.display = show ? '' : 'none';
        });
    }

    function filterByCategory() {
        const val = this.value;
        // For category filtering we would need data-category on cards
        // This is a progressive enhancement
        const cards = document.querySelectorAll('.bbm-event-card');
        cards.forEach(card => {
            if (!val) {
                card.style.display = '';
                return;
            }
            const cats = card.dataset.categories || '';
            card.style.display = cats.includes(val) ? '' : 'none';
        });
    }

    // ── Calendar Render ───────────────────────────────────────────────────────

    function shiftMonth(delta) {
        currentMonth += delta;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        if (currentMonth < 0)  { currentMonth = 11; currentYear--; }
    }

    function renderCalendar() {
        const titleEl = document.getElementById('bbm-cal-title');
        const gridEl  = document.getElementById('bbm-cal-grid');
        if (!titleEl || !gridEl) return;

        titleEl.textContent = TR_MONTHS[currentMonth] + ' ' + currentYear;

        // Build grid HTML
        const today      = new Date();
        const firstDay   = new Date(currentYear, currentMonth, 1);
        const lastDay    = new Date(currentYear, currentMonth + 1, 0);
        const startDow   = (firstDay.getDay() + 6) % 7; // Monday=0
        const totalDays  = lastDay.getDate();

        // Events in this month
        const monthStr = String(currentMonth + 1).padStart(2, '0');
        const prefix   = `${currentYear}-${monthStr}-`;
        const monthEvents = {};
        allEvents.forEach(ev => {
            if (ev.date && ev.date.startsWith(prefix)) {
                const day = parseInt(ev.date.slice(8, 10), 10);
                if (!monthEvents[day]) monthEvents[day] = [];
                monthEvents[day].push(ev);
            }
        });

        let html = '<div class="bbm-cal-row bbm-cal-header" role="row">';
        TR_DAYS_SHORT.forEach(d => {
            html += `<div class="bbm-cal-cell bbm-cal-cell--header" role="columnheader" aria-label="${d}">${d}</div>`;
        });
        html += '</div>';

        html += '<div class="bbm-cal-row" role="row">';
        // Empty cells before first day
        for (let i = 0; i < startDow; i++) {
            html += '<div class="bbm-cal-cell bbm-cal-cell--empty" role="gridcell" aria-hidden="true"></div>';
        }

        for (let d = 1; d <= totalDays; d++) {
            const isToday = (d === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear());
            const evs     = monthEvents[d] || [];
            const col     = (startDow + d - 1) % 7;
            const hasEvs  = evs.length > 0;

            let cellClasses = 'bbm-cal-cell';
            if (isToday) cellClasses += ' bbm-cal-cell--today';
            if (hasEvs)  cellClasses += ' bbm-cal-cell--has-event';

            html += `<div class="${cellClasses}" role="gridcell" aria-label="${d} ${TR_MONTHS[currentMonth]}"${hasEvs ? '' : ''}>`;
            html += `<span class="bbm-cal-day-num">${d}</span>`;

            if (hasEvs) {
                html += `<div class="bbm-cal-events-list">`;
                evs.slice(0, 2).forEach(ev => {
                    const typeClass = ev.type === 'online' ? 'bbm-cal-ev--online' : '';
                    html += `<a href="${escapeHtml(ev.url)}" class="bbm-cal-event ${typeClass}" title="${escapeHtml(ev.title)}">${escapeHtml(ev.title)}</a>`;
                });
                if (evs.length > 2) {
                    html += `<span class="bbm-cal-more">+${evs.length - 2} daha</span>`;
                }
                html += `</div>`;
            }

            html += `</div>`;

            // New row after Saturday
            if ((startDow + d) % 7 === 0 && d < totalDays) {
                html += '</div><div class="bbm-cal-row" role="row">';
            }
        }

        html += '</div>';
        gridEl.innerHTML = html;
    }

    // ── Utils ─────────────────────────────────────────────────────────────────

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;')
            .replace(/'/g,'&#039;');
    }

    // ── Boot ──────────────────────────────────────────────────────────────────

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.BBMCalendar = { render: renderCalendar, switchView };

})();
