-- Skema Database KRISNA TOPUP

-- 1. Tabel Users (Admin & Customer)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  role ENUM('admin', 'customer') DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabel Products (Topup & Akun)
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  game_name ENUM('PUBG', 'Mobile Legends', 'Free Fire', 'Roblox', 'Clash of Clans', 'Honor of Kings', 'E-Football') NOT NULL,
  product_type ENUM('topup', 'account') NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(15, 2) NOT NULL,
  stock INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabel Orders (Transaksi)
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  product_id INT,
  target_id VARCHAR(50),
  status ENUM('pending', 'paid', 'completed', 'cancelled') DEFAULT 'pending',
  total_price DECIMAL(15, 2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Data Awal (Admin)
-- Password 'admin123' di hash menggunakan bcrypt
INSERT INTO users (username, password_hash, full_name, role)
VALUES ('admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Administrator KRISNA TOPUP', 'admin');

-- Seeding Topup (10 List Harga per game)
-- Menggunakan prosedur sederhana untuk generate dummy data
INSERT INTO products (game_name, product_type, title, description, price, stock) VALUES
('Mobile Legends', 'topup', '5 Diamonds', 'Topup MLBB 5 DM', 1500, 999),
('Mobile Legends', 'topup', '11 Diamonds', 'Topup MLBB 11 DM', 3000, 999),
('Mobile Legends', 'topup', '28 Diamonds', 'Topup MLBB 28 DM', 7500, 999),
('Mobile Legends', 'topup', '59 Diamonds', 'Topup MLBB 59 DM', 15000, 999),
('Mobile Legends', 'topup', '86 Diamonds', 'Topup MLBB 86 DM', 22000, 999),
('Mobile Legends', 'topup', '172 Diamonds', 'Topup MLBB 172 DM', 45000, 999),
('Mobile Legends', 'topup', '257 Diamonds', 'Topup MLBB 257 DM', 67000, 999),
('Mobile Legends', 'topup', '344 Diamonds', 'Topup MLBB 344 DM', 90000, 999),
('Mobile Legends', 'topup', '514 Diamonds', 'Topup MLBB 514 DM', 135000, 999),
('Mobile Legends', 'topup', '706 Diamonds', 'Topup MLBB 706 DM', 180000, 999),
('Free Fire', 'topup', '5 Diamonds', 'FF 5 DM', 1000, 999),
('Free Fire', 'topup', '12 Diamonds', 'FF 12 DM', 2000, 999),
('Free Fire', 'topup', '50 Diamonds', 'FF 50 DM', 7000, 999),
('Free Fire', 'topup', '70 Diamonds', 'FF 70 DM', 10000, 999),
('Free Fire', 'topup', '140 Diamonds', 'FF 140 DM', 20000, 999),
('Free Fire', 'topup', '210 Diamonds', 'FF 210 DM', 30000, 999),
('Free Fire', 'topup', '355 Diamonds', 'FF 355 DM', 50000, 999),
('Free Fire', 'topup', '720 Diamonds', 'FF 720 DM', 100000, 999),
('Free Fire', 'topup', '1450 Diamonds', 'FF 1450 DM', 200000, 999),
('Free Fire', 'topup', '2180 Diamonds', 'FF 2180 DM', 300000, 999);

-- Seeding Akun (8 list akun per game utama: FF & ML)
INSERT INTO products (game_name, product_type, title, description, price, stock) VALUES
('Free Fire', 'account', 'Akun FF Murah 1', 'Login FB, Skin Elite', 50000, 1),
('Free Fire', 'account', 'Akun FF Murah 2', 'Login VK, Skin Senjata', 75000, 1),
('Free Fire', 'account', 'Akun FF Sultan 1', 'Full Bundle, Login FB', 200000, 1),
('Free Fire', 'account', 'Akun FF Sultan 2', 'Skin Legend, Full Email', 500000, 1),
('Free Fire', 'account', 'Akun FF Pro Player', 'Stats GG, Login Gmail', 350000, 1),
('Free Fire', 'account', 'Akun FF Pemula', 'Akun Fresh, Murah', 20000, 1),
('Free Fire', 'account', 'Akun FF Veteran', 'Akun Season Lama', 400000, 1),
('Free Fire', 'account', 'Akun FF Random', 'Login FB, Skin Random', 30000, 1),
('Mobile Legends', 'account', 'Akun ML Mythic 1', 'Hero Lengkap', 150000, 1),
('Mobile Legends', 'account', 'Akun ML Mythic 2', 'Skin Epic Limited', 300000, 1),
('Mobile Legends', 'account', 'Akun ML Legend', 'Winrate 70%', 100000, 1),
('Mobile Legends', 'account', 'Akun ML Epic', 'Banyak Skin Spesial', 50000, 1),
('Mobile Legends', 'account', 'Akun ML Sultan', 'Skin Collector', 1000000, 1),
('Mobile Legends', 'account', 'Akun ML Fresh', 'Banyak Diamond', 75000, 1),
('Mobile Legends', 'account', 'Akun ML Pro', 'Hero Assassin', 200000, 1),
('Mobile Legends', 'account', 'Akun ML All Role', 'Hero Lengkap Semua', 500000, 1);
