<?php

// Fungsi untuk mencegah XSS (tetap gunakan ini)
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Fungsi untuk redirect halaman
function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

// Fungsi Flash Message
function set_flash(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function get_flash(string $key): ?string
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }
    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $message;
}

/**
 * Daftar Game di KRISNA TOPUP
 */
function get_games(): array
{
    return [
        'Mobile Legends', 'Free Fire', 'PUBG', 
        'Roblox', 'Clash of Clans', 'Honor of Kings', 'E-Football'
    ];
}

/**
 * Validasi Produk (Pengganti validate_task)
 */
function validate_product(string $title, string $price, string $stock): array
{
    $errors = [];

    if (empty($title)) {
        $errors[] = 'Nama produk wajib diisi.';
    }
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = 'Harga harus angka yang valid.';
    }
    if (!is_numeric($stock) || $stock < 0) {
        $errors[] = 'Stok tidak valid.';
    }

    return $errors;
}

/**
 * Format tanggal untuk UI
 */
function format_date(?string $date): string
{
    if (!$date) return '-';
    return date('d M Y H:i', strtotime($date));
}