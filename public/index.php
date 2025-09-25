<?php
// MVC Entry Point
session_start();

// Autoload
require_once '../core/Router.php';
require_once '../core/Controller.php';
require_once '../config/database.php';

// Controllers
require_once '../app/Controllers/HomeController.php';
require_once '../app/Controllers/AuthController.php';
require_once '../app/Controllers/DashboardController.php';
require_once '../app/Controllers/AdminController.php';

// Router instance
$router = new Router();

// Routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/contact', [HomeController::class, 'contact']);

$router->get('/auth/login', [AuthController::class, 'loginForm']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/logout', [AuthController::class, 'logout']);
$router->get('/auth/google', [AuthController::class, 'googleAuth']);
$router->get('/auth/google/callback', [AuthController::class, 'googleCallback']);

$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/dashboard/admin', [DashboardController::class, 'admin']);
$router->get('/dashboard/teacher', [DashboardController::class, 'teacher']);
$router->get('/dashboard/guest', [DashboardController::class, 'guest']);

// Admin routes
$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/', [AdminController::class, 'index']);
$router->get('/admin/login', [AdminController::class, 'login']);
$router->post('/admin/login', [AdminController::class, 'login']);
$router->get('/admin/logout', [AdminController::class, 'logout']);
$router->get('/admin/programs', [AdminController::class, 'programs']);
$router->get('/admin/teachers', [AdminController::class, 'teachers']);

// 404 handler
if (!file_exists('../app/Views/errors')) {
    mkdir('../app/Views/errors', 0777, true);
}

if (!file_exists('../app/Views/errors/404.php')) {
    file_put_contents('../app/Views/errors/404.php', '<?php
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
?>');
}

// Resolve route
try {
    $router->resolve();
} catch (Exception $e) {
    error_log("Router Error: " . $e->getMessage());
    http_response_code(500);
    echo "Sistem hatası oluştu. Lütfen daha sonra tekrar deneyiniz.";
}
?>