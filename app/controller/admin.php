<?php
/**
 * Controller Admin (Alias / Redirect ke Dashboard) - Template Proyek Akhir Mahasiswa
 */

class admin extends Controller {

    public function index() {
        $this->start_default_session_app();
        if (empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        header('Location: ' . APP_PATH . '/dashboard/');
        exit();
    }
}
