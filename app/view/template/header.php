<?php
$currentRole = $_SESSION['user-role'] ?? 'user';
$currentName = $_SESSION['user-name'] ?? 'Pengguna';
$activeMenu = $data['active_menu'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($data['title'] ?? APP_NAME); ?></title>
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
      box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
  </style>
</head>
<body>

<!-- TOP NAVBAR SEDERHANA -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top py-2">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?php echo APP_PATH; ?>/dashboard/">
      <i class="bi bi-code-slash text-primary fs-4"></i>
      <span><?php echo htmlspecialchars(APP_SHORT_NAME ?? 'Proyek'); ?></span>
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navApp">
      <i class="bi bi-list fs-3"></i>
    </button>

    <div class="collapse navbar-collapse" id="navApp">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo ($activeMenu === 'dashboard') ? 'active fw-bold' : ''; ?>" href="<?php echo APP_PATH; ?>/dashboard/">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($activeMenu === 'mahasiswa') ? 'active fw-bold' : ''; ?>" href="<?php echo APP_PATH; ?>/mahasiswa/">
            <i class="bi bi-people me-1"></i> Kelola Mahasiswa
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($activeMenu === 'panduan') ? 'active fw-bold' : ''; ?>" href="<?php echo APP_PATH; ?>/panduan/">
            <i class="bi bi-journal-text me-1"></i> Panduan
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-secondary" href="<?php echo APP_PATH; ?>/" target="_blank">
            <i class="bi bi-box-arrow-up-right me-1"></i> Landing Page
          </a>
        </li>
      </ul>

      <!-- PROFIL & LOGOUT -->
      <div class="d-flex align-items-center gap-3">
        <span class="badge bg-success-subtle text-success border border-success-subtle d-none d-md-inline">
          <i class="bi bi-database me-1"></i> db_project
        </span>
        <div class="dropdown">
          <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i>
            <span><?php echo htmlspecialchars($currentName); ?> (<?php echo htmlspecialchars($currentRole); ?>)</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li><a class="dropdown-item small" href="<?php echo APP_PATH; ?>/dashboard/"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
            <li><a class="dropdown-item small" href="<?php echo APP_PATH; ?>/panduan/"><i class="bi bi-book me-2"></i> Petunjuk</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item small text-danger" href="<?php echo APP_PATH; ?>/login/logout/"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- CONTAINER UTAMA -->
<div class="container my-4">
