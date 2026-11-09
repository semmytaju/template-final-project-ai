<?php
/**
 * Model Mahasiswa (Mahasiswa Model) - Template Proyek Akhir Mahasiswa
 * Contoh implementasi lengkap CRUD (Create, Read, Update, Delete),
 * Search, Pagination, dan Agregasi Statistik.
 */

class Mahasiswa_model {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Ambil data mahasiswa dengan filter pencarian dan paginasi
     */
    public function getAll($keyword = null, $limit = 10, $offset = 0, $jurusan = null) {
        if (!$this->db->isConnected() || !$this->db->hasTable('mahasiswa')) {
            return [];
        }

        $sql = "SELECT * FROM mahasiswa WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $keyword = trim($keyword);
            $sql .= " AND (nim LIKE :kw OR nama LIKE :kw OR email LIKE :kw)";
            $params[':kw'] = "%$keyword%";
        }

        if (!empty($jurusan) && $jurusan !== 'ALL') {
            $sql .= " AND jurusan = :jur";
            $params[':jur'] = $jurusan;
        }

        $limit = max(1, (int)$limit);
        $offset = max(0, (int)$offset);
        $sql .= " ORDER BY nim ASC LIMIT $offset, $limit";

        $this->db->query($sql);
        foreach ($params as $k => $v) {
            $this->db->bind($k, $v);
        }

        return $this->db->resultSet();
    }

    /**
     * Hitung total data mahasiswa sesuai filter
     */
    public function countAll($keyword = null, $jurusan = null) {
        if (!$this->db->isConnected() || !$this->db->hasTable('mahasiswa')) {
            return 0;
        }

        $sql = "SELECT COUNT(*) as total FROM mahasiswa WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $keyword = trim($keyword);
            $sql .= " AND (nim LIKE :kw OR nama LIKE :kw OR email LIKE :kw)";
            $params[':kw'] = "%$keyword%";
        }

        if (!empty($jurusan) && $jurusan !== 'ALL') {
            $sql .= " AND jurusan = :jur";
            $params[':jur'] = $jurusan;
        }

        $this->db->query($sql);
        foreach ($params as $k => $v) {
            $this->db->bind($k, $v);
        }

        $row = $this->db->single();
        return (int)($row['total'] ?? 0);
    }

    /**
     * Ambil data mahasiswa berdasarkan ID
     */
    public function getById($id) {
        if (!$this->db->isConnected() || !$this->db->hasTable('mahasiswa')) return null;
        $this->db->query("SELECT * FROM mahasiswa WHERE id = :id LIMIT 1");
        $this->db->bind(':id', (int)$id);
        return $this->db->single();
    }

    /**
     * Ambil data mahasiswa berdasarkan NIM
     */
    public function getByNim($nim) {
        if (!$this->db->isConnected() || !$this->db->hasTable('mahasiswa')) return null;
        $this->db->query("SELECT * FROM mahasiswa WHERE nim = :nim LIMIT 1");
        $this->db->bind(':nim', trim($nim));
        return $this->db->single();
    }

    /**
     * Tambah data mahasiswa baru
     */
    public function insert($data) {
        if (!$this->db->isConnected() || !$this->db->hasTable('mahasiswa')) return false;

        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, angkatan, email, no_hp, status, alamat) 
                VALUES (:nim, :nama, :jurusan, :angkatan, :email, :no_hp, :status, :alamat)";

        $this->db->query($sql);
        $this->db->bind(':nim', trim($data['nim']));
        $this->db->bind(':nama', trim($data['nama']));
        $this->db->bind(':jurusan', trim($data['jurusan']));
        $this->db->bind(':angkatan', trim($data['angkatan']));
        $this->db->bind(':email', trim($data['email']));
        $this->db->bind(':no_hp', trim($data['no_hp'] ?? ''));
        $this->db->bind(':status', $data['status'] ?? 'Aktif');
        $this->db->bind(':alamat', trim($data['alamat'] ?? ''));

        return $this->db->execute();
    }

    /**
     * Update data mahasiswa
     */
    public function update($id, $data) {
        if (!$this->db->isConnected() || !$this->db->hasTable('mahasiswa')) return false;

        $sql = "UPDATE mahasiswa 
                SET nim = :nim, nama = :nama, jurusan = :jurusan, angkatan = :angkatan, 
                    email = :email, no_hp = :no_hp, status = :status, alamat = :alamat 
                WHERE id = :id";

        $this->db->query($sql);
        $this->db->bind(':nim', trim($data['nim']));
        $this->db->bind(':nama', trim($data['nama']));
        $this->db->bind(':jurusan', trim($data['jurusan']));
        $this->db->bind(':angkatan', trim($data['angkatan']));
        $this->db->bind(':email', trim($data['email']));
        $this->db->bind(':no_hp', trim($data['no_hp'] ?? ''));
        $this->db->bind(':status', $data['status'] ?? 'Aktif');
        $this->db->bind(':alamat', trim($data['alamat'] ?? ''));
        $this->db->bind(':id', (int)$id);

        return $this->db->execute();
    }

    /**
     * Hapus data mahasiswa
     */
    public function delete($id) {
        if (!$this->db->isConnected() || !$this->db->hasTable('mahasiswa')) return false;
        $this->db->query("DELETE FROM mahasiswa WHERE id = :id");
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    /**
     * Ambil daftar jurusan yang tersedia
     */
    public function getJurusanList() {
        if (!$this->db->isConnected() || !$this->db->hasTable('mahasiswa')) {
            return ['Teknik Informatika', 'Sistem Informasi', 'Ilmu Komputer', 'Sains Data', 'Teknik Komputer'];
        }

        $this->db->query("SELECT DISTINCT jurusan FROM mahasiswa WHERE jurusan IS NOT NULL AND jurusan != '' ORDER BY jurusan ASC");
        $rows = $this->db->resultSet();
        $list = array_column($rows, 'jurusan');
        if (empty($list)) {
            $list = ['Teknik Informatika', 'Sistem Informasi', 'Ilmu Komputer', 'Sains Data'];
        }
        return $list;
    }

    /**
     * Statistik ringkasan untuk dashboard
     */
    public function getStats() {
        if (!$this->db->isConnected() || !$this->db->hasTable('mahasiswa')) {
            return [
                'total' => 0,
                'aktif' => 0,
                'cuti' => 0,
                'lulus' => 0,
                'jurusan_count' => 0
            ];
        }

        $this->db->query("SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'Aktif' THEN 1 ELSE 0 END) as aktif,
            SUM(CASE WHEN status = 'Cuti' THEN 1 ELSE 0 END) as cuti,
            SUM(CASE WHEN status = 'Lulus' THEN 1 ELSE 0 END) as lulus,
            COUNT(DISTINCT jurusan) as jurusan_count
        FROM mahasiswa");

        $row = $this->db->single();
        return [
            'total' => (int)($row['total'] ?? 0),
            'aktif' => (int)($row['aktif'] ?? 0),
            'cuti' => (int)($row['cuti'] ?? 0),
            'lulus' => (int)($row['lulus'] ?? 0),
            'jurusan_count' => (int)($row['jurusan_count'] ?? 0)
        ];
    }
}
