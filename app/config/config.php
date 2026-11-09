<?php
/**
 * Konfigurasi Utama Aplikasi - Template Proyek Akhir Mahasiswa
 * Framework MVC Native PHP
 */

// 1. Deteksi Base URL / APP_PATH secara otomatis
if (!defined('APP_PATH')) {
    if (php_sapi_name() === 'cli' || empty($_SERVER['HTTP_HOST'])) {
        $folderName = basename(dirname(__DIR__, 2));
        define("APP_PATH", "http://localhost/" . $folderName);
    } else {
        $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        $protocol = $is_https ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        $cleanDir = preg_replace('#/public$#', '', $scriptDir);
        define("APP_PATH", $protocol . $host . $cleanDir);
    }
}

// 2. Direktori Root Proyek (Filesystem Path)
if (!defined('BASEPATH')) {
    define('BASEPATH', dirname(__DIR__, 2));
}

// 3. Konfigurasi Database MySQL
define("HOST_DB", "localhost");
define("NAME_DB", "db_project");  // Nama database default untuk proyek mahasiswa
define("USER_DB", "root");
define("PASS_DB", "");            // Password default XAMPP (sistem juga otomatis mencoba "12345" dan "root")

// 4. Informasi Proyek / Metadata Template
define("APP_NAME", "Template Proyek Akhir Mahasiswa");
define("APP_SHORT_NAME", "ProyekAkhir");
define("APP_DESC", "Starter Kit MVC PHP Native untuk Proyek Akhir, Skripsi & Capstone Project");
define("APP_VERSION", "1.0.0");
define("APP_AUTHOR", "Tim Dosen & Mahasiswa");

// 5. Kunci Enkripsi & Keamanan Aplikasi (Ganti salt jika diperlukan)
define('APP_ENC_KEY', hash('sha256', 'FINAL_PROJECT_TEMPLATE_SECRET_KEY_2026', true));
define('APP_BIDX_KEY', hash('sha256', 'FINAL_PROJECT_BLIND_INDEX_SALT_KEY_2026', true));
