<?php
/**
 * Controller Panduan Penggunaan - Template Proyek Akhir Mahasiswa
 * Menampilkan dokumentasi lengkap, tutorial MVC, panduan konfigurasi database,
 * dan praktik terbaik pembuatan fitur baru untuk mahasiswa.
 */

class panduan extends Controller {

    public function index() {
        $this->start_default_session_app();

        $data = [
            'title' => 'Petunjuk Penggunaan Template - ' . APP_NAME,
            'app_name' => APP_NAME,
            'app_desc' => APP_DESC,
            'active_menu' => 'panduan',
            'user' => !empty($_SESSION['user-id']) ? [
                'nama' => $_SESSION['user-name'] ?? 'Pengguna',
                'role' => $_SESSION['user-role'] ?? 'user'
            ] : null,
            'csrf_token' => $this->get_csrf_token()
        ];

        $this->display('panduan/index', $data);
    }
}
