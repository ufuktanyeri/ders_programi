<?php include_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/dashboard/admin">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/users">
                            <i class="fas fa-users"></i> Kullanıcı Yönetimi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/programs">
                            <i class="fas fa-graduation-cap"></i> Program Yönetimi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/teachers">
                            <i class="fas fa-chalkboard-teacher"></i> Öğretim Elemanları
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/schedules">
                            <i class="fas fa-calendar-alt"></i> Program Yönetimi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/reports">
                            <i class="fas fa-chart-bar"></i> Raporlar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/settings">
                            <i class="fas fa-cog"></i> Sistem Ayarları
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Admin Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Dışa Aktar</button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Toplam Kullanıcı</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $stats['users']['total'] ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                        Aktif Programlar</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $stats['programs']['active'] ?? 0 ?>
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
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Öğretim Elemanları</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $stats['teachers']['active'] ?? 0 ?>
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
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Bekleyen Onaylar</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= count($pendingApprovals) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Pending Approvals -->
                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Bekleyen Onaylar</h6>
                            <a href="/users/approvals" class="btn btn-sm btn-primary">Tümünü Gör</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($pendingApprovals)): ?>
                                <p class="text-muted">Bekleyen onay yok.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($pendingApprovals, 0, 5) as $approval): ?>
                                    <div class="d-flex align-items-center border-bottom py-2">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= htmlspecialchars($approval['name']) ?></h6>
                                            <small class="text-muted"><?= htmlspecialchars($approval['email']) ?></small>
                                        </div>
                                        <div>
                                            <span class="badge bg-warning">
                                                <?= ucfirst($approval['requested_role'] ?? 'guest') ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Son Aktiviteler</h6>
                        </div>
                        <div class="card-body">
                            <?php if (empty($recentActivities)): ?>
                                <p class="text-muted">Henüz aktivite yok.</p>
                            <?php else: ?>
                                <?php foreach ($recentActivities as $activity): ?>
                                    <div class="d-flex align-items-center border-bottom py-2">
                                        <div class="flex-grow-1">
                                            <small class="text-muted">
                                                <strong><?= htmlspecialchars($activity['user_name'] ?? 'Sistem') ?></strong>
                                                <?= htmlspecialchars($activity['action']) ?>
                                                <?php if ($activity['resource']): ?>
                                                    - <?= htmlspecialchars($activity['resource']) ?>
                                                <?php endif; ?>
                                            </small>
                                            <div class="small text-muted">
                                                <?= date('d.m.Y H:i', strtotime($activity['created_at'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Sistem Durumu</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="fas fa-database fa-2x <?= $systemHealth['database'] == 'healthy' ? 'text-success' : 'text-danger' ?>"></i>
                                        <h6 class="mt-2">Veritabanı</h6>
                                        <span class="badge <?= $systemHealth['database'] == 'healthy' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $systemHealth['database'] == 'healthy' ? 'Sağlıklı' : 'Sorunlu' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="fas fa-hdd fa-2x <?= $systemHealth['disk_space'] == 'healthy' ? 'text-success' : 'text-warning' ?>"></i>
                                        <h6 class="mt-2">Disk Alanı</h6>
                                        <span class="badge <?= $systemHealth['disk_space'] == 'healthy' ? 'bg-success' : 'bg-warning' ?>">
                                            <?= $systemHealth['disk_space'] == 'healthy' ? 'Yeterli' : 'Uyarı' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="fas fa-memory fa-2x text-success"></i>
                                        <h6 class="mt-2">Bellek</h6>
                                        <span class="badge bg-success">Sağlıklı</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="fas fa-exclamation-circle fa-2x <?= $systemHealth['errors'] == 'healthy' ? 'text-success' : 'text-warning' ?>"></i>
                                        <h6 class="mt-2">Hatalar</h6>
                                        <span class="badge <?= $systemHealth['errors'] == 'healthy' ? 'bg-success' : 'bg-warning' ?>">
                                            <?= $systemHealth['errors'] == 'healthy' ? 'Normal' : 'Uyarı' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include_once '../app/Views/layouts/footer.php'; ?>