@extends('layouts.app')

@section('title', 'Mailbox')

@section('content')

<div class="mailbox">

    <aside class="sidebar">
        <div class="brand white">🐝 {{ config('app.name') }}</div>

        <button class="compose-btn" onclick="openCompose()">+ Compose</button>

        <a class="menu active" onclick="showInbox()">Inbox <span>3</span></a>
        <a class="menu" onclick="showSent()">Sent <span id="sentCount">0</span></a>
        <a class="menu">Drafts <span>0</span></a>
        <a class="menu">Archived <span>4</span></a>
    </aside>

    <main class="main">
        <header class="topbar">
            <div>
                <h2 id="mailTitle">Inbox</h2>
                <small id="userEmail">Verified User</small>
            </div>
            <input id="searchMail" placeholder="Search mail..." onkeyup="filterMail()">
        </header>

        <section class="mail-area">
            <div id="mailList" class="mail-list"></div>

            <div class="preview">
                <h2 id="previewTitle">Select an email</h2>
                <p id="previewMeta" class="muted"></p>
                <p id="previewBody"></p>
            </div>
        </section>
    </main>

</div>

<div id="composeModal" class="modal">
    <div class="modal-card">
        <button class="close" onclick="closeCompose()">×</button>
        <h2>Compose Email</h2>

        <label for="composeTo">To</label>
        <input id="composeTo" type="email" placeholder="recipient@email.com">

        <label for="composeSubject">Subject</label>
        <input id="composeSubject" type="text" placeholder="Email subject">

        <label for="composeBody">Message</label>
        <textarea id="composeBody" placeholder="Write your message..."></textarea>

        <button class="btn primary" onclick="sendEmail()">Send Email</button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    loadMailbox();
</script>
@endpush
