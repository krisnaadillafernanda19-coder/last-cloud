<?php

require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

// Pastikan hanya admin yang bisa edit
if (current_user()['role'] !== 'admin') {
    redirect('dashboard.php');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('error', 'ID Produk tidak valid.');
    redirect('dashboard.php');
}

$pdo = db();
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('error', 'Produk tidak ditemukan.');
    redirect('dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_name = $_POST['game_name'] ?? '';
    $product_type = $_POST['product_type'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    // Validasi sederhana
    if (empty($title) || empty($price)) {
        $errors[] = "Judul dan Harga wajib diisi.";
    }

    if (count($errors) === 0) {
        $update = $pdo->prepare(
            "UPDATE products 
             SET game_name = :game_name, product_type = :product_type, 
                 title = :title, description = :description, 
                 price = :price, stock = :stock
             WHERE id = :id"
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

        set_flash('success', 'Produk berhasil diperbarui.');
        redirect('dashboard.php');
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="container narrow">
        <section class="panel">
            <div class="panel-header">
                <div>
                    <p class="eyebrow">ADMIN PANEL</p>
                    <h1>Edit Produk</h1>
                </div>
                <a href="dashboard.php" class="button button-secondary">Kembali</a>
            </div>

            <?php foreach ($errors as $error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endforeach; ?>

            <form method="post" class="form">
                <label>Nama Game</label>
                <select name="game_name" required>
                    <?php foreach (['Mobile Legends', 'Free Fire', 'PUBG', 'Roblox', 'Clash of Clans', 'Honor of Kings', 'E-Football'] as $game): ?>
                        <option value="<?= $game ?>" <?= $product['game_name'] === $game ? 'selected' : '' ?>><?= $game ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Tipe Produk</label>
                <select name="product_type">
                    <option value="topup" <?= $product['product_type'] === 'topup' ? 'selected' : '' ?>>Top-up</option>
                    <option value="account" <?= $product['product_type'] === 'account' ? 'selected' : '' ?>>Jual Akun</option>
                </select>

                <label>Judul/Nama Produk</label>
                <input name="title" type="text" value="<?= e($product['title']) ?>" required>

                <label>Deskripsi</label>
                <textarea name="description" rows="3"><?= e($product['description']) ?></textarea>

                <label>Harga (Rp)</label>
                <input name="price" type="number" value="<?= e($product['price']) ?>" required>

                <label>Stok</label>
                <input name="stock" type="number" value="<?= e($product['stock']) ?>" required>

                <button type="submit" class="button button-primary">Update Produk</button>
            </form>
        </section>
    </main>
</body>
</html>