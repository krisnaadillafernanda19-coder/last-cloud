<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/helpers.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } elseif (attempt_login($username, $password)) {
        redirect('dashboard.php');
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-page">
    <main class="auth-card">
        <div class="brand">
            <span class="brand-mark">KT</span>
            <div>
                <h1>Login Admin</h1>
                <p>Kelola Produk KRISNA TOPUP</p>
            </div>
        </div>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" class="form">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" autocomplete="username" required autofocus placeholder="Masukkan username...">

            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••">

            <button type="submit" class="button button-primary">Masuk ke Dashboard</button>
        </form>

        <p class="demo-note" style="margin-top: 20px; text-align: center;">
            <a href="index.php">&larr; Kembali ke Beranda</a>
        </p>
    </main>
</body>
</html>