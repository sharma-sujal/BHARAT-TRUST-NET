<?php
// profile.php
session_start();

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}

$active_page = 'profile';

// ---- Phase 2: dummy user profile (replace with DB record in Phase 3) ----
$user = [
    'name'        => $_SESSION['user_name'] ?? 'Demo User',
    'email'       => $_SESSION['user_email'],
    'role'        => 'Citizen Account',
    'joined'      => 'June 2026',
    'trust_score' => '92%',
    'reports'     => 3,
];

$initials = strtoupper(substr($user['name'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile — Bharat TrustNet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <main class="tn-page" style="max-width:760px;">
        <div class="tn-page-header">
            <h1>Profile</h1>
            <p>Your TrustNet account details</p>
        </div>

        <div class="tn-card tn-section-gap">
            <div class="tn-profile-header">
                <div class="tn-avatar"><?= htmlspecialchars($initials) ?></div>
                <div>
                    <h2><?= htmlspecialchars($user['name']) ?></h2>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>

            <div class="tn-form-row">
                <div class="meta"><strong>Account type</strong><span>Role on TrustNet</span></div>
                <span class="tn-badge-pill verified"><?= htmlspecialchars($user['role']) ?></span>
            </div>
            <div class="tn-form-row">
                <div class="meta"><strong>Member since</strong><span>Account creation date</span></div>
                <span><?= htmlspecialchars($user['joined']) ?></span>
            </div>
            <div class="tn-form-row">
                <div class="meta"><strong>Trust score</strong><span>Your overall TrustNet rating</span></div>
                <span style="color:var(--tn-primary);font-weight:700;"><?= htmlspecialchars($user['trust_score']) ?></span>
            </div>
            <div class="tn-form-row">
                <div class="meta"><strong>Phishing reports filed</strong><span>Citizen reports contributed</span></div>
                <span><?= htmlspecialchars((string)$user['reports']) ?></span>
            </div>
        </div>

        <a href="settings.php" class="tn-btn tn-btn-outline">Edit in Settings</a>
    </main>

</body>
</html>