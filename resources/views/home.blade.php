@extends('layouts.app')

@section('title', 'DDS – Detect, Defend, Secure')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    .page { min-height: 100vh; background: #0a0a0a; font-family: 'Inter', sans-serif; overflow: hidden; }

    .orb {
        position: fixed;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(227,0,15,0.2) 0%, transparent 70%);
        border-radius: 50%;
        top: -100px;
        right: -100px;
        pointer-events: none;
        z-index: 0;
    }
    .orb2 {
        position: fixed;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(227,0,15,0.1) 0%, transparent 70%);
        border-radius: 50%;
        bottom: -100px;
        left: -100px;
        pointer-events: none;
        z-index: 0;
    }

    nav {
        position: relative;
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 22px 60px;
        border-bottom: 1px solid #1a1a1a;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 20px;
        font-weight: 900;
        color: white;
        letter-spacing: -0.5px;
    }
    .logo span { color: #e3000f; }

    .nav-links { display: flex; gap: 12px; align-items: center; }
    .nav-links a {
        color: #888;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 999px;
        border: 1px solid #222;
        background: #111;
        transition: all 0.2s;
    }
    .nav-links a:hover { color: white; border-color: #e3000f; }

    .nav-links .nav-login {
        background: #e3000f;
        color: white;
        border-color: #e3000f;
    }
    .nav-links .nav-login:hover { background: #b0000c; border-color: #b0000c; }

    .hero {
        position: relative;
        z-index: 1;
        text-align: center;
        padding: 120px 20px 60px;
    }

    .hero h1 {
        font-size: 110px;
        font-weight: 900;
        color: white;
        line-height: 0.95;
        letter-spacing: -4px;
        text-transform: uppercase;
        margin-bottom: 24px;
    }
    .hero h1 em { font-style: normal; color: #e3000f; }

    .hero p { color: #666; font-size: 16px; margin-bottom: 20px; }

    .hero-btns {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 30px;
    }
    .hero-btns a {
        padding: 12px 28px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
    }
    .hero-btns .btn-red { background: #e3000f; color: white; }
    .hero-btns .btn-red:hover { background: #b0000c; }
    .hero-btns .btn-outline { border: 1px solid #333; color: #888; background: #111; }
    .hero-btns .btn-outline:hover { border-color: #e3000f; color: white; }

    .star {
        position: absolute;
        color: #e3000f;
        font-size: 40px;
        animation: spin 8s linear infinite;
    }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .marquee-wrap {
        position: relative;
        z-index: 1;
        border-top: 1px solid #1a1a1a;
        padding: 20px 0;
        overflow: hidden;
        margin-top: 60px;
    }
    .marquee-label {
        text-align: center;
        color: #333;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    .marquee { display: flex; gap: 40px; animation: marquee 20s linear infinite; white-space: nowrap; }
    .marquee span { color: #333; font-size: 13px; font-weight: 700; }
    @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

    footer {
        position: relative;
        z-index: 1;
        text-align: center;
        color: #333;
        font-size: 12px;
        padding: 30px;
        border-top: 1px solid #111;
    }
    footer span { color: #e3000f; }
    .hero-btns form { margin: 0; display: contents; }
    .hero-btns button {
    padding: 12px 28px;
    border-radius: 999px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    border: 1px solid #333;
    color: #888;
    background: #111;
    transition: all 0.2s;
}
.hero-btns button:hover { border-color: #e3000f; color: white; }
</style>

<div class="page">
    <div class="orb"></div>
    <div class="orb2"></div>

    {{-- Navbar --}}
    <nav>
        <div class="logo">🛡️ <span>DDS</span></div>
        <div class="nav-links">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-login">Dashboard →</a>
            @else
                <a href="{{ route('login') }}" class="nav-login">Login →</a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <div class="hero">
        @if(session('success'))
            <div style="background:#1a0000; border:1px solid #e3000f; border-radius:12px; padding:14px; margin:0 auto 30px; max-width:400px; color:#ff4444; font-weight:700;">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#1a0000; border:1px solid #e3000f; border-radius:12px; padding:14px; margin:0 auto 30px; max-width:400px; color:#ff4444; font-weight:700;">
                ❌ {{ session('error') }}
            </div>
        @endif

        <div style="position:relative; display:inline-block;">
            <div class="star" style="top:-20px; left:-80px;">✦</div>
            <h1>Detect<br><em>Defend</em><br>Secure</h1>
            <div class="star" style="bottom:-10px; right:-70px; font-size:24px; animation-duration:5s;">✦</div>
        </div>

        <p style="margin-top:30px;">We Craft <span style="color:#e3000f; font-weight:700;">Secure</span> Authentication For Your Applications.</p>

        <div class="hero-btns">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-red">Go to Dashboard →</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-outline">Logout</button>
                </form>
            @else
                <a href="{{ route('register') }}" class="btn-red">Get Started →</a>
                <a href="{{ route('auth.google') }}" class="btn-outline">Login with Google</a>
            @endauth
        </div>
    </div>

        <div class="faq-section">
            <p class="section-label">— FREQUENTLY ASKED QUESTIONS —</p>
            <div class="faq-container">
                <div class="faq-item">
                    <button class="faq-question">What is DDS?</button>
                    <div class="faq-answer">DDS (Detect, Defend, Secure) is a secure authentication and communication platform.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">How does OTP work?</button>
                    <div class="faq-answer">We send a 6-digit code via SMS or email using Repohive API. You enter it to verify your identity.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">Is Google Login secure?</button>
                    <div class="faq-answer">Yes, we use OAuth 2.0 with Google – no password is ever stored by us.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">Can I change my password later?</button>
                    <div class="faq-answer">Absolutely. Go to Account Settings > Update Password.</div>
                </div>
            </div>
        </div>

        <style>
        .faq-section {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 60px auto 40px;
            padding: 0 20px;
        }
        .faq-container {
            background: #0f0f0f;
            border: 1px solid #1a1a1a;
            border-radius: 24px;
            overflow: hidden;
        }
        .faq-item {
            border-bottom: 1px solid #1f1f1f;
        }
        .faq-item:last-child {
            border-bottom: none;
        }
        .faq-question {
            width: 100%;
            text-align: left;
            background: transparent;
            border: none;
            padding: 18px 20px;
            font-size: 16px;
            font-weight: 700;
            color: white;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }
        .faq-question:hover {
            background: #1a1a1a;
        }
        .faq-question::after {
            content: '+';
            font-size: 20px;
            color: #e3000f;
        }
        .faq-question.active::after {
            content: '−';
        }
        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            color: #aaa;
            font-size: 14px;
            line-height: 1.5;
        }
        .faq-answer.show {
            padding: 0 20px 18px;
            max-height: 200px;
        }
        </style>

    <script>
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const currentAnswer = button.nextElementSibling;
                const isCurrentlyActive = button.classList.contains('active');

                // Close all answers
                document.querySelectorAll('.faq-answer').forEach(answer => {
                    answer.classList.remove('show');
                });
                document.querySelectorAll('.faq-question').forEach(btn => {
                    btn.classList.remove('active');
                });

                // If the clicked question was not active, open it
                if (!isCurrentlyActive) {
                    button.classList.add('active');
                    currentAnswer.classList.add('show');
                }
            });
        });
    </script>
    {{-- Marquee --}}
    <div class="marquee-wrap">
        <p class="marquee-label">Trusted by students & fast-growing teams</p>
        <div class="marquee">
            <span>SMS API</span><span>·</span>
            <span>Email API</span><span>·</span>
            <span>OTP Verification</span><span>·</span>
            <span>Laravel</span><span>·</span>
            <span>Repohive</span><span>·</span>
            <span>AI Chatbot</span><span>·</span>
            <span>Mailbox</span><span>·</span>
            <span>DeepSeek</span><span>·</span>
            <span>Claude AI</span><span>·</span>
            <span>SMS API</span><span>·</span>
            <span>Email API</span><span>·</span>
            <span>OTP Verification</span><span>·</span>
            <span>Laravel</span><span>·</span>
            <span>Repohive</span><span>·</span>
            <span>AI Chatbot</span><span>·</span>
            <span>Mailbox</span><span>·</span>
            <span>DeepSeek</span><span>·</span>
            <span>Claude AI</span><span>·</span>
        </div>
    </div>

    <footer>© 2026 <span>DDS</span> — Detect, Defend, Secure. Built with Laravel & Repohive API.</footer>
</div>

@endsection
