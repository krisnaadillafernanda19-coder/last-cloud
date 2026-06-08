<?php
// logout.php

require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/helpers.php';

// Menjalankan fungsi logout (biasanya menghapus session_destroy)
logout();

// Setelah logout, arahkan kembali ke halaman login atau halaman depan
redirect('login.php');
exit;