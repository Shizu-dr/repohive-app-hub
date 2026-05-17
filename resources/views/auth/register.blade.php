{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Register')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');

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
    .register-wrapper {
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
        max-width: 440px;
        width: 100%;
        text-align: center;
        box-shadow: 0 20px 35px -10px rgba(0,0,0,0.5);
        transition: border-color 0.2s;
    }
    .card:hover { border-color: #e3000f; }
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
    .error-box p { margin: 0; }
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
    .btn-primary:hover { background: #b0000c; }
    .btn-outline {
        background: transparent;
        border: 1px solid #333;
        color: #ddd;
    }
    .btn-outline:hover { border-color: #e3000f; color: white; }
    hr {
        border-color: #222;
        margin: 20px 0;
    }
    .note {
        margin-top: 20px;
        font-size: 12px;
        color: #777;
    }
    .note a {
        color: #e3000f;
        text-decoration: none;
        font-weight: 700;
    }
</style>

<div class="page">
    <div class="orb"></div>
    <div class="orb2"></div>

    <div class="register-wrapper">
        <div class="card">

            <h1>Create Account</h1>
            <p class="muted">Sign up to get started</p>

            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <p>✕ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf
                <div class="form-group">
                    <label>FULL NAME</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label>EMAIL ADDRESS</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@gmail.com" required>
                </div>

                <div class="form-group">
                    <label>PHONE NUMBER <span style="color:#555;">(optional)</span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+63 900 000 0000">
                </div>

                <div class="form-group">
                    <label>PASSWORD</label>
                    <input type="password" name="password" placeholder="Min. 8 characters" required>
                </div>

                <div class="form-group">
                    <label>CONFIRM PASSWORD</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat password" required>
                </div>

                <button type="submit" class="btn btn-primary">🚀 CREATE ACCOUNT</button>
            </form>

            <hr>

            <a href="{{ route('auth.google') }}" class="btn btn-outline">
                <img src="https://www.google.com/favicon.ico" alt="Google" height="14" style="margin-right: 6px;">
                SIGN UP WITH GOOGLE
            </a>

            <div class="note">
                Already have an account? <a href="{{ route('login') }}">Login here</a>
            </div>
        </div>
    </div>
</div>
@endsection
