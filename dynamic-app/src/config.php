<?php
// config.php

/**
 * Fungsi pembantu untuk mengambil environment variable wajib
 */
function required_env(string $key): string
{
    $value = getenv($key);

    if ($value === false || trim($value) === '') {
        throw new RuntimeException("Environment variable {$key} belum dikonfigurasi.");
    }

    return trim($value);
}

// Mengembalikan konfigurasi aplikasi
return [
    'db' => [
        'host'     => required_env('DB_HOST'),
        'port'     => (int) required_env('DB_PORT'),
        'name'     => required_env('DB_NAME'),
        'user'     => required_env('DB_USER'),
        'password' => required_env('DB_PASSWORD'),
    ],
    // Kamu bisa menambahkan konfigurasi lain di sini nantinya,
    // misalnya: 'app_name' => 'KRISNA TOPUP'
];