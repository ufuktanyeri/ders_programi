<?php
class Controller {
    protected $db;
    protected $router;
    protected $container;

    public function __construct($container = null) {
        global $db, $router;

        $this->container = $container ?? $GLOBALS['container'] ?? null;
        $this->db = $db;
        $this->router = $router;
    }

    protected function view($view, $data = []) {
        extract($data);

        // Try different path combinations
        $possiblePaths = [
            __DIR__ . "/../app/Views/{$view}.php",
            "../app/Views/{$view}.php",
            "app/Views/{$view}.php"
        ];

        foreach ($possiblePaths as $viewPath) {
            if (file_exists($viewPath)) {
                include $viewPath;
                return;
            }
        }

        throw new Exception("View not found: {$view} - Tried paths: " . implode(', ', $possiblePaths));
    }

    protected function redirect($path) {
        $this->router->redirect($path);
    }

    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function getCurrentUser() {
        if (!isset($_SESSION['admin_logged_in'])) {
            return null;
        }

        if (isset($_SESSION['admin_user_id'])) {
            $stmt = $this->db->prepare("SELECT * FROM admin_users WHERE id = ? AND status = 'active'");
            $stmt->execute([$_SESSION['admin_user_id']]);
            return $stmt->fetch();
        }

        return null;
    }

    protected function requireAuth() {
        if (!$this->getCurrentUser()) {
            $this->redirect('/auth/login');
        }
    }

    protected function hasPermission($permission, $action = 'read') {
        $user = $this->getCurrentUser();
        if (!$user) return false;

        if ($user['role'] === 'super_admin') return true;

        $column = 'can_' . $action;
        $stmt = $this->db->prepare("SELECT $column FROM admin_permissions WHERE role = ? AND permission_name = ?");
        $stmt->execute([$user['role'], $permission]);
        return (bool)$stmt->fetchColumn();
    }

    protected function requirePermission($permission, $action = 'read') {
        if (!$this->hasPermission($permission, $action)) {
            http_response_code(403);
            $this->view('errors/403');
            exit;
        }
    }
}
?>