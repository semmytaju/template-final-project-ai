<?php
/**
 * Controller Dashboard - Template Proyek Akhir Mahasiswa
 * Area utama setelah pengguna berhasil login ke sistem.
 */

class dashboard extends Controller {

    public function index() {
        $this->start_default_session_app();

        // Autentikasi Pengguna
        if (empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        $db = new Database();
        $dbDiagnostics = $db->getDiagnostics();

        $mhsModel = $this->logic('Mahasiswa_model');
        $stats = $mhsModel ? $mhsModel->getStats() : ['total' => 0, 'aktif' => 0, 'cuti' => 0, 'lulus' => 0, 'jurusan_count' => 0];

        $aktModel = $this->logic('Aktivitas_model');
        $recentLogs = $aktModel ? $aktModel->getRecent(6) : [];

        $userModel = $this->logic('User_model');
        $userCount = $userModel ? $userModel->get_user_count() : 0;

        $data = [
            'title' => 'Dashboard - ' . APP_NAME,
            'app_name' => APP_NAME,
            'active_menu' => 'dashboard',
            'user' => [
                'id' => $_SESSION['user-id'],
                'nama' => $_SESSION['user-name'] ?? 'Pengguna',
                'username' => $_SESSION['user-username'] ?? 'user',
                'role' => $_SESSION['user-role'] ?? 'user',
                'email' => $_SESSION['user-email'] ?? '',
                'avatar' => $_SESSION['user-avatar'] ?? 'default-avatar.png'
            ],
            'stats' => $stats,
            'user_count' => $userCount,
            'recent_logs' => $recentLogs,
            'db_diagnostics' => $dbDiagnostics,
            'csrf_token' => $this->get_csrf_token()
        ];

        $this->display('dashboard/index', $data);
    }
}
