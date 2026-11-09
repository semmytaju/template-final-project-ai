<?php
/**
 * Controller Mahasiswa (Master Data CRUD) - Template Proyek Akhir Mahasiswa
 * Contoh lengkap implementasi CRUD (Create, Read, Update, Delete) berstandar MVC:
 * Paginasi, Pencarian, Filter Kategori, Validasi Input, Log Audit & Anti-CSRF.
 */

class mahasiswa extends Controller {

    public function index() {
        $this->start_default_session_app();

        // Autentikasi Pengguna
        if (empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        $mhsModel = $this->logic('Mahasiswa_model');

        // Parameter Pencarian & Filter
        $keyword = trim($_GET['q'] ?? '');
        $jurusan = trim($_GET['jurusan'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 5; // Tampilkan 5 baris per halaman
        $offset = ($page - 1) * $limit;

        $totalData = $mhsModel ? $mhsModel->countAll($keyword, $jurusan) : 0;
        $mahasiswaList = $mhsModel ? $mhsModel->getAll($keyword, $limit, $offset, $jurusan) : [];
        $jurusanList = $mhsModel ? $mhsModel->getJurusanList() : [];
        $totalPages = max(1, (int)ceil($totalData / $limit));

        // Pesan Flash Notifikasi
        $flash = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $data = [
            'title' => 'Kelola Data Mahasiswa - ' . APP_NAME,
            'app_name' => APP_NAME,
            'active_menu' => 'mahasiswa',
            'user' => [
                'nama' => $_SESSION['user-name'] ?? 'Pengguna',
                'role' => $_SESSION['user-role'] ?? 'user'
            ],
            'mahasiswa_list' => $mahasiswaList,
            'jurusan_list' => $jurusanList,
            'keyword' => $keyword,
            'selected_jurusan' => $jurusan,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_data' => $totalData,
            'flash' => $flash,
            'csrf_token' => $this->get_csrf_token()
        ];

        $this->display('mahasiswa/index', $data);
    }

    public function tambah() {
        $this->start_default_session_app();
        if (empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        $mhsModel = $this->logic('Mahasiswa_model');
        $jurusanList = $mhsModel ? $mhsModel->getJurusanList() : [];

        $error = $_SESSION['crud_error'] ?? null;
        $oldInput = $_SESSION['old_input'] ?? [];
        unset($_SESSION['crud_error'], $_SESSION['old_input']);

        $data = [
            'title' => 'Tambah Mahasiswa - ' . APP_NAME,
            'app_name' => APP_NAME,
            'active_menu' => 'mahasiswa',
            'user' => [
                'nama' => $_SESSION['user-name'] ?? 'Pengguna',
                'role' => $_SESSION['user-role'] ?? 'user'
            ],
            'jurusan_list' => $jurusanList,
            'error' => $error,
            'old' => $oldInput,
            'csrf_token' => $this->get_csrf_token()
        ];

        $this->display('mahasiswa/tambah', $data);
    }

    public function simpan() {
        $this->start_default_session_app();
        if (empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        }

        if (!$this->verify_csrf_token()) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Validasi CSRF token gagal.'];
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        }

        $mhsModel = $this->logic('Mahasiswa_model');

        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $jurusan = trim($_POST['jurusan'] ?? '');
        $angkatan = trim($_POST['angkatan'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $status = trim($_POST['status'] ?? 'Aktif');
        $alamat = trim($_POST['alamat'] ?? '');

        // Validasi input
        if (empty($nim) || empty($nama) || empty($jurusan) || empty($angkatan) || empty($email)) {
            $_SESSION['crud_error'] = 'NIM, Nama, Jurusan, Angkatan, dan Email wajib diisi.';
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . APP_PATH . '/mahasiswa/tambah/');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['crud_error'] = 'Format email tidak valid.';
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . APP_PATH . '/mahasiswa/tambah/');
            exit();
        }

        // Cek duplikasi NIM
        if ($mhsModel->getByNim($nim)) {
            $_SESSION['crud_error'] = "Mahasiswa dengan NIM $nim sudah terdaftar.";
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . APP_PATH . '/mahasiswa/tambah/');
            exit();
        }

        $payload = [
            'nim' => $nim,
            'nama' => $nama,
            'jurusan' => $jurusan,
            'angkatan' => $angkatan,
            'email' => $email,
            'no_hp' => $no_hp,
            'status' => $status,
            'alamat' => $alamat
        ];

        $saved = $mhsModel->insert($payload);

        if ($saved) {
            $aktModel = $this->logic('Aktivitas_model');
            if ($aktModel) {
                $aktModel->log($_SESSION['user-id'], $_SESSION['user-username'] ?? 'user', 'Tambah Mahasiswa', "Menambahkan mahasiswa baru: $nama ($nim)");
            }
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => "Data mahasiswa $nama berhasil ditambahkan!"];
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        } else {
            $_SESSION['crud_error'] = 'Gagal menyimpan data ke database.';
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . APP_PATH . '/mahasiswa/tambah/');
            exit();
        }
    }

    public function edit($id = null) {
        $this->start_default_session_app();
        if (empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        $id = (int)$id;
        $mhsModel = $this->logic('Mahasiswa_model');
        $mahasiswa = $mhsModel ? $mhsModel->getById($id) : null;

        if (!$mahasiswa) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Data mahasiswa tidak ditemukan.'];
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        }

        $jurusanList = $mhsModel->getJurusanList();
        $error = $_SESSION['crud_error'] ?? null;
        unset($_SESSION['crud_error']);

        $data = [
            'title' => 'Edit Mahasiswa - ' . APP_NAME,
            'app_name' => APP_NAME,
            'active_menu' => 'mahasiswa',
            'user' => [
                'nama' => $_SESSION['user-name'] ?? 'Pengguna',
                'role' => $_SESSION['user-role'] ?? 'user'
            ],
            'mahasiswa' => $mahasiswa,
            'jurusan_list' => $jurusanList,
            'error' => $error,
            'csrf_token' => $this->get_csrf_token()
        ];

        $this->display('mahasiswa/edit', $data);
    }

    public function update($id = null) {
        $this->start_default_session_app();
        if (empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        }

        if (!$this->verify_csrf_token()) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Validasi CSRF token gagal.'];
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        }

        $id = (int)$id;
        $mhsModel = $this->logic('Mahasiswa_model');
        $existing = $mhsModel ? $mhsModel->getById($id) : null;

        if (!$existing) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Data mahasiswa tidak ditemukan.'];
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        }

        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $jurusan = trim($_POST['jurusan'] ?? '');
        $angkatan = trim($_POST['angkatan'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $status = trim($_POST['status'] ?? 'Aktif');
        $alamat = trim($_POST['alamat'] ?? '');

        if (empty($nim) || empty($nama) || empty($jurusan) || empty($angkatan) || empty($email)) {
            $_SESSION['crud_error'] = 'NIM, Nama, Jurusan, Angkatan, dan Email wajib diisi.';
            header('Location: ' . APP_PATH . '/mahasiswa/edit/' . $id);
            exit();
        }

        // Cek jika NIM diganti dan duplikat dengan mahasiswa lain
        $checkNim = $mhsModel->getByNim($nim);
        if ($checkNim && $checkNim['id'] != $id) {
            $_SESSION['crud_error'] = "Mahasiswa lain dengan NIM $nim sudah terdaftar.";
            header('Location: ' . APP_PATH . '/mahasiswa/edit/' . $id);
            exit();
        }

        $payload = [
            'nim' => $nim,
            'nama' => $nama,
            'jurusan' => $jurusan,
            'angkatan' => $angkatan,
            'email' => $email,
            'no_hp' => $no_hp,
            'status' => $status,
            'alamat' => $alamat
        ];

        $updated = $mhsModel->update($id, $payload);

        if ($updated) {
            $aktModel = $this->logic('Aktivitas_model');
            if ($aktModel) {
                $aktModel->log($_SESSION['user-id'], $_SESSION['user-username'] ?? 'user', 'Update Mahasiswa', "Memperbarui data mahasiswa: $nama ($nim)");
            }
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => "Data mahasiswa $nama berhasil diperbarui!"];
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        } else {
            $_SESSION['crud_error'] = 'Gagal menyimpan perubahan ke database.';
            header('Location: ' . APP_PATH . '/mahasiswa/edit/' . $id);
            exit();
        }
    }

    public function hapus($id = null) {
        $this->start_default_session_app();
        if (empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        }

        if (!$this->verify_csrf_token()) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Validasi keamanan CSRF token gagal.'];
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        }

        $id = (int)$id;
        $mhsModel = $this->logic('Mahasiswa_model');
        $mhs = $mhsModel ? $mhsModel->getById($id) : null;

        if ($mhs && $mhsModel->delete($id)) {
            $aktModel = $this->logic('Aktivitas_model');
            if ($aktModel) {
                $aktModel->log($_SESSION['user-id'], $_SESSION['user-username'] ?? 'user', 'Hapus Mahasiswa', "Menghapus data mahasiswa: " . $mhs['nama'] . " (" . $mhs['nim'] . ")");
            }
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => "Data mahasiswa " . $mhs['nama'] . " berhasil dihapus."];
        } else {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal menghapus data mahasiswa.'];
        }

        header('Location: ' . APP_PATH . '/mahasiswa/');
        exit();
    }

    public function detail($id = null) {
        $this->start_default_session_app();
        if (empty($_SESSION['user-id'])) {
            header('Location: ' . APP_PATH . '/login/');
            exit();
        }

        $id = (int)$id;
        $mhsModel = $this->logic('Mahasiswa_model');
        $mahasiswa = $mhsModel ? $mhsModel->getById($id) : null;

        if (!$mahasiswa) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Data mahasiswa tidak ditemukan.'];
            header('Location: ' . APP_PATH . '/mahasiswa/');
            exit();
        }

        $data = [
            'title' => 'Detail Mahasiswa - ' . htmlspecialchars($mahasiswa['nama']),
            'app_name' => APP_NAME,
            'active_menu' => 'mahasiswa',
            'user' => [
                'nama' => $_SESSION['user-name'] ?? 'Pengguna',
                'role' => $_SESSION['user-role'] ?? 'user'
            ],
            'mahasiswa' => $mahasiswa,
            'csrf_token' => $this->get_csrf_token()
        ];

        $this->display('mahasiswa/detail', $data);
    }
}
