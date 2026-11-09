<?php
/**
 * Model Aktivitas Log - Template Proyek Akhir Mahasiswa
 */

class Aktivitas_model {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function log($userId, $username, $aktivitas, $detail = null) {
        if (!$this->db->isConnected() || !$this->db->hasTable('aktivitas_log')) return false;

        $ip = class_exists('Security') ? Security::get_client_ip() : ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255);

        $sql = "INSERT INTO aktivitas_log (user_id, username, aktivitas, detail, ip_address, user_agent) 
                VALUES (:uid, :u, :a, :d, :ip, :ua)";

        $this->db->query($sql);
        $this->db->bind(':uid', $userId ? (int)$userId : null);
        $this->db->bind(':u', $username);
        $this->db->bind(':a', $aktivitas);
        $this->db->bind(':d', $detail);
        $this->db->bind(':ip', $ip);
        $this->db->bind(':ua', $ua);

        return $this->db->execute();
    }

    public function getRecent($limit = 10) {
        if (!$this->db->isConnected() || !$this->db->hasTable('aktivitas_log')) return [];
        $limit = max(1, (int)$limit);
        $this->db->query("SELECT * FROM aktivitas_log ORDER BY id DESC LIMIT $limit");
        return $this->db->resultSet();
    }
}
