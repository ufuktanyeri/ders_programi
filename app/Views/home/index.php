<?php
$content = ob_start(); ?>

<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Ankara Üniversitesi<br>
                    <span class="text-warning">Nallıhan Meslek Yüksekokulu</span><br>
                    Ders Programı Sistemi
                </h1>
                <p class="lead mb-4">
                    Modern ve kullanıcı dostu arayüzü ile ders programlarınızı kolayca yönetin.
                    Öğretim elemanları ve öğrenciler için geliştirilmiş kapsamlı çözüm.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <?php if (!isset($_SESSION['admin_logged_in'])): ?>
                        <a href="/auth/login" class="btn btn-warning btn-lg px-4 py-3">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Giriş Yap
                        </a>
                        <a href="https://nallihanmyo.ankara.edu.tr/" target="_blank" class="btn btn-outline-light btn-lg px-4 py-3">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Okul Web Sitesi
                        </a>
                    <?php else: ?>
                        <a href="/dashboard" class="btn btn-warning btn-lg px-4 py-3">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard'a Git
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <img src="https://nallihanmyo.ankara.edu.tr/wp-content/uploads/sites/171/2019/10/cropped-ankara-universitesi-logo-1.png"
                         alt="Ankara Üniversitesi Logo"
                         class="img-fluid mb-4"
                         style="max-width: 200px; filter: brightness(0) invert(1);">
                    <div class="bg-white bg-opacity-10 p-4 rounded-3">
                        <h3 class="h5 mb-3">Sistem İstatistikleri</h3>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="mb-3">
                                    <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                                    <h4 class="fw-bold"><?= $stats['programs'] ?></h4>
                                    <p class="mb-0 small">Program</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                                    <h4 class="fw-bold"><?= $stats['teachers'] ?></h4>
                                    <p class="mb-0 small">Öğretim Elemanı</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <i class="fas fa-door-open fa-2x mb-2"></i>
                                    <h4 class="fw-bold"><?= $stats['classrooms'] ?></h4>
                                    <p class="mb-0 small">Derslik</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <i class="fas fa-book fa-2x mb-2"></i>
                                    <h4 class="fw-bold"><?= $stats['courses'] ?></h4>
                                    <p class="mb-0 small">Ders</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-6 mx-auto">
                <h2 class="fw-bold text-gradient">Sistem Özellikleri</h2>
                <p class="text-muted">Modern teknoloji ile hazırlanmış kapsamlı ders programı yönetim sistemi</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                        <h4 class="card-title">Otomatik Program Oluşturma</h4>
                        <p class="card-text text-muted">Çakışma kontrolü ile otomatik ders programı oluşturma ve düzenleme imkanı</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fab fa-google fa-2x text-success"></i>
                        </div>
                        <h4 class="card-title">Google OAuth Entegrasyonu</h4>
                        <p class="card-text text-muted">Google hesabınızla güvenli giriş yaparak sistemi kullanabilirsiniz</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-users-cog fa-2x text-warning"></i>
                        </div>
                        <h4 class="card-title">Rol Bazlı Yetkilendirme</h4>
                        <p class="card-text text-muted">Admin, öğretim elemanı ve misafir rolleri ile güvenli erişim kontrolü</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-mobile-alt fa-2x text-info"></i>
                        </div>
                        <h4 class="card-title">Responsive Tasarım</h4>
                        <p class="card-text text-muted">Masaüstü, tablet ve mobil cihazlarda mükemmel görüntüleme</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-chart-line fa-2x text-danger"></i>
                        </div>
                        <h4 class="card-title">Detaylı Raporlama</h4>
                        <p class="card-text text-muted">Ders yükü, derslik kullanımı ve çakışma analiz raporları</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-download fa-2x text-secondary"></i>
                        </div>
                        <h4 class="card-title">Dışa Aktarma</h4>
                        <p class="card-text text-muted">PDF ve Excel formatlarında program dışa aktarma özelliği</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<?php if (!empty($programs)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-6 mx-auto">
                <h2 class="fw-bold text-gradient">Programlarımız</h2>
                <p class="text-muted">Nallıhan MYO'da eğitim verilen ön lisans programları</p>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach($programs as $program): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                <i class="fas fa-graduation-cap text-primary"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($program['program_kodu']) ?></h5>
                                <h6 class="card-subtitle text-muted"><?= htmlspecialchars($program['program_adi']) ?></h6>
                            </div>
                        </div>
                        <a href="/program/<?= $program['program_kodu'] ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-2"></i>Program Detayı
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="/programs" class="btn btn-primary">
                <i class="fas fa-list me-2"></i>Tüm Programları Görüntüle
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Announcements Section -->
<?php if (!empty($announcements)): ?>
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-6 mx-auto">
                <h2 class="fw-bold text-gradient">Duyurular</h2>
                <p class="text-muted">Son haberler ve sistem güncellemeleri</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php foreach($announcements as $index => $announcement): ?>
                    <?php
                    $alertClass = [
                        'info' => 'alert-info',
                        'success' => 'alert-success',
                        'warning' => 'alert-warning',
                        'error' => 'alert-danger'
                    ][$announcement['type']] ?? 'alert-info';
                    ?>
                    <div class="alert <?= $alertClass ?> d-flex align-items-start mb-3" role="alert">
                        <div class="me-3">
                            <?php if($announcement['type'] === 'success'): ?>
                                <i class="fas fa-check-circle fa-lg"></i>
                            <?php elseif($announcement['type'] === 'warning'): ?>
                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                            <?php elseif($announcement['type'] === 'error'): ?>
                                <i class="fas fa-times-circle fa-lg"></i>
                            <?php else: ?>
                                <i class="fas fa-info-circle fa-lg"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading fw-bold mb-2"><?= htmlspecialchars($announcement['title']) ?></h6>
                            <p class="mb-2"><?= htmlspecialchars($announcement['content']) ?></p>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-2"></i>
                                <?= date('d.m.Y', strtotime($announcement['date'])) ?>
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact Section -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4">İletişim Bilgileri</h3>
                <div class="mb-3">
                    <i class="fas fa-map-marker-alt text-primary me-3"></i>
                    <span>Nallıhan Meslek Yüksekokulu, Ankara Üniversitesi</span>
                </div>
                <div class="mb-3">
                    <i class="fas fa-phone text-primary me-3"></i>
                    <span>+90 (312) XXX XX XX</span>
                </div>
                <div class="mb-3">
                    <i class="fas fa-envelope text-primary me-3"></i>
                    <span>info@nallihanmyo.ankara.edu.tr</span>
                </div>
                <div class="mb-3">
                    <i class="fas fa-globe text-primary me-3"></i>
                    <a href="https://nallihanmyo.ankara.edu.tr/" target="_blank" class="text-white text-decoration-none">
                        nallihanmyo.ankara.edu.tr
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4">Hızlı Erişim</h3>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="/programs" class="text-white-50 text-decoration-none">
                                    <i class="fas fa-angle-right me-2"></i>Programlar
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/schedules" class="text-white-50 text-decoration-none">
                                    <i class="fas fa-angle-right me-2"></i>Ders Programları
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/about" class="text-white-50 text-decoration-none">
                                    <i class="fas fa-angle-right me-2"></i>Hakkında
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="/contact" class="text-white-50 text-decoration-none">
                                    <i class="fas fa-angle-right me-2"></i>İletişim
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/auth/login" class="text-white-50 text-decoration-none">
                                    <i class="fas fa-angle-right me-2"></i>Giriş Yap
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/help" class="text-white-50 text-decoration-none">
                                    <i class="fas fa-angle-right me-2"></i>Yardım
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();

// Layout'a veri gönder
include '../app/Views/layouts/app.php';
?>