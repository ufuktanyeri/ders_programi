<?php
$content = ob_start(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-gradient mb-3">Ders Programları</h1>
                <p class="lead text-muted">
                    <?php if (!empty($aktif_donem)): ?>
                        <?= htmlspecialchars($aktif_donem['donem_adi']) ?> - 
                        <?= htmlspecialchars($aktif_donem['akademik_yil']) ?>
                    <?php else: ?>
                        Haftalık ders programlarını görüntüleyin
                    <?php endif; ?>
                </p>
            </div>

            <?php if (!empty($programs)): ?>
                <!-- Programs Grid -->
                <div class="row g-4">
                    <?php foreach($programs as $program): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 shadow-sm hover-shadow transition">
                                <div class="card-body text-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                                    </div>
                                    
                                    <h5 class="card-title fw-bold mb-2">
                                        <?= htmlspecialchars($program['program_kodu']) ?>
                                    </h5>
                                    
                                    <p class="card-text text-muted mb-4">
                                        <?= htmlspecialchars($program['program_adi']) ?>
                                    </p>

                                    <a href="/schedule/<?= urlencode($program['program_kodu']) ?>" 
                                       class="btn btn-primary w-100">
                                        <i class="fas fa-eye me-2"></i>
                                        Ders Programını Görüntüle
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Info Box -->
                <div class="alert alert-info mt-5" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Bilgi:</strong> 
                    İlgili program kartına tıklayarak haftalık ders programını görüntüleyebilirsiniz. 
                    Programları PDF formatında indirebilir veya yazdırabilirsiniz.
                </div>

            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times fa-4x text-muted"></i>
                    </div>
                    <h3 class="text-muted mb-3">Henüz Ders Programı Bulunmuyor</h3>
                    <p class="text-muted mb-4">
                        Sistemde aktif ders programı bulunmamaktadır. Lütfen daha sonra tekrar kontrol edin.
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
