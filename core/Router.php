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
        if (isset($_GET['debug']) && defined('APP_DEBUG') && APP_DEBUG) {
            echo "DEBUG: method={$method}, original_path=" . $_SERVER['REQUEST_URI'] . ", basePath={$basePath}, final_path={$path}<br>";
            echo "Available routes: " . print_r(array_keys($this->routes[$method] ?? []), true) . "<br>";
        }

        // Try exact match first
        if (isset($this->routes[$method][$path])) {
            $this->currentRoute = $this->routes[$method][$path];
            return $this->executeRoute($this->currentRoute);
        }

        // Try dynamic route matching
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $callback) {
                // Convert route pattern to regex
                // {param} becomes ([^/]+)
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $path, $matches)) {
                    // Remove the full match
                    array_shift($matches);
                    
                    $this->currentRoute = $callback;
                    return $this->executeRoute($callback, $matches);
                }
            }
        }

        // 404 Not Found
        $this->handle404();
    }

    private function executeRoute($callback, $params = []) {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        if (is_array($callback)) {
            [$controller, $method] = $callback;

            if (is_string($controller)) {
                $controller = new $controller();
            }

            return call_user_func_array([$controller, $method], $params);
        }

        return null;
    }

    private function handle404() {
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
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The requested page <code>" . htmlspecialchars($_SERVER['REQUEST_URI']) . "</code> could not be found.</p>";
        echo "<p><a href='/'>Return to Home</a></p>";
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
