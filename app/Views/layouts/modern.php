<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?? 'Ders Programı Yönetim Sistemi'; ?></title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Custom CSS -->
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #3498db;
      --success-color: #27ae60;
      --warning-color: #f39c12;
      --danger-color: #e74c3c;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }

    .navbar {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .content-wrapper {
      min-height: calc(100vh - 120px);
      padding: 20px 0;
    }

    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .card-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      color: white;
      border-radius: 10px 10px 0 0 !important;
      padding: 15px 20px;
    }

    footer {
      background-color: var(--primary-color);
      color: white;
      padding: 20px 0;
      margin-top: 40px;
    }
  </style>

  <?php $this->yield('styles'); ?>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="/">
        <i class="fas fa-calendar-alt me-2"></i>
        Ders Programı Sistemi
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="/"><i class="fas fa-home me-1"></i> Ana Sayfa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/dashboard"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
          </li>
          <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user me-1"></i> <?php echo $this->e($_SESSION['admin_username'] ?? 'Kullanıcı'); ?>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/user/dashboard">Profilim</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="/auth/logout">Çıkış Yap</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="/auth/login"><i class="fas fa-sign-in-alt me-1"></i> Giriş Yap</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Flash Messages -->
  <?php if ($success = $this->flash('success')): ?>
    <div class="container mt-3">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo $this->e($success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($error = $this->flash('error')): ?>
    <div class="container mt-3">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $this->e($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  <?php endif; ?>

  <!-- Main Content -->
  <div class="content-wrapper">
    <div class="container">
      <?php $this->yield('content'); ?>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>Ders Programı Yönetim Sistemi</h5>
          <p class="mb-0">Ankara Üniversitesi Nallıhan MYO</p>
        </div>
        <div class="col-md-6 text-md-end">
          <p class="mb-0">&copy; <?php echo date('Y'); ?> Tüm hakları saklıdır.</p>
          <small>Version 2.0 - Modern MVC Architecture</small>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <?php $this->yield('scripts'); ?>
</body>

</html>