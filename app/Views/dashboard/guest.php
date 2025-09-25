<?php include_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/dashboard/guest">
                            <i class="fas fa-tachometer-alt"></i> Ana Sayfa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/schedules/public">
                            <i class="fas fa-calendar-alt"></i> Ders Programları
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/programs">
                            <i class="fas fa-graduation-cap"></i> Programlar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/teachers/public">
                            <i class="fas fa-chalkboard-teacher"></i> Öğretim Elemanları
                        </a>
                    </li>
                    <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">
                            <i class="fas fa-user"></i> Profil
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <?php if ($user): ?>
                        Hoş Geldiniz, <?= htmlspecialchars($user->getName()) ?>
                    <?php else: ?>
                        Ders Programı Sistemi
                    <?php endif; ?>
                </h1>
                <?php if (!$user): ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="/auth/login" class="btn btn-sm btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Giriş Yap
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- University Info Banner -->
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="card-title mb-2">Ankara Üniversitesi Nallıhan MYO</h3>
                            <p class="card-text mb-0">
                                Ders Programı Yönetim Sistemi - Programlarımızı ve ders saatlerini buradan takip edebilirsiniz.
                            </p>
                            <a href="https://nallihanmyo.ankara.edu.tr/" target="_blank" class="btn btn-light btn-sm mt-2">
                                <i class="fas fa-external-link-alt"></i> Okul Web Sitesi
                            </a>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-university fa-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Status Alert -->
            <?php if ($user && !$user->canAccess()): ?>
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Hesap Durumu:</strong>
                    <?php if ($user->getApprovalStatus() === 'pending'): ?>
                        Hesabınız henüz onaylanmamış. Admin onayı bekleniyor.
                    <?php elseif ($user->getApprovalStatus() === 'rejected'): ?>
                        Hesabınız reddedilmiş. Daha fazla bilgi için yönetici ile iletişime geçin.
                    <?php elseif (!$user->isActive()): ?>
                        Hesabınız şu anda aktif değil.
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Announcements -->
            <?php if (!empty($announcements)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-bullhorn"></i> Duyurular
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($announcements as $announcement): ?>
                            <?php
                            $alertClass = match($announcement['type']) {
                                'success' => 'alert-success',
                                'warning' => 'alert-warning',
                                'error' => 'alert-danger',
                                default => 'alert-info'
                            };
                            $icon = match($announcement['type']) {
                                'success' => 'fas fa-check-circle',
                                'warning' => 'fas fa-exclamation-triangle',
                                'error' => 'fas fa-exclamation-circle',
                                default => 'fas fa-info-circle'
                            };
                            ?>
                            <div class="alert <?= $alertClass ?> mb-3" role="alert">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="<?= $icon ?>"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="alert-heading mb-2"><?= htmlspecialchars($announcement['title']) ?></h6>
                                        <p class="mb-1"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
                                        <small class="text-muted">
                                            <?= date('d.m.Y H:i', strtotime($announcement['created_at'])) ?>
                                            <?php if ($announcement['priority'] === 'urgent'): ?>
                                                <span class="badge bg-danger ms-2">URGENT</span>
                                            <?php elseif ($announcement['priority'] === 'high'): ?>
                                                <span class="badge bg-warning ms-2">ÖNEMLİ</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Aktif Programlar</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $publicStats['programs'] ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Öğretim Elemanları</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $publicStats['teachers'] ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Aktif Dersler</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $publicStats['courses'] ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Derslikler</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $publicStats['classrooms'] ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-door-open fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Programs Overview -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Programlarımız</h6>
                            <a href="/programs" class="btn btn-sm btn-primary">Tümünü Gör</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($publicSchedules)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Henüz program bilgisi yok.</p>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($publicSchedules as $program): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-left-primary h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary">
                                                        <?= htmlspecialchars($program['program_adi']) ?>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-book"></i>
                                                        <?= $program['ders_sayisi'] ?> ders
                                                    </small>
                                                </div>
                                                <div class="card-footer bg-transparent border-top-0">
                                                    <a href="/schedules/program/<?= $program['program_id'] ?>"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-calendar-alt"></i> Programı Gör
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Access -->
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Hızlı Erişim</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/schedules/public" class="btn btn-outline-primary">
                                    <i class="fas fa-calendar-alt"></i> Ders Programları
                                </a>
                                <a href="/programs" class="btn btn-outline-success">
                                    <i class="fas fa-graduation-cap"></i> Tüm Programlar
                                </a>
                                <a href="/teachers/public" class="btn btn-outline-info">
                                    <i class="fas fa-chalkboard-teacher"></i> Öğretim Elemanları
                                </a>
                                <?php if (!$user): ?>
                                <a href="/auth/login" class="btn btn-outline-warning">
                                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                                </a>
                                <a href="/auth/register" class="btn btn-outline-secondary">
                                    <i class="fas fa-user-plus"></i> Kayıt Ol
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">İletişim</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <i class="fas fa-envelope text-primary"></i>
                                <small class="ms-2">info@nallihanmyo.ankara.edu.tr</small>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-phone text-primary"></i>
                                <small class="ms-2">+90 312 XXX XX XX</small>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <small class="ms-2">Nallıhan, Ankara</small>
                            </p>
                            <a href="https://nallihanmyo.ankara.edu.tr/" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt"></i> Web Sitesi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include_once '../app/Views/layouts/footer.php'; ?>