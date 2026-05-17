{{-- resources/views/otp-phone.blade.php --}}
@extends('layouts.app')

@section('title', 'Send OTP via Phone')

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
    .otp-wrapper {
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
        max-width: 400px;
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
        font-size: 26px;
        font-weight: 800;
        color: white;
        margin: 0 0 8px 0;
    }
    .muted {
        color: #888;
        font-size: 13px;
        margin-bottom: 24px;
    }
    .form-group {
        margin-bottom: 20px;
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
        margin-top: 8px;
    }
    .btn-primary {
        background: #e3000f;
        color: white;
    }
    .btn-primary:hover { background: #b0000c; }
    .link {
        display: inline-block;
        margin-top: 16px;
        color: #e3000f;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
    }
    .link:hover { text-decoration: underline; }
</style>

<div class="page">
    <div class="orb"></div>
    <div class="orb2"></div>

    <div class="otp-wrapper">
        <div class="card">
            <div class="brand">📱 Phone Verification</div>
            <h1>Send OTP to Phone</h1>
            <p class="muted">Enter your phone number to receive a 6-digit code via SMS</p>

            <div class="form-group">
                <label>PHONE NUMBER</label>
                <input type="tel" id="phone" placeholder="+63 900 000 0000">
            </div>

            <button class="btn btn-primary" onclick="sendPhoneOtp()">SEND OTP</button>
            <a class="link" href="{{ route('home') }}">← Back</a>
        </div>
    </div>
</div>

<script>
    function sendPhoneOtp() {
        const phone = document.getElementById('phone').value;
        if (!phone) {
            alert('Please enter your phone number.');
            return;
        }
        fetch("{{ route('otp.phone.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('OTP sent to ' + phone);
                window.location.href = "{{ route('otp.verify') }}";
            } else {
                alert(data.message || 'Failed to send OTP.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
</script>
@endsection
