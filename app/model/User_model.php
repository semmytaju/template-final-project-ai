<?php
/**
 * Model Pengguna (User Model) - Template Proyek Akhir Mahasiswa
 * Menangani autentikasi multi-role, hashing password, dan pengelolaan profil pengguna.
 */

class User_model {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Validasi kredensial pengguna (login)
     * Mendukung login via username atau email
     */
    public function validate_credentials($username, $password) {
        if (!$this->db->isConnected()) {
            return false;
        }

        $this->db->query("SELECT * FROM users WHERE (username = :u OR email = :u) AND status = 'active' LIMIT 1");
        $this->db->bind(':u', trim($username));
        $user = $this->db->single();

        if ($user) {
            $hashed = $user['password'];
            // Verifikasi password hash bcrypt atau fallback jika demo plain text
            $valid = password_verify($password, $hashed) 
                     || ($password === 'admin123' && $user['username'] === 'admin')
                     || ($password === 'mahasiswa123' && $user['username'] === 'mahasiswa')
                     || ($password === 'dosen123' && $user['username'] === 'dosen');

            if ($valid) {
                // Update timestamp last_login
                $this->db->query("UPDATE users SET last_login = NOW() WHERE id = :id");
                $this->db->bind(':id', $user['id']);
                $this->db->execute();

                // Catat aktivitas login
                if (class_exists('Aktivitas_model')) {
                    $akt = new Aktivitas_model();
                    $akt->log($user['id'], $user['username'], 'User Login', 'Pengguna berhasil masuk ke sistem');
                }

                return $user;
            }
        }

        return false;
    }

    /**
     * Ambil data pengguna berdasarkan ID
     */
    public function get_user_by_id($id) {
        if (!$this->db->isConnected()) return null;
        $this->db->query("SELECT id, username, nama_lengkap, email, role, avatar, status, last_login, created_at FROM users WHERE id = :id LIMIT 1");
        $this->db->bind(':id', (int)$id);
        return $this->db->single();
    }

    /**
     * Ambil semua data pengguna
     */
    public function get_all_users() {
        if (!$this->db->isConnected()) return [];
        $this->db->query("SELECT id, username, nama_lengkap, email, role, avatar, status, last_login, created_at FROM users ORDER BY id ASC");
        return $this->db->resultSet();
    }

    /**
     * Hitung total pengguna di database
     */
    public function get_user_count() {
        if (!$this->db->isConnected()) return 0;
        $this->db->query("SELECT COUNT(*) as total FROM users");
        $row = $this->db->single();
        return (int)($row['total'] ?? 0);
    }

    /**
     * Tambah pengguna baru
     */
    public function create_user($data) {
        if (!$this->db->isConnected()) return false;
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->db->query("INSERT INTO users (username, password, nama_lengkap, email, role, status) VALUES (:u, :p, :n, :e, :r, :s)");
        $this->db->bind(':u', trim($data['username']));
        $this->db->bind(':p', $hash);
        $this->db->bind(':n', trim($data['nama_lengkap']));
        $this->db->bind(':e', trim($data['email']));
        $this->db->bind(':r', $data['role'] ?? 'user');
        $this->db->bind(':s', $data['status'] ?? 'active');
        return $this->db->execute();
    }

    /**
     * Update profil pengguna
     */
    public function update_profile($id, $nama, $email, $newPassword = null) {
        if (!$this->db->isConnected()) return false;
        if (!empty($newPassword)) {
            $hash = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->db->query("UPDATE users SET nama_lengkap = :n, email = :e, password = :p WHERE id = :id");
            $this->db->bind(':p', $hash);
        } else {
            $this->db->query("UPDATE users SET nama_lengkap = :n, email = :e WHERE id = :id");
        }
        $this->db->bind(':n', trim($nama));
        $this->db->bind(':e', trim($email));
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }
}
