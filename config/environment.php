<?php
/**
 * Environment Configuration
 */

// Environment detection
define('ENVIRONMENT', $_ENV['APP_ENV'] ?? 'development');

// Production server configuration
define('PRODUCTION_SERVER_IP', '46.182.69.9');
define('PRODUCTION_DB_HOST', '46.182.69.9');
define('PRODUCTION_WEB_URL', 'http://46.182.69.9/ders_programi');

// Environment-specific configurations
$config = [
    'development' => [
        'db_host' => 'localhost',
        'db_name' => 'ders_programi',
        'db_user' => 'root',
        'db_pass' => '',
        'base_url' => 'http://localhost/ders_programi',
        'debug' => true,
        'google_redirect_uri' => 'http://localhost/ders_programi/public/auth/google/callback',
        'ssl_required' => false
    ],
    'production' => [
        'db_host' => PRODUCTION_DB_HOST,
        'db_name' => 'ders_programi',
        'db_user' => 'ders_programi_user',
        'db_pass' => 'secure_password_here', // Bu production'da güçlü şifre olacak
        'base_url' => PRODUCTION_WEB_URL,
        'debug' => false,
        'google_redirect_uri' => PRODUCTION_WEB_URL . '/auth/google/callback',
        'ssl_required' => false // LAN içi olduğu için HTTP
    ],
    'staging' => [
        'db_host' => '46.182.69.9',
        'db_name' => 'ders_programi_test',
        'db_user' => 'test_user',
        'db_pass' => 'test_password',
        'base_url' => 'http://46.182.69.9/ders_programi_test',
        'debug' => true,
        'google_redirect_uri' => 'http://46.182.69.9/ders_programi_test/auth/google/callback',
        'ssl_required' => false
    ]
];

// Get current environment config
function getConfig($key = null) {
    global $config;
    $currentConfig = $config[ENVIRONMENT] ?? $config['development'];

    if ($key === null) {
        return $currentConfig;
    }

    return $currentConfig[$key] ?? null;
}

// Database configuration
define('DB_HOST', getConfig('db_host'));
define('DB_NAME', getConfig('db_name'));
define('DB_USER', getConfig('db_user'));
define('DB_PASS', getConfig('db_pass'));
define('DB_CHARSET', 'utf8mb4');

// App configuration
define('APP_URL', getConfig('base_url'));
define('APP_DEBUG', getConfig('debug'));
define('GOOGLE_REDIRECT_URI', getConfig('google_redirect_uri'));

// Network configuration for production
if (ENVIRONMENT === 'production') {
    // Production-specific settings
    define('ALLOWED_IPS', [
        '46.182.69.9',     // Server IP
        '192.168.1.0/24',  // Local network range (örnek)
        '10.0.0.0/24'      // Internal network range (örnek)
    ]);

    // Security headers for production
    define('SECURITY_HEADERS', [
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
        'X-Content-Type-Options' => 'nosniff',
        'Referrer-Policy' => 'strict-origin-when-cross-origin'
    ]);
}

// Display environment info (development only)
if (APP_DEBUG) {
    function showEnvironmentInfo() {
        echo "<!-- Environment: " . ENVIRONMENT . " -->\n";
        echo "<!-- DB Host: " . DB_HOST . " -->\n";
        echo "<!-- Base URL: " . APP_URL . " -->\n";
    }
} else {
    function showEnvironmentInfo() {
        // Production'da bilgi gösterme
    }
}
?>