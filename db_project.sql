-- =====================================================================
-- DATABASE: db_project
-- TEMPLATE PROYEK AKHIR MAHASISWA (STARTER KIT MVC PHP NATIVE)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `db_project` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_project`;

-- ---------------------------------------------------------------------
-- 1. Tabel: users (Pengguna & Hak Akses)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `role` ENUM('admin', 'mahasiswa', 'dosen', 'user') NOT NULL DEFAULT 'user',
  `avatar` VARCHAR(255) DEFAULT 'default-avatar.png',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. Tabel: mahasiswa (Contoh Master Data CRUD)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nim` VARCHAR(20) NOT NULL UNIQUE,
  `nama` VARCHAR(100) NOT NULL,
  `jurusan` VARCHAR(100) NOT NULL,
  `angkatan` VARCHAR(4) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `status` ENUM('Aktif', 'Cuti', 'Lulus', 'Non-Aktif') NOT NULL DEFAULT 'Aktif',
  `alamat` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. Tabel: pengaturan (Pengaturan Global Aplikasi)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pengaturan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(50) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `deskripsi` VARCHAR(255) NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. Tabel: aktivitas_log (Log Aktivitas Pengguna)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `aktivitas_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `username` VARCHAR(50) NULL,
  `aktivitas` VARCHAR(100) NOT NULL,
  `detail` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`user_id`),
  INDEX (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- DATA BAWAAN / SEEDER AWAL
-- =====================================================================

-- Seed data: users
-- Akun:
-- 1. admin     / admin123     (Role: admin)
-- 2. mahasiswa / mahasiswa123 (Role: mahasiswa)
-- 3. dosen     / dosen123     (Role: dosen)
INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `status`) VALUES
(1, 'admin', '$2y$10$wQ1XX1yl25e2.fK3ttYn0.ilPYpI1rjG8TiWK2sAM5ARRdknFi2Em', 'Administrator Utama', 'admin@project.local', 'admin', 'active'),
(2, 'mahasiswa', '$2y$10$GdmingCCb7FwltxXwTqlKu9mvBQfclzxoc2v/AnabmH6M0Qa9TWxC', 'Ahmad Fauzi (Mahasiswa)', 'fauzi@student.project.local', 'mahasiswa', 'active'),
(3, 'dosen', '$2y$10$erMB.qVkH4N3IbxQDhSIJeqeApJLxqXRPoE0BMKnJsQ/iEBHQGhZy', 'Dr. Ir. Hendra Wijaya, M.Kom', 'hendra@dosen.project.local', 'dosen', 'active')
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

-- Seed data: mahasiswa (Sample CRUD Data)
INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `jurusan`, `angkatan`, `email`, `no_hp`, `status`, `alamat`) VALUES
(1, '230101001', 'Ahmad Fauzi', 'Teknik Informatika', '2023', 'fauzi@student.project.local', '081234567890', 'Aktif', 'Jl. Merdeka No. 10, Jakarta'),
(2, '230101002', 'Siti Nurhaliza', 'Sistem Informasi', '2023', 'siti@student.project.local', '081298765432', 'Aktif', 'Jl. Sudirman No. 45, Bandung'),
(3, '220101015', 'Budi Santoso', 'Teknik Informatika', '2022', 'budi@student.project.local', '082155566778', 'Aktif', 'Jl. Diponegoro No. 8, Yogyakarta'),
(4, '220102030', 'Rina Anggraini', 'Ilmu Komputer', '2022', 'rina@student.project.local', '085712344321', 'Cuti', 'Jl. Pemuda No. 12, Semarang'),
(5, '210101009', 'Dimas Pratama', 'Sains Data', '2021', 'dimas@student.project.local', '087811223344', 'Lulus', 'Jl. Basuki Rahmat No. 99, Surabaya')
ON DUPLICATE KEY UPDATE `nim` = VALUES(`nim`);

-- Seed data: pengaturan
INSERT INTO `pengaturan` (`setting_key`, `setting_value`, `deskripsi`) VALUES
('app_name', 'Sistem Informasi Proyek Akhir', 'Nama resmi proyek aplikasi'),
('app_desc', 'Starter Kit MVC PHP Native untuk Tugas Akhir & Skripsi Mahasiswa', 'Deskripsi singkat aplikasi'),
('instansi', 'Fakultas Ilmu Komputer & Teknologi Informasi', 'Nama fakultas atau universitas'),
('tahun_akademik', '2026/2027', 'Tahun akademik yang sedang berjalan'),
('kontak_email', 'project@kampus.ac.id', 'Email layanan bantuan mahasiswa')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- Seed data: aktivitas_log
INSERT INTO `aktivitas_log` (`user_id`, `username`, `aktivitas`, `detail`, `ip_address`, `user_agent`) VALUES
(1, 'admin', 'Inisialisasi Sistem', 'Database db_project dan tabel bawaan berhasil dibuat.', '127.0.0.1', 'CLI/Setup Script')
ON DUPLICATE KEY UPDATE `aktivitas` = VALUES(`aktivitas`);
