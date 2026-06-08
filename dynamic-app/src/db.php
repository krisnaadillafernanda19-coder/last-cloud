<?php
// Kita bypass config.php agar langsung mengambil dari environment Docker
// Ini adalah cara paling aman untuk memastikan koneksi database jalan
function db() {
    $host = getenv('DB_HOST') ?: 'mariadb';
    $db   = getenv('DB_NAME') ?: 'uas_db';
    $user = getenv('DB_USER') ?: 'uas_user';
    $pass = getenv('DB_PASSWORD') ?: 'uas_password';
    $port = getenv('DB_PORT') ?: '3306';

    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$db";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Koneksi Database Gagal: " . $e->getMessage());
    }
}
?>
