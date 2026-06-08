<?php

require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

require_login();

// Pastikan hanya admin yang bisa menghapus produk
if (current_user()['role'] !== 'admin') {
    redirect('dashboard.php');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('error', 'ID Produk tidak valid.');
    redirect('dashboard.php');
}

$pdo = db();
// Mengambil title produk untuk konfirmasi tampilan
$stmt = $pdo->prepare("SELECT id, title FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('error', 'Produk tidak ditemukan.');
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $delete->execute(['id' => $id]);

    set_flash('success', 'Produk berhasil dihapus.');
    redirect('dashboard.php');
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Produk - KRISNA TOPUP</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main class="container narrow">
        <section class="panel">
            <p class="eyebrow">Konfirmasi Keamanan</p>
            <h1>Hapus Produk</h1>
            <p>Apakah kamu yakin ingin menghapus produk berikut? Tindakan ini tidak dapat dibatalkan.</p>
            
            <div class="delete-box"><?= e($product['title']) ?></div>

            <form method="post" class="button-row">
                <button type="submit" class="button button-danger">Ya, Hapus Permanen</button>
                <a href="dashboard.php" class="button button-secondary">Batal</a>
            </form>
        </section>
    </main>
</body>
</html>