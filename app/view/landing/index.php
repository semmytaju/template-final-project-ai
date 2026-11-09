<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($data['title']); ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      background-color: #f8fafc;
      color: #334155;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .card-simple {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .card-simple:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.06);
    }
  </style>
</head>
<body>

<!-- NAVBAR SEDERHANA -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?php echo APP_PATH; ?>/">
      <i class="bi bi-code-slash text-primary fs-4"></i>
      <span><?php echo htmlspecialchars($data['app_name']); ?></span>
    </a>
    <div class="ms-auto d-flex gap-2">
      <a href="<?php echo APP_PATH; ?>/mahasiswa/" class="btn btn-outline-light btn-sm">
        <i class="bi bi-people me-1"></i> Contoh CRUD
      </a>
      <a href="<?php echo APP_PATH; ?>/panduan/" class="btn btn-outline-light btn-sm">
        <i class="bi bi-book me-1"></i> Panduan
      </a>
      <a href="<?php echo APP_PATH; ?>/login/" class="btn btn-primary btn-sm fw-semibold">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
      </a>
    </div>
  </div>
</nav>

<!-- MAIN CONTENT -->
<main class="container my-5" style="max-width: 900px;">

  <!-- NOTIFIKASI FLASH -->
  <?php if (!empty($data['flash_message'])): ?>
    <div class="alert alert-<?php echo htmlspecialchars($data['flash_message']['type'] ?? 'info'); ?> alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
      <strong><?php echo htmlspecialchars($data['flash_message']['title'] ?? ''); ?></strong>
      <?php echo htmlspecialchars($data['flash_message']['text'] ?? ''); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- HEADER / HERO SEDERHANA -->
  <div class="text-center mb-5">
    <h2 class="fw-bold mb-2"><?php echo htmlspecialchars($data['app_name']); ?></h2>
    <p class="text-muted lead fs-6 mb-0">
      Starter template berbasis PHP Native MVC untuk Tugas Akhir, Skripsi, dan Proyek Mahasiswa.
    </p>
  </div>

  <!-- ============================================================= -->
  <!-- STATUS KONEKSI DATABASE (INFORMASI UTAMA)                     -->
  <!-- ============================================================= -->
  <div class="mb-4">
    <?php if (!$data['db_diagnostics']['is_connected']): ?>
      <!-- JIKA DATABASE BELUM TERHUBUNG / ERROR -->
      <div class="alert alert-danger shadow-sm border-0 p-4 rounded-3" role="alert">
        <div class="d-flex align-items-start gap-3">
          <i class="bi bi-exclamation-triangle-fill fs-2 text-danger flex-shrink-0"></i>
          <div class="flex-grow-1">
            <h5 class="alert-heading fw-bold mb-1">⚠️ Database Belum Terhubung!</h5>
            <p class="mb-2 text-secondary small">
              Sistem tidak dapat terhubung ke database <code><?php echo htmlspecialchars($data['db_diagnostics']['database']); ?></code> pada host <code><?php echo htmlspecialchars($data['db_diagnostics']['host']); ?></code>.
            </p>
            <div class="bg-white p-2 rounded border border-danger-subtle small text-danger font-monospace mb-3">
              Error: <?php echo htmlspecialchars($data['db_diagnostics']['error_message'] ?: 'MySQL Connection Refused'); ?>
            </div>
            
            <div class="small text-secondary mb-3">
              <strong>Solusi Cepat untuk Mahasiswa:</strong>
              <ol class="mb-0 ps-3 mt-1">
                <li>Pastikan modul <strong>MySQL</strong> di XAMPP Control Panel sudah <strong>RUNNING</strong>.</li>
                <li>Import skema database <code>database/db_project.sql</code> ke phpMyAdmin (<a href="http://localhost/phpmyadmin" target="_blank" class="text-decoration-underline">localhost/phpmyadmin</a>).</li>
                <li>Periksa password MySQL di <code>app/config/config.php</code> jika Anda menggunakan password khusus.</li>
              </ol>
            </div>

            <!-- TOMBOL INISIALISASI OTOMATIS -->
            <form action="<?php echo APP_PATH; ?>/setup/init/" method="POST" class="d-inline-block">
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token']); ?>">
              <button type="submit" class="btn btn-danger btn-sm fw-semibold shadow-sm">
                <i class="bi bi-magic me-1"></i> Buat Database & Tabel Otomatis
              </button>
            </form>
          </div>
        </div>
      </div>

    <?php elseif (!$data['db_diagnostics']['is_schema_ready']): ?>
      <!-- JIKA DATABASE ADA TAPI TABEL BELUM DIIMPORT -->
      <div class="alert alert-warning shadow-sm border-0 p-4 rounded-3" role="alert">
        <div class="d-flex align-items-start gap-3">
          <i class="bi bi-exclamation-circle-fill fs-2 text-warning flex-shrink-0"></i>
          <div class="flex-grow-1">
            <h5 class="alert-heading fw-bold mb-1">⚠️ Tabel Database Belum Diimpor</h5>
            <p class="mb-3 text-secondary small">
              Database <code><?php echo htmlspecialchars($data['db_diagnostics']['database']); ?></code> terhubung, tetapi tabel bawaan (users, mahasiswa, dll.) belum ada.
            </p>
            <form action="<?php echo APP_PATH; ?>/setup/init/" method="POST">
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token']); ?>">
              <button type="submit" class="btn btn-warning btn-sm fw-semibold shadow-sm">
                <i class="bi bi-download me-1"></i> Import Tabel Default Sekarang
              </button>
            </form>
          </div>
        </div>
      </div>

    <?php else: ?>
      <!-- JIKA DATABASE TERHUBUNG DENGAN SUKSES -->
      <div class="alert alert-success shadow-sm border-0 p-3 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2" role="alert">
        <div class="d-flex align-items-center gap-3">
          <i class="bi bi-check-circle-fill fs-3 text-success"></i>
          <div>
            <h6 class="fw-bold mb-0 text-success">✅ Database "<?php echo htmlspecialchars($data['db_diagnostics']['database']); ?>" Terhubung</h6>
            <div class="small text-secondary">
              Host: <code><?php echo htmlspecialchars($data['db_diagnostics']['host']); ?></code> &bull; 
              Tabel Siap: <code><?php echo implode(', ', $data['db_diagnostics']['tables']); ?></code> &bull; 
              PHP: <code><?php echo htmlspecialchars($data['php_version']); ?></code>
            </div>
          </div>
        </div>
        <span class="badge bg-success px-3 py-2">Sistem Siap Digunakan</span>
      </div>
    <?php endif; ?>
  </div>

  <!-- MENU / KARTU FITUR UTAMA -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card-simple p-4 h-100 text-center">
        <div class="d-inline-flex p-3 rounded-circle bg-primary-subtle text-primary mb-3">
          <i class="bi bi-people fs-3"></i>
        </div>
        <h5 class="fw-bold mb-1">Contoh CRUD</h5>
        <p class="text-muted small mb-3">Lihat contoh implementasi Create, Read, Update, Delete data mahasiswa.</p>
        <a href="<?php echo APP_PATH; ?>/mahasiswa/" class="btn btn-outline-primary btn-sm w-100 mt-auto fw-semibold">
          Buka Data Mahasiswa &rarr;
        </a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card-simple p-4 h-100 text-center">
        <div class="d-inline-flex p-3 rounded-circle bg-success-subtle text-success mb-3">
          <i class="bi bi-speedometer2 fs-3"></i>
        </div>
        <h5 class="fw-bold mb-1">Dashboard</h5>
        <p class="text-muted small mb-3">Halaman utama setelah login untuk mengelola sistem dan melihat aktivitas.</p>
        <a href="<?php echo APP_PATH; ?>/login/" class="btn btn-outline-success btn-sm w-100 mt-auto fw-semibold">
          Masuk ke Dashboard &rarr;
        </a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card-simple p-4 h-100 text-center">
        <div class="d-inline-flex p-3 rounded-circle bg-info-subtle text-info mb-3">
          <i class="bi bi-journal-text fs-3"></i>
        </div>
        <h5 class="fw-bold mb-1">Petunjuk Penggunaan</h5>
        <p class="text-muted small mb-3">Panduan langkah-demi-langkah cara menambah fitur baru pada template.</p>
        <a href="<?php echo APP_PATH; ?>/panduan/" class="btn btn-outline-info btn-sm w-100 mt-auto fw-semibold">
          Baca Panduan &rarr;
        </a>
      </div>
    </div>
  </div>

  <!-- AKUN DEMO DEFAULT -->
  <div class="card-simple p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-key-fill text-warning me-1"></i> Akun Demo Default untuk Pengujian:</h6>
    <div class="table-responsive">
      <table class="table table-sm table-bordered align-middle small mb-0">
        <thead class="table-light">
          <tr>
            <th>Role</th>
            <th>Username</th>
            <th>Password</th>
            <th>Keterangan</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="badge bg-danger">admin</span></td>
            <td><code>admin</code></td>
            <td><code>admin123</code></td>
            <td class="text-muted">Hak akses penuh (kelola data, dashboard, log)</td>
            <td class="text-center"><a href="<?php echo APP_PATH; ?>/login/?user=admin" class="btn btn-sm btn-link py-0">Login</a></td>
          </tr>
          <tr>
            <td><span class="badge bg-success">mahasiswa</span></td>
            <td><code>mahasiswa</code></td>
            <td><code>mahasiswa123</code></td>
            <td class="text-muted">Hak akses mahasiswa</td>
            <td class="text-center"><a href="<?php echo APP_PATH; ?>/login/?user=mahasiswa" class="btn btn-sm btn-link py-0">Login</a></td>
          </tr>
          <tr>
            <td><span class="badge bg-info text-dark">dosen</span></td>
            <td><code>dosen</code></td>
            <td><code>dosen123</code></td>
            <td class="text-muted">Hak akses dosen / penguji</td>
            <td class="text-center"><a href="<?php echo APP_PATH; ?>/login/?user=dosen" class="btn btn-sm btn-link py-0">Login</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- PANDUAN CEPAT (3 LANGKAH) -->
  <div class="card-simple p-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb-fill text-primary me-1"></i> Cara Cepat Memulai Proyek:</h6>
    <ol class="small text-secondary lh-lg mb-0 ps-3">
      <li><strong>Konfigurasi Database:</strong> Buka file <code>app/config/config.php</code> untuk melihat atau mengubah nama database (default: <code>db_project</code>).</li>
      <li><strong>Struktur Folder MVC:</strong>
        <ul class="mb-1">
          <li><code>app/controller/</code> : Tempat file controller (contoh: <code>mahasiswa.php</code>)</li>
          <li><code>app/model/</code> : Tempat file query database (contoh: <code>Mahasiswa_model.php</code>)</li>
          <li><code>app/view/</code> : Tempat file tampilan HTML/PHP (contoh: <code>app/view/mahasiswa/index.php</code>)</li>
          <li><code>database/db_project.sql</code> : File skema database</li>
        </ul>
      </li>
      <li><strong>Mulai Koding:</strong> Buat Controller, Model, dan View baru sesuai dengan topik proyek Anda.</li>
    </ol>
  </div>

</main>

<!-- FOOTER SEDERHANA -->
<footer class="py-4 text-center text-muted small border-top bg-white mt-5">
  <div class="container">
    &copy; <?php echo date('Y'); ?> <strong><?php echo htmlspecialchars($data['app_name']); ?></strong> &bull; Template Sederhana untuk Mahasiswa
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
