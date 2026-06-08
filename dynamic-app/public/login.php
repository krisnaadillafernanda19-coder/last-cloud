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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .login-form { display:flex; flex-direction:column; gap:16px; }
        .form-field { display:flex; flex-direction:column; gap:6px; }
        .form-field label { font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px; }
        .input-wrap { position:relative; }
        .input-wrap .ico { position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:16px;pointer-events:none; }
        .input-wrap input {
            width:100%;background:rgba(255,255,255,0.05);border:1px solid var(--border);
            border-radius:8px;padding:12px 14px 12px 42px;color:var(--text);
            font-family:'Nunito',sans-serif;font-size:14px;outline:none;transition:border-color .2s;
        }
        .input-wrap input:focus { border-color:var(--primary);background:rgba(255,107,53,0.06); }
        .divider { display:flex;align-items:center;gap:12px;margin:8px 0;color:var(--text-muted);font-size:12px; }
        .divider::before,.divider::after { content:'';flex:1;height:1px;background:var(--border); }
        .back-link { display:block;text-align:center;font-size:13px;color:var(--text-muted);transition:color .2s; }
        .back-link:hover { color:var(--primary); }
        .auth-card { animation: slideUp .4s ease; }
        @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    </style>
</head>
<body class="auth-page">

<main class="auth-card">
    <div class="brand">
        <div class="brand-mark">KT</div>
        <div>
            <h1>Login Admin</h1>
            <p>Kelola Produk KRISNA TOPUP</p>
        </div>
    </div>

    <?php if ($error !== ''): ?>
        <div class="alert alert-error" style="margin:0 0 16px;">⚠️ <?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" class="login-form">
        <div class="form-field">
            <label for="username">Username</label>
            <div class="input-wrap">
                <span class="ico">👤</span>
                <input id="username" name="username" type="text" autocomplete="username"
                    required autofocus placeholder="Masukkan username..."
                    value="<?= e($_POST['username'] ?? '') ?>">
            </div>
        </div>

        <div class="form-field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <span class="ico">🔒</span>
                <input id="password" name="password" type="password"
                    autocomplete="current-password" required placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="button button-primary"
            style="width:100%;justify-content:center;padding:13px;font-size:15px;margin-top:4px;">
            🔐 Masuk ke Dashboard
        </button>
    </form>

    <div class="divider">atau</div>
    <a href="index.php" class="back-link">← Kembali ke Beranda</a>
</main>

</body>
</html>
