@extends('layouts.app')

@section('title', 'DDS – Dashboard')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #0a0a0a; color: white; }

    .dashboard-layout { display: flex; min-height: 100vh; }

    /* Collapsible Sidebar */
    .dash-sidebar {
        width: 70px;
        background: #0f0f0f;
        border-right: 1px solid #1a1a1a;
        padding: 28px 8px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex-shrink: 0;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        transition: width 0.3s ease;
        z-index: 100;
    }
    .dash-sidebar:hover { width: 260px; }
    .dash-main {
        flex: 1;
        margin-left: 70px;
        padding: 40px;
        overflow-y: auto;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }
    .sidebar-logo {
        font-size: 20px;
        font-weight: 900;
        color: white;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
    }
    .sidebar-logo span { color: #e3000f; }
    .sidebar-user {
        background: #1a1a1a;
        border-radius: 12px;
        padding: 8px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
    }
    .sidebar-user img, .user-avatar-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .user-avatar-placeholder {
        background: #e3000f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 16px;
    }
    .sidebar-user-info {
        opacity: 0;
        transition: opacity 0.2s ease;
        white-space: nowrap;
    }
    .dash-sidebar:hover .sidebar-user-info { opacity: 1; }
    .sidebar-user-name { font-weight: 700; font-size: 14px; color: white; }
    .sidebar-user-email { font-size: 11px; color: #555; }
    .sidebar-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #333;
        padding: 8px 8px 4px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .dash-sidebar:hover .sidebar-label { opacity: 1; }
    .dash-link, .btn-logout {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: #555;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: 1px solid transparent;
        background: none;
        width: 100%;
        text-align: left;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
    }
    .dash-link:hover, .btn-logout:hover { color: white; background: #1a1a1a; border-color: #222; }
    .dash-link.active { color: white; background: #1a0000; border-color: #e3000f; }
    .link-icon { font-size: 18px; width: 28px; text-align: center; flex-shrink: 0; }
    .link-text {
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .dash-sidebar:hover .link-text { opacity: 1; }
    .power-icon {
        font-size: 20px;
        font-weight: normal;
        transition: color 0.2s ease;
        color: #e3000f;
    }
    .dash-sidebar:hover .power-icon { color: #00c853; }
    .sidebar-bottom {
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid #1a1a1a;
    }

    /* Dashboard panels */
    .dash-panel { display: none; }
    .dash-panel.active { display: block; }
    .panel-header { margin-bottom: 28px; }
    .panel-header h2 { font-size: 28px; font-weight: 900; color: white; letter-spacing: -1px; }
    .panel-header p { color: #555; font-size: 14px; margin-top: 6px; }

    /* Compose panel – black/red */
    .compose-form {
        background: #0f0f0f;
        border: 1px solid #1a1a1a;
        border-radius: 24px;
        padding: 28px;
        max-width: 640px;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 24px;
        transition: border-color 0.2s;
    }
    .compose-form:hover { border-color: #e3000f; }
    .compose-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .compose-field label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #e3000f;
    }
    .input-wrapper, .textarea-wrapper { position: relative; width: 100%; }
    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #555;
        pointer-events: none;
    }
    .compose-form input, .compose-form textarea {
        width: 100%;
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 16px;
        padding: 14px 16px 14px 42px;
        color: white;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: all 0.2s;
    }
    .compose-form textarea {
        padding: 14px 16px;
        min-height: 160px;
        resize: vertical;
    }
    .compose-form input:focus, .compose-form textarea:focus {
        border-color: #e3000f;
        box-shadow: 0 0 0 2px rgba(227,0,15,0.2);
    }
    .send-btn {
        background: #e3000f;
        color: white;
        border: none;
        border-radius: 40px;
        padding: 14px 24px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: auto;
        align-self: flex-start;
        box-shadow: 0 2px 8px rgba(227,0,15,0.3);
    }
    .send-btn:hover {
        background: #b0000c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(227,0,15,0.4);
    }
    .send-status {
        font-size: 13px;
        font-weight: 600;
        padding: 12px 16px;
        border-radius: 16px;
        display: none;
    }
    .send-status.success { background: #0a2e1a; border: 1px solid #00c853; color: #4ade80; display: block; }
    .send-status.error { background: #1a0000; border: 1px solid #e3000f; color: #f87171; display: block; }
    .send-status.loading { background: #1a1a1a; border: 1px solid #333; color: #aaa; display: block; }

    /* Sent panel */
    .sent-list { display: flex; flex-direction: column; gap: 12px; max-width: 700px; }
    .sent-item {
        background: #0f0f0f;
        border: 1px solid #1a1a1a;
        border-radius: 16px;
        padding: 20px 24px;
        transition: border-color 0.2s;
    }
    .sent-item:hover { border-color: #e3000f; }
    .sent-to { font-size: 13px; color: #555; margin-bottom: 6px; display: flex; justify-content: space-between; }
    .sent-to strong { color: #888; }
    .sent-time { color: #333; font-size: 12px; }
    .sent-subject { font-weight: 700; font-size: 15px; color: white; margin-bottom: 6px; }
    .sent-body { font-size: 13px; color: #444; line-height: 1.6; }

    /* Mailbox panel */
    .mailbox-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 16px;
        height: calc(100vh - 160px);
    }
    .mail-list-panel {
        background: #0f0f0f;
        border: 1px solid #1a1a1a;
        border-radius: 20px;
        overflow-y: auto;
    }
    .mail-item {
        position: relative;
        padding: 16px 20px;
        border-bottom: 1px solid #1a1a1a;
        cursor: pointer;
        transition: background 0.2s;
    }
    .mail-item:last-child { border-bottom: none; }
    .mail-item:hover { background: #1a1a1a; }
    .mail-item.active { background: #1a0000; border-left: 3px solid #e3000f; }
    .mail-from { font-weight: 700; font-size: 13px; color: white; margin-bottom: 4px; }
    .mail-subject { font-size: 12px; color: #555; }
    .mail-time-tag { font-size: 11px; color: #333; margin-top: 4px; }

    .mail-item .delete-mail-btn {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #555;
        font-size: 16px;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .mail-item:hover .delete-mail-btn { opacity: 1; }
    .mail-item .delete-mail-btn:hover { color: #e3000f; }
    .delete-mail-btn, .reply-mail-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
    }
    .delete-mail-btn:hover { color: #e3000f; }
    .reply-mail-btn {
        background: #e3000f;
        color: white;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .reply-mail-btn:hover { background: #b0000c; transform: translateY(-1px); }

    .mail-preview {
        background: #0f0f0f;
        border: 1px solid #1a1a1a;
        border-radius: 20px;
        padding: 28px;
        overflow-y: auto;
    }
    .mail-preview h2 { font-size: 20px; font-weight: 700; color: white; margin-bottom: 8px; }
    .mail-meta { font-size: 13px; color: #555; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #1a1a1a; }
    .mail-body { font-size: 14px; color: #888; line-height: 1.8; }

    /* Chatbot panel (unchanged) */
    .chat-container {
        max-width: 720px;
        background: #0f0f0f;
        border: 1px solid #1a1a1a;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 200px);
        overflow: hidden;
    }
    .chat-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 24px;
        border-bottom: 1px solid #1a1a1a;
        flex-shrink: 0;
    }
    .chat-orb {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #1a0000;
        border: 1px solid #e3000f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .chat-header-info { flex: 1; }
    .chat-header-info h3 { font-size: 15px; font-weight: 700; color: white; }
    .chat-header-info small { font-size: 12px; color: #555; }
    .ai-switcher { display: flex; gap: 8px; }
    .ai-btn {
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        border: 1px solid #222;
        background: #1a1a1a;
        color: #555;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
    }
    .ai-btn.active { background: #1a0000; border-color: #e3000f; color: #e3000f; }
    .ai-btn:hover { border-color: #e3000f; color: white; }
    .chat-window {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .chat-message { display: flex; gap: 10px; align-items: flex-end; }
    .chat-message.user { flex-direction: row-reverse; }
    .chat-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #1a1a1a;
        border: 1px solid #222;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .chat-bubble {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.6;
        background: #1a1a1a;
        border: 1px solid #222;
        color: #ccc;
    }
    .chat-message.user .chat-bubble {
        background: #e3000f;
        border-color: #e3000f;
        color: white;
        border-radius: 16px 16px 4px 16px;
    }
    .chat-message.bot .chat-bubble { border-radius: 16px 16px 16px 4px; }
    .chat-input-bar {
        display: flex;
        gap: 10px;
        padding: 16px 20px;
        border-top: 1px solid #1a1a1a;
        flex-shrink: 0;
    }
    .chat-input-bar input {
        flex: 1;
        background: #1a1a1a;
        border: 1px solid #222;
        border-radius: 10px;
        padding: 12px 16px;
        color: white;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border 0.2s;
    }
    .chat-input-bar input:focus { border-color: #e3000f; }
    .chat-input-bar .chat-send-btn {
        background: #e3000f;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: background 0.2s;
    }
    .chat-input-bar .chat-send-btn:hover { background: #b0000c; }

    .empty-state { text-align: center; padding: 60px 20px; color: #333; }
    .empty-state .empty-icon { font-size: 48px; margin-bottom: 16px; }
    .empty-state p { font-size: 14px; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #222; border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: #e3000f; }

    @media (max-width: 768px) {
        .dash-sidebar { width: 60px; }
        .dash-main { margin-left: 60px; }
        .dash-sidebar:hover { width: 220px; }
        .mailbox-layout { grid-template-columns: 1fr; height: auto; }
    }
</style>

<div class="dashboard-layout">
    <aside class="dash-sidebar">
        <div class="sidebar-logo">🛡️<span>DDS</span></div>
        <div class="sidebar-user">
            @auth
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="avatar">
                @else
                    <div class="user-avatar-placeholder">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-email">{{ Auth::user()->email }}</div>
                </div>
            @else
                <div class="user-avatar-placeholder">👤</div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">User</div>
                    <div class="sidebar-user-email">OTP User</div>
                </div>
            @endauth
        </div>

        <p class="sidebar-label">Main Menu</p>
        <button type="button" class="dash-link" onclick="showPanel('compose', this)">
            <span class="link-icon">✉️</span><span class="link-text">Compose</span>
        </button>
        <button type="button" class="dash-link" onclick="showPanel('sent', this)">
            <span class="link-icon">📤</span><span class="link-text">Sent</span>
        </button>
        <button type="button" class="dash-link" onclick="showPanel('mailbox', this)">
            <span class="link-icon">📬</span><span class="link-text">Mailbox</span>
        </button>
        <button type="button" class="dash-link" onclick="showPanel('chatbot', this)">
            <span class="link-icon">🤖</span><span class="link-text">AI Chatbot</span>
        </button>
        <a class="dash-link" href="{{ route('account.settings') }}">
            <span class="link-icon">⚙️</span><span class="link-text">Account Settings</span>
        </a>

        <p class="sidebar-label">Navigation</p>
        <a class="dash-link" href="{{ route('home') }}">
            <span class="link-icon">🏠</span><span class="link-text">Home</span>
        </a>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <span class="link-icon power-icon">⏻</span>
                    <span class="link-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="dash-main">
        <!-- Compose Panel -->
        <div id="panel-compose" class="dash-panel active">
            <div class="panel-header">
                <h2>✉️ Compose Email</h2>
                <p>Send an email to anyone directly from your dashboard.</p>
            </div>
            <div class="compose-form">
                <div class="compose-field">
                    <label for="to">To</label>
                    <div class="input-wrapper">
                        <span class="input-icon">📧</span>
                        <input id="to" type="email" placeholder="recipient@gmail.com">
                    </div>
                </div>
                <div class="compose-field">
                    <label for="subject">Subject</label>
                    <div class="input-wrapper">
                        <span class="input-icon">📝</span>
                        <input id="subject" type="text" placeholder="Enter subject">
                    </div>
                </div>
                <div class="compose-field">
                    <label for="body">Message</label>
                    <div class="textarea-wrapper">
                        <textarea id="body" placeholder="Write your message here..."></textarea>
                    </div>
                </div>
                <button type="button" class="send-btn" onclick="sendDashboardEmail()">
                    <span>📨</span> Send Email
                </button>
                <div id="sendMsg" class="send-status"></div>
            </div>
        </div>

        <!-- Sent Panel -->
        <div id="panel-sent" class="dash-panel">
            <div class="panel-header"><h2>📤 Sent Emails</h2><p>Emails you have sent (stored in database).</p></div>
            <div id="sentList" class="sent-list"><div class="empty-state"><div class="empty-icon">📭</div><p>Loading...</p></div></div>
        </div>

        <!-- Mailbox Panel -->
        <div id="panel-mailbox" class="dash-panel">
            <div class="panel-header"><h2>📬 Mailbox</h2><p>Your inbox messages (demo data).</p></div>
            <div class="mailbox-layout">
                <div class="mail-list-panel" id="mailList"></div>
                <div class="mail-preview" id="mailPreview">
                    <div class="empty-state"><div class="empty-icon">📩</div><p>Select an email to read.</p></div>
                </div>
            </div>
        </div>

        <!-- Chatbot Panel -->
        <div id="panel-chatbot" class="dash-panel">
            <div class="panel-header"><h2>🤖 AI Chatbot</h2><p>Chat with DeepSeek or Claude AI.</p></div>
            <div class="chat-container">
                <div class="chat-header">
                    <div class="chat-orb">🤖</div>
                    <div class="chat-header-info"><h3>AI Assistant</h3><small id="activeAiLabel">⚡ Using DeepSeek</small></div>
                    <div class="ai-switcher">
                        <button type="button" id="btn-deepseek" class="ai-btn active" onclick="switchAi('deepseek')">⚡ DeepSeek</button>
                        <button type="button" id="btn-claude" class="ai-btn" onclick="switchAi('claude')">🧠 Claude</button>
                    </div>
                </div>
                <div class="chat-window" id="chatWindow">
                    <div class="chat-message bot">
                        <div class="chat-avatar">🤖</div>
                        <div class="chat-bubble">Hi <strong>{{ Auth::check() ? Auth::user()->name : 'there' }}</strong>! Select <strong>DeepSeek</strong> or <strong>Claude</strong> above, then ask me anything!</div>
                    </div>
                </div>
                <div class="chat-input-bar">
                    <input type="text" id="chatInput" placeholder="Type your message..." onkeydown="handleChatKey(event)">
                    <button type="button" class="chat-send-btn" onclick="sendChat()">Send →</button>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Helper: escape HTML
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // ── Panel navigation with hash support ──
    function showPanel(name, el) {
        document.querySelectorAll('.dash-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.dash-link').forEach(l => l.classList.remove('active'));
        document.getElementById('panel-' + name).classList.add('active');
        if (el) el.classList.add('active');
        if (name === 'mailbox') loadMailbox();
        if (name === 'sent') loadSentEmails();
        window.location.hash = name;
    }

    function handleHash() {
        const hash = window.location.hash.substring(1);
        if (hash && ['compose', 'sent', 'mailbox', 'chatbot'].includes(hash)) {
            showPanel(hash, null);
        }
    }
    window.addEventListener('hashchange', handleHash);
    window.addEventListener('load', handleHash);

    // ── Compose Email (store in database) ──
    function sendDashboardEmail() {
        const to = document.getElementById('to').value.trim();
        const subject = document.getElementById('subject').value.trim();
        const body = document.getElementById('body').value.trim();
        const msg = document.getElementById('sendMsg');

        if (!to || !subject || !body) {
            msg.className = 'send-status error';
            msg.textContent = '⚠️ Please fill in all fields.';
            return;
        }

        msg.className = 'send-status loading';
        msg.textContent = '⏳ Sending...';

        fetch('/dashboard/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ to, subject, body })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                msg.className = 'send-status success';
                msg.textContent = '✅ Email sent to ' + to;
                // Clear form
                document.getElementById('to').value = '';
                document.getElementById('subject').value = '';
                document.getElementById('body').value = '';
                // Reload sent emails list
                loadSentEmails();
            } else {
                msg.className = 'send-status error';
                msg.textContent = '❌ ' + (data.message || 'Failed to send.');
            }
        })
        .catch(() => {
            msg.className = 'send-status error';
            msg.textContent = '❌ Server error. Try again.';
        });
    }

    // ── Sent Emails – load from database ──
    async function loadSentEmails() {
        const list = document.getElementById('sentList');
        list.innerHTML = '<div class="empty-state"><div class="empty-icon">⏳</div><p>Loading sent emails...</p></div>';
        try {
            const response = await fetch('/sent-emails', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const emails = await response.json();
            if (emails.length === 0) {
                list.innerHTML = '<div class="empty-state"><div class="empty-icon">📭</div><p>No emails sent yet.</p></div>';
            } else {
                list.innerHTML = emails.map(email => `
                    <div class="sent-item">
                        <div class="sent-to">
                            <span>To: <strong>${escapeHtml(email.to_email)}</strong></span>
                            <span class="sent-time">${new Date(email.created_at).toLocaleString()}</span>
                        </div>
                        <div class="sent-subject">${escapeHtml(email.subject)}</div>
                        <div class="sent-body">${escapeHtml(email.body)}</div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error(error);
            list.innerHTML = '<div class="empty-state"><div class="empty-icon">⚠️</div><p>Failed to load sent emails.</p></div>';
        }
    }

    // ── Mailbox with Delete & Reply ──
    let seedMails = [
        { id: 1, from: 'admin@dds.com', subject: 'Welcome to DDS!', body: 'Thanks for joining DDS — Detect, Defend, Secure. Your account is ready and protected.', time: '10:30 AM' },
        { id: 2, from: 'noreply@dds.com', subject: 'Your OTP Code', body: 'Your one-time password was sent successfully. It expires in 10 minutes. Do not share it with anyone.', time: '11:00 AM' },
        { id: 3, from: 'support@dds.com', subject: 'Need help?', body: 'Our support team is here 24/7. Reply to this email anytime and we will get back to you shortly.', time: '1:45 PM' },
    ];

    function loadMailbox() {
        const list = document.getElementById('mailList');
        if (seedMails.length === 0) {
            list.innerHTML = '<div class="empty-state" style="padding:40px;"><div class="empty-icon">📭</div><p>No emails in your inbox.</p></div>';
            document.getElementById('mailPreview').innerHTML = '<div class="empty-state"><div class="empty-icon">📩</div><p>Select an email to read.</p></div>';
            return;
        }
        list.innerHTML = seedMails.map(m => `
            <div class="mail-item" data-id="${m.id}" onclick="previewMail(${m.id}, this)">
                <div class="mail-from">${escapeHtml(m.from)}</div>
                <div class="mail-subject">${escapeHtml(m.subject)}</div>
                <div class="mail-time-tag">${m.time}</div>
                <button class="delete-mail-btn" onclick="event.stopPropagation(); deleteMail(${m.id})" title="Delete email">🗑️</button>
            </div>
        `).join('');
    }

    function previewMail(id, el) {
        const mail = seedMails.find(m => m.id === id);
        if (!mail) return;
        document.querySelectorAll('.mail-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('mailPreview').innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <h2>${escapeHtml(mail.subject)}</h2>
                <div>
                    <button class="delete-mail-btn" onclick="deleteMail(${mail.id})" style="background:#1a0000; border:1px solid #e3000f; border-radius:8px; padding:6px 12px; margin-right:8px; color:#e3000f; cursor:pointer;">🗑️ Delete</button>
                    <button class="reply-mail-btn" onclick="replyToMail(${mail.id})">✉️ Reply</button>
                </div>
            </div>
            <div class="mail-meta">From: <strong>${escapeHtml(mail.from)}</strong> &nbsp;·&nbsp; ${mail.time}</div>
            <div class="mail-body">${escapeHtml(mail.body)}</div>
        `;
    }

    function deleteMail(id) {
        if (confirm('Are you sure you want to delete this email?')) {
            seedMails = seedMails.filter(m => m.id !== id);
            loadMailbox();
            // Clear preview if the deleted email was being viewed
            const preview = document.getElementById('mailPreview');
            if (preview && preview.innerHTML.includes('Delete')) {
                preview.innerHTML = '<div class="empty-state"><div class="empty-icon">📩</div><p>Select an email to read.</p></div>';
            }
        }
    }

    function replyToMail(id) {
        const mail = seedMails.find(m => m.id === id);
        if (!mail) return;

        // Switch to Compose panel
        showPanel('compose', null);

        // Fill compose fields
        document.getElementById('to').value = mail.from;
        let subject = mail.subject;
        if (!subject.toLowerCase().startsWith('re:')) {
            subject = 'Re: ' + subject;
        }
        document.getElementById('subject').value = subject;

        const date = new Date().toLocaleString();
        const quotedBody = mail.body.split('\n').map(line => '> ' + line).join('\n');
        const replyBody = `On ${date}, ${mail.from} wrote:\n\n${quotedBody}\n\n`;
        document.getElementById('body').value = replyBody;
        document.getElementById('body').focus();
    }

    // ── Chatbot ──
    let selectedAi = 'deepseek';
    function switchAi(ai) {
        selectedAi = ai;
        document.querySelectorAll('.ai-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('btn-' + ai).classList.add('active');
        document.getElementById('activeAiLabel').textContent = ai === 'deepseek' ? '⚡ Using DeepSeek' : '🧠 Using Claude';
    }
    function handleChatKey(e) { if (e.key === 'Enter') sendChat(); }
    async function sendChat() {
        const input = document.getElementById('chatInput');
        const msg = input.value.trim();
        if (!msg) return;
        appendMessage('user', msg);
        input.value = '';
        const bubble = appendMessage('bot', '...');
        try {
            const res = await fetch('/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: msg, ai: selectedAi })
            });
            const data = await res.json();
            bubble.textContent = data.reply || 'No response.';
        } catch {
            bubble.textContent = '❌ Error connecting to AI.';
        }
        scrollChat();
    }
    function appendMessage(role, text) {
        const win = document.getElementById('chatWindow');
        const div = document.createElement('div');
        div.className = `chat-message ${role}`;
        div.innerHTML = `<div class="chat-avatar">${role === 'bot' ? '🤖' : '🧑'}</div><div class="chat-bubble">${text}</div>`;
        win.appendChild(div);
        scrollChat();
        return div.querySelector('.chat-bubble');
    }
    function scrollChat() { const w = document.getElementById('chatWindow'); if (w) w.scrollTop = w.scrollHeight; }
</script>
@endsection
