<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

$pdo = db();
$message = get_flash('success');
$error   = get_flash('error');

$stmt = $pdo->query(
    "SELECT id, game_name, product_type, title, description, price, stock, created_at
     FROM products ORDER BY created_at DESC"
);
$products = $stmt->fetchAll();

// Stats
$total = count($products);
$topup_count   = count(array_filter($products, fn($p) => $p['product_type'] === 'topup'));
$account_count = count(array_filter($products, fn($p) => $p['product_type'] === 'account'));
$low_stock     = count(array_filter($products, fn($p) => $p['stock'] <= 3));

$game_icons = [
    'Mobile Legends' => '⚔️',
    'Free Fire'      => '🔥',
    'PUBG'           => '🎯',
    'Roblox'         => '🧱',
    'Clash of Clans' => '🏰',
    'Honor of Kings' => '👑',
    'E-Football'     => '⚽',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body { background: var(--bg-panel); }

        .dash-header {
            background: rgba(15,15,19,0.98);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top:0; z-index:100;
        }

        .dash-header .logo {
            display:flex;align-items:center;gap:12px;
            font-family:'Rajdhani',sans-serif;font-size:20px;font-weight:700;
        }
        .logo-icon { width:38px;height:38px;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:900;color:white; }
        .logo-text span { color:var(--primary); }

        .dash-user { display:flex;align-items:center;gap:12px; }
        .dash-user .user-chip {
            display:flex;align-items:center;gap:8px;
            background:rgba(255,255,255,0.05);
            border:1px solid var(--border);
            border-radius:999px;
            padding:6px 14px 6px 8px;
            font-size:13px;font-weight:600;
        }
        .user-avatar {
            width:28px;height:28px;background:linear-gradient(135deg,var(--accent),var(--primary));
            border-radius:50%;display:flex;align-items:center;justify-content:center;
            font-size:12px;font-weight:900;color:white;
        }

        /* Stats row */
        .stats-row {
            display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;
            padding:28px 28px 0;
        }

        .stat-card {
            background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);
            padding:20px;display:flex;align-items:center;gap:16px;
            transition:transform .2s;
        }
        .stat-card:hover { transform:translateY(-2px); }

        .stat-icon {
            width:48px;height:48px;border-radius:12px;
            display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;
        }

        .stat-info h3 {
            font-family:'Rajdhani',sans-serif;font-size:28px;font-weight:700;line-height:1;
        }
        .stat-info p { font-size:12px;color:var(--text-muted);margin-top:3px; }

        /* Table header with search */
        .table-toolbar {
            display:flex;align-items:center;justify-content:space-between;
            padding:16px 24px;gap:12px;flex-wrap:wrap;
        }

        .table-search {
            background:rgba(255,255,255,0.05);border:1px solid var(--border);
            border-radius:8px;padding:9px 14px;color:var(--text);
            font-family:'Nunito',sans-serif;font-size:14px;outline:none;
            transition:border-color .2s;min-width:240px;
        }
        .table-search:focus { border-color:var(--primary); }
        .table-search::placeholder { color:var(--text-muted); }

        .badge-topup   { background:rgba(245,158,11,0.12);color:var(--gold);border-color:rgba(245,158,11,0.25); }
        .badge-account { background:rgba(124,58,237,0.12);color:var(--accent-2);border-color:rgba(124,58,237,0.25); }

        .stock-pill {
            display:inline-block;padding:3px 10px;border-radius:999px;
            font-size:11px;font-weight:700;
        }
        .stock-ok  { background:rgba(16,185,129,0.1);color:var(--success); }
        .stock-low { background:rgba(239,68,68,0.1);color:var(--danger); }

        .main-wrap { padding: 0 28px 40px; }
    </style>
</head>
<body>

<!-- HEADER -->
<header class="dash-header">
    <a href="index.php" class="logo">
        <div class="logo-icon">KT</div>
        <div class="logo-text">KRISNA<span>TOPUP</span> <span style="font-size:13px;color:var(--text-muted);font-family:'Nunito',sans-serif;font-weight:400;">Admin</span></div>
    </a>
    <div class="dash-user">
        <div class="user-chip">
            <div class="user-avatar"><?= strtoupper(substr(current_user()['full_name'], 0, 1)) ?></div>
            <?= e(current_user()['full_name']) ?>
        </div>
        <a href="logout.php" class="button button-secondary" style="padding:8px 16px;">
            🚪 Logout
        </a>
    </div>
</header>

<!-- STATS -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(255,107,53,0.12);">📦</div>
        <div class="stat-info">
            <h3><?= $total ?></h3>
            <p>Total Produk</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(245,158,11,0.12);">💎</div>
        <div class="stat-info">
            <h3><?= $topup_count ?></h3>
            <p>Produk Top Up</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(124,58,237,0.12);">🎭</div>
        <div class="stat-info">
            <h3><?= $account_count ?></h3>
            <p>Jual Akun</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,0.12);">⚠️</div>
        <div class="stat-info">
            <h3 style="<?= $low_stock > 0 ? 'color:var(--danger)' : '' ?>"><?= $low_stock ?></h3>
            <p>Stok Hampir Habis</p>
        </div>
    </div>
</div>

<div class="main-wrap">
    <!-- Flash messages -->
    <?php if ($message): ?>
        <div class="alert alert-success" style="margin:20px 0 0;">✅ <?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin:20px 0 0;">⚠️ <?= e($error) ?></div>
    <?php endif; ?>

    <!-- PANEL -->
    <div class="panel" style="margin-top:24px;">
        <div class="panel-header">
            <div>
                <p class="eyebrow">Admin Panel</p>
                <h2>Daftar Produk Game</h2>
                <p>Kelola semua produk top-up dan akun game di sini.</p>
            </div>
            <a href="create.php" class="button button-primary">+ Tambah Produk</a>
        </div>

        <div class="table-toolbar">
            <input type="text" class="table-search" id="tableSearch" placeholder="🔍 Filter produk...">
            <span style="font-size:13px;color:var(--text-muted);"><?= $total ?> produk</span>
        </div>

        <?php if (count($products) === 0): ?>
            <div class="empty-state">
                <div style="font-size:48px;margin-bottom:16px;">📭</div>
                <h3>Belum ada produk</h3>
                <p>Silakan tambahkan produk baru untuk memulai.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table id="produkTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Game</th>
                            <th>Tipe</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $i => $p): ?>
                            <tr>
                                <td style="color:var(--text-muted);font-size:12px;"><?= $i + 1 ?></td>
                                <td>
                                    <span style="font-size:16px;margin-right:6px;">
                                        <?= $game_icons[$p['game_name']] ?? '🎮' ?>
                                    </span>
                                    <?= e($p['game_name']) ?>
                                </td>
                                <td>
                                    <span class="badge <?= $p['product_type'] === 'topup' ? 'badge-topup' : 'badge-account' ?>">
                                        <?= $p['product_type'] === 'topup' ? '💎 Top Up' : '🎭 Akun' ?>
                                    </span>
                                </td>
                                <td><strong><?= e($p['title']) ?></strong></td>
                                <td style="font-family:'Rajdhani',sans-serif;font-size:15px;font-weight:700;color:var(--primary);">
                                    Rp <?= number_format($p['price'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <span class="stock-pill <?= $p['stock'] <= 3 ? 'stock-low' : 'stock-ok' ?>">
                                        <?= $p['stock'] > 0 ? $p['stock'] : '0' ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="edit.php?id=<?= (int)$p['id'] ?>">✏️ Edit</a>
                                    <a href="delete.php?id=<?= (int)$p['id'] ?>" class="danger">🗑️ Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Live filter table
document.getElementById('tableSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#produkTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

</body>
</html>
