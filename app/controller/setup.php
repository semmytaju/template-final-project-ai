<?php
/**
 * Controller Setup & Inisialisasi Database - Template Proyek Akhir Mahasiswa
 * Membantu mahasiswa menginisialisasi database db_project dan tabel bawaan
 * secara instan dengan 1-klik melalui antarmuka web.
 */

class setup extends Controller {

    public function index() {
        header('Location: ' . APP_PATH . '/');
        exit();
    }

    /**
     * Jalankan proses import skema SQL awal (db_project.sql)
     */
    public function init() {
        $this->start_default_session_app();
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
                  || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Metode HTTP tidak diizinkan. Gunakan POST.']);
                exit();
            }
            header('Location: ' . APP_PATH . '/');
            exit();
        }

        // Verifikasi CSRF Token
        if (!$this->verify_csrf_token()) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Validasi CSRF Token gagal. Silakan muat ulang halaman.']);
                exit();
            }
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'title' => 'Gagal',
                'text' => 'Token keamanan CSRF tidak valid. Silakan coba kembali.'
            ];
            header('Location: ' . APP_PATH . '/');
            exit();
        }

        // Cari file SQL di folder database/ atau root
        $sqlPath = BASEPATH . '/database/db_project.sql';
        if (!file_exists($sqlPath)) {
            $sqlPath = BASEPATH . '/db_project.sql';
        }

        if (!file_exists($sqlPath)) {
            $msg = 'File database/db_project.sql tidak ditemukan di direktori proyek.';
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit();
            }
            $_SESSION['flash_message'] = ['type' => 'danger', 'title' => 'Gagal', 'text' => $msg];
            header('Location: ' . APP_PATH . '/');
            exit();
        }

        $db = new Database();
        $result = $db->importSqlFile($sqlPath);

        if ($result['success']) {
            $msg = 'Inisialisasi database db_project dan tabel bawaan berhasil diselesaikan!';
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => $msg]);
                exit();
            }
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'title' => 'Inisialisasi Berhasil',
                'text' => $msg
            ];
        } else {
            $msg = 'Inisialisasi database gagal: ' . ($result['message'] ?? 'Kesalahan koneksi');
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit();
            }
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'title' => 'Inisialisasi Gagal',
                'text' => $msg
            ];
        }

        header('Location: ' . APP_PATH . '/');
        exit();
    }
}
