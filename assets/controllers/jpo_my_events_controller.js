import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["editModal", "deleteModal"];
    static values = {
        editUrl: String,
        deleteUrl: String
    };

    connect() {
        this.MONTHS = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        this.FALLBACK_ICONS = ['ph-graduation-cap', 'ph-buildings', 'ph-laptop', 'ph-flask', 'ph-bank', 'ph-palette', 'ph-first-aid', 'ph-triangle', 'ph-microscope', 'ph-rocket'];
        this._deleteId = null;
        this._isForcedDelete = false;
        this.searchTimeout = null;
    }

    search() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => this.fetchEvents(), 300);
    }

    goToDetails(e) {
        if (e.target.closest('button')) return;
        const id = e.currentTarget.dataset.id;
        if (id) window.location.href = `/jpo/details/${id}`;
    }

    async fetchEvents() {
        const q = document.getElementById('searchInput')?.value || '';
        const grid = document.getElementById('myEventsGrid');
        if (!grid) return;

        grid.style.opacity = '0.5';

        const params = new URLSearchParams(window.location.search);
        params.set('q', q);
        params.set('page', '1'); // Reset to page 1 on search

        try {
            const resp = await fetch(window.location.pathname + '?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await resp.json();
            this.renderGrid(data.events);
            // Update URL without reload
            window.history.pushState({}, '', window.location.pathname + '?' + params.toString());
        } catch (e) {
            console.error('Search error:', e);
        } finally {
            grid.style.opacity = '1';
        }
    }

    renderGrid(events) {
        const grid = document.getElementById('myEventsGrid');
        if (!grid) return;
        grid.innerHTML = '';

        if (!events.length) {
            grid.innerHTML = `
                <div class="no-results" style="padding: 60px 20px; text-align: center; background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px dashed rgba(255,255,255,0.1); grid-column: 1 / -1;">
                  <div style="font-size: 48px; color: var(--text-dim); margin-bottom: 20px;"><i class="ph ph-calendar-blank"></i></div>
                  <h3 style="color: white; margin-bottom: 10px;">Aucun événement trouvé</h3>
                  <p style="color: var(--text-dim); max-width: 400px; margin: 0 auto 30px;">Aucun résultat ne correspond à votre recherche.</p>
                </div>
            `;
            return;
        }

        events.forEach((ev, i) => {
            const isHistory = new URLSearchParams(window.location.search).get('history') === '1';
            const fallback = `<i class="ph ${this.FALLBACK_ICONS[i % this.FALLBACK_ICONS.length]}"></i>`;
            const img = ev.image_path ? `<img src="${ev.image_path}" alt="${ev.titre}">` : fallback;

            const card = document.createElement('div');
            card.className = 'event-card';
            card.id = `event-${ev.id}`;
            card.dataset.id = ev.id;
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => this.goToDetails(e));
            card.innerHTML = `
                <div class="card-img">
                  ${img}
                  <div class="card-badge" style="background: var(--cyan); color: #060e1a; font-weight: 800; opacity: 1; box-shadow: 0 4px 12px rgba(0, 229, 255, 0.4);">
                    ${ev.current_participants}/${ev.max_participants}
                  </div>
                </div>
                <div class="card-body">
                  <div class="card-date"><i class="ph ph-calendar-blank"></i> ${ev.date_evenement}</div>
                  <div class="card-title">${ev.titre}</div>
                  ${ev.lieu ? `<div style="font-size: 11px; color: var(--cyan); margin-bottom: 5px;"><i class="ph ph-map-pin"></i> ${ev.lieu}</div>` : ''}
                  ${ev.description ? `<div class="card-desc" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 12px; color: var(--text-dim); line-height: 1.4;">${ev.description}</div>` : ''}
                </div>
                <div class="card-footer" style="padding: 15px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: flex-end; gap: 8px; height: 60px; position: relative;">
                  ${!isHistory ? `
                    <button class="btn-icon btn-secondary" title="Modifier l'événement" data-action="jpo-my-events#openEdit"
                            data-event-id="${ev.id}" data-event-titre="${ev.titre}" data-event-lieu="${ev.lieu || ''}"
                            data-event-desc="${ev.description || ''}" data-event-max="${ev.max_participants}" data-event-date="${ev.date_evenement}"
                            data-event-locked="${ev.is_locked}"
                            style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                      <i class="ph ph-pencil-simple" style="font-size: 18px; margin: 0;"></i>
                    </button>
                    <button class="btn-icon btn-danger" title="Supprimer l'événement" data-action="jpo-my-events#delete" data-id="${ev.id}"
                            data-locked="${ev.is_locked}"
                            style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444;">
                      <i class="ph ph-trash" style="font-size: 18px; margin: 0;"></i>
                    </button>
                  ` : `
                    <div style="font-size: 11px; color: var(--text-dim); font-style: italic; display: flex; align-items: center;">
                      <i class="ph ph-lock-simple" style="margin-right: 5px;"></i> Lecture seule
                    </div>
                  `}
                </div>
            `;
            grid.appendChild(card);
        });
    }

    openEdit(e) {
        const btn = e.currentTarget;
        const isLocked = btn.dataset.eventLocked === 'true';

        if (isLocked) {
            window.uiAlert("Cet événement commence dans moins de 24h et ne peut plus être modifié.", "Action impossible");
            return;
        }

        const id = btn.dataset.eventId;
        const titre = btn.dataset.eventTitre;
        const lieu = btn.dataset.eventLieu;
        const desc = btn.dataset.eventDesc;
        const max = btn.dataset.eventMax;
        const date = btn.dataset.eventDate;

        document.getElementById('editId').value = id;
        document.getElementById('editTitre').value = titre;
        document.getElementById('editLieu').value = lieu || '';
        document.getElementById('editDesc').value = desc || '';
        document.getElementById('editMax').value = max;
        document.getElementById('editModalSubtitle').textContent = date;

        document.getElementById('formError').textContent = '';
        document.getElementById('formSuccess').textContent = '';
        document.getElementById('fileName').textContent = 'Remplacer l\'image...';

        this.editModalTarget.classList.add('popup-open');
    }

    closeEdit() {
        this.editModalTarget.classList.remove('popup-open');
    }

    async submitEdit(e) {
        const btn = e.currentTarget;
        const form = document.getElementById('editForm');
        const err = document.getElementById('formError');
        const suc = document.getElementById('formSuccess');
        const id = document.getElementById('editId').value;

        err.textContent = '';
        suc.textContent = '';
        btn.disabled = true;
        btn.textContent = 'Enregistrement...';

        try {
            const formData = new FormData(form);
            const resp = await fetch(this.editUrlValue + id, {
                method: 'POST',
                body: formData
            });
            const res = await resp.json();

            if (res.success) {
                suc.textContent = '✓ Modifications enregistrées !';
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                err.textContent = res.error || 'Erreur lors de la modification';
            }
        } catch (ex) {
            err.textContent = 'Erreur réseau';
        } finally {
            btn.disabled = false;
            btn.textContent = 'ENREGISTRER LES MODIFICATIONS';
        }
    }

    // Custom Delete Confirmation with two-step for participants
    delete(e) {
        const isLocked = e.currentTarget.dataset.locked === 'true';

        if (isLocked) {
            window.uiAlert("Cet événement commence dans moins de 24h et ne peut plus être supprimé.", "Action impossible");
            return;
        }

        this._deleteId = e.currentTarget.dataset.id;
        this._isForcedDelete = false;

        // Reset modal state
        const title = this.deleteModalTarget.querySelector('.confirm-title');
        const text = this.deleteModalTarget.querySelector('.confirm-text');
        const btn = document.getElementById('confirmDeleteBtn');
        title.textContent = "Supprimer l'événement ?";
        text.textContent = "Attention : Cette action est irréversible. Voulez-vous vraiment supprimer cet événement ?";
        btn.textContent = "SUPPRIMER";

        this.deleteModalTarget.classList.add('popup-open');
    }

    closeDelete() {
        this.deleteModalTarget.classList.remove('popup-open');
        this._deleteId = null;
        this._isForcedDelete = false;
    }

    async confirmDelete(e) {
        if (!this._deleteId) return;
        const btn = e.currentTarget;
        const titleEl = this.deleteModalTarget.querySelector('.confirm-title');
        const textEl = this.deleteModalTarget.querySelector('.confirm-text');

        btn.disabled = true;
        btn.textContent = 'CHARGEMENT...';

        try {
            const formData = new FormData();
            if (this._isForcedDelete) formData.append('force', '1');

            const resp = await fetch(this.deleteUrlValue + this._deleteId, {
                method: 'POST',
                body: formData
            });
            const res = await resp.json();

            if (res.success) {
                const card = document.getElementById(`event-${this._deleteId}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => card.remove(), 300);
                }
                this.closeDelete();
            } else if (res.error === 'has_participants') {
                this._isForcedDelete = true;
                titleEl.textContent = "⚠️ ATTENTION CRITIQUE";
                textEl.innerHTML = `Cet événement compte <strong>${res.count} participants</strong>. <br><br>La suppression annulera toutes leurs participations. <br>Voulez-vous vraiment TOUT supprimer ?`;
                btn.textContent = "OUI, TOUT SUPPRIMER";
                btn.disabled = false;
            } else {
                alert(res.error || 'Erreur lors de la suppression');
                this.closeDelete();
            }
        } catch (ex) {
            alert('Erreur réseau');
            this.closeDelete();
        }
    }
}
