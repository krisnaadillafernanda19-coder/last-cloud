<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

$pdo = db();
$message = get_flash('success');
$error = get_flash('error');

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
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Game</th>
                                <th>Tipe</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td><?= e($p['game_name']) ?></td>
                                    <td><span class="badge"><?= e($p['product_type']) ?></span></td>
                                    <td><strong><?= e($p['title']) ?></strong></td>
                                    <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                    <td><?= e($p['stock']) ?></td>
                                    <td class="actions">
                                        <a href="edit.php?id=<?= (int) $p['id'] ?>">Edit</a>
                                        <a href="delete.php?id=<?= (int) $p['id'] ?>" class="danger">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>