<?php
// extension.php
session_start();
$active_page = 'extension';

define('TN_EXTENSION_FOLDER', __DIR__ . '/extensions');
define('TN_ZIP_NAME', 'bharat-trustnet-extension.zip');

function tn_find_manifest_dir($base) {
    if (!is_dir($base)) {
        return null;
    }
    if (file_exists($base . '/manifest.json')) {
        return $base;
    }
    foreach (glob($base . '/*', GLOB_ONLYDIR) as $sub) {
        if (file_exists($sub . '/manifest.json')) {
            return $sub;
        }
    }
    return null;
}

$tn_manifest_dir = tn_find_manifest_dir(TN_EXTENSION_FOLDER);
$extension_exists = $tn_manifest_dir !== null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get the Extension — Bharat TrustNet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <main class="tn-page">
        <div class="tn-download-hero">
            <div class="icon">🧩</div>
            <h1>TrustNet Browser Extension</h1>
            <p>Real-time trust scores and phishing warnings, right where you browse.
               Get instant verification for banking, UPI, and government sites.</p>
        </div>

        <?php if (!$extension_exists): ?>
            <div class="tn-checker-result tn-checker-danger" style="max-width:600px;margin:0 auto 32px;">
                <strong>Extension folder not found.</strong>
                <p class="tn-checker-note" style="margin-top:6px;">
                    Place your unpacked extension inside <code>TRUSTNET/extensions/</code>
                    (either directly, or one folder deep) so this page can package it for download.
                    It must contain a <code>manifest.json</code> file.
                </p>
            </div>
        <?php else: ?>
            <div style="text-align:center;margin-bottom:40px;">
                <a href="download_extension.php" class="tn-btn tn-btn-primary" style="padding:14px 32px;font-size:1rem;">
                    ⬇ Download Extension (.zip)
                </a>
            </div>
        <?php endif; ?>

        <div class="tn-card tn-section-gap" style="max-width:760px;margin:0 auto;">
            <h2 style="margin-bottom:16px;font-size:1.1rem;">How to install (Chrome / Edge / Brave)</h2>
            <div class="tn-activity-list">
                <div class="tn-activity-row">
                    <div class="tn-activity-domain">1️⃣ Download and unzip the file above</div>
                </div>
                <div class="tn-activity-row">
                    <div class="tn-activity-domain">2️⃣ Open <code>chrome://extensions</code> in a new tab</div>
                </div>
                <div class="tn-activity-row">
                    <div class="tn-activity-domain">3️⃣ Turn on <strong>Developer mode</strong> (top-right toggle)</div>
                </div>
                <div class="tn-activity-row">
                    <div class="tn-activity-domain">4️⃣ Click <strong>Load unpacked</strong> and select the unzipped folder</div>
                </div>
                <div class="tn-activity-row">
                    <div class="tn-activity-domain">5️⃣ Pin the TrustNet icon to your toolbar — you're done ✅</div>
                </div>
            </div>
        </div>

        <div class="tn-card" style="max-width:760px;margin:24px auto 0;">
            <h2 style="margin-bottom:16px;font-size:1.1rem;">What it does</h2>
            <div class="tn-activity-list">
                <div class="tn-activity-row">
                    <div class="tn-activity-domain">✅ Shows a live trust score on every site you visit</div>
                </div>
                <div class="tn-activity-row">
                    <div class="tn-activity-domain">⚠️ Warns you instantly on unverified or suspicious domains</div>
                </div>
                <div class="tn-activity-row">
                    <div class="tn-activity-domain">🏦 Cross-checks against bank and government domain registries</div>
                </div>
                <div class="tn-activity-row">
                    <div class="tn-activity-domain">📡 Reports phishing attempts to I4C (coming soon)</div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>