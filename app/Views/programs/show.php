<?php
$content = ob_start(); ?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="/programs">Programlar</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($program['program_kodu']) ?>
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <!-- Program Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                        </div>
                        <div>
                            <h2 class="mb-1 fw-bold"><?= htmlspecialchars($program['program_adi']) ?></h2>
                            <p class="text-muted mb-0">
                                <span class="badge bg-primary me-2"><?= htmlspecialchars($program['program_kodu']) ?></span>
                                <span class="badge bg-success">Aktif</span>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="/schedule/<?= urlencode($program['program_kodu']) ?>" class="btn btn-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Ders Programını Görüntüle
                        </a>
                        <a href="/programs" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Tüm Programlar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Course List -->
            <?php if (!empty($dersler)): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-book me-2"></i>Program Dersleri
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ders Kodu</th>
                                        <th>Ders Adı</th>
                                        <th class="text-center">Dönem</th>
                                        <th class="text-center">Teorik</th>
                                        <th class="text-center">Pratik</th>
                                        <th class="text-center">AKTS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $currentDonem = null;
                                    foreach($dersler as $ders): 
                                        $donemChanged = ($currentDonem !== $ders['donem']);
                                        $currentDonem = $ders['donem'];
                                    ?>
                                        <?php if($donemChanged): ?>
                                            <tr class="table-secondary">
                                                <td colspan="6" class="fw-bold">
                                                    <i class="fas fa-angle-right me-2"></i>
                                                    <?= $ders['donem'] ?>. Dönem
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="fw-bold"><?= htmlspecialchars($ders['ders_kodu']) ?></td>
                                            <td><?= htmlspecialchars($ders['ders_adi']) ?></td>
                                            <td class="text-center"><?= $ders['donem'] ?></td>
                                            <td class="text-center"><?= $ders['teorik_saat'] ?? '-' ?></td>
                                            <td class="text-center"><?= $ders['pratik_saat'] ?? '-' ?></td>
                                            <td class="text-center"><?= $ders['akts'] ?? '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-book fa-2x text-primary mb-3"></i>
                                <h3 class="mb-0"><?= count($dersler) ?></h3>
                                <p class="text-muted mb-0">Toplam Ders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-layer-group fa-2x text-success mb-3"></i>
                                <h3 class="mb-0">
                                    <?= count(array_unique(array_column($dersler, 'donem'))) ?>
                                </h3>
                                <p class="text-muted mb-0">Dönem Sayısı</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-info mb-3"></i>
                                <h3 class="mb-0">
                                    <?= array_sum(array_map(function($d) { 
                                        return ($d['teorik_saat'] ?? 0) + ($d['pratik_saat'] ?? 0); 
                                    }, $dersler)) ?>
                                </h3>
                                <p class="text-muted mb-0">Toplam Saat</p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Empty State -->
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Bu programa ait ders bulunmuyor</h4>
                        <p class="text-muted">Dersler henüz sisteme eklenmemiştir.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
