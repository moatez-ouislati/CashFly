import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container', 'messages', 'input', 'toggleBtn', 'replyPreview', 'replyAuthor', 'replySnippet', 'inputArea', 'readonlyNotice'];
    static values = {
        eventId: Number,
        currentUserId: Number,
        loadUrl: String,
        sendUrl: String,
        editUrl: String,
        deleteUrl: String,
        presenceUrl: String,
        readonly: Boolean
    };

    connect() {
        this.isOpen = false;
        this.messages = [];
        this.lastMessageId = 0;
        this.replyToId = null;
        this.pollInterval = null;
        this.presenceInterval = null;
        this.eventListenersAdded = false;
        this.firstFetchDone = false;

        // Listen for global open requests (e.g. from Admin Dashboard)
        this.boundOpenAdminChat = this.openAdminChat.bind(this);
        document.addEventListener('chat:openAdmin', this.boundOpenAdminChat);

        // Determine if we should start open (e.g. if container is already visible)
        if (this.containerTarget.style.display !== 'none' && !this.containerTarget.classList.contains('modal-overlay--hidden')) {
            this.isOpen = true;
            this.startPolling();
        }
    }

    disconnect() {
        this.stopPolling();
        document.removeEventListener('chat:openAdmin', this.boundOpenAdminChat);
    }

    eventIdValueChanged() {
        if (this.eventIdValue) {
            this.messages = [];
            this.lastMessageId = 0;
            if (this.isOpen) {
                this.stopPolling();
                this.startPolling();
            }
            if (this.hasMessagesTarget) this.messagesTarget.innerHTML = '<div class="chat-loading"><div class="spin"></div><p>Chargement du chat...</p></div>';
        }
    }

    toggle() {
        this.isOpen = !this.isOpen;
        this.containerTarget.style.display = this.isOpen ? 'flex' : 'none';
        this.containerTarget.classList.toggle('modal-overlay--hidden', !this.isOpen);

        if (this.isOpen) {
            if (this.messages.length === 0) {
                this.messagesTarget.innerHTML = '<div class="chat-loading"><div class="spin"></div><p>Chargement des messages...</p></div>';
            }
            this.startPolling();
            this.scrollToBottom();
            setTimeout(() => this.inputTarget.focus(), 100);
        } else {
            this.stopPolling();
        }
    }

    /**
     * Called from external listeners (like admin dashboard) 
     * to switch context and open the chat.
     */
    openAdminChat(event) {
        const { id, title, loadUrl, sendUrl, presenceUrl } = event.detail || event.currentTarget.dataset;

        // Update values - Stimulus will trigger valueChanged if id changes
        this.eventIdValue = parseInt(id, 10);
        this.loadUrlValue = loadUrl || `/api/chat/${id}/messages`;
        this.sendUrlValue = sendUrl || `/api/chat/${id}/send`;
        this.presenceUrlValue = presenceUrl || `/api/chat/${id}/presence`;

        // Update header title if it exists externally
        const titleEl = document.getElementById('admin-chat-title');
        if (titleEl) titleEl.innerText = "Chat: " + title;

        // Ensure it's open
        if (!this.isOpen) {
            this.toggle();
        } else {
            // If already open, just refresh fetching
            this.stopPolling();
            this.startPolling();
        }

        // Show the wrapper if it was hidden via display:none
        if (this.element.style.display === 'none') {
            this.element.style.display = 'block';
        }
    }

    startPolling() {
        this.stopPolling(); // Safety
        this.fetchMessages();
        this.pollInterval = setInterval(() => this.fetchMessages(), 8000);
        this.presenceInterval = setInterval(() => this.updatePresence(), 60000);
    }

    stopPolling() {
        clearInterval(this.pollInterval);
        clearInterval(this.presenceInterval);
    }

    async fetchMessages() {
        try {
            const response = await fetch(this.loadUrlValue);
            const rawData = await response.json();
            const data = rawData.messages || [];
            this.readonlyValue = rawData.is_readonly || false;

            if (JSON.stringify(data) !== JSON.stringify(this.messages) || !this.firstFetchDone) {
                const hadNewMessages = data.length > this.messages.length;
                this.messages = data;
                this.firstFetchDone = true;
                this.renderMessages();
                this.updateUIState();

                if (hadNewMessages) {
                    if (this.isOpen) {
                        this.scrollToBottom();
                    }
                }
            }
        } catch (error) {
            console.error('Chat fetch error:', error);
            if (this.messages.length === 0) {
                this.messagesTarget.innerHTML = '<div class="chat-error-state"><i class="ph ph-warning-circle"></i><p>Impossible de charger les messages.</p></div>';
            }
        }
    }

    async updatePresence() {
        try {
            await fetch(this.presenceUrlValue, { method: 'POST' });
        } catch (e) { }
    }

    updateUIState() {
        if (this.hasInputAreaTarget) {
            this.inputAreaTarget.style.display = this.readonlyValue ? 'none' : 'flex';
        }
        if (this.hasReadonlyNoticeTarget) {
            this.readonlyNoticeTarget.style.display = this.readonlyValue ? 'flex' : 'none';
        }
    }

    renderMessages() {
        this.messagesTarget.innerHTML = '';
        if (this.messages.length === 0) {
            this.messagesTarget.innerHTML = '<div class="chat-empty-state"><i class="ph ph-chat-centered-dots"></i><p>Aucun message pour le moment.<br>Soyez le premier à écrire !</p></div>';
            return;
        }
        this.messages.forEach(msg => {
            const isMe = msg.author && msg.author.id === this.currentUserIdValue;
            const isAdmin = msg.author && msg.author.is_admin;
            const isOwner = msg.author && msg.author.is_owner;

            const msgEl = document.createElement('div');
            msgEl.className = `chat-bubble-wrap ${isMe ? 'msg-me' : 'msg-them'} ${isAdmin ? 'msg-admin' : ''} ${isOwner ? 'msg-owner' : ''}`;

            let replyHtml = '';
            if (msg.reply_to) {
                replyHtml = `
                    <div class="msg-reply-ref">
                        <div class="reply-ref-author">${msg.reply_to.author}</div>
                        <div class="reply-ref-text">${msg.reply_to.snippet}</div>
                    </div>
                `;
            }

            const actionsHtml = (msg.status !== 'deleted' && (msg.can_edit || msg.can_delete || !this.readonlyValue)) ? `
                <div class="msg-actions">
                    ${!this.readonlyValue ? `<button class="msg-action-btn" onclick="document.dispatchEvent(new CustomEvent('chat:reply', {detail: {id: ${msg.id}, author: '${msg.author.nom}', snippet: '${msg.message.replace(/'/g, "\\'")}'}}))" title="Répondre"><i class="ph ph-arrow-bend-up-left"></i></button>` : ''}
                    ${msg.can_edit ? `<button class="msg-action-btn" onclick="document.dispatchEvent(new CustomEvent('chat:edit', {detail: {id: ${msg.id}, text: '${msg.message.replace(/'/g, "\\'")}'}}))" title="Modifier"><i class="ph ph-pencil"></i></button>` : ''}
                    ${msg.can_delete ? `<button class="msg-action-btn" onclick="document.dispatchEvent(new CustomEvent('chat:delete', {detail: {id: ${msg.id}}}) )" title="Supprimer"><i class="ph ph-trash"></i></button>` : ''}
                </div>
            ` : '';

            msgEl.innerHTML = `
                <div class="msg-meta">
                    <span class="msg-author">${isAdmin ? 'Administrateur' : (msg.author ? msg.author.nom : 'Utilisateur')}</span>
                    ${isAdmin ? '<span class="badge badge-admin">ADMIN</span>' : ''}
                    ${isOwner ? '<span class="badge badge-owner">ORGANISATEUR</span>' : ''}
                    <span class="msg-time">${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                </div>
                <div class="msg-bubble">
                    ${replyHtml}
                    <div class="msg-text">${msg.message}</div>
                    ${msg.is_edited ? '<span class="msg-edited">(modifié)</span>' : ''}
                    ${actionsHtml}
                </div>
            `;
            this.messagesTarget.appendChild(msgEl);
        });

        // Listen for internal events (using dispatchEvent for simplicity in bubble generated HTML)
        if (!this.eventListenersAdded) {
            document.addEventListener('chat:reply', e => this.setReply(e.detail));
            document.addEventListener('chat:edit', e => this.startEdit(e.detail));
            document.addEventListener('chat:delete', e => this.confirmDelete(e.detail.id));
            this.eventListenersAdded = true;
        }
    }

    setReply(detail) {
        this.replyToId = detail.id;
        this.replyAuthorTarget.innerText = detail.author;
        this.replySnippetTarget.innerText = detail.snippet;
        this.replyPreviewTarget.classList.remove('modal-overlay--hidden');
        this.inputTarget.focus();
    }

    cancelReply() {
        this.replyToId = null;
        this.replyPreviewTarget.classList.add('modal-overlay--hidden');
    }

    startEdit(detail) {
        this.isEditingId = detail.id;
        this.inputTarget.value = detail.text;
        this.inputTarget.focus();
        this.inputTarget.classList.add('editing-active');
    }

    handleKeydown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            e.stopImmediatePropagation();
            this.send();
        }
    }

    async send() {
        const now = Date.now();
        if (this._lastSend && (now - this._lastSend < 500)) return;
        this._lastSend = now;

        if (this.isSending) return;
        const text = this.inputTarget.value.trim();
        if (!text) return;

        this.isSending = true;
        const url = this.isEditingId ? `${this.editUrlValue}${this.isEditingId}` : this.sendUrlValue;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    message: text,
                    reply_to: this.replyToId
                })
            });

            if (response.ok) {
                this.inputTarget.value = '';
                this.cancelReply();
                this.isEditingId = null;
                this.inputTarget.classList.remove('editing-active');
                this.fetchMessages();
            }
        } catch (e) {
            console.error('Send error:', e);
        } finally {
            this.isSending = false;
        }
    }

    confirmDelete(id) {
        window.uiConfirm("Voulez-vous vraiment supprimer ce message ?", async () => {
            try {
                const response = await fetch(`${this.deleteUrlValue}${id}`, { method: 'POST' });
                if (response.ok) this.fetchMessages();
            } catch (e) { }
        });
    }

    scrollToBottom() {
        this.messagesTarget.scrollTop = this.messagesTarget.scrollHeight;
    }
}
