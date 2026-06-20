<?php
// login.php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === 'demo@trustnet.in' && $password === 'demo1234') {
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name']  = 'Demo User';
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid email or password. Try the demo credentials below.';
    }
}

if (isset($_SESSION['user_email'])) {
    header('Location: dashboard.php');
    exit;
}

$active_page = 'login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Bharat TrustNet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="tn-auth-wrap">
        <div class="tn-auth-card">
            <h2>Welcome back</h2>
            <p class="sub">Sign in to your TrustNet account</p>

            <?php if ($error): ?>
                <div class="tn-alert tn-alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="tn-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="demo@trustnet.in" required>
                </div>
                <div class="tn-field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="tn-btn tn-btn-primary">Login</button>
            </form>

            <p class="tn-auth-note">Demo login — use <strong>demo@trustnet.in</strong> / <strong>demo1234</strong></p>
        </div>
    </div>

</body>
</html>