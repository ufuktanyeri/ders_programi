<?php
// Application routes
require_once 'bootstrap.php';

// Manual controller includes for now
require_once 'app/Controllers/HomeController.php';
require_once 'app/Controllers/AuthController.php';
require_once 'app/Controllers/DashboardController.php';

// Home routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);
$router->get('/dashboard', [HomeController::class, 'dashboard']);
$router->get('/legacy', [HomeController::class, 'legacy']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/contact', [HomeController::class, 'contact']);

// Authentication routes
$router->get('/auth/login', [AuthController::class, 'loginForm']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/google', [AuthController::class, 'googleAuth']);
$router->get('/auth/google/callback', [AuthController::class, 'googleCallback']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

// User Dashboard routes (role-based)
$router->get('/user/dashboard', [DashboardController::class, 'index']);
$router->get('/user/admin', [DashboardController::class, 'admin']);
$router->get('/user/teacher', [DashboardController::class, 'teacher']);
$router->get('/user/guest', [DashboardController::class, 'guest']);

// API routes (if needed)
$router->get('/api/programs', function() use ($container) {
    $service = $container->make('App\Services\ProgramService');
    header('Content-Type: application/json');
    echo json_encode($service->getActivePrograms());
});

$router->get('/api/statistics', function() use ($container) {
    $service = $container->make('App\Services\StatisticsService');
    header('Content-Type: application/json');
    echo json_encode($service->getSystemStatistics());
});

// Resolve the current route
try {
    $router->resolve();
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
?>