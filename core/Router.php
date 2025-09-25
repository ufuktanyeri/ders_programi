<?php
class Router {
    private $routes = [];
    private $currentRoute;

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base directory from path
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        // Fix for nested directory structure
        if ($basePath !== '/') {
            // Remove both /ders_programi and /ders_programi/public
            $path = str_replace('/ders_programi', '', $path);
            $path = str_replace($basePath, '', $path);
        }

        $path = $path ?: '/';

        // Debug information (only in development)
        if (isset($_GET['debug']) && APP_DEBUG) {
            echo "DEBUG: method={$method}, original_path=" . $_SERVER['REQUEST_URI'] . ", basePath={$basePath}, final_path={$path}<br>";
            echo "Available routes: " . print_r(array_keys($this->routes[$method] ?? []), true) . "<br>";
        }

        // Find matching route
        if (isset($this->routes[$method][$path])) {
            $this->currentRoute = $this->routes[$method][$path];

            if (is_callable($this->currentRoute)) {
                return call_user_func($this->currentRoute);
            }

            if (is_array($this->currentRoute)) {
                [$controller, $method] = $this->currentRoute;

                if (is_string($controller)) {
                    $controller = new $controller();
                }

                return $controller->$method();
            }
        }

        // 404 Not Found
        http_response_code(404);

        $possiblePaths = [
            __DIR__ . '/../app/Views/errors/404.php',
            '../app/Views/errors/404.php',
            'app/Views/errors/404.php'
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                include $path;
                return;
            }
        }

        // Fallback 404
        echo "<h1>404 - Page Not Found</h1><p>The requested page could not be found.</p>";
    }

    public function redirect($path) {
        $baseUrl = $this->getBaseUrl();
        header("Location: {$baseUrl}{$path}");
        exit;
    }

    public function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        return $protocol . '://' . $host . $basePath;
    }
}
?>