<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

$pdo = db();
$search = trim($_GET['search'] ?? '');

$query = "SELECT * FROM products WHERE title LIKE :search OR game_name LIKE :search ORDER BY game_name ASC";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$products = $stmt->fetchAll();

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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="navbar">
        <a class="brand" href="index.php">
            <img src="assets/logo.svg" alt="KRISNA TOPUP" class="brand-logo">
            <span>KRISNA TOPUP</span>
        </a>
        <nav class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <section class="promo-banner">
        <div class="container banner-content">
            <div>
                <p class="hero-badge">PROMO TOP-UP DIAMOND</p>
                <h2>Harga terbaik & kirim instan untuk semua game populer.</h2>
                <p>Dapatkan harga spesial, tampilan produk rapi, dan order cepat lewat WhatsApp dengan dukungan 24/7.</p>
            </div>
            <a href="https://wa.me/6283116854467?text=Halo%20Admin%20KRISNA%20TOPUP,%20saya%20mau%20tanya%20promo" target="_blank" class="button button-primary">Chat Sekarang</a>
        </div>
    </section>

    <main>
        <section class="hero container">
            <div class="hero-copy">
                <p class="hero-badge">TOP-UP GAME MODERN</p>
                <h1>Top-up cepat, aman, dan terlihat profesional.</h1>
                <p class="hero-text">Temukan produk game terbaik dengan tampilan katalog modern, ada gambar produknya, dan langsung order lewat WhatsApp.</p>
                <div class="hero-actions">
                    <form method="GET" class="search-form">
                        <input type="search" name="search" placeholder="Cari game atau produk..." value="<?= e($search) ?>">
                        <button type="submit" class="button button-primary">Cari Produk</button>
                    </form>
                    <a href="https://wa.me/6283116854467?text=Halo%20Admin%20KRISNA%20TOPUP,%20saya%20mau%20tanya%20produk" target="_blank" class="button button-secondary">Chat WA: 0831-1685-4467</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-card">
                    <span class="hero-card-tag">Produk Unggulan</span>
                    <h2>Lebih cepat dapatkan top-up dan akun premium.</h2>
                    <p>Semua produk tampil dengan preview modern dan informasi harga yang jelas.</p>
                </div>
            </div>
        </section>

        <section class="container feature-grid">
            <article class="feature-card">
                <h3>Diamond & Top-up Lengkap</h3>
                <p>Semua produk ditampilkan dalam kotak rapi dengan gambar, harga, dan jenis game yang jelas.</p>
            </article>
            <article class="feature-card">
                <h3>Stok Tersedia</h3>
                <p>Setiap produk dilengkapi label stok dan status agar pelanggan tidak perlu bingung.</p>
            </article>
            <article class="feature-card">
                <h3>Order Mudah</h3>
                <p>Langsung chat WhatsApp dan pesan produk tanpa ribet, sesuai tampilan web topup profesional.</p>
            </article>
        </section>

        <section class="container product-section">
            <div class="section-header">
                <div>
                    <p class="section-label">Katalog Produk</p>
                    <h2>Produk Top-up & Akun Game</h2>
                </div>
                <span class="section-meta"><?= count($products) ?> produk ditemukan</span>
            </div>

            <?php if (count($products) === 0): ?>
                <div class="empty-state">
                    <h3>Belum ada produk.</h3>
                    <p>Silakan tambahkan produk di dashboard agar dapat tampil di katalog.</p>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $p): ?>
                        <article class="product-card">
                            <div class="product-top">
                                <img src="<?= e(product_image($p['game_name'])) ?>" alt="<?= e($p['game_name']) ?>" class="product-image">
                                <span class="product-badge"><?= e($p['game_name']) ?></span>
                            </div>
                            <div class="product-body">
                                <h3><?= e($p['title']) ?></h3>
                                <p><?= e($p['description'] ?: 'Top-up cepat dan aman untuk semua game populer.') ?></p>
                                <div class="product-meta">
                                    <span class="tag"><?= e(ucfirst($p['product_type'])) ?></span>
                                    <span class="stock">Stok <?= e($p['stock']) ?></span>
                                </div>
                            </div>
                            <div class="product-footer">
                                <strong>Rp <?= number_format($p['price'], 0, ',', '.') ?></strong>
                                <a href="https://wa.me/6283116854467?text=Halo%20Admin%20KRISNA%20TOPUP,%20saya%20mau%20beli%20<?= urlencode($p['title']) ?>" target="_blank" class="button button-primary">Order WA</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer container">
        <div>
            <p class="footer-title">KRISNA TOPUP</p>
            <p>Layanan top-up game modern dengan tampilan katalog produk yang segar dan mudah digunakan.</p>
        </div>
        <div class="footer-links">
            <a href="https://wa.me/6283116854467" target="_blank">WhatsApp: 0831-1685-4467</a>
            <a href="dashboard.php">Admin Dashboard</a>
        </div>
    </footer>
</body>
</html>