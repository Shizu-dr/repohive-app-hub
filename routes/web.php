<?php

use App\Http\Controllers\SmsController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// ── Home ──
Route::get('/', fn() => view('home'))->name('home');

// ── Auth ──
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Google OAuth ──
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ── OTP Phone ──
Route::view('/otp/phone', 'otp-phone')->name('otp.phone');
Route::post('/otp/phone', [SmsController::class, 'sendSms'])->name('otp.phone.send');

// ── OTP Email ──
Route::view('/otp/email', 'otp-email')->name('otp.email');
Route::post('/otp/email', [EmailController::class, 'sendEmail'])->name('otp.email.send');

// ── OTP Verify ──
Route::view('/otp/verify', 'otp-verify')->name('otp.verify');
Route::post('/otp/verify', [EmailController::class, 'verifyOtp'])->name('otp.verify.post');

// ── Mailbox (static view – for demo) ──
Route::view('/mailbox', 'mailbox')->name('mailbox');

// ── Chatbot ──
Route::post('/chatbot/message', [ChatbotController::class, 'message'])->name('chatbot.message');

// ── Authenticated Routes (require login) ──
Route::middleware(['auth'])->group(function () {
    // Dashboard (your email interface)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/dashboard/send', [EmailController::class, 'sendMail'])->name('dashboard.send');

    // Get sent emails (JSON)
    Route::get('/sent-emails', [EmailController::class, 'getSentEmails'])->name('sent.emails');

    // Account Settings
    Route::get('/account-settings', function () {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->is_current_device = ($session->id === session()->getId());
                return $session;
            });
        return view('account-settings', compact('sessions'));
    })->name('account.settings');

    // Profile management routes (PUT, DELETE)
    Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::put('/profile/name', [ProfileController::class, 'updateName'])->name('profile.name');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/sessions/logout-others', [ProfileController::class, 'logoutOtherDevices'])->name('profile.sessions.logout-others');
    Route::delete('/profile/sessions/{id}', [ProfileController::class, 'revokeSession'])->name('profile.sessions.revoke');
});
