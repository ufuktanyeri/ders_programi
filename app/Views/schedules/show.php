<?php
$content = ob_start(); ?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="/schedules">Ders Programları</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($program['program_kodu']) ?>
            </li>
        </ol>
    </nav>

    <!-- Program Header -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                        <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold"><?= htmlspecialchars($program['program_adi']) ?></h2>
                        <p class="text-muted mb-0">
                            <span class="badge bg-primary me-2"><?= htmlspecialchars($program['program_kodu']) ?></span>
                            <?php if (!empty($aktif_donem)): ?>
                                <span class="badge bg-success">
                                    <?= htmlspecialchars($aktif_donem['donem_adi']) ?> - 
                                    <?= htmlspecialchars($aktif_donem['akademik_yil']) ?>
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Yazdır
                    </button>
                    <a href="/schedules" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Geri
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($schedule)): ?>
        <!-- Weekly Schedule Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center" style="width: 120px;">Saat</th>
                                <th class="text-center">Pazartesi</th>
                                <th class="text-center">Salı</th>
                                <th class="text-center">Çarşamba</th>
                                <th class="text-center">Perşembe</th>
                                <th class="text-center">Cuma</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Organize schedule by time slots and days
                            $timeSlots = [];
                            foreach($schedule as $item) {
                                $timeSlots[$item['saat_dilimi']][$item['gun']] = $item;
                            }
                            ksort($timeSlots);
                            ?>
                            
                            <?php foreach($timeSlots as $time => $days): ?>
                                <tr>
                                    <td class="text-center fw-bold bg-light"><?= htmlspecialchars($time) ?></td>
                                    <?php
                                    $dayNames = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma'];
                                    foreach($dayNames as $dayName):
                                        if(isset($days[$dayName])):
                                            $item = $days[$dayName];
                                    ?>
                                        <td class="p-2">
                                            <div class="small">
                                                <strong class="text-primary"><?= htmlspecialchars($item['ders_kodu']) ?></strong><br>
                                                <span class="text-dark"><?= htmlspecialchars($item['ders_adi']) ?></span><br>
                                                <?php if(!empty($item['ogretim_elemani_ad'])): ?>
                                                    <span class="text-muted">
                                                        <i class="fas fa-user fa-xs me-1"></i>
                                                        <?= htmlspecialchars($item['ogretim_elemani_ad'] . ' ' . $item['ogretim_elemani_soyad']) ?>
                                                    </span><br>
                                                <?php endif; ?>
                                                <?php if(!empty($item['derslik_adi'])): ?>
                                                    <span class="text-muted">
                                                        <i class="fas fa-door-open fa-xs me-1"></i>
                                                        <?= htmlspecialchars($item['derslik_adi']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    <?php else: ?>
                                        <td class="bg-light"></td>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle text-info me-2"></i>Açıklamalar
                </h6>
                <div class="row">
                    <div class="col-md-4">
                        <small>
                            <i class="fas fa-user fa-xs text-muted me-1"></i>
                            Öğretim Elemanı
                        </small>
                    </div>
                    <div class="col-md-4">
                        <small>
                            <i class="fas fa-door-open fa-xs text-muted me-1"></i>
                            Derslik
                        </small>
                    </div>
                    <div class="col-md-4">
                        <small>
                            <i class="fas fa-print fa-xs text-muted me-1"></i>
                            Yazdırma özelliği ile çıktı alabilirsiniz
                        </small>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Empty State -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Bu programa ait ders programı bulunmuyor</h4>
                <p class="text-muted mb-4">
                    Ders programı henüz oluşturulmamıştır veya aktif dönem için program bulunmamaktadır.
                </p>
                <a href="/schedules" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Ders Programları Listesine Dön
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
@media print {
    .breadcrumb, .btn, .card-body > div:last-child {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
