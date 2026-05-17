// ── OTP: Phone ──
function sendPhoneOtp() {
    const phone = document.getElementById('phone').value.trim();
    const emailEl = document.getElementById('email');
    const email = emailEl ? emailEl.value.trim() : '';
    if (!phone) return alert('Enter a phone number.');

    fetch('/otp/phone', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ phone, email })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem('otp_target', phone);
            window.location.href = '/otp/verify';
        } else {
            alert(data.message || 'Failed to send OTP.');
        }
    })
    .catch(() => alert('Server error. Try again.'));
}

// ── OTP: Email ──
function sendEmailOtp() {
    const email = document.getElementById('email').value.trim();
    if (!email) return alert('Enter an email address.');

    fetch('/otp/email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ email })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem('otp_target', email);
            window.location.href = '/otp/verify';
        } else {
            alert(data.message || 'Failed to send OTP.');
        }
    })
    .catch(() => alert('Server error. Try again.'));
}

// ── OTP: Verify Page ──
document.addEventListener('DOMContentLoaded', () => {
    const target = document.getElementById('otpTarget');
    if (target) target.textContent = localStorage.getItem('otp_target') || '—';

    const otpInputs = document.querySelectorAll('.otp');
    otpInputs.forEach((input, i) => {
        input.addEventListener('input', () => {
            if (input.value && i < otpInputs.length - 1) otpInputs[i + 1].focus();
        });
        input.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !input.value && i > 0) otpInputs[i - 1].focus();
        });
    });
});

function validateOtp() {
    const inputs = document.querySelectorAll('.otp');
    const code = Array.from(inputs).map(i => i.value).join('');
    const msg = document.getElementById('message');

    if (code.length < 6) {
        msg.textContent = 'Enter all 6 digits.';
        msg.style.color = '#e3000f';
        return;
    }

    const target = localStorage.getItem('otp_target');

    fetch('/otp/verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ email: target, otp: code })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            msg.textContent = '✅ OTP verified! Redirecting...';
            msg.style.color = '#4caf7d';
            setTimeout(() => window.location.href = '/dashboard', 1500);
        } else {
            msg.textContent = '❌ ' + (data.message || 'Invalid OTP.');
            msg.style.color = '#e3000f';
            document.querySelectorAll('.otp').forEach(i => i.value = '');
            document.querySelector('.otp').focus();
        }
    })
    .catch(() => {
        msg.textContent = '❌ Server error. Try again.';
        msg.style.color = '#e3000f';
    });
}

// ── Google Login ──
function loginWithGoogle() {
    alert('Google login not connected yet.');
}
