<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($data['title'] ?? 'Login'); ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      background-color: #f1f5f9;
      color: #334155;
      font-family: system-ui, -apple-system, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card-login {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      max-width: 400px;
      width: 100%;
    }
  </style>
</head>
<body class="p-3">

<div class="card-login p-4">
  <div class="text-center mb-4">
    <a href="<?php echo APP_PATH; ?>/" class="d-inline-flex p-2 bg-primary text-white rounded-3 mb-2 text-decoration-none">
      <i class="bi bi-code-slash fs-4"></i>
    </a>
    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars(APP_NAME); ?></h5>
    <p class="text-muted small mb-0">Silakan login untuk masuk ke sistem</p>
  </div>

  <?php if (!empty($data['error'])): ?>
    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo htmlspecialchars($data['error']); ?>
    </div>
  <?php endif; ?>

  <form action="<?php echo APP_PATH; ?>/login/authenticate/" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($data['csrf_token']); ?>">

    <div class="mb-3">
      <label for="username" class="form-label small fw-semibold">Username / Email</label>
      <input type="text" class="form-control" id="username" name="username" placeholder="admin / mahasiswa" required autofocus>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label small fw-semibold">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="admin123" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-semibold mb-3">
      <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
    </button>
  </form>

  <!-- QUICK FILL UNTUK MAHASISWA -->
  <div class="border-top pt-3 text-center">
    <div class="text-muted small mb-2">Klik akun demo untuk isi otomatis:</div>
    <div class="d-flex justify-content-center gap-1 flex-wrap">
      <button type="button" class="btn btn-outline-secondary btn-sm py-1" onclick="fillLogin('admin', 'admin123')">
        Admin
      </button>
      <button type="button" class="btn btn-outline-secondary btn-sm py-1" onclick="fillLogin('mahasiswa', 'mahasiswa123')">
        Mahasiswa
      </button>
      <button type="button" class="btn btn-outline-secondary btn-sm py-1" onclick="fillLogin('dosen', 'dosen123')">
        Dosen
      </button>
    </div>
  </div>

  <div class="text-center mt-3">
    <a href="<?php echo APP_PATH; ?>/" class="small text-decoration-none text-muted">
      &larr; Kembali ke Beranda
    </a>
  </div>
</div>

<script>
function fillLogin(u, p) {
  document.getElementById('username').value = u;
  document.getElementById('password').value = p;
}
const urlParams = new URLSearchParams(window.location.search);
const reqUser = urlParams.get('user');
if (reqUser === 'admin') fillLogin('admin', 'admin123');
else if (reqUser === 'mahasiswa') fillLogin('mahasiswa', 'mahasiswa123');
else if (reqUser === 'dosen') fillLogin('dosen', 'dosen123');
</script>
</body>
</html>
