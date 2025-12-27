const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

let currentConversationUserId = null;
let pollInterval = null;

// helper: escape HTML to avoid injecting raw message text into innerHTML
function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;')
                      .replace(/</g, '&lt;')
                      .replace(/>/g, '&gt;')
                      .replace(/"/g, '&quot;')
                      .replace(/'/g, '&#039;');
}

function enableChatUI() {
    const empty = document.getElementById('chat-empty');
    const messages = document.getElementById('messages');
    const formWrap = document.getElementById('message-form-wrap');
    if (empty) empty.classList.add('hidden');
    if (messages) messages.classList.remove('hidden');
    if (formWrap) formWrap.classList.remove('hidden');

    const input = document.getElementById('message-input');
    if (input) { input.disabled = false; input.classList.remove('opacity-0'); }

    const submit = document.querySelector('#message-form button[type="submit"]');
    if (submit) submit.disabled = false;

    const attachmentBtn = document.getElementById('attachment-btn');
    if (attachmentBtn) attachmentBtn.disabled = false;

    // restore header appearance
    const topBar = document.getElementById('chat-top-bar');
    if (topBar) { topBar.classList.remove('bg-green-500'); topBar.classList.add('bg-gradient-to-l','from-[#7CC576]','to-white'); }
    const avatar = document.getElementById('chat-header-avatar');
    const nameEl = document.getElementById('chat-header-name');
    const subEl = document.getElementById('chat-header-sub');
    if (avatar) avatar.classList.remove('hidden');
    if (nameEl) nameEl.classList.remove('hidden');
    if (subEl) subEl.classList.remove('hidden');
}

function disableChatUI() {
    const empty = document.getElementById('chat-empty');
    const messages = document.getElementById('messages');
    const formWrap = document.getElementById('message-form-wrap');
    if (empty) empty.classList.remove('hidden');
    if (messages) messages.classList.add('hidden');
    if (formWrap) formWrap.classList.add('hidden');

    const input = document.getElementById('message-input');
    if (input) input.disabled = true;

    const submit = document.querySelector('#message-form button[type="submit"]');
    if (submit) submit.disabled = true;

    const attachmentBtn = document.getElementById('attachment-btn');
    if (attachmentBtn) attachmentBtn.disabled = true;

    // make header a blank green bar
    const topBar = document.getElementById('chat-top-bar');
    if (topBar) { topBar.classList.add('bg-green-500'); topBar.classList.remove('bg-gradient-to-l','from-[#7CC576]','to-white'); }
    const avatar = document.getElementById('chat-header-avatar');
    const nameEl = document.getElementById('chat-header-name');
    const subEl = document.getElementById('chat-header-sub');
    if (avatar) avatar.classList.add('hidden');
    if (nameEl) { nameEl.textContent = ''; nameEl.classList.add('hidden'); }
    if (subEl) { subEl.textContent = ''; subEl.classList.add('hidden'); }

    const recipient = document.getElementById('recipient-id');
    if (recipient) recipient.value = '';
    currentConversationUserId = null;
}

function renderUsers(users) {
    const ul = document.getElementById('chat-users');
    ul.innerHTML = '';
    users.forEach(u => {
        const li = document.createElement('li');
        li.className = 'flex items-center gap-3 p-2 rounded hover:bg-gray-100 cursor-pointer';
        // store data attributes to make debugging easier
        li.dataset.id = u.id;
        li.dataset.name = u.name;
        li.dataset.avatar = u.profile_photo_url || '/images/profile-user.png';
        const avatar = u.profile_photo_url || '/images/profile-user.png';
        const unreadBadge = u.unread_count ? `<span class="bg-red-500 text-white rounded-full text-xs px-2 py-0.5">${u.unread_count}</span>` : '';
        const importantMark = u.important ? `<span class="ms-2 text-yellow-600">★</span>` : '';
        li.innerHTML = `
            <img src="${avatar}" class="h-10 w-10 rounded-full" />
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div class="font-medium truncate">${escapeHtml(u.name)} ${importantMark}</div>
                    <div class="text-sm text-gray-500 ml-2 truncate max-w-[35%] text-right">${escapeHtml(u.email)}</div>
                </div>
                <div class="flex items-center justify-between mt-1">
                    <div class="text-sm text-gray-500 truncate">${escapeHtml(u.last_message)}</div>
                    <div class="text-xs text-gray-400 ms-2 ml-2 text-right">${u.last_message_at ? formatTime(u.last_message_at) : ''}</div>
                </div>
            </div>
            <div class="text-sm text-gray-400">${unreadBadge}</div>
        `;
        li.addEventListener('click', () => {
            // set header synchronously from the clicked item for instant feedback
            document.getElementById('chat-header-name').textContent = u.name || 'User';
            document.getElementById('chat-header-avatar').src = avatar;
            document.getElementById('chat-header-sub').textContent = u.email || '';
            // show chat panel and enable inputs immediately
            enableChatUI();
            // initial open should scroll to bottom for the user
            loadConversation(u.id, u.name, avatar, true);
        });
        ul.appendChild(li);
    });
}

async function searchUsers(q = '', filter = 'all') {
    const res = await axios.get(`/users/search?q=${encodeURIComponent(q)}&filter=${encodeURIComponent(filter)}`);
    renderUsers(res.data);
}

function formatTime(dateString) {
    if (!dateString) return '';
    // Some backends return 'YYYY-MM-DD HH:MM:SS' without the 'T' iso separator. Normalize to ISO when needed.
    let s = String(dateString);
    if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(s)) {
        s = s.replace(' ', 'T');
    }
    const d = new Date(s);
    if (isNaN(d)) return '';
    return d.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
}

function renderMessages(messages, options = {}) {
    const cont = document.getElementById('messages');

    // capture scroll position before updating content
    const prevFromBottom = cont.scrollHeight - cont.scrollTop;
    const atBottomThreshold = 50; // px
    const wasAtBottom = prevFromBottom < atBottomThreshold;
    const forceScroll = !!options.forceScroll;

    cont.innerHTML = '';
    const meId = window.Laravel?.user?.id || null;

    messages.forEach(m => {
        const isMine = m.sender_id === meId;
        const wrapper = document.createElement('div');
        wrapper.className = `mb-4 flex ${isMine ? 'justify-end' : 'justify-start'}`;

        const bubble = document.createElement('div');
        bubble.className = `${isMine ? 'bubble bubble--me text-black' : 'bubble bubble--other text-black'} p-3 max-w-[60%] relative`;

        let html = '';
        if (m.body) html += `<div class="mb-1">${m.body}</div>`;
        if (m.attachment_path) {
            const attachmentPath = m.attachment_path;
            const isImage = /\.(jpe?g|png|gif|webp|svg)$/i.test(attachmentPath);
            if (isImage) {
                html += `<div class="mt-2"><a href="/storage/${attachmentPath}" target="_blank" class="inline-block"><img src="/storage/${attachmentPath}" alt="attachment" class="max-w-[240px] rounded shadow"/></a></div>`;
            } else {
                const filename = attachmentPath.split('/').pop();
                html += `<div class="mt-2"><a href="/storage/${attachmentPath}" target="_blank" class="inline-flex items-center gap-2 text-sm text-blue-600"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7v10a4 4 0 004 4h6m-10-14h8l2 2v10a4 4 0 01-4 4H7"/></svg>${filename}</a></div>`;
            }
        }
        html += `<div class="text-xs text-gray-500 mt-1 flex items-center justify-end">${formatTime(m.created_at)} `;
        if (isMine) {
            // checkmark: single check when not read, double when read
            if (m.is_read) {
                const readTitle = m.read_at ? `title="Dibaca ${new Date(m.read_at).toLocaleString()}"` : `title="Dibaca"`;
                html += `<span class="ms-2 text-blue-500" ${readTitle}>✔✔</span>`;
            } else {
                html += `<span class="ms-2 text-gray-400" title="Terkirim">✔</span>`;
            }
            // menu
            html += ` <span class="ms-2 text-gray-400 cursor-pointer message-menu" data-id="${m.id}">⋮</span>`;
        }
        html += `</div>`;

        // if message edited, show small tag next to timestamp
        if (m.edited_at) {
            html += ` <span class="ms-1 text-xs text-gray-400" title="Diedit ${new Date(m.edited_at).toLocaleString()}">(edited)</span>`;
        }

        bubble.innerHTML = html;
        // store message id, body and timestamps for edit operations
        bubble.setAttribute('data-message-id', m.id);
        bubble.setAttribute('data-body', m.body || '');
        if (m.created_at) bubble.setAttribute('data-created-at', m.created_at);
        if (m.edited_at) bubble.setAttribute('data-edited-at', m.edited_at);

        // attach menu handlers
        wrapper.appendChild(bubble);
        cont.appendChild(wrapper);
    });

    // attach menu listeners
    document.querySelectorAll('.message-menu').forEach(el => {
        el.addEventListener('click', async (e) => {
            const id = e.target.dataset.id;
            showMessageMenu(e.target, id);
        });
    });

    // restore scroll behaviour: scroll to bottom only when user was at bottom or forceScroll is set
    if (forceScroll || wasAtBottom) {
        cont.scrollTop = cont.scrollHeight;
    } else {
        // preserve previous distance from bottom
        cont.scrollTop = Math.max(0, cont.scrollHeight - prevFromBottom);
    }
}

function showMessageMenu(targetEl, messageId) {
    // styled popup menu
    const menu = document.createElement('div');
    menu.className = 'absolute bg-white border rounded shadow p-2';
    menu.style.zIndex = 1000;
    menu.innerHTML = `
        <div class="p-1 hover:bg-gray-100 cursor-pointer" id="edit-msg-${messageId}">Edit</div>
        <div class="p-1 hover:bg-gray-100 cursor-pointer text-red-600" id="del-msg-${messageId}">Delete</div>
    `;

    document.body.appendChild(menu);
    const rect = targetEl.getBoundingClientRect();
    menu.style.top = rect.bottom + 'px';
    menu.style.left = (rect.left - 80) + 'px';

    const removeMenu = () => { menu.remove(); document.removeEventListener('click', removeOnClickOutside); };
    const removeOnClickOutside = (ev) => { if (!menu.contains(ev.target)) removeMenu(); };
    setTimeout(() => document.addEventListener('click', removeOnClickOutside), 0);

    const editEl = document.getElementById(`edit-msg-${messageId}`);
    const bubbleEl = document.querySelector(`[data-message-id="${messageId}"]`);
    const createdAt = bubbleEl?.getAttribute('data-created-at');
    let canEdit = true;
    if (createdAt) {
        const ageMinutes = (Date.now() - new Date(createdAt).getTime()) / 60000;
        canEdit = ageMinutes <= 20;
    }

    if (!canEdit) {
        // disable edit option visually and with tooltip
        editEl.classList.add('opacity-50', 'cursor-not-allowed');
        editEl.setAttribute('title', 'Waktu edit telah habis (20 menit)');
    } else {
        editEl.addEventListener('click', async () => {
            removeMenu();
            // open edit modal and prefill
            const currentBody = bubbleEl?.getAttribute('data-body') || '';
            const editModal = document.getElementById('edit-modal');
            const editText = document.getElementById('edit-modal-text');
            editText.value = currentBody;
            editModal.dataset.messageId = messageId;
            editModal.classList.remove('hidden');
            setTimeout(() => editText.focus(), 50);
        });
    }
    document.getElementById(`del-msg-${messageId}`).addEventListener('click', async () => {
        removeMenu();
        // open confirm modal
        const confirmModal = document.getElementById('confirm-modal');
        const confirmMessage = document.getElementById('confirm-modal-message');
        confirmModal.dataset.messageId = messageId;
        confirmMessage.textContent = 'Hapus pesan ini?';
        confirmModal.classList.remove('hidden');
        setTimeout(() => document.getElementById('confirm-ok-btn').focus(), 50);
    });
}

async function loadConversation(userId, name = '', avatar = '', forceScroll = false) {
    if (!userId) return;
    console.log('loading conversation', userId, name, avatar, forceScroll);
    currentConversationUserId = userId;
    document.getElementById('recipient-id').value = userId;
    // make sure chat UI is active
    enableChatUI();
    // show name/avatar immediately (may be overwritten by server data) - only update if a non-empty value is provided
    if (typeof name === 'string' && name.trim() !== '') {
        document.getElementById('chat-header-name').textContent = name;
    }
    if (typeof avatar === 'string' && avatar.trim() !== '') {
        document.getElementById('chat-header-avatar').src = avatar;
    }

    try {
        const res = await axios.get(`/messages/conversation/${userId}`);
        renderMessages(res.data, { forceScroll });

        // mark as read (best-effort)
        await axios.post(`/messages/conversation/${userId}/read`);

        // start polling
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(() => loadConversation(userId, name, avatar), 3000);
    } catch (err) {
        console.error('Failed to load conversation', err);
        // show a friendly message in messages pane
        const cont = document.getElementById('messages');
        cont.innerHTML = '<div class="text-sm text-gray-500">Tidak dapat memuat pesan. Coba lagi.</div>';
    }
}

async function sendMessage(e) {
    e.preventDefault();
    const recipientId = document.getElementById('recipient-id').value;
    const input = document.getElementById('message-input');
    const fileInput = document.getElementById('attachment-input');

    if (!recipientId) { alert('Pilih penerima'); return; }

    const form = new FormData();
    form.append('recipient_id', recipientId);
    if (input.value) form.append('body', input.value);
    if (fileInput.files[0]) form.append('attachment', fileInput.files[0]);
    const cat = document.getElementById('attachment-category')?.value || '';
    if (cat) form.append('attachment_category', cat);

    try {
        await axios.post('/messages', form, { headers: { 'Content-Type': 'multipart/form-data' } });
        input.value = '';
        fileInput.value = null;
        // reload conversation and keep header; only pass name/avatar if name is non-empty
        const currentNameEl = document.getElementById('chat-header-name');
        const currentAvatarEl = document.getElementById('chat-header-avatar');
        const currentName = currentNameEl?.textContent?.trim();
        const currentAvatar = currentAvatarEl?.src?.trim();
        if (currentName) {
            loadConversation(recipientId, currentName, currentAvatar);
        } else {
            loadConversation(recipientId);
        }
    } catch (err) {
        console.error('send message failed', err);
        alert('Gagal mengirim pesan. Periksa konsol atau network tab.');
    }
}

/**
 * Send attachment file immediately. Uses the same endpoint as messages.
 * If no recipient is chosen, shows an alert and clears the file input.
 */
async function sendAttachment(file) {
    const recipientId = document.getElementById('recipient-id').value;
    const fileInput = document.getElementById('attachment-input');

    if (!recipientId) { alert('Pilih penerima'); if (fileInput) fileInput.value = null; return; }

    const form = new FormData();
    form.append('recipient_id', recipientId);
    if (file) form.append('attachment', file);
    const cat = document.getElementById('attachment-category')?.value || '';
    if (cat) form.append('attachment_category', cat);

    try {
        await axios.post('/messages', form, { headers: { 'Content-Type': 'multipart/form-data' } });
        if (fileInput) fileInput.value = null;
        // clear category/accept after successful send
        const attachmentCategoryInputEl = document.getElementById('attachment-category');
        if (attachmentCategoryInputEl) attachmentCategoryInputEl.value = '';
        if (fileInput) fileInput.accept = '';
        // ensure any preview modal is hidden
        const overlayEl = document.getElementById('attachment-preview-overlay');
        if (overlayEl) overlayEl.classList.add('hidden');

        const currentNameEl = document.getElementById('chat-header-name');
        const currentAvatarEl = document.getElementById('chat-header-avatar');
        const currentName = currentNameEl?.textContent?.trim();
        const currentAvatar = currentAvatarEl?.src?.trim();
        if (currentName) {
            loadConversation(recipientId, currentName, currentAvatar);
        } else {
            loadConversation(recipientId);
        }
    } catch (err) {
        console.error('send attachment failed', err);
        alert('Gagal mengirim lampiran. Periksa konsol atau network tab.');
    }
}

// attach events
function initChat() {
    const userSearch = document.getElementById('user-search');
    userSearch.addEventListener('input', (e) => {
        const v = e.target.value;
        searchUsers(v, currentFilter);
    });

    const messageForm = document.getElementById('message-form');
    const attachmentBtn = document.getElementById('attachment-btn');
    const fileInput = document.getElementById('attachment-input');

    // submit form message
    messageForm.addEventListener('submit', sendMessage);

    // attach menu handling: show/hide category menu on clip click
    const attachmentMenu = document.getElementById('attachment-menu');
    const attachmentCategoryInput = document.getElementById('attachment-category');

    const showAttachmentMenu = (anchorEl) => {
        if (!attachmentMenu) return;
        attachmentMenu.classList.remove('hidden');
        // listen for outside clicks to hide
        setTimeout(() => document.addEventListener('click', outsideClickAttachment));
    };
    const hideAttachmentMenu = () => {
        if (!attachmentMenu) return;
        attachmentMenu.classList.add('hidden');
        document.removeEventListener('click', outsideClickAttachment);
    };
    const outsideClickAttachment = (ev) => {
        const menu = document.getElementById('attachment-menu');
        const btn = document.getElementById('attachment-btn');
        if (!menu || !btn) return;
        if (!menu.contains(ev.target) && !btn.contains(ev.target)) hideAttachmentMenu();
    };

    attachmentBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        // if UI disabled, ignore
        if (attachmentBtn.disabled) return;
        // toggle menu if open
        if (attachmentMenu && !attachmentMenu.classList.contains('hidden')) {
            hideAttachmentMenu();
            return;
        }
        showAttachmentMenu(attachmentBtn);
    });

    // when an attachment option is clicked, set accept, remember category and open file picker
    document.querySelectorAll('.attachment-option').forEach(opt => {
        opt.addEventListener('click', (e) => {
            const accept = opt.dataset.accept || '';
            const cat = opt.dataset.category || '';
            if (fileInput) fileInput.accept = accept;
            if (attachmentCategoryInput) attachmentCategoryInput.value = cat;
            hideAttachmentMenu();
            if (fileInput) fileInput.click();
        });
    });

    // show preview modal instead of auto-sending when a file is chosen
    let pendingAttachmentFile = null;
    const overlay = document.getElementById('attachment-preview-overlay');
    const previewContent = document.getElementById('attachment-preview-content');
    const previewMeta = document.getElementById('attachment-preview-meta');
    const sendBtn = document.getElementById('attachment-send-btn');
    const cancelBtn = document.getElementById('attachment-cancel-btn');

    function clearPreview() {
        pendingAttachmentFile = null;
        if (previewContent) previewContent.innerHTML = '';
        if (previewMeta) previewMeta.textContent = '';
        if (fileInput) { fileInput.value = null; fileInput.accept = ''; }
        if (attachmentCategoryInput) attachmentCategoryInput.value = '';
    }

    function hidePreview() {
        if (overlay) overlay.classList.add('hidden');
        clearPreview();
    }

    function showPreviewForFile(file) {
        if (!file) return;
        pendingAttachmentFile = file;
        if (previewContent) {
            previewContent.innerHTML = '';
            const type = file.type || '';
            if (type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'max-h-60 max-w-full rounded';
                previewContent.appendChild(img);
            } else if (type.startsWith('audio/')) {
                const aud = document.createElement('audio');
                aud.controls = true;
                aud.src = URL.createObjectURL(file);
                previewContent.appendChild(aud);
            } else if (type.startsWith('video/')) {
                const vid = document.createElement('video');
                vid.controls = true;
                vid.className = 'max-h-60 max-w-full rounded';
                vid.src = URL.createObjectURL(file);
                previewContent.appendChild(vid);
            } else {
                const wrap = document.createElement('div');
                wrap.className = 'flex items-center gap-3';
                const icon = document.createElement('div');
                icon.className = 'h-10 w-10 bg-gray-100 rounded flex items-center justify-center text-gray-500';
                icon.textContent = '📄';
                const name = document.createElement('div');
                name.className = 'text-sm text-gray-700';
                name.textContent = file.name;
                wrap.appendChild(icon);
                wrap.appendChild(name);
                previewContent.appendChild(wrap);
            }
        }
        if (previewMeta) {
            const sizeKB = Math.round(file.size / 1024);
            const cat = document.getElementById('attachment-category')?.value || '';
            previewMeta.textContent = `${file.name} • ${sizeKB} KB ${cat ? '• ' + cat : ''}`;
        }
        if (overlay) overlay.classList.remove('hidden');
        // focus send button for accessibility
        setTimeout(() => sendBtn?.focus(), 50);
    }

    // file input change -> show preview if file picked, otherwise clear category
    fileInput.addEventListener('change', (e) => {
        if (fileInput.files && fileInput.files[0]) {
            showPreviewForFile(fileInput.files[0]);
        } else {
            // user canceled selection; clear category
            if (attachmentCategoryInput) attachmentCategoryInput.value = '';
            if (fileInput) fileInput.accept = '';
        }
    });

    // send/cancel buttons
    if (sendBtn) sendBtn.addEventListener('click', async () => {
        if (!pendingAttachmentFile) return;
        hidePreview();
        await sendAttachment(pendingAttachmentFile);
    });
    if (cancelBtn) cancelBtn.addEventListener('click', (e) => { e.preventDefault(); hidePreview(); });

    // modal handlers: edit & confirm
    const editModal = document.getElementById('edit-modal');
    const editText = document.getElementById('edit-modal-text');
    const editCancelBtn = document.getElementById('edit-cancel-btn');
    const editSaveBtn = document.getElementById('edit-save-btn');

    const confirmModal = document.getElementById('confirm-modal');
    const confirmCancelBtn = document.getElementById('confirm-cancel-btn');
    const confirmOkBtn = document.getElementById('confirm-ok-btn');

    if (editCancelBtn) editCancelBtn.addEventListener('click', () => { editModal?.classList.add('hidden'); });
    if (editSaveBtn) editSaveBtn.addEventListener('click', async () => {
        const messageId = editModal?.dataset?.messageId;
        if (!messageId) return;
        const newText = editText?.value || '';
        // additional guard: double-check creation time hasn't exceeded 20 minutes
        const bubbleEl = document.querySelector(`[data-message-id="${messageId}"]`);
        const createdAt = bubbleEl?.getAttribute('data-created-at');
        if (createdAt) {
            const ageMinutes = (Date.now() - new Date(createdAt).getTime()) / 60000;
            if (ageMinutes > 20) {
                alert('Waktu edit telah habis (20 menit).');
                editModal?.classList.add('hidden');
                return;
            }
        }
        try {
            await axios.put(`/messages/${messageId}`, { body: newText });
            editModal.classList.add('hidden');
            loadConversation(currentConversationUserId);
        } catch (err) {
            console.error('edit failed', err);
            alert('Gagal mengubah pesan.');
        }
    });

    if (confirmCancelBtn) confirmCancelBtn.addEventListener('click', () => { confirmModal?.classList.add('hidden'); });
    if (confirmOkBtn) confirmOkBtn.addEventListener('click', async () => {
        const messageId = confirmModal?.dataset?.messageId;
        if (!messageId) return;
        try {
            await axios.delete(`/messages/${messageId}`);
            confirmModal.classList.add('hidden');
            loadConversation(currentConversationUserId);
        } catch (err) {
            console.error('delete failed', err);
            alert('Gagal menghapus pesan.');
        }
    });

    // close modals on Escape and click outside
    document.addEventListener('keydown', (ev) => {
        if (ev.key === 'Escape') {
            editModal?.classList?.add('hidden');
            confirmModal?.classList?.add('hidden');
            overlay?.classList?.add('hidden');
        }
    });

    [editModal, confirmModal].forEach(m => {
        if (!m) return;
        m.addEventListener('click', (ev) => { if (ev.target === m) m.classList.add('hidden'); });
    });

    // filter buttons
    const filterAll = document.getElementById('filter-all');
    const filterUnread = document.getElementById('filter-unread');
    const filterImportant = document.getElementById('filter-important');

    [filterAll, filterUnread, filterImportant].forEach(btn => {
        btn.addEventListener('click', (e) => {
            const f = btn.dataset.filter;
            setActiveFilter(f);
            searchUsers(userSearch.value || '', f);
        });
    });

    // initial load of users
    setActiveFilter('all');
    searchUsers('', 'all');
    // show empty right panel until a user is selected
    disableChatUI();
}

let currentFilter = 'all';
function setActiveFilter(f) {
    currentFilter = f;
    document.querySelectorAll('#chat-filters button').forEach(b => b.classList.remove('bg-green-100', 'text-green-700'));
    const active = document.querySelector(`#chat-filters button[data-filter="${f}"]`);
    if (active) { active.classList.remove('bg-gray-100','text-gray-700'); active.classList.add('bg-green-100','text-green-700'); }
}
document.addEventListener('DOMContentLoaded', () => {
    initChat();
});

export {};
