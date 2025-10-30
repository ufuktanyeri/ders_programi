<?php
$content = ob_start(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-gradient mb-3">İletişim</h1>
                <p class="lead text-muted">Bize ulaşın, sorularınızı yanıtlayalım</p>
            </div>

            <!-- Contact Cards -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-map-marker-alt fa-lg text-primary"></i>
                            </div>
                            <h5 class="card-title">Adres</h5>
                            <p class="card-text text-muted">
                                Nallıhan Meslek Yüksekokulu<br>
                                Ankara Üniversitesi<br>
                                Nallıhan / Ankara
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-phone fa-lg text-success"></i>
                            </div>
                            <h5 class="card-title">Telefon</h5>
                            <p class="card-text text-muted">
                                +90 (312) XXX XX XX<br>
                                Dahili: XXXX
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-envelope fa-lg text-info"></i>
                            </div>
                            <h5 class="card-title">E-posta</h5>
                            <p class="card-text text-muted">
                                info@nallihanmyo.ankara.edu.tr<br>
                                <a href="https://nallihanmyo.ankara.edu.tr" target="_blank" class="text-decoration-none">
                                    Web Sitesi <i class="fas fa-external-link-alt small"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-link text-warning me-2"></i>
                        Hızlı Bağlantılar
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="https://nallihanmyo.ankara.edu.tr" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-external-link-alt me-2 text-primary"></i>
                                        Okul Web Sitesi
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="https://ankara.edu.tr" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-external-link-alt me-2 text-primary"></i>
                                        Ankara Üniversitesi
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="/programs" class="text-decoration-none">
                                        <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                        Eğitim Programları
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="/schedules" class="text-decoration-none">
                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                        Ders Programları
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="/help" class="text-decoration-none">
                                        <i class="fas fa-question-circle me-2 text-primary"></i>
                                        Yardım
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="/auth/login" class="text-decoration-none">
                                        <i class="fas fa-sign-in-alt me-2 text-primary"></i>
                                        Giriş Yap
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Working Hours -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-clock text-secondary me-2"></i>
                        Çalışma Saatleri
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Hafta İçi:</strong></p>
                            <p class="text-muted">Pazartesi - Cuma: 08:30 - 17:00</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Hafta Sonu:</strong></p>
                            <p class="text-muted">Cumartesi - Pazar: Kapalı</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
