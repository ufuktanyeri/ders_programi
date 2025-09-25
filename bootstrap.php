<?php
// Bootstrap file for application initialization

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader (simple version)
spl_autoload_register(function ($className) {
    // Convert namespace to file path
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';

    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    // Define search paths
    $paths = [
        __DIR__ . '/app/' . $fileName,
        __DIR__ . '/core/' . $fileName,
        __DIR__ . '/' . $fileName,
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Load configuration
require_once 'config/database.php';
require_once 'core/Container.php';
require_once 'core/Router.php';

// Initialize container
$container = new Container();

// Bind services to container
$container->singleton('PDO', function() use ($db) {
    return $db;
});

$container->bind('App\Services\StatisticsService', function($container) {
    return new App\Services\StatisticsService($container->make('PDO'));
});

$container->bind('App\Services\ProgramService', function($container) {
    return new App\Services\ProgramService($container->make('PDO'));
});

$container->bind('App\Services\AcademicTermService', function($container) {
    return new App\Services\AcademicTermService($container->make('PDO'));
});

$container->bind('App\Repositories\UserRepository', function($container) {
    return new App\Repositories\UserRepository($container->make('PDO'));
});

$container->bind('App\Repositories\ProgramRepository', function($container) {
    return new App\Repositories\ProgramRepository($container->make('PDO'));
});

$container->bind('App\Repositories\TeacherRepository', function($container) {
    return new App\Repositories\TeacherRepository($container->make('PDO'));
});

// Initialize router
$router = new Router();

// Make container globally available
$GLOBALS['container'] = $container;
$GLOBALS['router'] = $router;

// Timezone
date_default_timezone_set('Europe/Istanbul');
?>