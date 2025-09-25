<?php
require_once __DIR__ . '/../../core/Controller.php';

class AdminController extends Controller {

    public function index() {
        // Authentication check
        if (!isset($_SESSION['admin_logged_in'])) {
            $this->redirect('/auth/login');
            return;
        }

        // Statistics
        $stats = [
            'programs' => $this->db->query("SELECT COUNT(*) FROM programlar")->fetchColumn(),
            'teachers' => $this->db->query("SELECT COUNT(*) FROM ogretim_elemanlari")->fetchColumn(),
            'classrooms' => $this->db->query("SELECT COUNT(*) FROM derslikler")->fetchColumn(),
            'courses' => $this->db->query("SELECT COUNT(*) FROM dersler")->fetchColumn(),
            'conflicts' => $this->db->query("SELECT COUNT(*) FROM cakisma_loglari WHERE cozuldu = FALSE")->fetchColumn()
        ];

        // User info
        $current_user = [
            'name' => $_SESSION['admin_username'] ?? 'Admin',
            'role' => 'admin'
        ];

        $this->view('admin/dashboard', [
            'title' => 'Admin Panel - Ders Programı Sistemi',
            'stats' => $stats,
            'current_user' => $current_user
        ]);
    }

    public function programs() {
        $this->requireAuth();

        $programs = $this->db->query("SELECT * FROM programlar ORDER BY program_kodu")->fetchAll();

        $this->view('admin/programs', [
            'title' => 'Program Yönetimi',
            'programs' => $programs
        ]);
    }

    public function teachers() {
        $this->requireAuth();

        $teachers = $this->db->query("SELECT * FROM ogretim_elemanlari ORDER BY ad")->fetchAll();

        $this->view('admin/teachers', [
            'title' => 'Öğretim Elemanları',
            'teachers' => $teachers
        ]);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = clean($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                $this->redirect('/admin');
                return;
            }
        }

        $this->view('admin/login', ['title' => 'Admin Giriş']);
    }

    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
?>