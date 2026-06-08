<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

if (current_user()['role'] !== 'admin') {
    redirect('dashboard.php');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('error', 'ID Produk tidak valid.');
    redirect('dashboard.php');
}

$pdo  = db();
$stmt = $pdo->prepare("SELECT id, title, game_name, product_type, price FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('error', 'Produk tidak ditemukan.');
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("DELETE FROM products WHERE id = :id")->execute(['id' => $id]);
    set_flash('success', "Produk \"{$product['title']}\" berhasil dihapus.");
    redirect('dashboard.php');
}

$game_icons = ['Mobile Legends'=>'⚔️','Free Fire'=>'🔥','PUBG'=>'🎯','Roblox'=>'🧱','Clash of Clans'=>'🏰','Honor of Kings'=>'👑','E-Football'=>'⚽'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Produk — KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            background: var(--bg-panel);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .confirm-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 460px;
            box-shadow: var(--shadow);
            animation: slideUp .3s ease;
        }

        @keyframes slideUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .danger-icon {
            width: 64px; height: 64px;
            background: rgba(239,68,68,0.12);
            border: 2px solid rgba(239,68,68,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px;
            margin: 0 auto 20px;
        }

        .product-preview {
            background: rgba(239,68,68,0.07);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: var(--radius);
            padding: 16px 20px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .product-preview .p-icon {
            font-size: 32px;
            flex-shrink: 0;
        }

        .product-preview .p-info h4 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .product-preview .p-info p {
            font-size: 12px;
            color: var(--text-muted);
        }

        .product-preview .p-price {
            margin-left: auto;
            font-family: 'Rajdhani', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            white-space: nowrap;
        }

        .btn-row { display:flex;gap:12px;margin-top:24px;flex-wrap:wrap; }
    </style>
</head>
<body>

<div class="confirm-card">
    <div class="danger-icon">🗑️</div>

    <div style="text-align:center;margin-bottom:4px;">
        <p class="eyebrow">Konfirmasi Hapus</p>
        <h2 style="font-family:'Rajdhani',sans-serif;font-size:24px;font-weight:700;margin-top:4px;">
            Hapus Produk?
        </h2>
        <p style="font-size:14px;color:var(--text-muted);margin-top:8px;">
            Tindakan ini <strong style="color:var(--danger);">tidak dapat dibatalkan</strong>. 
            Produk akan dihapus secara permanen dari database.
        </p>
    </div>

    <div class="product-preview">
        <div class="p-icon"><?= $game_icons[$product['game_name']] ?? '🎮' ?></div>
        <div class="p-info">
            <h4><?= e($product['title']) ?></h4>
            <p><?= e($product['game_name']) ?> &bull; <?= $product['product_type'] === 'topup' ? 'Top Up' : 'Jual Akun' ?></p>
        </div>
        <div class="p-price">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
    </div>

    <form method="post" class="btn-row">
        <button type="submit" class="button button-danger" style="flex:1;justify-content:center;padding:13px;">
            🗑️ Ya, Hapus Sekarang
        </button>
        <a href="dashboard.php" class="button button-secondary" style="flex:1;justify-content:center;padding:13px;">
            ← Batal
        </a>
    </form>
</div>

</body>
</html>
