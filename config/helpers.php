<?php
/**
 * Global Helper Functions
 * 
 * Bu dosya tüm projede kullanılabilen global yardımcı fonksiyonları içerir.
 */

if (!function_exists('env')) {
    /**
     * Get environment variable value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = null) {
        $value = $_ENV[$key] ?? getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        // Boolean değerleri dönüştür
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
            case 'empty':
            case '(empty)':
                return '';
        }
        
        return $value;
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null) {
        static $config = [];
        
        if (empty($config)) {
            $config = [
                'app.name' => env('APP_NAME', 'Ders Programı'),
                'app.env' => env('APP_ENV', 'production'),
                'app.debug' => env('APP_DEBUG', false),
                'app.url' => env('APP_URL', 'http://localhost'),
                'db.host' => env('DB_HOST', 'localhost'),
                'db.name' => env('DB_DATABASE', 'ders_programi'),
                'db.user' => env('DB_USERNAME', 'root'),
                'db.password' => env('DB_PASSWORD', ''),
            ];
        }
        
        return $config[$key] ?? $default;
    }
}

if (!function_exists('base_path')) {
    /**
     * Get base path
     * 
     * @param string $path
     * @return string
     */
    function base_path($path = '') {
        return __DIR__ . '/../' . ltrim($path, '/');
    }
}

if (!function_exists('app_path')) {
    /**
     * Get app path
     * 
     * @param string $path
     * @return string
     */
    function app_path($path = '') {
        return base_path('app/' . ltrim($path, '/'));
    }
}

if (!function_exists('config_path')) {
    /**
     * Get config path
     * 
     * @param string $path
     * @return string
     */
    function config_path($path = '') {
        return base_path('config/' . ltrim($path, '/'));
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get storage path
     * 
     * @param string $path
     * @return string
     */
    function storage_path($path = '') {
        return base_path('storage/' . ltrim($path, '/'));
    }
}

if (!function_exists('public_path')) {
    /**
     * Get public path
     * 
     * @param string $path
     * @return string
     */
    function public_path($path = '') {
        return base_path('public/' . ltrim($path, '/'));
    }
}

if (!function_exists('asset')) {
    /**
     * Generate asset URL
     * 
     * @param string $path
     * @return string
     */
    function asset($path) {
        return config('app.url') . '/public/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Generate URL
     * 
     * @param string $path
     * @return string
     */
    function url($path = '') {
        return config('app.url') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to URL
     * 
     * @param string $url
     * @param int $status
     * @return void
     */
    function redirect($url, $status = 302) {
        header("Location: $url", true, $status);
        exit;
    }
}

if (!function_exists('back')) {
    /**
     * Redirect back
     * 
     * @return void
     */
    function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($referer);
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die
     * 
     * @param mixed ...$vars
     * @return void
     */
    function dd(...$vars) {
        echo '<pre>';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        die(1);
    }
}

if (!function_exists('dump')) {
    /**
     * Dump variable
     * 
     * @param mixed ...$vars
     * @return void
     */
    function dump(...$vars) {
        echo '<pre>';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
    }
}

if (!function_exists('logger')) {
    /**
     * Log message
     * 
     * @param string $message
     * @param string $level
     * @return void
     */
    function logger($message, $level = 'info') {
        $logFile = storage_path('logs/' . date('Y-m-d') . '.log');
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function old($key, $default = null) {
        return $_SESSION['_old_input'][$key] ?? $default;
    }
}

if (!function_exists('session')) {
    /**
     * Get or set session value
     * 
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function session($key = null, $default = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($key === null) {
            return $_SESSION;
        }
        
        return $_SESSION[$key] ?? $default;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get CSRF token
     * 
     * @return string
     */
    function csrf_token() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF token field
     * 
     * @return string
     */
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('method_field')) {
    /**
     * Generate method field for forms
     * 
     * @param string $method
     * @return string
     */
    function method_field($method) {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
}

if (!function_exists('now')) {
    /**
     * Get current timestamp
     * 
     * @return string
     */
    function now() {
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('today')) {
    /**
     * Get today's date
     * 
     * @return string
     */
    function today() {
        return date('Y-m-d');
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format date for Turkish locale
     * 
     * @param string $date
     * @param string $format
     * @return string
     */
    function formatDate($date, $format = 'd.m.Y') {
        return date($format, strtotime($date));
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * Format datetime for Turkish locale
     * 
     * @param string $datetime
     * @param string $format
     * @return string
     */
    function formatDateTime($datetime, $format = 'd.m.Y H:i') {
        return date($format, strtotime($datetime));
    }
}

if (!function_exists('str_limit')) {
    /**
     * Limit string length
     * 
     * @param string $value
     * @param int $limit
     * @param string $end
     * @return string
     */
    function str_limit($value, $limit = 100, $end = '...') {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }
        
        return mb_substr($value, 0, $limit) . $end;
    }
}

if (!function_exists('str_slug')) {
    /**
     * Generate URL friendly slug
     * 
     * @param string $title
     * @param string $separator
     * @return string
     */
    function str_slug($title, $separator = '-') {
        $title = mb_strtolower($title, 'UTF-8');
        
        // Turkish character replacements
        $turkish = ['ş', 'Ş', 'ı', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'ç', 'Ç'];
        $english = ['s', 's', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c'];
        $title = str_replace($turkish, $english, $title);
        
        $title = preg_replace('/[^a-z0-9]+/i', $separator, $title);
        $title = trim($title, $separator);
        
        return $title;
    }
}

if (!function_exists('array_get')) {
    /**
     * Get array value using dot notation
     * 
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function array_get($array, $key, $default = null) {
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }
        
        return $array;
    }
}

if (!function_exists('view')) {
    /**
     * Render view (simple implementation)
     * 
     * @param string $view
     * @param array $data
     * @return void
     */
    function view($view, $data = []) {
        extract($data);
        $viewPath = base_path('app/Views/' . str_replace('.', '/', $view) . '.php');
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new Exception("View not found: $view");
        }
    }
}
