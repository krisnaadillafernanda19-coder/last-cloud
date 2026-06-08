<?php
// Nama service database di docker-compose.yml adalah 'db'
$host = 'db'; 
$db   = 'uas_db';
$user = 'uas_user';
$pass = 'uas_password';
$charset = 'utf8mb4';

// Contoh cara menggunakannya dengan PDO agar koneksi lebih aman:
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Koneksi database berhasil!";
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>