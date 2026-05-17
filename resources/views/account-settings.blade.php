@extends('layouts.app')

@section('title', 'Account Settings')

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

    /* Sidebar internal elements */
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

    .link-icon {
        font-size: 18px;
        width: 28px;
        text-align: center;
        flex-shrink: 0;
    }
    .link-text {
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .dash-sidebar:hover .link-text { opacity: 1; }

    /* Power icon */
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

    /* Account settings specific */
    .settings-container { max-width: 1100px; margin: 0 auto; }
    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    .card {
        background: #0f0f0f;
        border: 1px solid #1a1a1a;
        border-radius: 24px;
        padding: 28px;
        transition: border-color 0.2s;
    }
    .card:hover { border-color: #e3000f; }
    .full-width { grid-column: span 2; }
    h2 {
        font-size: 20px;
        font-weight: 700;
        color: white;
        margin-bottom: 8px;
    }
    .card-sub {
        color: #888;
        font-size: 13px;
        margin-bottom: 24px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    input {
        width: 100%;
        padding: 12px 14px;
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        font-size: 14px;
        color: white;
        transition: all 0.2s;
    }
    input:focus {
        outline: none;
        border-color: #e3000f;
        box-shadow: 0 0 0 2px rgba(227,0,15,0.2);
    }
    input:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 40px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        background: #e3000f;
        color: white;
    }
    .btn-secondary {
        background: transparent;
        border: 1px solid #333;
        color: #ddd;
    }
    .btn-secondary:hover {
        border-color: #e3000f;
        color: white;
    }
    .avatar-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .avatar-img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e3000f;
    }
    .avatar-initials {
        width: 80px;
        height: 80px;
        background: #e3000f;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
    }
    .success-message {
        background: #0a2e1a;
        border: 1px solid #00c853;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 24px;
        color: #00c853;
        font-size: 13px;
    }
    .error-message {
        background: #1a0000;
        border: 1px solid #e3000f;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 24px;
        color: #e3000f;
        font-size: 13px;
    }
    hr { border-color: #1f1f1f; margin: 20px 0; }
    .session-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #1f1f1f;
    }
    .session-info { font-size: 13px; }
    .session-device { font-weight: 600; color: white; }
    .current-badge {
        background: #e3000f20;
        color: #e3000f;
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 10px;
    }
    @media (max-width: 768px) {
        .grid { grid-template-columns: 1fr; }
        .full-width { grid-column: span 1; }
        .dash-sidebar { width: 60px; }
        .dash-main { margin-left: 60px; }
        .dash-sidebar:hover { width: 220px; }
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
                    <div class="sidebar-user-email">Guest</div>
                </div>
            @endauth
        </div>

        <p class="sidebar-label">Main Menu</p>
        <a class="dash-link" href="{{ route('dashboard') }}#compose">
            <span class="link-icon">✉️</span><span class="link-text">Compose</span>
        </a>
        <a class="dash-link" href="{{ route('dashboard') }}#sent">
            <span class="link-icon">📤</span><span class="link-text">Sent</span>
        </a>
        <a class="dash-link" href="{{ route('dashboard') }}#mailbox">
            <span class="link-icon">📬</span><span class="link-text">Mailbox</span>
        </a>
        <a class="dash-link" href="{{ route('dashboard') }}#chatbot">
            <span class="link-icon">🤖</span><span class="link-text">AI Chatbot</span>
        </a>
        <a class="dash-link active" href="{{ route('account.settings') }}">
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
        <div class="settings-container">
            @if(session('success'))
                <div class="success-message">✓ {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        ✕ {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <div class="grid">
                {{-- Profile Information Card (left) --}}
                <div class="card">
                    <h2>Profile Information</h2>
                    <p class="card-sub">Update your account's profile information and email address.</p>

                    <div class="avatar-section">
                        @php
                            $avatarPath = Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : null;
                        @endphp
                        @if($avatarPath)
                            <img src="{{ $avatarPath }}" alt="Avatar" class="avatar-img" id="avatarPreview">
                        @else
                            <div class="avatar-initials">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        @endif
                        <div>
                            <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="file" name="avatar" accept="image/*" onchange="this.form.submit()" style="display:none;" id="avatarInput">
                                <button type="button" class="btn btn-secondary" onclick="document.getElementById('avatarInput').click()">Select a new photo</button>
                            </form>
                            <p class="card-sub" style="margin-top: 8px;">JPG, PNG, GIF up to 2MB</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.name') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" disabled>
                        </div>
                        <button type="submit" class="btn">Save</button>
                    </form>
                </div>

                {{-- Update Password Card (right) --}}
                <div class="card">
                    <h2>Update Password</h2>
                    <p class="card-sub">Ensure your account is using a long, random password to stay secure.</p>

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn">Save</button>
                    </form>
                </div>

                {{-- Browser Sessions Card (full width) --}}
                <div class="card full-width">
                    <h2>Browser Sessions</h2>
                    <p class="card-sub">Manage and log out your active sessions on other browsers and devices.</p>
                    <p class="card-sub" style="margin-bottom: 20px;">
                        If necessary, you may log out of all of your other browser sessions across all of your devices.
                        Some of your recent sessions are listed below; however, this list may not be exhaustive.
                        If you feel your account has been compromised, you should also update your password.
                    </p>

                    @if($sessions && $sessions->count() > 0)
                        @foreach($sessions as $session)
                            <div class="session-item">
                                <div class="session-info">
                                    <span class="session-device">
                                        {{ $session->user_agent ? (str_contains($session->user_agent, 'Windows') ? 'Windows' : (str_contains($session->user_agent, 'Mac') ? 'Mac' : (str_contains($session->user_agent, 'iPhone') ? 'iPhone' : (str_contains($session->user_agent, 'Android') ? 'Android' : 'Unknown')))) : 'Unknown' }} -
                                        {{ $session->user_agent ? (str_contains($session->user_agent, 'Chrome') ? 'Chrome' : (str_contains($session->user_agent, 'Firefox') ? 'Firefox' : (str_contains($session->user_agent, 'Safari') ? 'Safari' : (str_contains($session->user_agent, 'Edg') ? 'Edge' : 'Browser')))) : 'Browser' }}
                                    </span>
                                    <div class="session-details">
                                        {{ $session->ip_address ?? 'Unknown IP' }}
                                        @if($session->is_current_device) , <strong>This device</strong> @endif
                                    </div>
                                    <div class="session-details">
                                        Last active: {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                    </div>
                                </div>
                                @if(!$session->is_current_device)
                                    <form method="POST" action="{{ route('profile.sessions.revoke', $session->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary" style="padding:6px 16px; font-size:12px;">Logout</button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary" style="padding:6px 16px; font-size:12px; opacity:0.6; cursor:default;" disabled>Current</button>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="session-item">No active sessions found.</div>
                    @endif

                    <hr>
                    <form method="POST" action="{{ route('profile.sessions.logout-others') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width: auto; padding: 10px 24px;">🚪 Logout Other Browser Sessions</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.getElementById('avatarInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('avatarPreview');
                if (preview) preview.src = event.target.result;
                else {
                    // If no preview image, create an img element
                    const avatarSection = document.querySelector('.avatar-section');
                    const oldDiv = document.querySelector('.avatar-initials');
                    if (oldDiv) {
                        const newImg = document.createElement('img');
                        newImg.src = event.target.result;
                        newImg.className = 'avatar-img';
                        newImg.id = 'avatarPreview';
                        oldDiv.replaceWith(newImg);
                    }
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
