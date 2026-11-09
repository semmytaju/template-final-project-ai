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
            <li class="breadcrumb-item active" aria-current="page">Edit Mahasiswa</li>
          </ol>
        </nav>
        <h3 class="fw-bold mb-0">Edit Data Mahasiswa</h3>
        <span class="text-muted small">Perbarui data mahasiswa <strong class="text-dark"><?php echo htmlspecialchars($mhs['nama']); ?></strong>.</span>
      </div>

      <a href="<?php echo APP_PATH; ?>/mahasiswa/" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
      </a>
    </div>

    <?php if (!empty($data['error'])): ?>
      <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3 border-0 py-3 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div><?php echo htmlspecialchars($data['error']); ?></div>
      </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
      <form action="<?php echo APP_PATH; ?>/mahasiswa/update/<?php echo (int)$mhs['id']; ?>/" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token']); ?>">

        <div class="row g-3">
          <!-- NIM -->
          <div class="col-md-6">
            <label for="nim" class="form-label small fw-semibold text-secondary">Nomor Induk Mahasiswa (NIM) <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo htmlspecialchars($mhs['nim']); ?>" required>
          </div>

          <!-- Nama Lengkap -->
          <div class="col-md-6">
            <label for="nama" class="form-label small fw-semibold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($mhs['nama']); ?>" required>
          </div>

          <!-- Jurusan -->
          <div class="col-md-6">
            <label for="jurusan" class="form-label small fw-semibold text-secondary">Program Studi / Jurusan <span class="text-danger">*</span></label>
            <select class="form-select" id="jurusan" name="jurusan" required>
              <option value="">-- Pilih Jurusan --</option>
              <?php foreach ($data['jurusan_list'] as $jur): ?>
                <option value="<?php echo htmlspecialchars($jur); ?>" <?php echo ($mhs['jurusan'] === $jur) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($jur); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Angkatan -->
          <div class="col-md-6">
            <label for="angkatan" class="form-label small fw-semibold text-secondary">Tahun Angkatan <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="angkatan" name="angkatan" min="2000" max="<?php echo date('Y') + 1; ?>" value="<?php echo htmlspecialchars($mhs['angkatan']); ?>" required>
          </div>

          <!-- Email -->
          <div class="col-md-6">
            <label for="email" class="form-label small fw-semibold text-secondary">Alamat Email Mahasiswa <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($mhs['email']); ?>" required>
          </div>

          <!-- Nomor HP -->
          <div class="col-md-6">
            <label for="no_hp" class="form-label small fw-semibold text-secondary">Nomor WhatsApp / HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($mhs['no_hp'] ?? ''); ?>">
          </div>

          <!-- Status Perkuliahan -->
          <div class="col-md-6">
            <label for="status" class="form-label small fw-semibold text-secondary">Status Perkuliahan</label>
            <select class="form-select" id="status" name="status">
              <option value="Aktif" <?php echo ($mhs['status'] === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
              <option value="Cuti" <?php echo ($mhs['status'] === 'Cuti') ? 'selected' : ''; ?>>Cuti</option>
              <option value="Lulus" <?php echo ($mhs['status'] === 'Lulus') ? 'selected' : ''; ?>>Lulus</option>
              <option value="Non-Aktif" <?php echo ($mhs['status'] === 'Non-Aktif') ? 'selected' : ''; ?>>Non-Aktif</option>
            </select>
          </div>

          <!-- Alamat -->
          <div class="col-12">
            <label for="alamat" class="form-label small fw-semibold text-secondary">Alamat Tempat Tinggal</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($mhs['alamat'] ?? ''); ?></textarea>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
          <a href="<?php echo APP_PATH; ?>/mahasiswa/" class="btn btn-light px-4">Batal</a>
          <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
require_once dirname(__DIR__) . '/template/footer.php';
?>
