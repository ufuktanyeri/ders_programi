<?php include_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/dashboard/instructor">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/schedules/my">
                            <i class="fas fa-calendar-alt"></i> Ders Programım
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/suggestions">
                            <i class="fas fa-lightbulb"></i> Öneri Yap
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">
                            <i class="fas fa-user"></i> Profil
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Öğretim Elemanı Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="/suggestions/create" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Yeni Öneri
                        </a>
                    </div>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i>
                Hoş geldiniz, <strong><?= htmlspecialchars($user->getName()) ?></strong>!
                Bu sayfadan ders programınızı görüntüleyebilir ve değişiklik önerilerinde bulunabilirsiniz.
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Toplam Ders Sayısı</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $instructorStats['total_courses'] ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Haftalık Ders Saati</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $instructorStats['total_hours'] ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Bekleyen Öneriler</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= count($suggestions) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-lightbulb fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Weekly Schedule -->
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Haftalık Ders Programım</h6>
                            <a href="/schedules/my" class="btn btn-sm btn-primary">Detaylı Görünüm</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($mySchedules)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Henüz ders programınız yok.</p>
                                    <a href="/contact" class="btn btn-outline-primary">Yönetici ile İletişime Geçin</a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Gün</th>
                                                <th>Saat</th>
                                                <th>Ders</th>
                                                <th>Program</th>
                                                <th>Derslik</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($mySchedules, 0, 10) as $schedule): ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            <?= htmlspecialchars($schedule['gun_adi']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small>
                                                            <?= date('H:i', strtotime($schedule['baslangic_saat'])) ?> -
                                                            <?= date('H:i', strtotime($schedule['bitis_saat'])) ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($schedule['ders_adi']) ?></strong>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            <?= htmlspecialchars($schedule['program_adi']) ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            <?= htmlspecialchars($schedule['derslik_adi_tr']) ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Suggestions -->
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Önerilerim</h6>
                            <a href="/suggestions" class="btn btn-sm btn-primary">Tümünü Gör</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($suggestions)): ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-lightbulb fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-3">Henüz öneri yok.</p>
                                    <a href="/suggestions/create" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus"></i> Yeni Öneri
                                    </a>
                                </div>
                            <?php else: ?>
                                <?php foreach (array_slice($suggestions, 0, 5) as $suggestion): ?>
                                    <div class="d-flex align-items-center border-bottom py-2">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= htmlspecialchars($suggestion['ders_adi'] ?? 'Ders Değişikliği') ?></h6>
                                            <small class="text-muted">
                                                <?= mb_substr(htmlspecialchars($suggestion['reason']), 0, 50) ?>...
                                            </small>
                                            <div class="small text-muted">
                                                <?= date('d.m.Y', strtotime($suggestion['created_at'])) ?>
                                            </div>
                                        </div>
                                        <div>
                                            <?php
                                            $statusColors = [
                                                'pending' => 'bg-warning',
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger'
                                            ];
                                            $statusTexts = [
                                                'pending' => 'Bekleyen',
                                                'approved' => 'Onaylandı',
                                                'rejected' => 'Reddedildi'
                                            ];
                                            ?>
                                            <span class="badge <?= $statusColors[$suggestion['status']] ?>">
                                                <?= $statusTexts[$suggestion['status']] ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Hızlı İşlemler</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/schedules/my" class="btn btn-outline-primary">
                                    <i class="fas fa-calendar-alt"></i> Programımı Görüntüle
                                </a>
                                <a href="/suggestions/create" class="btn btn-outline-success">
                                    <i class="fas fa-plus"></i> Değişiklik Öner
                                </a>
                                <a href="/profile" class="btn btn-outline-info">
                                    <i class="fas fa-user"></i> Profil Güncelle
                                </a>
                                <a href="/contact" class="btn btn-outline-secondary">
                                    <i class="fas fa-envelope"></i> İletişim
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include_once '../app/Views/layouts/footer.php'; ?>