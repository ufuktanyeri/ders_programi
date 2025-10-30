<?php
// Use the modern layout
$this->extends('modern');
?>

<?php $this->section('content'); ?>
<!-- Hero Section -->
<div class="row mb-5">
  <div class="col-12">
    <div class="card bg-primary text-white">
      <div class="card-body text-center py-5">
        <h1 class="display-4 mb-3">
          <i class="fas fa-graduation-cap me-3"></i>
          Ders Programı Yönetim Sistemi
        </h1>
        <p class="lead mb-4">Modern, Güvenli ve Kullanıcı Dostu</p>
        <a href="/auth/login" class="btn btn-light btn-lg">
          <i class="fas fa-sign-in-alt me-2"></i> Giriş Yapın
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Statistics Section -->
<div class="row mb-4">
  <div class="col-12">
    <h2 class="mb-4"><i class="fas fa-chart-line me-2"></i> Sistem İstatistikleri</h2>
  </div>
</div>

<div class="row">
  <?php
  $this->component('stat-card', [
    'value' => $stats['programs'] ?? 0,
    'label' => 'Aktif Program',
    'icon' => 'fas fa-book',
    'color' => '#3498db',
    'size' => '3'
  ]);
  ?>

  <?php
  $this->component('stat-card', [
    'value' => $stats['teachers'] ?? 0,
    'label' => 'Öğretim Elemanı',
    'icon' => 'fas fa-chalkboard-teacher',
    'color' => '#27ae60',
    'size' => '3'
  ]);
  ?>

  <?php
  $this->component('stat-card', [
    'value' => $stats['classrooms'] ?? 0,
    'label' => 'Derslik',
    'icon' => 'fas fa-door-open',
    'color' => '#f39c12',
    'size' => '3'
  ]);
  ?>

  <?php
  $this->component('stat-card', [
    'value' => $stats['courses'] ?? 0,
    'label' => 'Ders',
    'icon' => 'fas fa-book-open',
    'color' => '#e74c3c',
    'size' => '3'
  ]);
  ?>
</div>

<!-- Features Section -->
<div class="row mt-5">
  <div class="col-12">
    <h2 class="mb-4"><i class="fas fa-star me-2"></i> Özellikler</h2>
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="text-center mb-3">
          <i class="fas fa-shield-alt fa-3x text-primary"></i>
        </div>
        <h5 class="card-title text-center">Güvenli Giriş</h5>
        <p class="card-text text-center">Google OAuth 2.0 entegrasyonu ile güvenli kimlik doğrulama</p>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="text-center mb-3">
          <i class="fas fa-users-cog fa-3x text-success"></i>
        </div>
        <h5 class="card-title text-center">Yetki Yönetimi</h5>
        <p class="card-text text-center">Role-based access control ile detaylı yetkilendirme sistemi</p>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="text-center mb-3">
          <i class="fas fa-calendar-check fa-3x text-warning"></i>
        </div>
        <h5 class="card-title text-center">Kolay Program Yönetimi</h5>
        <p class="card-text text-center">Drag & drop ile sürükle bırak program düzenleme</p>
      </div>
    </div>
  </div>
</div>

<!-- Programs List -->
<?php if (!empty($programlar)): ?>
  <div class="row mt-5">
    <div class="col-12">
      <h2 class="mb-4"><i class="fas fa-list me-2"></i> Aktif Programlar</h2>
    </div>
  </div>

  <div class="row">
    <?php foreach (array_slice($programlar, 0, 6) as $program): ?>
      <div class="col-md-4 mb-3">
        <div class="card">
          <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted"><?php echo $this->e($program['program_kodu'] ?? ''); ?></h6>
            <h5 class="card-title"><?php echo $this->e($program['program_adi'] ?? ''); ?></h5>
            <p class="card-text">
              <small class="text-muted">
                <i class="fas fa-clock me-1"></i> <?php echo $this->e($program['sure'] ?? '2'); ?> Yıl
              </small>
            </p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
  // Custom JavaScript for this page
  console.log('Modern template engine working!');

  // Animate stats on load
  document.addEventListener('DOMContentLoaded', function() {
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
      setTimeout(() => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';

        setTimeout(() => {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, 50);
      }, index * 100);
    });
  });
</script>
<?php $this->endSection(); ?>