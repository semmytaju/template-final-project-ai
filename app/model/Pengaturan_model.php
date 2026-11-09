<?php
/**
 * Model Pengaturan (Settings Model) - Template Proyek Akhir Mahasiswa
 */

class Pengaturan_model {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        if (!$this->db->isConnected() || !$this->db->hasTable('pengaturan')) return [];
        $this->db->query("SELECT * FROM pengaturan ORDER BY id ASC");
        return $this->db->resultSet();
    }

    public function get($key, $default = null) {
        if (!$this->db->isConnected() || !$this->db->hasTable('pengaturan')) return $default;
        $this->db->query("SELECT setting_value FROM pengaturan WHERE setting_key = :k LIMIT 1");
        $this->db->bind(':k', trim($key));
        $row = $this->db->single();
        return $row ? $row['setting_value'] : $default;
    }

    public function set($key, $value, $deskripsi = null) {
        if (!$this->db->isConnected() || !$this->db->hasTable('pengaturan')) return false;
        $this->db->query("INSERT INTO pengaturan (setting_key, setting_value, deskripsi) 
                          VALUES (:k, :v, :d) 
                          ON DUPLICATE KEY UPDATE setting_value = :v2, deskripsi = COALESCE(:d2, deskripsi)");
        $this->db->bind(':k', trim($key));
        $this->db->bind(':v', $value);
        $this->db->bind(':d', $deskripsi);
        $this->db->bind(':v2', $value);
        $this->db->bind(':d2', $deskripsi);
        return $this->db->execute();
    }
}
