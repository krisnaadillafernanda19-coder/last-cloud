<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

$pdo = db();
$message = get_flash('success');
$error = get_flash('error');

function product_image($gameName)
{
    $map = [
        'Mobile Legends' => 'assets/product-placeholder.svg',
        'Free Fire' => 'assets/product-placeholder.svg',
        'PUBG' => 'assets/product-placeholder.svg',
        'Roblox' => 'assets/product-placeholder.svg',
        'Clash of Clans' => 'assets/product-placeholder.svg',
        'Honor of Kings' => 'assets/product-placeholder.svg',
        'E-Football' => 'assets/product-placeholder.svg',
    ];

    return $map[$gameName] ?? 'assets/product-placeholder.svg';
}

// Mengambil data produk dari database
$stmt = $pdo->query(
    "SELECT id, game_name, product_type, title, description, price, stock, created_at
     FROM products
     ORDER BY created_at DESC"
);
$products = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="topbar">
        <div>
            <p class="eyebrow">KRISNA TOPUP | Admin Panel</p>
            <h1>Dashboard Produk</h1>
        </div>
        <nav class="nav-actions">
            <span>Halo, <?= e(current_user()['full_name']) ?></span>
            <a href="logout.php" class="button button-secondary">Logout</a>
        </nav>
    </header>

    <main class="container">
        <?php if ($message): ?>
            <div class="alert alert-success"><?= e($message) ?></div>
        <?php endif; ?>

        <section class="panel">
            <div class="panel-header">
                <div>
                    <h2>Daftar Produk Game</h2>
                    <p>Kelola data top-up dan akun game di sini.</p>
                </div>
                <a href="create.php" class="button button-primary">Tambah Produk</a>
            </div>

            <?php if (count($products) === 0): ?>
                <div class="empty-state">
                    <h3>Belum ada produk</h3>
                    <p>Silakan tambahkan produk baru untuk memulai.</p>
                </div>
            <?php else: ?>
                <div class="dashboard-grid">
                    <?php foreach ($products as $p): ?>
                        <article class="product-card-admin">
                            <div class="card-image-wrap">
                                <img src="<?= e(product_image($p['game_name'])) ?>" alt="<?= e($p['game_name']) ?>" class="card-image">
                                <div class="card-badge">
                                    <img src="assets/diamond-icon.svg" alt="Diamond" class="icon icon-diamond">
                                    <span><?= e(ucfirst($p['product_type'])) ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-head">
                                    <div>
                                        <h3><?= e($p['title']) ?></h3>
                                        <p class="card-game"><?= e($p['game_name']) ?></p>
                                    </div>
                                    <div class="price">Rp <?= number_format($p['price'], 0, ',', '.') ?></div>
                                </div>
                                <p class="card-description"><?= e($p['description'] ?: 'Top-up cepat dan aman untuk pemain game.') ?></p>
                            </div>
                            <div class="card-footer">
                                <span class="stock-pill">
                                    <img src="assets/stock-icon.svg" alt="Stok" class="icon">
                                    <?= e($p['stock']) ?> stok
                                </span>
                                <div class="card-actions">
                                    <a href="edit.php?id=<?= (int) $p['id'] ?>" class="button button-secondary">Edit</a>
                                    <a href="delete.php?id=<?= (int) $p['id'] ?>" class="button button-danger">Hapus</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>