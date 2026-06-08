<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

// Pastikan hanya admin yang bisa menambah produk
if (current_user()['role'] !== 'admin') {
    redirect('dashboard.php');
}

$errors = [];
$game_name = '';
$product_type = 'topup';
$title = '';
$description = '';
$price = '';
$stock = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_name = $_POST['game_name'] ?? '';
    $product_type = $_POST['product_type'] ?? 'topup';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    // Validasi sederhana
    if (empty($title) || empty($price)) {
        $errors[] = "Judul dan Harga wajib diisi!";
    }

    if (count($errors) === 0) {
        $stmt = db()->prepare(
            "INSERT INTO products (game_name, product_type, title, description, price, stock)
             VALUES (:game_name, :product_type, :title, :description, :price, :stock)"
        );
        $stmt->execute([
            'game_name' => $game_name,
            'product_type' => $product_type,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
        ]);

        set_flash('success', 'Produk berhasil ditambahkan.');
        redirect('dashboard.php');
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="container narrow">
        <section class="panel">
            <div class="panel-header">
                <div>
                    <p class="eyebrow">ADMIN PANEL</p>
                    <h1>Tambah Produk Baru</h1>
                </div>
                <a href="dashboard.php" class="button button-secondary">Kembali</a>
            </div>

            <?php foreach ($errors as $error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>

            <form method="post" class="form">
                <label>Nama Game</label>
                <select name="game_name" required>
                    <option value="Mobile Legends">Mobile Legends</option>
                    <option value="Free Fire">Free Fire</option>
                    <option value="PUBG">PUBG</option>
                    <option value="Roblox">Roblox</option>
                    <option value="Clash of Clans">Clash of Clans</option>
                    <option value="Honor of Kings">Honor of Kings</option>
                    <option value="E-Football">E-Football</option>
                </select>

                <label>Tipe Produk</label>
                <select name="product_type">
                    <option value="topup">Top-up</option>
                    <option value="account">Jual Akun</option>
                </select>

                <label>Nama Produk (Contoh: 86 Diamonds / Akun Sultan)</label>
                <input name="title" type="text" required>

                <label>Deskripsi</label>
                <textarea name="description" rows="3"></textarea>

                <label>Harga (Rp)</label>
                <input name="price" type="number" required>

                <label>Stok</label>
                <input name="stock" type="number" value="1" required>

                <button type="submit" class="button button-primary">Simpan Produk</button>
            </form>
        </section>
    </main>
</body>
</html>