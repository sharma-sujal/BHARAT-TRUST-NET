<?php
// chatbot.php
session_start();
require 'config.php';
$active_page = 'chatbot';
$ai_connected = (TN_AI_PROVIDER === 'openai' && TN_OPENAI_API_KEY !== '')
             || (TN_AI_PROVIDER === 'gemini' && TN_GEMINI_API_KEY !== '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetAI Assistant — Bharat TrustNet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <main class="tn-page" style="max-width:760px;">
        <div class="tn-page-header">
            <h1>NetAI Assistant</h1>
            <p>Ask about any link, scam pattern, or site — NetAI helps you verify before you click.</p>
        </div>

        <?php if (!$ai_connected): ?>
            <div class="tn-checker-result tn-checker-danger" style="margin-bottom:20px;">
                <strong>Offline demo mode</strong>
                <p class="tn-checker-note" style="margin-top:4px;">
                    No AI key configured yet. Add one in <code>config.php</code> (TN_OPENAI_API_KEY or
                    TN_GEMINI_API_KEY + set TN_AI_PROVIDER) to enable full AI responses.
                    Domain/URL checks against the TrustNet registry still work right now.
                </p>
            </div>
        <?php endif; ?>

        <div class="tn-chat-shell">
            <div class="tn-chat-header">
                <span class="dot"></span>
                <strong>NetAI</strong>
                <span style="color:var(--tn-text-dim);font-size:0.85rem;">— Fraud detection assistant</span>
            </div>

            <div class="tn-chat-body" id="tnChatBody">
                <div class="tn-chat-msg bot">
                    Hi! I'm NetAI 👋 Paste a website link or describe a message you received,
                    and I'll help you check if it looks safe.
                </div>
            </div>

            <form class="tn-chat-input-row" id="tnChatForm">
                <input type="text" id="tnChatInput" placeholder="Paste a link or describe the message..." autocomplete="off">
                <button type="submit" class="tn-btn tn-btn-primary">Send</button>
            </form>
        </div>
    </main>

    <script>
        const form  = document.getElementById('tnChatForm');
        const input = document.getElementById('tnChatInput');
        const body  = document.getElementById('tnChatBody');

        function addMessage(text, who) {
            const msg = document.createElement('div');
            msg.className = 'tn-chat-msg ' + who;
            msg.textContent = text;
            body.appendChild(msg);
            body.scrollTop = body.scrollHeight;
            return msg;
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const text = input.value.trim();
            if (!text) return;

            addMessage(text, 'user');
            input.value = '';

            const typingMsg = addMessage('NetAI is typing...', 'bot');

            try {
                const res = await fetch('netai_handler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'message=' + encodeURIComponent(text)
                });
                const data = await res.json();
                typingMsg.textContent = data.reply || "Sorry, I couldn't process that. Please try again.";
            } catch (err) {
                typingMsg.textContent = "Connection error — please check your server is running and try again.";
            }
            body.scrollTop = body.scrollHeight;
        });
    </script>

</body>
</html>