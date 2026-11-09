<?php
require_once dirname(__DIR__) . '/template/header.php';
?>

<!-- BANNER SELAMAT DATANG -->
<div class="card card-simple p-4 mb-4 border-0 bg-primary text-white">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
      <h4 class="fw-bold mb-1">Selamat Datang, <?php echo htmlspecialchars($data['user']['nama']); ?>!</h4>
      <p class="mb-0 opacity-75 small">Anda masuk sebagai <strong><?php echo htmlspecialchars($data['user']['role']); ?></strong> pada sistem template proyek mahasiswa.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?php echo APP_PATH; ?>/mahasiswa/tambah/" class="btn btn-light btn-sm fw-semibold">
        <i class="bi bi-plus-lg me-1"></i> Tambah Mahasiswa
      </a>
      <a href="<?php echo APP_PATH; ?>/panduan/" class="btn btn-outline-light btn-sm">
        <i class="bi bi-book me-1"></i> Petunjuk
      </a>
    </div>
  </div>
</div>

<!-- STATISTIK RINGKAS -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card card-simple p-3">
      <div class="text-muted small fw-semibold text-uppercase">Total Mahasiswa</div>
      <h3 class="fw-bold my-1 text-primary"><?php echo (int)($data['stats']['total'] ?? 0); ?></h3>
      <div class="small text-muted">Contoh Data Master CRUD</div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card card-simple p-3">
      <div class="text-muted small fw-semibold text-uppercase">Mahasiswa Aktif</div>
      <h3 class="fw-bold my-1 text-success"><?php echo (int)($data['stats']['aktif'] ?? 0); ?></h3>
      <div class="small text-muted">Status Perkuliahan Aktif</div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card card-simple p-3">
      <div class="text-muted small fw-semibold text-uppercase">Status Database</div>
      <h3 class="fw-bold my-1 text-dark fs-4">
        <?php if ($data['db_diagnostics']['is_connected']): ?>
          <span class="text-success"><i class="bi bi-check-circle-fill"></i> Terhubung</span>
        <?php else: ?>
          <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Terputus</span>
        <?php endif; ?>
      </h3>
      <div class="small text-muted">Database: <code>db_project</code></div>
    </div>
  </div>
</div>

<!-- RIWAYAT AKTIVITAS TERAKHIR -->
<div class="card card-simple p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Riwayat Aktivitas Terakhir</h5>
    <a href="<?php echo APP_PATH; ?>/mahasiswa/" class="btn btn-sm btn-link text-decoration-none">Kelola Mahasiswa &rarr;</a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-sm align-middle small mb-0">
      <thead class="table-light">
        <tr>
          <th>Waktu</th>
          <th>User</th>
          <th>Aktivitas</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($data['recent_logs'])): ?>
          <?php foreach ($data['recent_logs'] as $log): ?>
            <tr>
              <td class="text-muted font-monospace"><?php echo date('d/m/Y H:i', strtotime($log['created_at'])); ?></td>
              <td><strong><?php echo htmlspecialchars($log['username'] ?? 'user'); ?></strong></td>
              <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($log['aktivitas']); ?></span></td>
              <td class="text-secondary"><?php echo htmlspecialchars($log['detail'] ?? '-'); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center py-3 text-muted">Belum ada riwayat aktivitas.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
require_once dirname(__DIR__) . '/template/footer.php';
?>
