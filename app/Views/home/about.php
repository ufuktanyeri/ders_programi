<?php
$content = ob_start(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-gradient mb-3">Hakkında</h1>
                <p class="lead text-muted">Ankara Üniversitesi Nallıhan MYO Ders Programı Yönetim Sistemi</p>
            </div>

            <!-- System Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Sistem Hakkında
                    </h3>
                    <p class="card-text">
                        Bu sistem, Ankara Üniversitesi Nallıhan Meslek Yüksekokulu'nda eğitim gören öğrenciler ve 
                        öğretim elemanları için ders programlarının yönetimini kolaylaştırmak amacıyla geliştirilmiştir.
                    </p>
                    <p class="card-text">
                        Modern MVC mimarisi kullanılarak PHP ile geliştirilmiş olup, Google OAuth 2.0 entegrasyonu 
                        ile güvenli kimlik doğrulama sağlamaktadır.
                    </p>
                </div>
            </div>

            <!-- Features -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-star text-warning me-2"></i>
                        Özellikler
                    </h3>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Otomatik ders programı oluşturma
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Çakışma kontrolü ve optimizasyon
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Google OAuth ile güvenli giriş
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Rol bazlı yetkilendirme (Admin, Öğretim Elemanı, Misafir)
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Responsive tasarım (Mobil uyumlu)
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            PDF/Excel formatında dışa aktarma
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Technology Stack -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-code text-info me-2"></i>
                        Teknoloji Stack
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Frontend</h5>
                            <ul class="list-unstyled">
                                <li><i class="fab fa-html5 text-danger me-2"></i>HTML5</li>
                                <li><i class="fab fa-css3-alt text-primary me-2"></i>CSS3</li>
                                <li><i class="fab fa-bootstrap text-purple me-2"></i>Bootstrap 5</li>
                                <li><i class="fab fa-js text-warning me-2"></i>JavaScript ES6+</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Backend</h5>
                            <ul class="list-unstyled">
                                <li><i class="fab fa-php text-primary me-2"></i>PHP 8.x</li>
                                <li><i class="fas fa-database text-success me-2"></i>MySQL</li>
                                <li><i class="fas fa-shield-alt text-danger me-2"></i>PDO (Security)</li>
                                <li><i class="fab fa-google text-info me-2"></i>Google OAuth 2.0</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Version Info -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-history text-secondary me-2"></i>
                        Versiyon Bilgileri
                    </h3>
                    <p class="mb-2"><strong>Versiyon:</strong> 2.0.0 (MVC Refactored)</p>
                    <p class="mb-2"><strong>Son Güncelleme:</strong> 30 Ekim 2025</p>
                    <p class="mb-0"><strong>Durum:</strong> <span class="badge bg-success">Aktif</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
