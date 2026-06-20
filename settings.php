<?php
// settings.php
session_start();

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}

$active_page = 'settings';
$user_name   = $_SESSION['user_name'] ?? 'Demo User';
$user_email  = $_SESSION['user_email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings — Bharat TrustNet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <main class="tn-page" style="max-width:760px;">
        <div class="tn-page-header">
            <h1>Settings</h1>
            <p>Manage your account and protection preferences</p>
        </div>

        <div class="tn-card tn-section-gap">
            <h2 style="font-size:1.05rem;margin-bottom:6px;">Account</h2>
            <div class="tn-form-row">
                <div class="meta"><strong>Full name</strong><span>Shown across TrustNet</span></div>
                <span><?= htmlspecialchars($user_name) ?></span>
            </div>
            <div class="tn-form-row">
                <div class="meta"><strong>Email</strong><span>Used to sign in</span></div>
                <span><?= htmlspecialchars($user_email) ?></span>
            </div>
        </div>

        <div class="tn-card tn-section-gap">
            <h2 style="font-size:1.05rem;margin-bottom:6px;">Protection preferences</h2>
            <div class="tn-form-row">
                <div class="meta"><strong>Real-time phishing alerts</strong><span>Warn me before visiting unverified sites</span></div>
                <label class="tn-toggle">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="tn-form-row">
                <div class="meta"><strong>Auto-report suspicious domains</strong><span>Send flagged sites to I4C automatically</span></div>
                <label class="tn-toggle">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
            </div>
            <div class="tn-form-row">
                <div class="meta"><strong>Email notifications</strong><span>Weekly trust score summary</span></div>
                <label class="tn-toggle">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="tn-card">
            <h2 style="font-size:1.05rem;margin-bottom:6px;color:var(--tn-danger);">Danger zone</h2>
            <div class="tn-form-row">
                <div class="meta"><strong>Log out of all sessions</strong><span>End your current TrustNet session</span></div>
                <a href="logout.php" class="tn-btn tn-btn-outline">Logout</a>
            </div>
        </div>
    </main>

</body>
</html>