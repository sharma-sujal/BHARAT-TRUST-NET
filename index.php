<?php
// index.php
session_start();
require 'trust_data.php';
$active_page = 'home';

$check_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_url'])) {
    $check_result = tn_check_domain($_POST['check_url']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bharat TrustNet — Building Towards National Safety</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <section class="tn-hero">
        <div class="tn-badge"> Cybersecurity Initiative</div>
        <h1>Bharat <span>TrustNet</span></h1>
        <p class="tn-tagline">Building Towards National Safety — verify banking, government,
            UPI, and KYC websites before you trust them.</p>

        <!-- URL / domain trust checker -->
        <form method="POST" action="index.php#checker" class="tn-checker-form" id="checker">
            <input
                type="text"
                name="check_url"
                placeholder="Paste a website URL or domain, e.g. sbi.co.in"
                value="<?= isset($_POST['check_url']) ? htmlspecialchars($_POST['check_url']) : '' ?>"
                required
            >
            <button type="submit" class="tn-btn tn-btn-primary">Check Trust Score</button>
        </form>

        <?php if ($check_result): ?>
            <div class="tn-checker-result tn-checker-<?= $check_result['status'] ?>">
                <div class="tn-checker-result-top">
                    <span class="tn-status-dot <?= $check_result['status'] === 'verified' ? 'verified' : 'danger' ?>"></span>
                    <strong><?= htmlspecialchars($check_result['domain']) ?></strong>
                    <span class="tn-badge-pill <?= $check_result['status'] === 'verified' ? 'verified' : 'danger' ?>">
                        <?= htmlspecialchars($check_result['label']) ?>
                    </span>
                </div>
                <div class="tn-checker-score">
                    Trust Score: <strong><?= (int)$check_result['score'] ?>/100</strong>
                </div>
                <p class="tn-checker-note"><?= htmlspecialchars($check_result['note']) ?></p>
            </div>
        <?php endif; ?>

        <div class="tn-hero-actions">
            <a href="login.php" class="tn-btn tn-btn-primary">Get Started</a>
            <a href="extension.php" class="tn-btn tn-btn-outline">Download Extension</a>
        </div>
    </section>

    <div class="tn-feature-grid">
        <div class="tn-feature-card">
            <div class="icon">🏦</div>
            <h3>Trusted Domain Database</h3>
            <p>Verified records of legitimate banking and government domains.</p>
        </div>
        <div class="tn-feature-card">
            <div class="icon">🔗</div>
            <h3>Bank Verification APIs</h3>
            <p>Real-time checks against official bank-issued site registries.</p>
        </div>
        <div class="tn-feature-card">
            <div class="icon">🚨</div>
            <h3>I4C Integration</h3>
            <p>Cross-referenced with India's Cyber Crime Coordination Centre.</p>
        </div>
        <div class="tn-feature-card">
            <div class="icon">👥</div>
            <h3>Citizen Reports</h3>
            <p>Crowd-verified phishing reports from everyday users.</p>
        </div>
    </div>

</body>
</html>