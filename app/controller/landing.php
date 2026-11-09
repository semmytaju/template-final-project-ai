<?php
/**
 * Controller Utama (Landing / Beranda) - Template Proyek Akhir Mahasiswa
 * Menampilkan status diagnostik server, status koneksi database,
 * demo akun, ringkasan struktur MVC, dan panduan inisialisasi awal.
 */

class landing extends Controller {

    public function index() {
        // Inisialisasi Database & Jalankan Diagnostik
        $db = new Database();
        $dbDiagnostics = $db->getDiagnostics();

        // Pemeriksaan Ekstensi Penting PHP
        $requiredExtensions = [
            'mysqli'    => extension_loaded('mysqli'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'openssl'   => extension_loaded('openssl'),
            'mbstring'  => extension_loaded('mbstring'),
            'json'      => extension_loaded('json'),
            'curl'      => extension_loaded('curl'),
            'fileinfo'  => extension_loaded('fileinfo')
        ];

        // Pemeriksaan Folder Uploads
        $uploadDir = BASEPATH . '/public/uploads';
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }
        $isUploadsWritable = is_writable($uploadDir);

        // Ambil data statistik jika database terhubung
        $stats = [
            'total_mahasiswa' => 0,
            'total_users' => 0
        ];
        if ($dbDiagnostics['is_connected']) {
            if ($dbDiagnostics['is_schema_ready']) {
                $mhsModel = $this->logic('Mahasiswa_model');
                $stats['total_mahasiswa'] = $mhsModel ? $mhsModel->countAll() : 0;

                $userModel = $this->logic('User_model');
                $stats['total_users'] = $userModel ? $userModel->get_user_count() : 0;
            }
        }

        // Siapkan pesan notifikasi flash jika ada
        $this->start_default_session_app();
        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $data = [
            'title' => APP_NAME . ' - Starter Kit MVC PHP Native',
            'app_name' => APP_NAME,
            'app_desc' => APP_DESC,
            'app_version' => APP_VERSION,
            'db_diagnostics' => $dbDiagnostics,
            'php_version' => PHP_VERSION,
            'php_version_ok' => version_compare(PHP_VERSION, '7.4.0', '>='),
            'extensions' => $requiredExtensions,
            'uploads_writable' => $isUploadsWritable,
            'stats' => $stats,
            'flash_message' => $flashMessage,
            'csrf_token' => $this->get_csrf_token()
        ];

        $this->display('landing/index', $data);
    }
}
