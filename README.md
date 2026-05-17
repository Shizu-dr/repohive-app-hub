# 🛡️ DDS – Detect, Defend, Secure

DDS is a modern authentication and communication platform built with **Laravel**.  
It provides secure login methods (email/password, OTP via SMS/email, Google OAuth), an internal email sender, an AI chatbot (DeepSeek/Claude with local fallback), and a fully responsive dashboard.

![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![License](https://img.shields.io/badge/license-MIT-green)

## ✨ Features

- **Multi‑method authentication**
    - Email + password (with remember me)
    - One‑time password (OTP) via **SMS** (Repohive API) or **email**
    - **Google OAuth 2.0** login
- **Dashboard with collapsible sidebar**
    - Compose & send real emails (stored in database)
    - Sent emails history
    - Mailbox (demo inbox with reply/delete)
    - AI Chatbot: toggle between **DeepSeek** and **Claude** (falls back to smart local responses)
- **Account settings**
    - Update profile name and avatar (image upload)
    - Change password
    - View and revoke active browser sessions
    - Logout from other devices
- **Fully responsive** – works on desktop and mobile
- **Dark/red theme** with animated orbs and smooth transitions
