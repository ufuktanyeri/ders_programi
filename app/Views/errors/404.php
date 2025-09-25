<?php
$content = ob_start(); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle fa-5x text-warning"></i>
            </div>
            <h1 class="display-4 fw-bold">404</h1>
            <h2>Sayfa Bulunamadı</h2>
            <p class="text-muted mb-4">Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>Ana Sayfaya Dön
            </a>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = "404 - Sayfa Bulunamadı";
include "../app/Views/layouts/app.php";
?>