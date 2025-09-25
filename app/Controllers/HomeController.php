<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/StatisticsService.php';
require_once __DIR__ . '/../Services/ProgramService.php';
require_once __DIR__ . '/../Services/AcademicTermService.php';

use App\Services\StatisticsService;
use App\Services\ProgramService;
use App\Services\AcademicTermService;

class HomeController extends Controller {
    private $statisticsService;
    private $programService;
    private $academicTermService;

    public function __construct($container = null) {
        parent::__construct($container);

        // Initialize services with dependency injection
        $this->statisticsService = new StatisticsService($this->db);
        $this->programService = new ProgramService($this->db);
        $this->academicTermService = new AcademicTermService($this->db);
    }

    public function index() {
        // Ana sayfa - Public welcome page with login menu
        $programlar = $this->programService->getActivePrograms();
        $stats = $this->statisticsService->getSystemStatistics();

        $this->view('home/welcome', [
            'title' => 'Ders Programı Yönetim Sistemi - Ana Sayfa',
            'programlar' => $programlar,
            'stats' => $stats
        ]);
    }

    public function dashboard() {
        // Dashboard - requires authentication
        $this->requireAuth();

        $aktif_donem = $this->academicTermService->getActiveTerm();
        $programlar = $this->programService->getActivePrograms();
        $stats = $this->statisticsService->getSystemStatistics();

        $this->view('home/dashboard', [
            'title' => 'Dashboard - Ders Programı Yönetim Sistemi',
            'aktif_donem' => $aktif_donem,
            'programlar' => $programlar,
            'stats' => $stats
        ]);
    }

    public function legacy() {
        // Legacy ana sayfa işlevselliği
        $announcements = [
            [
                'title' => 'Güz Dönemi Ders Programları Yayınlandı',
                'content' => '2024-2025 Güz dönemi ders programları öğrenci ve öğretim elemanlarının erişimine sunulmuştur.',
                'date' => '2024-09-15',
                'type' => 'info'
            ],
            [
                'title' => 'Sistem Bakım Bildirimi',
                'content' => '25 Eylül Çarşamba günü 02:00-04:00 saatleri arasında sistem bakım nedeniyle erişilemeyecektir.',
                'date' => '2024-09-20',
                'type' => 'warning'
            ],
            [
                'title' => 'Yeni Özellikler Eklendi',
                'content' => 'Google OAuth entegrasyonu ve yetki yönetim sistemi devreye alınmıştır.',
                'date' => '2024-09-25',
                'type' => 'success'
            ]
        ];

        // Program listesi
        $programs = $this->db->query("SELECT program_kodu, program_adi FROM programlar WHERE aktif = TRUE ORDER BY program_kodu LIMIT 6")->fetchAll();

        $this->view('home/index', [
            'title' => 'Ana Sayfa',
            'announcements' => $announcements,
            'programs' => $programs
        ]);
    }

    public function about() {
        $this->view('home/about', [
            'title' => 'Hakkında'
        ]);
    }

    public function contact() {
        $this->view('home/contact', [
            'title' => 'İletişim'
        ]);
    }
}
?>