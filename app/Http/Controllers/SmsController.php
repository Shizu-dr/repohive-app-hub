<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $otp = rand(100000, 999999);
        Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(10));

        $response = Http::withToken(config('services.repohive_sms.token'))
            ->acceptJson()
            ->timeout(30)
            ->post(rtrim(config('services.repohive_sms.base_url'), '/') . '/messages', [
                'phone'   => $request->phone,
                'message' => "Your OTP code is: {$otp}. It expires in 10 minutes.",
            ]);

        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'OTP sent to your phone.']);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP.',
            'error'   => $response->json()
        ], 500);
    }
}
