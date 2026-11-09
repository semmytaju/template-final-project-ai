<?php
/**
 * Controller Autentikasi (Login / Logout) - Template Proyek Akhir Mahasiswa
 * Menangani login multi-role (Admin, Mahasiswa, Dosen) dengan perlindungan CSRF.
 */

class login extends Controller {

    public function index() {
        $this->start_default_session_app();

        // Jika sudah login, redirect langsung ke Dashboard
        if (!empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/dashboard/');
            exit();
        }

        // Ambil pesan error login jika ada
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        $data = [
            'title' => 'Login Sistem - ' . APP_NAME,
            'app_name' => APP_NAME,
            'error' => $error,
            'csrf_token' => $this->get_csrf_token()
        ];

        $this->display('login/index', $data);
    }

    public function authenticate() {
        $this->start_default_session_app();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        // Validasi CSRF Token
        if (!$this->verify_csrf_token()) {
            $_SESSION['login_error'] = 'Token sesi tidak valid atau telah kedaluwarsa. Silakan coba lagi.';
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'Username dan password wajib diisi.';
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        // Periksa status database terlebih dahulu
        $db = new Database();
        if (!$db->isConnected()) {
            $_SESSION['login_error'] = 'Koneksi database db_project belum terhubung. Periksa konfigurasi MySQL Anda.';
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        $userModel = $this->logic('User_model');
        $user = $userModel ? $userModel->validate_credentials($username, $password) : false;

        if ($user) {
            // Regenerate session id untuk keamanan (mencegah session fixation)
            session_regenerate_id(true);

            $_SESSION['user-id'] = $user['id'];
            $_SESSION['user-name'] = $user['nama_lengkap'];
            $_SESSION['user-username'] = $user['username'];
            $_SESSION['user-role'] = $user['role'];
            $_SESSION['user-email'] = $user['email'];
            $_SESSION['user-avatar'] = $user['avatar'] ?? 'default-avatar.png';

            header('Location: ' . APP_PATH . '/dashboard/');
            exit();
        } else {
            $_SESSION['login_error'] = 'Username atau password yang Anda masukkan salah.';
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }
    }

    public function logout() {
        $this->start_default_session_app();

        // Catat aktivitas logout jika ada user
        if (!empty($_SESSION['user-id'])) {
            $aktModel = $this->logic('Aktivitas_model');
            if ($aktModel) {
                $aktModel->log($_SESSION['user-id'], $_SESSION['user-username'] ?? 'user', 'User Logout', 'Pengguna keluar dari sistem');
            }
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        @session_destroy();

        header('Location: ' . APP_PATH . '/login/');
        exit();
    }
}
