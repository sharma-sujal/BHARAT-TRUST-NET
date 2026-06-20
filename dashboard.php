<?php
// dashboard.php
session_start();
require 'trust_data.php';

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}

$active_page = 'dashboard';
$user_name   = $_SESSION['user_name'] ?? 'Demo User';

$recent_domains = ['bankofbaroda.in', 'uidai.gov.in', 'fake-bank-login.com'];
$recent_activity = array_map('tn_check_domain', $recent_domains);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Bharat TrustNet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body >

    <?php include 'includes/navbar.php'; ?>

    <main class="tn-page">
        <div class="tn-page-header">
            <h1 >TrustNet USER PAGE</h1>
            <p>Welcome, <?= htmlspecialchars($user_name) ?> 👋</p>
        </div>

        <div class="tn-stat-grid">
            <div class="tn-stat-card">
                <div class="label">Trust Score</div>
                <div class="value">92%</div>
                <div class="trend">↑ Strong standing</div>
            </div>
            <div class="tn-stat-card">
                <div class="label">Verified Sites</div>
                <div class="value">18</div>
                <div class="trend">+3 this week</div>
            </div>
            <div class="tn-stat-card danger">
                <div class="label">Threats Blocked</div>
                <div class="value">4</div>
                <div class="trend">2 in last 24h</div>
            </div>
        </div>

        <div class="tn-panel">
            <h2>Recent Activity</h2>
            <div class="tn-activity-list">
                <?php foreach ($recent_activity as $item): ?>
                    <?php $dotClass = $item['status'] === 'verified' ? 'verified' : 'danger'; ?>
                    <div class="tn-activity-row">
                        <div class="tn-activity-domain">
                            <span class="tn-status-dot <?= $dotClass ?>"></span>
                            <?= htmlspecialchars($item['domain']) ?>
                        </div>
                        <span class="tn-badge-pill <?= $dotClass ?>"><?= htmlspecialchars($item['label']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

</body>
</html>