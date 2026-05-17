@extends('layouts.app')

@section('title', 'Validate OTP')

@section('content')

<div class="center-screen">
    <div class="card">
        <div class="brand">🔐 OTP Verification</div>
        <h1>Validate OTP</h1>
        <p class="muted">
            Code sent to: <strong id="otpTarget"></strong>
        </p>

        <div class="otp-box">
            @for ($i = 0; $i < 6; $i++)
                <input maxlength="1" class="otp">
            @endfor
        </div>

        <button class="btn primary" onclick="validateOtp()">Verify OTP</button>
        <p id="message" class="muted center"></p>
    </div>
</div>

@endsection
