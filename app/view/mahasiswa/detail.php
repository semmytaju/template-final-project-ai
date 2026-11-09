<?php
require_once dirname(__DIR__) . '/template/header.php';
$mhs = $data['mahasiswa'];
?>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1 small">
            <li class="breadcrumb-item"><a href="<?php echo APP_PATH; ?>/dashboard/" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo APP_PATH; ?>/mahasiswa/" class="text-decoration-none">Kelola Mahasiswa</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Mahasiswa</li>
          </ol>
        </nav>
        <h3 class="fw-bold mb-0">Profil Mahasiswa</h3>
        <span class="text-muted small">Informasi rinci data mahasiswa terdaftar.</span>
      </div>

      <div class="d-flex gap-2">
        <a href="<?php echo APP_PATH; ?>/mahasiswa/edit/<?php echo (int)$mhs['id']; ?>/" class="btn btn-outline-primary btn-sm">
          <i class="bi bi-pencil-square me-1"></i> Edit Data
        </a>
        <a href="<?php echo APP_PATH; ?>/mahasiswa/" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
      <div class="d-flex flex-wrap align-items-center gap-4 pb-4 border-bottom mb-4">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fs-2 fw-bold shadow-sm" style="width: 72px; height: 72px;">
          <?php echo strtoupper(substr($mhs['nama'], 0, 1)); ?>
        </div>
        <div>
          <h4 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($mhs['nama']); ?></h4>
          <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="badge bg-light text-dark border code-font fs-6"><?php echo htmlspecialchars($mhs['nim']); ?></span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?php echo htmlspecialchars($mhs['jurusan']); ?></span>
            <span class="badge bg-success-subtle text-success border border-success-subtle"><?php echo htmlspecialchars($mhs['status']); ?></span>
          </div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-sm-6">
          <label class="text-muted small fw-semibold text-uppercase">Tahun Angkatan</label>
          <div class="fs-6 fw-bold code-font"><?php echo htmlspecialchars($mhs['angkatan']); ?></div>
        </div>
        <div class="col-sm-6">
          <label class="text-muted small fw-semibold text-uppercase">Status Akademik</label>
          <div class="fs-6 fw-bold"><?php echo htmlspecialchars($mhs['status']); ?></div>
        </div>
        <div class="col-sm-6">
          <label class="text-muted small fw-semibold text-uppercase">Alamat Email</label>
          <div class="fs-6"><a href="mailto:<?php echo htmlspecialchars($mhs['email']); ?>" class="text-decoration-none"><?php echo htmlspecialchars($mhs['email']); ?></a></div>
        </div>
        <div class="col-sm-6">
          <label class="text-muted small fw-semibold text-uppercase">Nomor WhatsApp / HP</label>
          <div class="fs-6"><?php echo htmlspecialchars($mhs['no_hp'] ?: 'Tidak dicantumkan'); ?></div>
        </div>
        <div class="col-12">
          <label class="text-muted small fw-semibold text-uppercase">Alamat Domisili</label>
          <div class="p-3 bg-light rounded-3 text-secondary">
            <?php echo nl2br(htmlspecialchars($mhs['alamat'] ?: 'Belum ada data alamat domisili.')); ?>
          </div>
        </div>
        <div class="col-sm-6">
          <label class="text-muted small fw-semibold text-uppercase">Tanggal Terdaftar</label>
          <div class="small text-muted code-font"><?php echo date('d F Y, H:i', strtotime($mhs['created_at'])); ?></div>
        </div>
        <div class="col-sm-6">
          <label class="text-muted small fw-semibold text-uppercase">Terakhir Diperbarui</label>
          <div class="small text-muted code-font"><?php echo date('d F Y, H:i', strtotime($mhs['updated_at'])); ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
require_once dirname(__DIR__) . '/template/footer.php';
?>
