<?php
/**
 * Controller Home - Template Proyek Akhir Mahasiswa
 * Mengarahkan pengunjung ke Landing atau Dashboard sesuai status login.
 */

class home extends Controller {

    public function index() {
        $this->start_default_session_app();
        if (!empty($_SESSION['user-id']) || !empty($_SESSION['user-role'])) {
            header('Location: ' . APP_PATH . '/dashboard/');
            exit();
        }

        header('Location: ' . APP_PATH . '/');
        exit();
    }
}
