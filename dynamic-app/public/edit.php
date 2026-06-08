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
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('error', 'Produk tidak ditemukan.');
    redirect('dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_name    = $_POST['game_name']    ?? '';
    $product_type = $_POST['product_type'] ?? '';
    $title        = trim($_POST['title']   ?? '');
    $description  = trim($_POST['description'] ?? '');
    $price        = $_POST['price']  ?? '';
    $stock        = $_POST['stock']  ?? '';

    if (empty($title))  $errors[] = 'Nama produk wajib diisi.';
    if (empty($price) || !is_numeric($price) || $price <= 0) $errors[] = 'Harga harus angka valid.';
    if (!is_numeric($stock) || $stock < 0) $errors[] = 'Stok tidak valid.';

    if (count($errors) === 0) {
        $update = $pdo->prepare(
            "UPDATE products
             SET game_name=:game_name, product_type=:product_type,
                 title=:title, description=:description,
                 price=:price, stock=:stock
             WHERE id=:id"
        );
        $update->execute([
            'game_name'    => $game_name,
            'product_type' => $product_type,
            'title'        => $title,
            'description'  => $description,
            'price'        => $price,
            'stock'        => $stock,
            'id'           => $id,
        ]);

        set_flash('success', "Produk \"$title\" berhasil diperbarui.");
        redirect('dashboard.php');
    }

    // repopulate
    $product['game_name']    = $game_name;
    $product['product_type'] = $product_type;
    $product['title']        = $title;
    $product['description']  = $description;
    $product['price']        = $price;
    $product['stock']        = $stock;
}

$games = ['Mobile Legends', 'Free Fire', 'PUBG', 'Roblox', 'Clash of Clans', 'Honor of Kings', 'E-Football'];
$game_icons = ['Mobile Legends'=>'⚔️','Free Fire'=>'🔥','PUBG'=>'🎯','Roblox'=>'🧱','Clash of Clans'=>'🏰','Honor of Kings'=>'👑','E-Football'=>'⚽'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk — KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body { background:var(--bg-panel); }
        .page-header {
            background:rgba(15,15,19,0.98);backdrop-filter:blur(16px);
            border-bottom:1px solid var(--border);padding:0 28px;height:70px;
            display:flex;align-items:center;justify-content:space-between;
            position:sticky;top:0;z-index:100;
        }
        .page-header .logo { display:flex;align-items:center;gap:10px;font-family:'Rajdhani',sans-serif;font-size:20px;font-weight:700; }
        .logo-icon { width:38px;height:38px;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:900;color:white; }
        .logo-text span { color:var(--primary); }

        .form-wrap { max-width:680px;margin:32px auto;padding:0 24px 40px; }
        .form-grid { display:grid;grid-template-columns:1fr 1fr;gap:16px; }
        @media(max-width:560px) { .form-grid { grid-template-columns:1fr; } }

        .field { display:flex;flex-direction:column;gap:6px; }
        .field label { font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px; }
        .field input, .field select, .field textarea {
            background:rgba(255,255,255,0.05);border:1px solid var(--border);
            border-radius:8px;padding:12px 14px;color:var(--text);
            font-family:'Nunito',sans-serif;font-size:14px;outline:none;
            transition:border-color .2s,background .2s;
        }
        .field input:focus, .field select:focus, .field textarea:focus {
            border-color:var(--primary);background:rgba(255,107,53,0.06);
        }
        .field select option { background:var(--bg-card); }
        .field textarea { resize:vertical;min-height:80px; }
        .price-input { position:relative; }
        .price-input .prefix { position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:13px;font-weight:700;color:var(--text-muted);pointer-events:none; }
        .price-input input { padding-left:40px; }

        .game-selector { display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:8px; }
        .game-option { display:none; }
        .game-label {
            display:flex;flex-direction:column;align-items:center;gap:6px;
            padding:14px 10px;border:1px solid var(--border);border-radius:var(--radius);
            cursor:pointer;text-align:center;transition:all .2s;font-size:12px;font-weight:600;
        }
        .game-label .emoji { font-size:26px; }
        .game-label:hover { border-color:rgba(255,107,53,0.4);background:rgba(255,107,53,0.06); }
        .game-option:checked + .game-label { border-color:var(--primary);background:rgba(255,107,53,0.1);color:var(--primary); }

        .type-selector { display:grid;grid-template-columns:1fr 1fr;gap:8px; }
        .type-option { display:none; }
        .type-label {
            display:flex;align-items:center;justify-content:center;gap:8px;
            padding:14px;border:1px solid var(--border);border-radius:var(--radius);
            cursor:pointer;transition:all .2s;font-size:14px;font-weight:700;
        }
        .type-label:hover { border-color:rgba(255,107,53,0.4);background:rgba(255,107,53,0.06); }
        .type-option:checked + .type-label { border-color:var(--primary);background:rgba(255,107,53,0.1);color:var(--primary); }

        .form-actions { display:flex;gap:12px;align-items:center;margin-top:8px;flex-wrap:wrap; }
    </style>
</head>
<body>

<header class="page-header">
    <a href="dashboard.php" class="logo">
        <div class="logo-icon">KT</div>
        <div class="logo-text">KRISNA<span>TOPUP</span></div>
    </a>
    <a href="dashboard.php" class="button button-secondary">← Kembali</a>
</header>

<div class="form-wrap">
    <div style="margin-bottom:28px;">
        <p class="eyebrow">Admin Panel</p>
        <h1 style="font-family:'Rajdhani',sans-serif;font-size:28px;font-weight:700;margin-top:4px;">
            Edit Produk
        </h1>
        <p style="font-size:14px;color:var(--text-muted);margin-top:6px;">
            Perbarui informasi produk <strong><?= e($product['title']) ?></strong>
        </p>
    </div>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error" style="margin:0 0 12px;">⚠️ <?= e($err) ?></div>
    <?php endforeach; ?>

    <form method="post">
        <!-- Game Selector -->
        <div class="field" style="margin-bottom:20px;">
            <label>Pilih Game</label>
            <div class="game-selector">
                <?php foreach ($games as $game): ?>
                    <input type="radio" name="game_name" id="game_<?= e(str_replace(' ','_',$game)) ?>"
                           value="<?= e($game) ?>" class="game-option"
                           <?= $product['game_name'] === $game ? 'checked' : '' ?>>
                    <label for="game_<?= e(str_replace(' ','_',$game)) ?>" class="game-label">
                        <span class="emoji"><?= $game_icons[$game] ?? '🎮' ?></span>
                        <?= e($game) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Type -->
        <div class="field" style="margin-bottom:20px;">
            <label>Tipe Produk</label>
            <div class="type-selector">
                <input type="radio" name="product_type" id="type_topup" value="topup" class="type-option"
                       <?= $product['product_type'] === 'topup' ? 'checked' : '' ?>>
                <label for="type_topup" class="type-label">💎 Top Up</label>

                <input type="radio" name="product_type" id="type_account" value="account" class="type-option"
                       <?= $product['product_type'] === 'account' ? 'checked' : '' ?>>
                <label for="type_account" class="type-label">🎭 Jual Akun</label>
            </div>
        </div>

        <!-- Title & Desc -->
        <div class="field" style="margin-bottom:16px;">
            <label for="title">Nama Produk</label>
            <input id="title" name="title" type="text" required
                   placeholder="Contoh: 86 Diamonds"
                   value="<?= e($product['title']) ?>">
        </div>

        <div class="field" style="margin-bottom:16px;">
            <label for="desc">Deskripsi <span style="color:var(--text-muted);text-transform:none;font-size:11px;">(opsional)</span></label>
            <textarea id="desc" name="description" rows="3"
                      placeholder="Deskripsi singkat..."><?= e($product['description']) ?></textarea>
        </div>

        <!-- Price & Stock -->
        <div class="form-grid" style="margin-bottom:24px;">
            <div class="field">
                <label for="price">Harga (Rp)</label>
                <div class="price-input">
                    <span class="prefix">Rp</span>
                    <input id="price" name="price" type="number" required min="1"
                           value="<?= e($product['price']) ?>">
                </div>
            </div>
            <div class="field">
                <label for="stock">Stok</label>
                <input id="stock" name="stock" type="number" required min="0"
                       value="<?= e($product['stock']) ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-primary" style="padding:12px 28px;font-size:15px;">
                💾 Update Produk
            </button>
            <a href="dashboard.php" class="button button-secondary">Batal</a>
        </div>
    </form>
</div>

</body>
</html>
