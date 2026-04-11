import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        data: Object,
        open: Boolean,
        url: String,
        isProprietaire: Boolean,
        createUrl: String,
        dayEventsUrl: String,
        currentUserId: Number,
        editUrl: String,
        deleteUrl: String,
    };

    connect() {
        this.CALENDAR_DATA = this.dataValue || {};
        const OPEN_CAL = this.openValue || false;
        const IS_PROPRIETAIRE = this.isProprietaireValue || false;
        const CREATE_URL = this.createUrlValue || '/jpo/create-event';
        const DAY_EVENTS_URL = this.dayEventsUrlValue || '/jpo/events-on-day';
        const CURRENT_USER_ID = this.currentUserIdValue;
        const EDIT_URL_BASE = this.editUrlValue || '/jpo/edit-event/';
        const DELETE_URL_BASE = this.deleteUrlValue || '/jpo/delete-event/';

        this.MONTHS_FR = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        this.DAYS_FR = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        this.calDate = new Date();
        this.currentView = 'month';
        this._clickedDate = null;
        this._deleteId = null;
        this._isForcedDelete = false;

        this.toKey = (d) => d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');

        // ── POPUP HELPERS ──────────────────────────────────────────────────────
        this.dayPopup = document.getElementById('dayEventsPopup');
        this.createPopup = document.getElementById('createEventPopup');
        this.editPopup = document.getElementById('editEventPopup');
        this.detailsPopup = document.getElementById('eventDetailsPopup');
        this.deleteConfirmModal = document.getElementById('deleteConfirmModal');

        this.closeAll = () => {
            this.dayPopup?.classList.remove('popup-open');
            this.createPopup?.classList.remove('popup-open');
            this.editPopup?.classList.remove('popup-open');
            this.detailsPopup?.classList.remove('popup-open');
            this.deleteConfirmModal?.classList.remove('popup-open');
            this._isForcedDelete = false;
            this._clickedDate = null;

            // Reset delete modal state
            const title = this.deleteConfirmModal?.querySelector('.confirm-title');
            const text = this.deleteConfirmModal?.querySelector('.confirm-text');
            const btn = document.getElementById('confirmDeleteBtn');
            if (title) title.textContent = "Supprimer l'événement ?";
            if (text) text.textContent = "Attention : Cette action est irréversible. Voulez-vous vraiment supprimer cet événement ?";
            if (btn) btn.textContent = "OUI, SUPPRIMER";
        };

        this.openDayPopup = (dateKey, events) => {
            this._clickedDate = dateKey;
            const [y, m, d] = dateKey.split('-').map(Number);
            const label = `${d} ${this.MONTHS_FR[m - 1]} ${y}`;
            const titleEl = document.getElementById('dayPopupTitle');
            if (titleEl) titleEl.textContent = label;

            const list = document.getElementById('dayEventsList');
            if (!list) return;

            list.innerHTML = '';
            if (events.length === 0) {
                list.innerHTML = '<p class="popup-empty">Aucun événement ce jour.</p>';
            } else {
                events.forEach(ev => {
                    const item = document.createElement('div');
                    item.className = 'day-event-item';

                    const isOwner = (Number(ev.id_createur) === Number(CURRENT_USER_ID));

                    let actionsHtml = '';
                    if (isOwner) {
                        actionsHtml = `
                            <div class="dei-actions">
                                <button class="dei-btn dei-btn--edit" title="Modifier">
                                    <i class="ph ph-pencil-simple"></i>
                                </button>
                                <button class="dei-btn dei-btn--delete" title="Supprimer">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </div>
                        `;
                    }

                    item.innerHTML = `
                        <span class="event-dot ${isOwner ? 'event-dot--own' : ''}"></span>
                        <div class="dei-content" style="cursor: pointer; flex: 1;">
                            <div class="dei-title" style="font-weight: 700; color: var(--text);">${ev.titre}</div>
                            <div class="dei-date" style="font-size: 11px; color: var(--text-dim);"><i class="ph ph-users"></i> Max: ${ev.max_participants}</div>
                        </div>
                        ${actionsHtml}
                    `;
                    list.appendChild(item);

                    // Click on content shows details
                    item.querySelector('.dei-content')?.addEventListener('click', () => {
                        this.openDetailsPopup(ev);
                    });

                    if (isOwner) {
                        item.querySelector('.dei-btn--edit')?.addEventListener('click', () => {
                            this.openEditPopup(ev);
                        });
                        item.querySelector('.dei-btn--delete')?.addEventListener('click', () => {
                            this.handleDelete(ev.id);
                        });
                    }
                });
            }
            this.dayPopup.classList.add('popup-open');
        };

        this.openCreatePopup = (dateKey, fromDayPopup = false) => {
            this._clickedDate = dateKey || this._clickedDate;
            const [y, m, d] = (this._clickedDate || this.toKey(new Date())).split('-').map(Number);
            const label = `${d} ${this.MONTHS_FR[m - 1]} ${y}`;
            document.getElementById('createPopupDateLabel').textContent = label;

            const form = document.getElementById('createEventForm');
            form.reset();
            document.getElementById('createEventDate').value = this._clickedDate;

            document.getElementById('createEventError').textContent = '';
            document.getElementById('createEventSuccess').textContent = '';
            document.getElementById('fileNameDisplay').textContent = 'Choisir une image…';

            const backBtn = document.getElementById('createPopupBack');
            if (backBtn) backBtn.style.display = fromDayPopup ? '' : 'none';

            this.createPopup.classList.add('popup-open');
        };

        this.openEditPopup = (eventData) => {
            const [y, m, d] = this._clickedDate.split('-').map(Number);
            const label = `${d} ${this.MONTHS_FR[m - 1]} ${y}`;
            document.getElementById('editPopupDateLabel').textContent = label;

            document.getElementById('editEventId').value = eventData.id;
            document.getElementById('editTitre').value = eventData.titre;
            document.getElementById('editLieu').value = eventData.lieu || '';
            document.getElementById('editDesc').value = eventData.description || '';
            document.getElementById('editMaxPart').value = eventData.max_participants;

            document.getElementById('editEventError').textContent = '';
            document.getElementById('editEventSuccess').textContent = '';
            document.getElementById('editFileNameDisplay').textContent = 'Remplacer l\'image…';

            this.dayPopup.classList.remove('popup-open');
            this.editPopup.classList.add('popup-open');
        };

        this.openDetailsPopup = (ev) => {
            const [y, m, d] = this._clickedDate.split('-').map(Number);
            const label = `${d} ${this.MONTHS_FR[m - 1]} ${y}`;
            document.getElementById('detailsPopupDateLabel').textContent = label;

            document.getElementById('detailsPopupTitle').textContent = ev.titre;
            document.getElementById('detailsPopupLieu').textContent = ev.lieu || 'Lieu non spécifié';
            document.getElementById('detailsPopupParticipants').textContent = `Max: ${ev.max_participants}`;
            document.getElementById('detailsPopupDesc').textContent = ev.description || 'Aucune description fournie.';

            const imgContainer = document.getElementById('detailsPopupImage');
            if (ev.image_path) {
                imgContainer.innerHTML = `<img src="${ev.image_path}" alt="${ev.titre}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;">`;
                imgContainer.style.display = 'block';
            } else {
                imgContainer.style.display = 'none';
            }

            this.dayPopup.classList.remove('popup-open');
            this.detailsPopup.classList.add('popup-open');
        };

        this.handleDelete = (id) => {
            this._deleteId = id;
            this._isForcedDelete = false;
            this.dayPopup.classList.remove('popup-open');
            this.deleteConfirmModal.classList.add('popup-open');
        };

        this.confirmDeleteAction = async () => {
            if (!this._deleteId) return;
            const btn = document.getElementById('confirmDeleteBtn');
            const titleEl = this.deleteConfirmModal.querySelector('.confirm-title');
            const textEl = this.deleteConfirmModal.querySelector('.confirm-text');

            btn.disabled = true;
            btn.textContent = 'CHARGEMENT...';

            try {
                const formData = new FormData();
                if (this._isForcedDelete) formData.append('force', '1');

                const resp = await fetch(DELETE_URL_BASE + this._deleteId, {
                    method: 'POST',
                    body: formData
                });
                const res = await resp.json();

                if (res.success) {
                    if (this.CALENDAR_DATA[this._clickedDate]) {
                        this.CALENDAR_DATA[this._clickedDate] = this.CALENDAR_DATA[this._clickedDate].filter(e => e.id != this._deleteId);
                    }
                    this.closeAll();
                    this.renderCalendar();
                } else if (res.error === 'has_participants') {
                    // SHOW SECOND WARNING
                    this._isForcedDelete = true;
                    titleEl.textContent = "⚠️ ATTENTION CRITIQUE";
                    textEl.innerHTML = `Cet événement compte <strong>${res.count} participants</strong>. <br><br>La suppression annulera toutes leurs participations. <br>Voulez-vous vraiment TOUT supprimer ?`;
                    btn.textContent = "OUI, TOUT SUPPRIMER";
                    btn.disabled = false;
                } else {
                    alert(res.error || 'Erreur lors de la suppression');
                    this.closeAll();
                }
            } catch (e) {
                alert('Erreur réseau');
                this.closeAll();
            }
        };

        // ── DAY CLICK HANDLER ──────────────────────────────────────────────────
        this.handleDayClick = async (dateKey) => {
            if (!IS_PROPRIETAIRE) return;

            try {
                const resp = await fetch(`${DAY_EVENTS_URL}?date=${dateKey}`);
                const events = await resp.json();

                if (events.length > 0) {
                    this.openDayPopup(dateKey, events);
                } else {
                    this.openCreatePopup(dateKey, false);
                }
            } catch (e) {
                this.openCreatePopup(dateKey, false);
            }
        };

        // ── CALENDAR RENDER ────────────────────────────────────────────────────
        this.renderMonthView = () => {
            document.getElementById('monthView').style.display = 'block';
            document.getElementById('weekView').classList.remove('active');
            const grid = document.getElementById('calGrid'); grid.innerHTML = '';
            const today = new Date();
            const y = this.calDate.getFullYear(), m = this.calDate.getMonth();
            let startDow = (new Date(y, m, 1).getDay() + 6) % 7;
            const daysInMonth = new Date(y, m + 1, 0).getDate();
            for (let i = 0; i < startDow; i++) { const el = document.createElement('div'); el.className = 'cal-day empty'; grid.appendChild(el); }
            for (let d = 1; d <= daysInMonth; d++) {
                const dt = new Date(y, m, d);
                const key = this.toKey(dt);
                const events = (this.CALENDAR_DATA[key] || []);
                const isToday = this.toKey(today) === key;
                const cell = document.createElement('div');
                cell.className = 'cal-day' + (isToday ? ' today' : '') + (IS_PROPRIETAIRE ? ' clickable-day' : '');
                cell.innerHTML = '<span>' + d + '</span>';
                if (events.length > 0) {
                    const dotsContainer = document.createElement('div');
                    dotsContainer.style.cssText = 'display:flex;gap:3px;flex-wrap:wrap;justify-content:center;margin-top:2px;max-width:30px;';
                    events.forEach((ev) => {
                        const isOwner = (Number(ev.id_createur) === Number(CURRENT_USER_ID));
                        const dot = document.createElement('div');
                        const isSelected = (this._clickedDate === key);
                        dot.className = 'event-dot' + (isOwner ? ' event-dot--own' : '');
                        if (isSelected) {
                            dot.style.background = '#0070f3'; // Blue
                            dot.style.boxShadow = '0 0 5px rgba(0, 112, 243, 0.5)';
                            dot.style.opacity = '0.7'; // Lower brightness/opacity
                        }
                        dotsContainer.appendChild(dot);
                    });
                    cell.appendChild(dotsContainer);
                }
                cell.addEventListener('click', (e) => {
                    const r = document.createElement('div'); r.className = 'ripple';
                    r.style.left = (e.offsetX - 20) + 'px'; r.style.top = (e.offsetY - 20) + 'px';
                    e.currentTarget.appendChild(r); setTimeout(() => r.remove(), 300);
                    if (IS_PROPRIETAIRE) this.handleDayClick(key);
                });
                grid.appendChild(cell);
            }
        };

        this.renderWeekView = () => {
            document.getElementById('monthView').style.display = 'none';
            const wv = document.getElementById('weekView');
            wv.classList.add('active'); wv.innerHTML = '';
            const today = new Date();
            const ref = new Date(this.calDate);
            const dow = (ref.getDay() + 6) % 7; ref.setDate(ref.getDate() - dow);
            for (let i = 0; i < 7; i++) {
                const d = new Date(ref); d.setDate(ref.getDate() + i);
                const key = this.toKey(d);
                const events = (this.CALENDAR_DATA[key] || []);
                const isToday = this.toKey(today) === key;
                const row = document.createElement('div'); row.className = 'week-row';
                row.innerHTML = `<div class="week-day-label"><div class="week-day-name">${this.DAYS_FR[i]}</div><div class="week-day-num ${isToday ? 'today-num' : ''}">${d.getDate()}</div></div><div class="week-events" id="we_${i}"></div>`;
                wv.appendChild(row);
                const we = row.querySelector('.week-events');
                if (!events.length) {
                    const empty = document.createElement('span');
                    empty.style.cssText = 'font-size:12px;color:var(--text-dim);align-self:center;opacity:.5';
                    empty.textContent = 'Aucun événement'; we.appendChild(empty);
                }
                events.forEach(ev => {
                    const isOwner = (Number(ev.id_createur) === Number(CURRENT_USER_ID));
                    const chip = document.createElement('div');
                    // No longer adding week-event-chip--clickable as requested: events should not be clickable in weekly view
                    chip.className = 'week-event-chip';

                    chip.innerHTML = `
                        <div class="chip-dot ${isOwner ? '' : 'chip-dot--other'}"></div>
                        <div>
                            <div>${ev.titre.substring(0, 40)}${ev.titre.length > 40 ? '…' : ''}</div>
                            <div class="chip-lieu">${ev.lieu || ''}</div>
                        </div>
                    `;
                    we.appendChild(chip);
                });
            }
        };

        this.renderCalendar = () => {
            const calTitle = document.getElementById('calTitle');
            if (!calTitle) return;
            calTitle.textContent = this.MONTHS_FR[this.calDate.getMonth()] + ' ' + this.calDate.getFullYear();
            if (this.currentView === 'month') this.renderMonthView();
            else this.renderWeekView();
        };

        // ── WIRE-UP ───────────────────────────────────────────────────────────
        document.getElementById('dayPopupClose')?.addEventListener('click', this.closeAll);
        document.getElementById('createPopupClose')?.addEventListener('click', this.closeAll);
        document.getElementById('editPopupClose')?.addEventListener('click', this.closeAll);
        document.getElementById('detailsPopupClose')?.addEventListener('click', this.closeAll);
        document.getElementById('cancelDeleteBtn')?.addEventListener('click', this.closeAll);
        document.getElementById('confirmDeleteBtn')?.addEventListener('click', this.confirmDeleteAction);

        document.getElementById('dayPopupCreateBtn')?.addEventListener('click', () => {
            this.dayPopup.classList.remove('popup-open');
            this.openCreatePopup(this._clickedDate, true);
        });

        document.getElementById('createPopupBack')?.addEventListener('click', () => {
            this.createPopup.classList.remove('popup-open');
            this.handleDayClick(this._clickedDate);
        });

        document.getElementById('editPopupBack')?.addEventListener('click', () => {
            this.editPopup.classList.remove('popup-open');
            this.handleDayClick(this._clickedDate);
        });

        document.getElementById('detailsPopupBack')?.addEventListener('click', () => {
            this.detailsPopup.classList.remove('popup-open');
            this.handleDayClick(this._clickedDate);
        });

        // ── FORM SUBMISSIONS ──────────────────────────────────────────────────
        const setupForm = (formId, btnId, errId, sucId, urlBase, isEdit = false) => {
            const form = document.getElementById(formId);
            const btn = document.getElementById(btnId);
            const err = document.getElementById(errId);
            const suc = document.getElementById(sucId);

            form?.addEventListener('submit', async (e) => {
                e.preventDefault();
                err.textContent = ''; suc.textContent = '';
                btn.disabled = true; btn.textContent = isEdit ? 'Enregistrement…' : 'Création en cours…';

                try {
                    const formData = new FormData(form);
                    const url = isEdit ? (EDIT_URL_BASE + document.getElementById('editEventId').value) : CREATE_URL;
                    const resp = await fetch(url, { method: 'POST', body: formData });
                    const res = await resp.json();

                    if (res.success) {
                        const key = isEdit ? this._clickedDate : document.getElementById('createEventDate').value;
                        if (!isEdit) {
                            if (!this.CALENDAR_DATA[key]) this.CALENDAR_DATA[key] = [];
                            this.CALENDAR_DATA[key].push({ id: res.id, titre: res.titre, lieu: '', id_createur: CURRENT_USER_ID });
                        } else {
                            const idx = this.CALENDAR_DATA[key].findIndex(ev => ev.id == res.id);
                            if (idx !== -1) {
                                this.CALENDAR_DATA[key][idx].titre = res.titre;
                                this.CALENDAR_DATA[key][idx].lieu = res.lieu || '';
                            }
                        }

                        suc.textContent = isEdit ? '✓ Modifications enregistrées !' : '✓ Événement créé avec succès !';
                        setTimeout(() => { this.closeAll(); this.renderCalendar(); }, 1200);
                    } else {
                        err.textContent = res.error || 'Une erreur est survenue.';
                    }
                } catch (ex) {
                    err.textContent = 'Erreur réseau.';
                } finally {
                    btn.disabled = false; btn.textContent = isEdit ? 'Enregistrer les modifications' : 'Créer l\'événement';
                }
            });
        };

        setupForm('createEventForm', 'createEventSubmit', 'createEventError', 'createEventSuccess', CREATE_URL);
        setupForm('editEventForm', 'editEventSubmit', 'editEventError', 'editEventSuccess', EDIT_URL_BASE, true);

        // ── CALENDAR CONTROLS ─────────────────────────────────────────────────
        const _c = document.getElementById('openCalBtn');
        if (_c) {
            _c.addEventListener('click', () => {
                this.calDate = new Date(); this.renderCalendar();
                document.getElementById('calOverlay').classList.add('open');
            });
            document.getElementById('calClose').addEventListener('click', () => document.getElementById('calOverlay').classList.remove('open'));
            document.getElementById('prevBtn').addEventListener('click', () => { this.currentView === 'month' ? this.calDate.setMonth(this.calDate.getMonth() - 1) : this.calDate.setDate(this.calDate.getDate() - 7); this.renderCalendar() });
            document.getElementById('nextBtn').addEventListener('click', () => { this.currentView === 'month' ? this.calDate.setMonth(this.calDate.getMonth() + 1) : this.calDate.setDate(this.calDate.getDate() + 7); this.renderCalendar() });
            document.getElementById('monthViewBtn').addEventListener('click', () => { this.currentView = 'month'; document.getElementById('monthViewBtn').classList.add('active'); document.getElementById('weekViewBtn').classList.remove('active'); this.renderCalendar() });
            document.getElementById('weekViewBtn').addEventListener('click', () => { this.currentView = 'week'; document.getElementById('weekViewBtn').classList.add('active'); document.getElementById('monthViewBtn').classList.remove('active'); this.renderCalendar() });
            if (OPEN_CAL) _c.click();
        }
    }
}
