<?php
// netai_handler.php
// AJAX endpoint called by chatbot.php. Returns JSON: { reply: "..." }

session_start();
require 'config.php';
require 'trust_data.php';

header('Content-Type: application/json');

$user_message = trim($_POST['message'] ?? '');
if ($user_message === '') {
    echo json_encode(['reply' => "Please type a message first."]);
    exit;
}

// If the user pasted something that looks like a domain/URL, check it
// against our own trust database first — this is fast, free, and more
// reliable than asking an LLM to know live domain reputation.
$domain_hint = '';
if (preg_match('#([a-z0-9.-]+\.[a-z]{2,})#i', $user_message, $m)) {
    $result = tn_check_domain($m[1]);
    if ($result) {
        $domain_hint = "[TrustNet registry check for {$result['domain']}: status={$result['status']}, score={$result['score']}/100, note=\"{$result['note']}\"] ";
    }
}

$system_prompt = "You are NetAI, the fraud-detection assistant inside Bharat TrustNet, "
    . "a platform that helps Indian citizens verify banking, government, UPI, and KYC websites. "
    . "Keep answers short, clear, and focused on phishing/scam safety. "
    . "If a [TrustNet registry check] note is provided, treat it as ground truth over your own judgment.";

$reply = null;

if (TN_AI_PROVIDER === 'openai' && TN_OPENAI_API_KEY !== '') {
    $reply = tn_call_openai($system_prompt, $domain_hint . $user_message);
} elseif (TN_AI_PROVIDER === 'gemini' && TN_GEMINI_API_KEY !== '') {
    $reply = tn_call_gemini($system_prompt, $domain_hint . $user_message);
}

// Offline fallback — no key configured yet, or the API call failed
if ($reply === null) {
    if ($domain_hint) {
        $reply = "NetAI is running in offline mode (no AI key configured yet), but I checked our own registry: "
            . $result['domain'] . " — " . $result['label'] . " (Trust Score: " . $result['score'] . "/100). "
            . $result['note'];
    } else {
        $reply = "NetAI is running in offline demo mode right now (no AI key configured in config.php yet). "
            . "Paste a website link or domain and I can still check it against the TrustNet registry directly.";
    }
}

echo json_encode(['reply' => $reply]);
exit;


function tn_call_openai($system_prompt, $user_message) {
    $payload = [
        'model' => TN_OPENAI_MODEL,
        'messages' => [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => $user_message],
        ],
        'max_tokens' => 300,
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . TN_OPENAI_API_KEY,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 20,
    ]);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err || !$response) {
        return null;
    }
    $data = json_decode($response, true);
    return $data['choices'][0]['message']['content'] ?? null;
}

function tn_call_gemini($system_prompt, $user_message) {
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/'
        . TN_GEMINI_MODEL . ':generateContent?key=' . TN_GEMINI_API_KEY;

    $payload = [
        'contents' => [
            ['role' => 'user', 'parts' => [['text' => $system_prompt . "\n\nUser: " . $user_message]]],
        ],
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 20,
    ]);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err || !$response) {
        return null;
    }
    $data = json_decode($response, true);
    return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
}