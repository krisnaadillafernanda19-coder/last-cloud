<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

$pdo = db();
$search = $_GET['search'] ?? '';
$filter_game = $_GET['game'] ?? '';

// Build query
$where = "WHERE 1=1";
$params = [];

if ($search !== '') {
    $where .= " AND (title LIKE :search OR game_name LIKE :search)";
    $params['search'] = "%$search%";
}

if ($filter_game !== '') {
    $where .= " AND game_name = :game";
    $params['game'] = $filter_game;
}

$stmt = $pdo->prepare("SELECT * FROM products $where ORDER BY game_name ASC, product_type ASC");
$stmt->execute($params);
$products = $stmt->fetchAll();

// Group by game
$grouped = [];
foreach ($products as $p) {
    $grouped[$p['game_name']][] = $p;
}

// Game images from open CDN
$game_data = [
    'Mobile Legends' => ['emoji' => '⚔️', 'color' => '#1A6DFF', 'img' => 'https://placehold.co/400x225/1A6DFF/white?text=Mobile+Legends'],
    'Free Fire'      => ['emoji' => '🔥', 'color' => '#FF6B00', 'img' => 'https://placehold.co/400x225/FF6B00/white?text=Free+Fire'],
    'PUBG'           => ['emoji' => '🎯', 'color' => '#C8A951', 'img' => 'https://placehold.co/400x225/C8A951/white?text=PUBG'],
    'Roblox'         => ['emoji' => '🧱', 'color' => '#E94747', 'img' => 'https://placehold.co/400x225/E94747/white?text=Roblox'],
    'Clash of Clans' => ['emoji' => '🏰', 'color' => '#2D9B47', 'img' => 'https://placehold.co/400x225/2D9B47/white?text=Clash+of+Clans'],
    'Honor of Kings' => ['emoji' => '👑', 'color' => '#9B2D8A', 'img' => 'https://placehold.co/400x225/9B2D8A/white?text=Honor+of+Kings'],
    'E-Football'     => ['emoji' => '⚽', 'color' => '#1A7A3A', 'img' => 'https://placehold.co/400x225/1A7A3A/white?text=E-Football'],
];

$all_games = array_keys($game_data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRISNA TOPUP — Top Up Game Murah & Terpercaya</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Extra styles for index page */
        .wa-float {
            position: fixed;
            bottom: 28px; right: 28px;
            background: linear-gradient(135deg, #25D366, #128C7E);
            color: white;
            width: 58px; height: 58px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            box-shadow: 0 4px 20px rgba(37,211,102,0.45);
            z-index: 200;
            transition: transform .2s;
        }
        .wa-float:hover { transform: scale(1.1); }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 28px 24px 0;
        }

        .feature-item {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .feature-icon {
            font-size: 28px;
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .feature-text p {
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
    <a href="index.php" class="logo">
        <div class="logo-icon">KT</div>
        <div class="logo-text">KRISNA<span>TOPUP</span></div>
    </a>
    <nav>
        <a href="index.php" class="active">🏠 Home</a>
        <a href="login.php">🔐 Admin</a>
    </nav>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-badge">⚡ Proses Cepat &amp; Aman</div>
    <h1>Top Up Game <span>Murah & Terpercaya</span></h1>
    <p>Beli diamond, voucher, dan akun game favoritmu dengan harga terbaik. Proses otomatis, 24 jam nonstop!</p>
    <div class="hero-stats">
        <div class="stat-item">
            <div class="stat-num">7+</div>
            <div class="stat-label">Game Tersedia</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">50+</div>
            <div class="stat-label">Produk</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">24/7</div>
            <div class="stat-label">Layanan</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">100%</div>
            <div class="stat-label">Terpercaya</div>
        </div>
    </div>
</section>

<!-- FEATURE BADGES -->
<div class="features">
    <div class="feature-item">
        <div class="feature-icon">⚡</div>
        <div class="feature-text">
            <h4>Proses Instan</h4>
            <p>Top up langsung diproses dalam hitungan menit</p>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon">🔒</div>
        <div class="feature-text">
            <h4>100% Aman</h4>
            <p>Transaksi terjamin keamanannya</p>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon">💰</div>
        <div class="feature-text">
            <h4>Harga Murah</h4>
            <p>Harga terbaik, lebih hemat dari toko lain</p>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon">🎧</div>
        <div class="feature-text">
            <h4>CS Responsif</h4>
            <p>Admin siap bantu via WhatsApp kapan saja</p>
        </div>
    </div>
</div>

<!-- SEARCH -->
<div class="search-wrap" style="margin-top:28px;">
    <form method="GET" class="search-form">
        <?php if ($filter_game): ?>
            <input type="hidden" name="game" value="<?= e($filter_game) ?>">
        <?php endif; ?>
        <input class="search-input" type="text" name="search"
               placeholder="🔍 Cari game atau produk..."
               value="<?= e($search) ?>">
        <button class="button button-primary" type="submit">Cari</button>
        <?php if ($search || $filter_game): ?>
            <a href="index.php" class="button button-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- FILTER TABS -->
<div class="filter-tabs">
    <a href="index.php<?= $search ? '?search='.urlencode($search) : '' ?>"
       class="filter-tab <?= !$filter_game ? 'active' : '' ?>">🎮 Semua Game</a>
    <?php foreach ($all_games as $game): ?>
        <a href="?game=<?= urlencode($game) ?><?= $search ? '&search='.urlencode($search) : '' ?>"
           class="filter-tab <?= $filter_game === $game ? 'active' : '' ?>">
            <?= $game_data[$game]['emoji'] ?? '🎮' ?> <?= e($game) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- PRODUCTS -->
<div class="container">
    <?php if (empty($grouped)): ?>
        <div class="empty-state">
            <div style="font-size:48px;margin-bottom:16px;">🔍</div>
            <h3>Produk Tidak Ditemukan</h3>
            <p>Coba kata kunci lain atau reset filter</p>
        </div>
    <?php else: ?>
        <?php foreach ($grouped as $game_name => $game_products): ?>
            <?php $gd = $game_data[$game_name] ?? ['emoji' => '🎮', 'color' => '#7C3AED']; ?>
            <div class="game-group">
                <div class="game-header">
                    <div class="game-icon-placeholder" style="background: <?= $gd['color'] ?>22; border: 2px solid <?= $gd['color'] ?>44;">
                        <?= $gd['emoji'] ?>
                    </div>
                    <div class="game-title-text">
                        <h3><?= e($game_name) ?></h3>
                        <p><?= count($game_products) ?> produk tersedia</p>
                    </div>
                </div>

                <div class="products-grid">
                    <?php foreach ($game_products as $p): ?>
                        <?php
                        $img_url = $gd['img'] ?? '';
                        $badge_class = $p['product_type'] === 'topup' ? 'topup' : '';
                        $badge_label = $p['product_type'] === 'topup' ? '💎 Top Up' : '🎭 Jual Akun';
                        $wa_text = urlencode("Halo Admin KRISNA TOPUP, saya mau order:\n\nProduk: {$p['title']}\nGame: {$p['game_name']}\nHarga: Rp " . number_format($p['price'],0,',','.') . "\n\nMohon konfirmasi stoknya ya kak 🙏");
                        ?>
                        <div class="product-card">
                            <div class="product-card-img-placeholder" style="background: linear-gradient(135deg, <?= $gd['color'] ?>33, #1A1A24);">
                                <span style="font-size:40px;"><?= $gd['emoji'] ?></span>
                            </div>
                            <div class="product-card-body">
                                <div class="product-badge <?= $badge_class ?>"><?= $badge_label ?></div>
                                <div class="product-card-title"><?= e($p['title']) ?></div>
                                <?php if ($p['description']): ?>
                                    <div class="product-card-desc"><?= e($p['description']) ?></div>
                                <?php endif; ?>
                                <div class="product-price">Rp <?= number_format($p['price'], 0, ',', '.') ?></div>
                                <div class="product-stock <?= $p['stock'] <= 3 ? 'low' : '' ?>">
                                    <?= $p['stock'] > 0 ? "📦 Stok: {$p['stock']}" : '❌ Habis' ?>
                                </div>
                                <?php if ($p['stock'] > 0): ?>
                                    <a href="https://wa.me/6283116854467?text=<?= $wa_text ?>"
                                       class="button-wa" target="_blank">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        Order via WhatsApp
                                    </a>
                                <?php else: ?>
                                    <div class="button-wa" style="background: rgba(239,68,68,0.2); cursor: not-allowed; color: #EF4444; border: 1px solid rgba(239,68,68,0.3);">
                                        Stok Habis
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-logo">KRISNA<span>TOPUP</span></div>
    <p>Top Up Game Murah, Cepat & Terpercaya</p>
    <p style="margin-top:8px;">📱 WA: <a href="https://wa.me/6283116854467" style="color:var(--primary);">083116854467</a></p>
</footer>

<!-- WA FLOAT BUTTON -->
<a href="https://wa.me/6283116854467?text=Halo Admin KRISNA TOPUP, saya mau tanya produk 😊"
   class="wa-float" target="_blank" title="Chat WhatsApp">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
</a>

</body>
</html>
