<?php
if (!empty($_SESSION['user-id'])) {
    require_once dirname(__DIR__) . '/template/header.php';
} else {
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($data['title']); ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body { background-color: #f8fafc; color: #334155; font-family: system-ui, -apple-system, sans-serif; }
    .card-simple { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; }
  </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark py-3">
  <div class="container" style="max-width: 850px;">
    <a class="navbar-brand fw-bold" href="<?php echo APP_PATH; ?>/"><?php echo htmlspecialchars(APP_NAME); ?></a>
    <div>
      <a href="<?php echo APP_PATH; ?>/" class="btn btn-outline-light btn-sm">&larr; Beranda</a>
      <a href="<?php echo APP_PATH; ?>/login/" class="btn btn-primary btn-sm">Login</a>
    </div>
  </div>
</nav>
<div class="container my-5" style="max-width: 850px;">
<?php } ?>

<div class="mb-4 text-center">
  <h3 class="fw-bold mb-1">Petunjuk Penggunaan Template</h3>
  <p class="text-muted small">Panduan singkat penggunaan template MVC PHP untuk mahasiswa.</p>
</div>

<!-- 1. INSTALASI & MENJALANKAN -->
<div class="card card-simple p-4 mb-4">
  <h5 class="fw-bold text-primary mb-3">1. Cara Menjalankan Proyek di XAMPP</h5>
  <ol class="small text-secondary lh-lg mb-0 ps-3">
    <li>Nyalakan <strong>Apache</strong> dan <strong>MySQL</strong> pada <strong>XAMPP Control Panel</strong>.</li>
    <li>Letakkan folder proyek di dalam <code>C:\xampp\htdocs\template-final-project-ai</code>.</li>
    <li>Buka browser dan akses: <a href="<?php echo APP_PATH; ?>/" target="_blank"><?php echo APP_PATH; ?>/</a>.</li>
    <li>Pastikan status koneksi database di halaman utama berstatus hijau (Terhubung).</li>
  </ol>
</div>

<!-- 2. KONFIGURASI DATABASE -->
<div class="card card-simple p-4 mb-4">
  <h5 class="fw-bold text-primary mb-3">2. Database (<code>db_project</code>)</h5>
  <p class="small text-secondary mb-2">
    Database bawaan bernama <strong><code>db_project</code></strong>. Anda dapat menginisialisasi database dengan salah satu cara berikut:
  </p>
  <ul class="small text-secondary mb-3">
    <li><strong>Cara Otomatis:</strong> Buka halaman utama aplikasi, lalu klik tombol <strong>"Buat Database & Tabel Otomatis"</strong>.</li>
    <li><strong>Cara Manual:</strong> Buka <code>http://localhost/phpmyadmin</code>, buat database <code>db_project</code>, lalu import file <code>database/db_project.sql</code>.</li>
  </ul>

  <div class="bg-light p-3 rounded small font-monospace">
    // Konfigurasi koneksi di: app/config/config.php<br>
    define("HOST_DB", "localhost");<br>
    define("NAME_DB", "db_project");<br>
    define("USER_DB", "root");<br>
    define("PASS_DB", ""); // default XAMPP kosong
  </div>
</div>

<!-- 3. AKUN LOGIN DEMO -->
<div class="card card-simple p-4 mb-4">
  <h5 class="fw-bold text-primary mb-3">3. Akun Login Bawaan</h5>
  <div class="table-responsive">
    <table class="table table-sm table-bordered align-middle small mb-0">
      <thead class="table-light">
        <tr><th>Role</th><th>Username</th><th>Password</th><th>Hak Akses</th></tr>
      </thead>
      <tbody>
        <tr><td><span class="badge bg-danger">admin</span></td><td><code>admin</code></td><td><code>admin123</code></td><td>Akses penuh sistem & dashboard</td></tr>
        <tr><td><span class="badge bg-success">mahasiswa</span></td><td><code>mahasiswa</code></td><td><code>mahasiswa123</code></td><td>Akses akun mahasiswa</td></tr>
        <tr><td><span class="badge bg-info text-dark">dosen</span></td><td><code>dosen</code></td><td><code>dosen123</code></td><td>Akses akun dosen / penguji</td></tr>
      </tbody>
    </table>
  </div>
</div>

<!-- 4. CARA MEMBUAT FITUR BARU -->
<div class="card card-simple p-4 mb-4">
  <h5 class="fw-bold text-primary mb-3">4. Cara Menambah Fitur Baru (3 Langkah)</h5>
  <p class="small text-secondary mb-3">Contoh jika ingin membuat halaman modul <strong>Buku</strong> (atau Barang / Transaksi):</p>

  <h6 class="fw-bold small mb-1">A. Buat Model di <code>app/model/Buku_model.php</code>:</h6>
  <pre class="bg-dark text-white p-3 rounded small font-monospace mb-3"><code>&lt;?php
class Buku_model {
    private $db;
    public function __construct() {
        $this-&gt;db = new Database();
    }
    public function getAll() {
        $this-&gt;db-&gt;query("SELECT * FROM buku ORDER BY id DESC");
        return $this-&gt;db-&gt;resultSet();
    }
}</code></pre>

  <h6 class="fw-bold small mb-1">B. Buat Controller di <code>app/controller/buku.php</code>:</h6>
  <pre class="bg-dark text-white p-3 rounded small font-monospace mb-3"><code>&lt;?php
class buku extends Controller {
    public function index() {
        $model = $this-&gt;logic('Buku_model');
        $data = [
            'title' => 'Data Buku',
            'buku' => $model-&gt;getAll()
        ];
        $this-&gt;display('buku/index', $data);
    }
}</code></pre>

  <h6 class="fw-bold small mb-1">C. Buat View di <code>app/view/buku/index.php</code>:</h6>
  <pre class="bg-dark text-white p-3 rounded small font-monospace mb-2"><code>&lt;?php require_once dirname(__DIR__) . '/template/header.php'; ?&gt;
&lt;h4 class="fw-bold"&gt;Daftar Buku&lt;/h4&gt;
&lt;ul&gt;
  &lt;?php foreach ($data['buku'] as $b): ?&gt;
    &lt;li&gt;&lt;?php echo htmlspecialchars($b['judul']); ?&gt;&lt;/li&gt;
  &lt;?php endforeach; ?&gt;
&lt;/ul&gt;
&lt;?php require_once dirname(__DIR__) . '/template/footer.php'; ?&gt;</code></pre>
  <div class="small text-muted">Akses melalui browser: <code>http://localhost/template-final-project-ai/buku/</code></div>
</div>

<!-- 5. CHEAT SHEET QUERY DATABASE -->
<div class="card card-simple p-4">
  <h5 class="fw-bold text-primary mb-3">5. Contoh Query Database yang Aman</h5>
  <pre class="bg-light p-3 rounded small font-monospace mb-0"><code>// 1. SELECT BANYAK DATA
$this->db->query("SELECT * FROM mahasiswa WHERE status = :s");
$this->db->bind(':s', 'Aktif');
$rows = $this->db->resultSet();

// 2. SELECT SATU BARIS
$this->db->query("SELECT * FROM mahasiswa WHERE id = :id LIMIT 1");
$this->db->bind(':id', $id);
$row = $this->db->single();

// 3. INSERT DATA
$this->db->query("INSERT INTO mahasiswa (nim, nama) VALUES (:nim, :nama)");
$this->db->bind(':nim', $nim);
$this->db->bind(':nama', $nama);
$this->db->execute();</code></pre>
</div>

<?php
if (!empty($_SESSION['user-id'])) {
    require_once dirname(__DIR__) . '/template/footer.php';
} else {
?>
</div>
<footer class="py-3 text-center text-muted small border-top bg-white mt-5">
  &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(APP_NAME); ?> &bull; Template Sederhana
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
