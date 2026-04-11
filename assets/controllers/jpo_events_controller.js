import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        url: String,
        initialQuery: String,
        initialStatus: String,
        initialSortBy: String,
        initialSortDir: String,
        initialHistory: String,
        initialMine: String
    };

    connect() {
        this.FALLBACK_ICONS = ['ph-graduation-cap', 'ph-buildings', 'ph-laptop', 'ph-flask', 'ph-bank', 'ph-palette', 'ph-first-aid', 'ph-triangle', 'ph-microscope', 'ph-rocket'];
        this.MONTHS_FR = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

        this.searchInput = document.getElementById('searchInput');
        this.searchBtn = document.getElementById('searchBtn');
        this.filterStatus = document.getElementById('filterStatus');
        this.sortBtns = document.querySelectorAll('.sort-btn');
        this.filterHistoryBtns = document.querySelectorAll('.toggle-history');
        this.toggleMineBtn = document.getElementById('toggleMine');

        this.fetchTimeout = null;

        // Default states
        this.sortBy = this.initialSortByValue || 'dateEvenement';
        this.sortDir = this.initialSortDirValue || 'ASC';
        this.history = this.initialHistoryValue === '1';
        this.mine = this.initialMineValue === '1';

        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => { setTimeout(() => this.fetchEvents(), 300); });
            if (this.searchBtn) {
                this.searchBtn.addEventListener('click', () => { clearTimeout(this.fetchTimeout); this.fetchEvents(); });
            }
            this.filterStatus.addEventListener('change', () => this.fetchEvents());
            this.filterHistoryBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    this.history = btn.getAttribute('data-history') === '1';
                    if (this.history && this.mine === null) {
                        this.mine = true;
                        if (this.toggleMineBtn) this.toggleMineBtn.classList.add('active');
                    }
                    this.filterHistoryBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    this.fetchEvents();
                });
            });
            if (this.toggleMineBtn) {
                this.toggleMineBtn.addEventListener('click', () => {
                    this.mine = !this.mine;
                    if (this.mine) {
                        this.toggleMineBtn.classList.add('active');
                    } else {
                        this.toggleMineBtn.classList.remove('active');
                    }
                    this.fetchEvents();
                });
            }
            this.filterStatus.addEventListener('change', () => this.fetchEvents());
            this.sortBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const field = btn.getAttribute('data-sort');
                    if (this.sortBy === field) this.sortDir = this.sortDir === 'ASC' ? 'DESC' : 'ASC';
                    else { this.sortBy = field; this.sortDir = 'ASC'; }
                    this.updateSortButtons();
                    this.fetchEvents();
                });
            });
        }
    }

    updateSortButtons() {
        this.sortBtns.forEach(b => {
            b.classList.remove('active');
            let lbl = '';
            if (b.getAttribute('data-sort') === 'dateEvenement') lbl = '<i class="ph ph-calendar-blank"></i> Date ';
            else if (b.getAttribute('data-sort') === 'titre') lbl = '<i class="ph ph-text-aa"></i> Titre ';
            else if (b.getAttribute('data-sort') === 'participants') lbl = '<i class="ph ph-users"></i> Participants ';

            if (b.getAttribute('data-sort') === this.sortBy) {
                b.classList.add('active');
                b.innerHTML = lbl + (this.sortDir === 'ASC' ? '↑' : '↓');
            } else {
                b.innerHTML = lbl;
            }
        });
    }

    formatDate(str) {
        if (!str) return '';
        let dte;
        if (str.date) {
            dte = new Date(str.date.replace(' ', 'T'));
        } else {
            dte = new Date(str);
        }
        const d = String(dte.getDate()).padStart(2, '0');
        const y = dte.getFullYear();
        return `${d} ${this.MONTHS_FR[dte.getMonth()]} ${y}`;
    }

    fetchEvents() {
        clearTimeout(this.fetchTimeout);
        const q = this.searchInput.value;
        const st = this.filterStatus.value;
        document.getElementById('eventsGrid').style.opacity = '0.5';
        const spinner = document.getElementById('spinner');
        if (spinner) spinner.classList.add('visible');

        const params = new URLSearchParams({ q: q, history: this.history ? 1 : 0, status: st, sort: this.sortBy, dir: this.sortDir, mine: this.mine ? 1 : 0 });
        fetch(this.urlValue + '?' + params.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(events => this.renderGrid(events))
            .catch(err => {
                console.error('Fetch error:', err);
                const sp = document.getElementById('spinner');
                if (sp) sp.classList.remove('visible');
                document.getElementById('eventsGrid').style.opacity = '1';
            });
    }

    renderGrid(events) {
        let spinner = document.getElementById('spinner');
        if (spinner) spinner.classList.remove('visible');
        const grid = document.getElementById('eventsGrid');
        grid.style.opacity = '1';
        const spinnerHtml = '<div class="spinner" id="spinner"><div class="spin"></div></div>';
        grid.innerHTML = '';

        if (!events.length) {
            grid.innerHTML = '<div class="no-results"><span><i class="ph ph-magnifying-glass"></i></span>Aucun événement trouvé</div>' + spinnerHtml;
            return;
        }

        events.forEach((ev, i) => {
            const fallback = `<i class="ph ${this.FALLBACK_ICONS[i % this.FALLBACK_ICONS.length]}"></i>`;
            const img = ev.image_path ? `<img src="${ev.image_path}" alt="${ev.titre}" onerror="this.onerror=null; this.outerHTML='${fallback.replace(/'/g, "\\'").replace(/"/g, "&quot;")}';">` : fallback;
            let badgeClass = ev.is_full ? 'full-badge' : 'open-badge';
            let badgeText = ev.is_full ? 'COMPLET' : 'OUVERT';
            if (ev.is_past) {
                badgeClass = 'past-badge';
                badgeText = 'TERMINÉ';
            }
            const badge = `<div class="${badgeClass}">${badgeText}</div>`;


            const card = document.createElement('a');
            card.className = `event-list-item ${ev.is_registered ? 'registered-highlight' : ''}`;
            card.href = ev.details_url;

            card.innerHTML = `
                <div class="list-img">${img}</div>
                <div class="list-content">
                    <div class="list-info-main">
                        <div class="list-date">${this.formatDate(ev.date_evenement)}</div>
                        <div class="list-title">${ev.titre}</div>
                    </div>
                    <div class="list-info-meta">
                        ${ev.lieu ? `<div class="list-location"><i class="ph ph-map-pin"></i> ${ev.lieu}</div>` : ''}
                        ${ev.description ? `<div class="list-desc">${ev.description}</div>` : ''}
                    </div>
                    <div class="list-stats">
                        <div class="list-badge-wrap">
                            ${badge}
                        </div>
                        <div class="list-capacity">
                            Participants: <span>${ev.current_participants}/${ev.max_participants}</span>
                        </div>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });
        grid.insertAdjacentHTML('beforeend', '<div class="spinner" id="spinner"><div class="spin"></div></div>');
    }
}
