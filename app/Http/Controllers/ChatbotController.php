<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    // Choose your AI provider: 'deepseek', 'claude', or 'local'
    private $defaultProvider = 'local'; // change to 'deepseek' when you have API key

    // API endpoints and keys (set in .env)
    private function getDeepSeekKey()
    {
        return env('DEEPSEEK_API_KEY');
    }

    private function getClaudeKey()
    {
        return env('CLAUDE_API_KEY');
    }

    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'ai'      => 'sometimes|string|in:deepseek,claude'
        ]);

        $message = $request->message;
        $ai = $request->ai ?? $this->defaultProvider;

        try {
            $reply = match ($ai) {
                'deepseek' => $this->callDeepSeek($message),
                'claude'   => $this->callClaude($message),
                default    => $this->localResponder($message),
            };

            return response()->json(['reply' => $reply]);
        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Sorry, I encountered an error. Please try again later.'
            ], 500);
        }
    }

    /**
     * DeepSeek API (free tier available)
     */
    private function callDeepSeek($message)
    {
        $apiKey = $this->getDeepSeekKey();
        if (!$apiKey) {
            return $this->localResponder($message);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->timeout(30)
          ->post('https://api.deepseek.com/v1/chat/completions', [
              'model'    => 'deepseek-chat',
              'messages' => [
                  ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                  ['role' => 'user', 'content' => $message]
              ],
              'temperature' => 0.7,
          ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'] ?? 'No response from DeepSeek.';
        }

        return $this->localResponder($message);
    }

    /**
     * Claude API (Anthropic)
     */
    private function callClaude($message)
    {
        $apiKey = $this->getClaudeKey();
        if (!$apiKey) {
            return $this->localResponder($message);
        }

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->timeout(30)
          ->post('https://api.anthropic.com/v1/messages', [
              'model'     => 'claude-3-haiku-20240307',
              'max_tokens' => 1024,
              'messages'  => [
                  ['role' => 'user', 'content' => $message]
              ]
          ]);

        if ($response->successful()) {
            return $response->json()['content'][0]['text'] ?? 'No response from Claude.';
        }

        return $this->localResponder($message);
    }

    /**
     * Local rule‑based responder – works without API keys.
     * Intelligently answers common questions and can hold basic conversations.
     */
    private function localResponder($message)
    {
        $msg = strtolower(trim($message));

        // Greetings
        if (preg_match('/\b(hi|hello|hey|greetings)\b/', $msg)) {
            return "Hello! How can I help you today? Ask me anything about DDS, OTP, emails, or just chat.";
        }

        // How are you
        if (preg_match('/\b(how are you|how do you do|what\'s up)\b/', $msg)) {
            return "I'm doing great, thanks for asking! I'm an AI assistant ready to help you with your tasks.";
        }

        // Name
        if (preg_match('/\b(your name|who are you)\b/', $msg)) {
            return "I'm DDS AI, your personal assistant. I'm here to help you send emails, manage your account, and answer questions.";
        }

        // Help
        if (preg_match('/\b(help|what can you do|capabilities)\b/', $msg)) {
            return "I can help you with:\n- Sending emails\n- Explaining OTP verification\n- Managing your account\n- Answering general questions\n- And more! Just ask.";
        }

        // OTP
        if (preg_match('/\b(otp|one time password|verification code)\b/', $msg)) {
            return "OTP (One-Time Password) is a 6-digit code sent via SMS or email to verify your identity. It expires in 10 minutes for security.";
        }

        // Email sending
        if (preg_match('/\b(send email|compose|how to send)\b/', $msg)) {
            return "To send an email, go to the 'Compose' tab in the sidebar. Fill in the recipient, subject, and message, then click 'Send Email'. The email will be delivered instantly.";
        }

        // Account settings
        if (preg_match('/\b(account settings|change name|update password|avatar)\b/', $msg)) {
            return "You can manage your account from the 'Account Settings' page (click the gear icon in sidebar). There you can update your name, password, profile picture, and view active sessions.";
        }

        // Browser sessions
        if (preg_match('/\b(session|logout other devices|browser sessions)\b/', $msg)) {
            return "You can see all active browser sessions in Account Settings. To log out other devices, click 'Logout Other Browser Sessions' after entering your password.";
        }

        // Mailbox / inbox
        if (preg_match('/\b(mailbox|inbox|read email|delete email)\b/', $msg)) {
            return "Your mailbox shows demo emails for now. You can reply, delete, or mark them as read. Real email fetching will be added soon.";
        }

        // About DDS
        if (preg_match('/\b(what is dds|dds platform|about dds)\b/', $msg)) {
            return "DDS stands for Detect, Defend, Secure. It's a secure authentication and communication platform that offers OTP login, email sending, AI chatbot, and account management.";
        }

        // Thank you
        if (preg_match('/\b(thank you|thanks|appreciate)\b/', $msg)) {
            return "You're very welcome! Feel free to ask anything else.";
        }

        // Goodbye
        if (preg_match('/\b(bye|goodbye|see you later|exit)\b/', $msg)) {
            return "Goodbye! Have a great day. Click the power icon in the sidebar to log out when you're done.";
        }

        // Default fallback – tries to be helpful
        if (strlen($msg) > 10) {
            return "That's interesting! I'm still learning. If you need help with something specific, try asking about OTP, email, account settings, or DDS features.";
        }

        return "Hmm, I didn't quite understand. Can you rephrase? You can ask me about OTP, sending emails, account settings, or just say 'help'.";
    }
}
