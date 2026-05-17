{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    .page {
        min-height: 100vh;
        background: #0a0a0a;
        font-family: 'Inter', sans-serif;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Animated orbs same as home page */
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

    /* Login Card - matches home card style but centered */
    .login-wrapper {
        position: relative;
        z-index: 2;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    .card {
        background: #0f0f0f;
        border: 1px solid #1a1a1a;
        border-radius: 32px;
        padding: 28px 32px;
        max-width: 420px;
        width: 100%;
        text-align: center;
        box-shadow: 0 20px 35px -10px rgba(0,0,0,0.5);
        transition: border-color 0.2s;
    }
    .card:hover {
        border-color: #e3000f;
    }
    .brand {
        font-size: 18px;
        font-weight: 800;
        color: #e3000f;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
    }
    h1 {
        font-size: 28px;
        font-weight: 800;
        color: white;
        margin: 0 0 6px 0;
    }
    .muted {
        color: #888;
        font-size: 13px;
        margin-bottom: 24px;
    }
    .error-box {
        background: #1a0000;
        border: 1px solid #e3000f;
        border-radius: 12px;
        padding: 10px;
        margin-bottom: 20px;
        color: #e3000f;
        font-size: 12px;
        font-weight: 500;
    }
    .form-group {
        margin-bottom: 18px;
        text-align: left;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        font-size: 12px;
    }
    .remember-me {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #aaa;
    }
    .remember-me input {
        width: 14px;
        height: 14px;
        margin: 0;
        accent-color: #e3000f;
    }
    .forgot-link {
        color: #e3000f;
        text-decoration: none;
        font-weight: 500;
        font-size: 12px;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        border-radius: 40px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    .btn-primary {
        background: #e3000f;
        color: white;
    }
    .btn-primary:hover {
        background: #b0000c;
    }
    .btn-outline {
        background: transparent;
        border: 1px solid #333;
        color: #ddd;
    }
    .btn-outline:hover {
        border-color: #e3000f;
        color: white;
    }
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 20px 0;
        color: #555;
        font-size: 11px;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #222;
    }
    .divider span {
        margin: 0 12px;
    }
    .otp-row {
        display: flex;
        gap: 12px;
        margin: 16px 0;
    }
    .otp-row .btn-outline {
        padding: 10px;
        font-size: 12px;
    }
    .register-link {
        margin-top: 20px;
        font-size: 12px;
        color: #777;
    }
    .register-link a {
        color: #e3000f;
        text-decoration: none;
        font-weight: 700;
    }
    hr {
        border-color: #222;
        margin: 16px 0;
    }
</style>

<div class="page">
    <div class="orb"></div>
    <div class="orb2"></div>

    <div class="login-wrapper">
        <div class="card">
            <div class="brand">{{ config('app.name') }}</div>
            <h1>Welcome Back</h1>
            <p class="muted">Login to access your dashboard</p>

            @if(session('error'))
                <div class="error-box">✕ {{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="form-group">
                    <label>EMAIL ADDRESS</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span style="color:#e3000f; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>PASSWORD</label>
                    <input type="password" name="password" required>
                    @error('password')
                        <span style="color:#e3000f; font-size:11px; margin-top:4px; display:block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">LOG IN WITH EMAIL</button>
            </form>

            <div class="divider">
                <span>OR LOGIN WITH OTP</span>
            </div>

            <div class="otp-row">
                <a href="{{ route('otp.phone') }}" class="btn btn-outline">📱 PHONE OTP</a>
                <a href="{{ route('otp.email') }}" class="btn btn-outline">✉️ EMAIL OTP</a>
            </div>

            <a href="{{ route('auth.google') }}" class="btn btn-outline" style="margin-top: 0;">
                <!-- Fixed Google icon – works without local file -->
                <img src="https://www.google.com/favicon.ico" alt="Google" height="14" style="margin-right: 6px;">
                LOGIN WITH GOOGLE
            </a>

            <hr>

            <div class="register-link">
                No account yet? <a href="{{ route('register') }}">Create one here</a>
            </div>
        </div>
    </div>
</div>
@endsection
