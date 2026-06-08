<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';

$pdo = db();
$search = $_GET['search'] ?? '';

// Logika Pencarian
$query = "SELECT * FROM products WHERE title LIKE :search OR game_name LIKE :search ORDER BY game_name ASC";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRISNA TOPUP</title>
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 36feb4746f8944fd83156951d89dd17182437534
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f7f6; margin: 0; padding-bottom: 30px; color: #333; }
        .navbar { background: #333; padding: 15px; display: flex; gap: 20px; margin-bottom: 20px; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #007bff; }
        .form { display: flex; gap: 10px; margin-bottom: 20px; }
        input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #007bff; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        tr:hover { background-color: #f1f1f1; }
        .button-primary { background: #28a745; color: white; padding: 8px 12px; border-radius: 5px; text-decoration: none; font-size: 14px; }
    </style>
<<<<<<< HEAD
=======
</head>
<body>

<nav class="navbar">
    <a href="index.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="login.php">Login</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Daftar Produk KRISNA TOPUP</h2>
    
    <form method="GET" class="form">
        <input type="text" name="search" placeholder="Cari game atau nama produk..." value="<?= e($search) ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Game</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= e($p['game_name']) ?></td>
                    <td><?= e($p['title']) ?></td>
                    <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                    <td>
                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20KRISNA%20TOPUP,%20saya%20mau%20beli%20<?= urlencode($p['title']) ?>" 
                           class="button-primary" target="_blank">Order WA</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
=======
    <link rel="stylesheet" href="style.css">
>>>>>>> 36feb4746f8944fd83156951d89dd17182437534
</head>
<body>

<nav class="navbar">
    <a href="index.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="login.php">Login</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Daftar Produk KRISNA TOPUP</h2>
    
    <form method="GET" class="form">
        <input type="text" name="search" placeholder="Cari game atau nama produk..." value="<?= e($search) ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Game</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= e($p['game_name']) ?></td>
                    <td><?= e($p['title']) ?></td>
                    <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                    <td>
                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20KRISNA%20TOPUP,%20saya%20mau%20beli%20<?= urlencode($p['title']) ?>" 
                           class="button-primary" target="_blank">Order WA</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
>>>>>>> df96bc9f03221255fd3eb8a7b46a5c07f7fcb967
