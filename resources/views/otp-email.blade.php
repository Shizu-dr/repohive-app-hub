{{-- resources/views/otp-verify.blade.php --}}
@extends('layouts.app')

@section('title', 'Validate OTP')

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
        max-width: 420px;
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
    .otp-box {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-bottom: 28px;
    }
    .otp-box input {
        width: 50px;
        height: 56px;
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        background: #1a1a1a;
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        color: white;
        transition: all 0.2s;
    }
    .otp-box input:focus {
        border-color: #e3000f;
        outline: none;
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
    .center { text-align: center; }
    #message {
        font-size: 12px;
        margin-top: 16px;
        color: #e3000f;
    }
</style>

<div class="page">
    <div class="orb"></div>
    <div class="orb2"></div>

    <div class="otp-wrapper">
        <div class="card">
            <div class="brand">🔐 OTP Verification</div>
            <h1>Validate OTP</h1>
            <p class="muted">
                Code sent to: <strong id="otpTarget"></strong>
            </p>

            <div class="otp-box">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" class="otp-digit" data-index="{{ $i }}" autocomplete="off">
                @endfor
            </div>

            <button class="btn btn-primary" onclick="validateOtp()">VERIFY OTP</button>
            <p id="message" class="center muted"></p>
        </div>
    </div>
</div>

<script>
    // Auto-focus navigation between OTP inputs
    const inputs = document.querySelectorAll('.otp-digit');
    inputs.forEach((input, idx) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && idx < inputs.length - 1) {
                inputs[idx + 1].focus();
            }
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && idx > 0 && !input.value) {
                inputs[idx - 1].focus();
            }
        });
    });

    // Retrieve target from sessionStorage
    const target = sessionStorage.getItem('otp_target') || 'your email/phone';
    document.getElementById('otpTarget').innerText = target;

    function validateOtp() {
        let otp = '';
        inputs.forEach(input => { otp += input.value; });
        if (otp.length !== 6) {
            document.getElementById('message').innerText = 'Please enter the 6-digit code.';
            return;
        }

        fetch("{{ route('otp.verify.post') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ target: target, otp: otp })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('OTP verified successfully! You are now logged in.');
                sessionStorage.removeItem('otp_target');
                sessionStorage.removeItem('otp_method');
                window.location.href = "{{ route('dashboard') }}";
            } else {
                document.getElementById('message').innerText = data.message || 'Invalid OTP. Please try again.';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('message').innerText = 'An error occurred. Please try again.';
        });
    }
</script>
@endsection
