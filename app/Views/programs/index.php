<?php
$content = ob_start(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-gradient mb-3">Eğitim Programları</h1>
                <p class="lead text-muted">Nallıhan MYO'da eğitim verilen ön lisans programları</p>
            </div>

            <?php if (!empty($programs)): ?>
                <!-- Programs Grid -->
                <div class="row g-4">
                    <?php foreach($programs as $program): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 shadow-sm hover-shadow transition">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                            <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1 fw-bold">
                                                <?= htmlspecialchars($program['program_kodu']) ?>
                                            </h5>
                                            <p class="card-text text-muted small mb-0">
                                                Program Kodu
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <h6 class="mb-3">
                                        <?= htmlspecialchars($program['program_adi']) ?>
                                    </h6>

                                    <div class="d-flex gap-2">
                                        <a href="/program/<?= urlencode($program['program_kodu']) ?>" 
                                           class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="fas fa-eye me-2"></i>Program Detayı
                                        </a>
                                        <a href="/schedule/<?= urlencode($program['program_kodu']) ?>" 
                                           class="btn btn-outline-primary btn-sm flex-grow-1">
                                            <i class="fas fa-calendar-alt me-2"></i>Ders Programı
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Info Box -->
                <div class="alert alert-info mt-5" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Bilgi:</strong> 
                    Detaylı bilgi için program kartlarındaki butonları kullanabilirsiniz. 
                    Ders programlarını görüntülemek için "Ders Programı" butonuna tıklayın.
                </div>

            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-inbox fa-4x text-muted"></i>
                    </div>
                    <h3 class="text-muted mb-3">Henüz Program Bulunmuyor</h3>
                    <p class="text-muted mb-4">
                        Sistemde aktif program bulunmamaktadır. Lütfen daha sonra tekrar kontrol edin.
                    </p>
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Ana Sayfaya Dön
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.transition {
    transition: all 0.3s ease;
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
