<?php
// navbar.php
if (!isset($active_page)) {
    $active_page = '';
}

$nav_user_name = $_SESSION['user_name'] ?? 'Demo User';
$is_logged_in  = isset($_SESSION['user_email']);

$tn_logo_file = __DIR__ . '/../assets/logo.png';
$tn_logo_url  = 'assets/logo.png';
$tn_has_logo  = file_exists($tn_logo_file);
?>
<header class="tn-navbar">
    <div class="tn-navbar-inner">

        <a href="index.php" class="tn-brand">
            <?php if ($tn_has_logo): ?>
                <img src="<?= htmlspecialchars($tn_logo_url) ?>" alt="Bharat TrustNet logo" class="tn-brand-logo">
            <?php else: ?>
                <span class="tn-brand-icon"><img src="logosec.png" width="100%"></span>
            <?php endif; ?>
            <span class="tn-brand-text">Bharat <strong>TrustNet</strong></span>
        </a>

        <button class="tn-nav-toggle" id="tnNavToggle" aria-label="Toggle navigation" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="tn-nav-links" id="tnNavLinks">
            <a style="color:white"; href="dashboard.php" class="<?= $active_page === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a style="color:white"; href="extension.php" class="<?= $active_page === 'extension' ? 'active' : '' ?>">Extension</a>
            <a style="color:white"; href="chatbot.php" class="<?= $active_page === 'chatbot' ? 'active' : '' ?>">NetAI Chat</a>
            <a style="color:white"; href="profile.php" class="<?= $active_page === 'profile' ? 'active' : '' ?>">Profile</a>
            <a style="color:white"; href="settings.php" class="<?= $active_page === 'settings' ? 'active' : '' ?>">Settings</a>
        </nav>

        <div class="tn-nav-right">
            <?php if ($is_logged_in): ?>
                <span class="tn-nav-user">👤 <?= htmlspecialchars($nav_user_name) ?></span>
                <a href="logout.php" class="tn-btn tn-btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php" class="tn-btn tn-btn-outline">Login</a>
            <?php endif; ?>
        </div>

    </div>
</header>

<script>
(function () {
    var toggle = document.getElementById('tnNavToggle');
    var links  = document.getElementById('tnNavLinks');
    if (!toggle || !links) return;
    toggle.addEventListener('click', function () {
        var isOpen = links.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        toggle.classList.toggle('open');
    });
})();
</script>