<?php

namespace App\Middleware;

use PDO;

class AuthMiddleware {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Check if user is authenticated
     */
    public function handle($request, $next) {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
            return false;
        }

        return $next($request);
    }

    /**
     * Check if user has required permission
     */
    public function requirePermission($permission, $action = 'read') {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
            return false;
        }

        if (!$this->hasPermission($permission, $action)) {
            $this->accessDenied();
            return false;
        }

        return true;
    }

    /**
     * Check if user has required role
     */
    public function requireRole($roles) {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
            return false;
        }

        $user = $this->getCurrentUser();
        if (!$user || !in_array($user['role'], $roles)) {
            $this->accessDenied();
            return false;
        }

        return true;
    }

    /**
     * Admin only middleware
     */
    public function requireAdmin() {
        return $this->requireRole(['super_admin', 'admin']);
    }

    /**
     * Super admin only middleware
     */
    public function requireSuperAdmin() {
        return $this->requireRole(['super_admin']);
    }

    /**
     * Check if current user is authenticated
     */
    private function isAuthenticated() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    /**
     * Get current authenticated user
     */
    private function getCurrentUser() {
        if (!$this->isAuthenticated()) {
            return null;
        }

        if (!isset($_SESSION['admin_user_id'])) {
            return null;
        }

        $stmt = $this->db->prepare("SELECT * FROM admin_users WHERE id = ? AND status = 'active' AND approval_status = 'approved'");
        $stmt->execute([$_SESSION['admin_user_id']]);
        return $stmt->fetch();
    }

    /**
     * Check if user has permission
     */
    private function hasPermission($permission, $action = 'read') {
        $user = $this->getCurrentUser();
        if (!$user) {
            return false;
        }

        // Super admin has all permissions
        if ($user['role'] === 'super_admin') {
            return true;
        }

        // Check permission in database
        $column = 'can_' . $action;
        $stmt = $this->db->prepare("SELECT {$column} FROM admin_permissions WHERE role = ? AND permission_name = ?");
        $stmt->execute([$user['role'], $permission]);
        $result = $stmt->fetchColumn();

        return (bool)$result;
    }

    /**
     * Redirect to login page
     */
    private function redirectToLogin() {
        $currentUrl = $_SERVER['REQUEST_URI'];
        $loginUrl = '/auth/login';

        if ($currentUrl !== $loginUrl) {
            $loginUrl .= '?redirect=' . urlencode($currentUrl);
        }

        header('Location: ' . $loginUrl);
        exit;
    }

    /**
     * Show access denied page
     */
    private function accessDenied() {
        http_response_code(403);
        include '../app/Views/errors/403.php';
        exit;
    }

    /**
     * API Authentication for AJAX requests
     */
    public function apiAuth() {
        if (!$this->isAuthenticated()) {
            http_response_code(401);
            echo json_encode([
                'error' => 'Unauthorized',
                'message' => 'Authentication required'
            ]);
            exit;
        }

        return true;
    }

    /**
     * API Permission check
     */
    public function apiPermission($permission, $action = 'read') {
        if (!$this->apiAuth()) {
            return false;
        }

        if (!$this->hasPermission($permission, $action)) {
            http_response_code(403);
            echo json_encode([
                'error' => 'Forbidden',
                'message' => 'Insufficient permissions'
            ]);
            exit;
        }

        return true;
    }

    /**
     * Check if user account is approved
     */
    public function requireApproval() {
        if (!$this->isAuthenticated()) {
            $this->redirectToLogin();
            return false;
        }

        $user = $this->getCurrentUser();
        if (!$user || $user['approval_status'] !== 'approved') {
            $this->pendingApproval();
            return false;
        }

        return true;
    }

    /**
     * Show pending approval page
     */
    private function pendingApproval() {
        http_response_code(403);
        include '../app/Views/errors/pending-approval.php';
        exit;
    }

    /**
     * Log activity
     */
    public function logActivity($action, $resource, $resource_id = null) {
        $user = $this->getCurrentUser();
        if (!$user) {
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO admin_activity_log (user_id, action, resource, resource_id, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $user['id'],
            $action,
            $resource,
            $resource_id,
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
    }

    /**
     * Rate limiting
     */
    public function rateLimit($action, $maxAttempts = 5, $timeWindow = 300) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $key = "rate_limit:{$action}:{$ip}";

        // Simple file-based rate limiting (in production use Redis/Memcached)
        $cacheFile = "../storage/cache/" . md5($key) . ".cache";

        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            $attempts = $data['attempts'] ?? 0;
            $firstAttempt = $data['first_attempt'] ?? time();

            // Reset if time window has passed
            if (time() - $firstAttempt > $timeWindow) {
                $attempts = 0;
                $firstAttempt = time();
            }

            // Check if limit exceeded
            if ($attempts >= $maxAttempts) {
                http_response_code(429);
                echo json_encode([
                    'error' => 'Too Many Requests',
                    'message' => 'Rate limit exceeded. Try again later.',
                    'retry_after' => $timeWindow - (time() - $firstAttempt)
                ]);
                exit;
            }

            // Increment attempts
            $attempts++;
        } else {
            $attempts = 1;
            $firstAttempt = time();
        }

        // Save to cache
        if (!is_dir('../storage/cache')) {
            mkdir('../storage/cache', 0777, true);
        }

        file_put_contents($cacheFile, json_encode([
            'attempts' => $attempts,
            'first_attempt' => $firstAttempt
        ]));

        return true;
    }
}
?>