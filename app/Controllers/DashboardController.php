<?php

use App\Services\AuthService;
use App\Middleware\AuthMiddleware;
use App\Models\UserModel;

class DashboardController extends Controller {
    private $authService;
    private $authMiddleware;
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->authService = new AuthService($this->db);
        $this->authMiddleware = new AuthMiddleware($this->db);
        $this->userModel = new UserModel($this->db);
    }

    /**
     * Main dashboard - redirects based on user role
     */
    public function index() {
        // Require authentication and approval
        if (!$this->authMiddleware->requireApproval()) {
            return;
        }

        $user = $this->authService->getCurrentUser();
        if (!$user) {
            $this->redirect('/auth/login');
            return;
        }

        // Redirect based on role
        switch ($user->getRole()) {
            case 'super_admin':
            case 'admin':
                $this->redirect('/dashboard/admin');
                break;
            case 'instructor':
            case 'teacher':
                $this->redirect('/dashboard/instructor');
                break;
            case 'editor':
            case 'guest':
            default:
                $this->redirect('/dashboard/guest');
                break;
        }
    }

    /**
     * Admin Dashboard
     */
    public function admin() {
        if (!$this->authMiddleware->requireRole(['super_admin', 'admin'])) {
            return;
        }

        $user = $this->authService->getCurrentUser();

        // Get statistics
        $stats = $this->getAdminStats();

        // Get pending approvals
        $pendingApprovals = $this->userModel->findPendingApprovals();

        // Get recent activities
        $recentActivities = $this->getRecentActivities(10);

        // Get system health
        $systemHealth = $this->getSystemHealth();

        $this->view('dashboard/admin', [
            'title' => 'Admin Dashboard',
            'user' => $user,
            'stats' => $stats,
            'pendingApprovals' => $pendingApprovals,
            'recentActivities' => $recentActivities,
            'systemHealth' => $systemHealth
        ]);
    }

    /**
     * Instructor Dashboard (formerly Teacher Dashboard)
     */
    public function instructor() {
        if (!$this->authMiddleware->requireRole(['super_admin', 'admin', 'instructor'])) {
            return;
        }

        $user = $this->authService->getCurrentUser();

        // Get instructor's schedule
        $mySchedules = $this->getInstructorSchedules($user);

        // Get instructor stats
        $instructorStats = $this->getInstructorStats($user);

        // Get upcoming classes
        $upcomingClasses = $this->getUpcomingClasses($user);

        // Get schedule suggestions
        $suggestions = $this->getScheduleSuggestions($user);

        $this->view('dashboard/instructor', [
            'title' => 'Öğretim Elemanı Dashboard',
            'user' => $user,
            'mySchedules' => $mySchedules,
            'instructorStats' => $instructorStats,
            'upcomingClasses' => $upcomingClasses,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Teacher Dashboard (alias for instructor)
     */
    public function teacher() {
        $this->instructor();
    }

    /**
     * Guest Dashboard
     */
    public function guest() {
        $user = $this->authService->getCurrentUser();

        // Get public schedules
        $publicSchedules = $this->getPublicSchedules();

        // Get announcements
        $announcements = $this->getPublicAnnouncements();

        // Get general stats
        $publicStats = $this->getPublicStats();

        $this->view('dashboard/guest', [
            'title' => 'Dashboard',
            'user' => $user,
            'publicSchedules' => $publicSchedules,
            'announcements' => $announcements,
            'publicStats' => $publicStats
        ]);
    }

    /**
     * Get admin statistics
     */
    private function getAdminStats() {
        $stats = [];

        // Users
        $userStats = $this->userModel->getUserStats();
        $stats['users'] = $userStats;

        // Programs
        $stmt = $this->db->query("SELECT COUNT(*) as total, COUNT(CASE WHEN aktif = 1 THEN 1 END) as active FROM programlar");
        $stats['programs'] = $stmt->fetch();

        // Teachers
        $stmt = $this->db->query("SELECT COUNT(*) as total, COUNT(CASE WHEN aktif = 1 THEN 1 END) as active FROM ogretim_elemanlari");
        $stats['teachers'] = $stmt->fetch();

        // Courses
        $stmt = $this->db->query("SELECT COUNT(*) as total, COUNT(CASE WHEN aktif = 1 THEN 1 END) as active FROM dersler");
        $stats['courses'] = $stmt->fetch();

        // Classrooms
        $stmt = $this->db->query("SELECT COUNT(*) as total, COUNT(CASE WHEN aktif = 1 THEN 1 END) as active FROM derslikler");
        $stats['classrooms'] = $stmt->fetch();

        // Conflicts
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM schedule_suggestions WHERE status = 'pending'");
        $stats['conflicts'] = $stmt->fetch();

        return $stats;
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities($limit = 10) {
        $sql = "SELECT al.*, u.name as user_name
                FROM admin_activity_log al
                LEFT JOIN admin_users u ON al.user_id = u.id
                ORDER BY al.created_at DESC
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);

        return $stmt->fetchAll();
    }

    /**
     * Get system health status
     */
    private function getSystemHealth() {
        $health = [
            'database' => 'healthy',
            'disk_space' => 'healthy',
            'memory' => 'healthy',
            'errors' => 'healthy'
        ];

        // Check database connection
        try {
            $this->db->query("SELECT 1");
        } catch (Exception $e) {
            $health['database'] = 'error';
        }

        // Check disk space (simplified)
        $diskFree = disk_free_space('.');
        $diskTotal = disk_total_space('.');
        if ($diskFree / $diskTotal < 0.1) { // Less than 10% free
            $health['disk_space'] = 'warning';
        }

        // Check for recent errors in logs
        if (is_dir('../storage/logs')) {
            $errorLogFile = '../storage/logs/error.log';
            if (file_exists($errorLogFile) && filesize($errorLogFile) > 1024 * 1024) { // > 1MB
                $health['errors'] = 'warning';
            }
        }

        return $health;
    }

    /**
     * Get instructor's schedules
     */
    private function getInstructorSchedules($user) {
        // Find instructor by email
        $stmt = $this->db->prepare("SELECT ogretmen_id FROM ogretim_elemanlari WHERE email = ?");
        $stmt->execute([$user->getEmail()]);
        $instructorId = $stmt->fetchColumn();

        if (!$instructorId) {
            return [];
        }

        $sql = "SELECT hp.*, d.ders_adi, dr.derslik_adi_tr, p.program_adi,
                       CASE hp.gun
                           WHEN 1 THEN 'Pazartesi'
                           WHEN 2 THEN 'Salı'
                           WHEN 3 THEN 'Çarşamba'
                           WHEN 4 THEN 'Perşembe'
                           WHEN 5 THEN 'Cuma'
                       END as gun_adi
                FROM haftalik_program hp
                JOIN ders_atamalari da ON hp.atama_id = da.atama_id
                JOIN dersler d ON da.ders_id = d.ders_id
                JOIN derslikler dr ON hp.derslik_id = dr.derslik_id
                JOIN programlar p ON d.program_id = p.program_id
                WHERE da.ogretmen_id = ?
                ORDER BY hp.gun, hp.baslangic_saat";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$instructorId]);

        return $stmt->fetchAll();
    }

    /**
     * Get teacher's schedules (alias for instructor)
     */
    private function getTeacherSchedules($user) {
        return $this->getInstructorSchedules($user);
    }

    /**
     * Get instructor statistics
     */
    private function getInstructorStats($user) {
        $stmt = $this->db->prepare("SELECT ogretmen_id FROM ogretim_elemanlari WHERE email = ?");
        $stmt->execute([$user->getEmail()]);
        $instructorId = $stmt->fetchColumn();

        if (!$instructorId) {
            return ['total_hours' => 0, 'total_courses' => 0, 'total_students' => 0];
        }

        // Calculate weekly hours
        $stmt = $this->db->prepare("
            SELECT
                COUNT(DISTINCT d.ders_id) as total_courses,
                SUM(d.haftalik_saat) as total_hours
            FROM ders_atamalari da
            JOIN dersler d ON da.ders_id = d.ders_id
            WHERE da.ogretmen_id = ? AND d.aktif = 1
        ");
        $stmt->execute([$instructorId]);
        $stats = $stmt->fetch();

        return $stats;
    }

    /**
     * Get teacher statistics (alias for instructor)
     */
    private function getTeacherStats($user) {
        return $this->getInstructorStats($user);
    }

    /**
     * Get upcoming classes for teacher
     */
    private function getUpcomingClasses($user) {
        // This is a simplified version - in real implementation you'd calculate actual upcoming classes
        return [];
    }

    /**
     * Get schedule suggestions for teacher
     */
    private function getScheduleSuggestions($user) {
        $stmt = $this->db->prepare("
            SELECT ss.*, d.ders_adi, dr1.derslik_adi_tr as current_classroom, dr2.derslik_adi_tr as suggested_classroom
            FROM schedule_suggestions ss
            LEFT JOIN dersler d ON ss.course_id = d.ders_id
            LEFT JOIN derslikler dr1 ON ss.classroom_id = dr1.derslik_id
            LEFT JOIN derslikler dr2 ON ss.classroom_id = dr2.derslik_id
            JOIN admin_users u ON ss.user_id = u.id
            WHERE u.email = ? AND ss.status = 'pending'
            ORDER BY ss.created_at DESC
        ");
        $stmt->execute([$user->getEmail()]);

        return $stmt->fetchAll();
    }

    /**
     * Get public schedules
     */
    private function getPublicSchedules() {
        $sql = "SELECT p.program_id, p.program_adi, COUNT(d.ders_id) as ders_sayisi
                FROM programlar p
                LEFT JOIN dersler d ON p.program_id = d.program_id AND d.aktif = 1
                WHERE p.aktif = 1
                GROUP BY p.program_id, p.program_adi
                ORDER BY p.program_adi
                LIMIT 6";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get public announcements
     */
    private function getPublicAnnouncements() {
        $sql = "SELECT * FROM system_announcements
                WHERE is_active = 1
                AND (start_date IS NULL OR start_date <= NOW())
                AND (end_date IS NULL OR end_date >= NOW())
                AND (target_roles IS NULL OR JSON_CONTAINS(target_roles, '\"guest\"'))
                ORDER BY priority DESC, created_at DESC
                LIMIT 5";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get public statistics
     */
    private function getPublicStats() {
        $stats = [];

        $stmt = $this->db->query("SELECT COUNT(*) FROM programlar WHERE aktif = 1");
        $stats['programs'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM ogretim_elemanlari WHERE aktif = 1");
        $stats['teachers'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM dersler WHERE aktif = 1");
        $stats['courses'] = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM derslikler WHERE aktif = 1");
        $stats['classrooms'] = $stmt->fetchColumn();

        return $stats;
    }
}
?>