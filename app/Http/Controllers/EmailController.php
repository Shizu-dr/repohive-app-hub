<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SentEmail;  // <-- correct import

class EmailController extends Controller
{
    // Send OTP via email
    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $otp = rand(100000, 999999);
        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));

        $response = Http::withToken(config('services.repohive_email.token'))
            ->acceptJson()
            ->timeout(30)
            ->post(rtrim(config('services.repohive_email.base_url'), '/') . '/email/send', [
                'to'      => $request->email,
                'from'    => env('MAIL_FROM_ADDRESS'),
                'subject' => 'Your OTP Code - ' . config('app.name'),
                'html'    => "<p>Your OTP code is: <strong>{$otp}</strong></p><p>This code expires in 10 minutes.</p>",
                'text'    => "Your OTP code is: {$otp}. This code expires in 10 minutes.",
            ]);

        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
        }
        return response()->json(['success' => false, 'message' => 'Failed to send OTP.'], 500);
    }

    // Verify OTP and log in
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'target' => 'required|string',
            'otp'    => 'required|string|size:6',
        ]);

        $target = $request->target;
        $cached = Cache::get('otp_' . $target);

        if (!$cached || (string)$cached !== (string)$request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 422);
        }

        // Find or create user
        $user = null;
        if (filter_var($target, FILTER_VALIDATE_EMAIL)) {
            $user = User::firstOrCreate(
                ['email' => $target],
                ['name' => explode('@', $target)[0], 'password' => bcrypt(uniqid())]
            );
        } else {
            $user = User::firstOrCreate(
                ['phone' => $target],
                ['name' => 'User_' . substr(preg_replace('/\D/', '', $target), -4), 'email' => null, 'password' => bcrypt(uniqid())]
            );
        }

        Auth::login($user, true);
        Cache::forget('otp_' . $target);
        return response()->json(['success' => true, 'message' => 'OTP verified and logged in.']);
    }

    // Send email from dashboard (store in database)
    public function sendMail(Request $request)
    {
        $request->validate([
            'to'      => 'required|email',
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        // Send via Repohive API
        $response = Http::withToken(config('services.repohive_email.token'))
            ->acceptJson()
            ->timeout(30)
            ->post(rtrim(config('services.repohive_email.base_url'), '/') . '/email/send', [
                'to'      => $request->to,
                'from'    => env('MAIL_FROM_ADDRESS'),
                'subject' => $request->subject,
                'html'    => nl2br(e($request->body)),
                'text'    => $request->body,
            ]);

        // Store in database if logged in – using SentEmail model correctly
        if (Auth::check()) {
            SentEmail::create([
                'user_id'   => Auth::id(),
                'to_email'  => $request->to,
                'subject'   => $request->subject,
                'body'      => $request->body,
            ]);
        }

        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Email sent successfully!']);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send email.',
            'error'   => $response->json()
        ], 500);
    }

    // Fetch sent emails for the authenticated user (returns JSON)
    public function getSentEmails()
    {
        $emails = SentEmail::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($emails);
    }
}
