<?php
/**
 * Login Model (Compatibility Layer) - Template Proyek Akhir Mahasiswa
 */

class Login_model {

    private $userModel;

    public function __construct() {
        $this->userModel = new User_model();
    }

    public function validate_credentials($username, $password) {
        return $this->userModel->validate_credentials($username, $password);
    }
}
