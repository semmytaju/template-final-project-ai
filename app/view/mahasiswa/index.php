<?php
require_once dirname(__DIR__) . '/template/header.php';
?>

<!-- BREADCRUMB & HEADER -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-1 small">
        <li class="breadcrumb-item"><a href="<?php echo APP_PATH; ?>/dashboard/" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kelola Mahasiswa</li>
      </ol>
    </nav>
    <h3 class="fw-bold mb-0">Kelola Data Mahasiswa</h3>
    <span class="text-muted small">Contoh modul CRUD master data berstandar MVC dengan pencarian, paginasi, dan validasi form.</span>
  </div>

  <div class="d-flex align-items-center gap-2">
    <a href="<?php echo APP_PATH; ?>/mahasiswa/tambah/" class="btn btn-primary fw-semibold shadow-sm">
      <i class="bi bi-person-plus-fill me-1"></i> Tambah Mahasiswa
    </a>
  </div>
</div>

<!-- FLASH NOTIFICATION -->
<?php if (!empty($data['flash'])): ?>
  <div class="alert alert-<?php echo htmlspecialchars($data['flash']['type'] ?? 'info'); ?> alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2 py-3 px-4 rounded-3 mb-4" role="alert">
    <i class="bi bi-check-circle-fill fs-5"></i>
    <div><?php echo htmlspecialchars($data['flash']['text'] ?? ''); ?></div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<!-- FILTER & SEARCH BAR -->
<div class="card border-0 shadow-sm rounded-3 p-3 bg-white mb-4">
  <form action="<?php echo APP_PATH; ?>/mahasiswa/" method="GET" class="row g-2 align-items-center">
    <div class="col-md-5">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control border-start-0" name="q" placeholder="Cari berdasarkan NIM, Nama, atau Email..." value="<?php echo htmlspecialchars($data['keyword'] ?? ''); ?>">
      </div>
    </div>

    <div class="col-md-4">
      <select name="jurusan" class="form-select">
        <option value="">Semua Jurusan</option>
        <?php foreach ($data['jurusan_list'] as $jur): ?>
          <option value="<?php echo htmlspecialchars($jur); ?>" <?php echo ($data['selected_jurusan'] === $jur) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($jur); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-secondary w-100 fw-semibold">
        <i class="bi bi-funnel me-1"></i> Filter
      </button>
      <?php if (!empty($data['keyword']) || !empty($data['selected_jurusan'])): ?>
        <a href="<?php echo APP_PATH; ?>/mahasiswa/" class="btn btn-outline-danger" title="Reset Filter">
          <i class="bi bi-x-lg"></i>
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- DATA TABLE -->
<div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden mb-4">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light border-bottom">
        <tr>
          <th scope="col" class="ps-4">NIM</th>
          <th scope="col">Nama Lengkap</th>
          <th scope="col">Jurusan</th>
          <th scope="col">Angkatan</th>
          <th scope="col">Email & Kontak</th>
          <th scope="col">Status</th>
          <th scope="col" class="text-end pe-4">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($data['mahasiswa_list'])): ?>
          <?php foreach ($data['mahasiswa_list'] as $m): ?>
            <tr>
              <td class="ps-4">
                <span class="badge bg-light text-dark border code-font fw-bold"><?php echo htmlspecialchars($m['nim']); ?></span>
              </td>
              <td>
                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($m['nama']); ?></div>
                <div class="text-muted small"><?php echo htmlspecialchars($m['alamat'] ?: 'Belum ada alamat'); ?></div>
              </td>
              <td><?php echo htmlspecialchars($m['jurusan']); ?></td>
              <td><span class="code-font"><?php echo htmlspecialchars($m['angkatan']); ?></span></td>
              <td>
                <div class="small text-muted"><i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($m['email']); ?></div>
                <?php if (!empty($m['no_hp'])): ?>
                  <div class="small text-muted"><i class="bi bi-telephone me-1"></i><?php echo htmlspecialchars($m['no_hp']); ?></div>
                <?php endif; ?>
              </td>
              <td>
                <?php 
                  $statusBadge = match($m['status']) {
                    'Aktif' => 'bg-success-subtle text-success border border-success-subtle',
                    'Cuti' => 'bg-warning-subtle text-warning border border-warning-subtle',
                    'Lulus' => 'bg-info-subtle text-info border border-info-subtle',
                    default => 'bg-secondary-subtle text-secondary'
                  };
                ?>
                <span class="badge <?php echo $statusBadge; ?> px-2 py-1">
                  <?php echo htmlspecialchars($m['status']); ?>
                </span>
              </td>
              <td class="text-end pe-4">
                <div class="btn-group btn-group-sm">
                  <a href="<?php echo APP_PATH; ?>/mahasiswa/detail/<?php echo (int)$m['id']; ?>/" class="btn btn-outline-secondary" title="Detail">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="<?php echo APP_PATH; ?>/mahasiswa/edit/<?php echo (int)$m['id']; ?>/" class="btn btn-outline-primary" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="confirmDelete(<?php echo (int)$m['id']; ?>, '<?php echo htmlspecialchars(addslashes($m['nama'])); ?>')">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
              <div class="fw-semibold">Tidak ada data mahasiswa ditemukan.</div>
              <p class="small text-muted mb-3">Coba ubah kata kunci pencarian atau tambah data baru.</p>
              <a href="<?php echo APP_PATH; ?>/mahasiswa/tambah/" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Mahasiswa Sekarang
              </a>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- PAGINATION & TOTAL INFO -->
  <div class="card-footer bg-white border-top py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div class="text-muted small">
      Menampilkan <span class="fw-bold"><?php echo count($data['mahasiswa_list']); ?></span> dari total <span class="fw-bold"><?php echo (int)$data['total_data']; ?></span> mahasiswa
    </div>

    <?php if ($data['total_pages'] > 1): ?>
      <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm mb-0">
          <?php 
            $qParam = !empty($data['keyword']) ? '&q=' . urlencode($data['keyword']) : '';
            $jurParam = !empty($data['selected_jurusan']) ? '&jurusan=' . urlencode($data['selected_jurusan']) : '';
            $basePageUrl = APP_PATH . '/mahasiswa/?' . ltrim($qParam . $jurParam, '&');
            $connector = (strpos($basePageUrl, '?') !== false && substr($basePageUrl, -1) !== '?') ? '&' : '';
          ?>

          <li class="page-item <?php echo ($data['current_page'] <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo $basePageUrl . $connector; ?>page=<?php echo $data['current_page'] - 1; ?>">&laquo; Sebelumnya</a>
          </li>

          <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
            <li class="page-item <?php echo ($data['current_page'] == $i) ? 'active' : ''; ?>">
              <a class="page-link" href="<?php echo $basePageUrl . $connector; ?>page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <li class="page-item <?php echo ($data['current_page'] >= $data['total_pages']) ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo $basePageUrl . $connector; ?>page=<?php echo $data['current_page'] + 1; ?>">Berikutnya &raquo;</a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 border-0 shadow">
      <form id="deleteForm" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token']); ?>">
        <div class="modal-body p-4 text-center">
          <div class="d-inline-flex p-3 rounded-circle bg-danger-subtle text-danger mb-3">
            <i class="bi bi-exclamation-triangle-fill fs-2"></i>
          </div>
          <h5 class="fw-bold text-dark mb-2">Hapus Data Mahasiswa?</h5>
          <p class="text-secondary small mb-4">
            Apakah Anda yakin ingin menghapus data <strong id="deleteNama" class="text-dark"></strong>? Tindakan ini tidak dapat dibatalkan.
          </p>
          <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger px-4 fw-semibold">Ya, Hapus Data</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function confirmDelete(id, nama) {
  document.getElementById('deleteNama').textContent = nama;
  document.getElementById('deleteForm').action = '<?php echo APP_PATH; ?>/mahasiswa/hapus/' + id + '/';
  const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
  modal.show();
}
</script>

<?php
require_once dirname(__DIR__) . '/template/footer.php';
?>
