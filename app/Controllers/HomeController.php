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
        // Program listesi database'den gelir
        $programs = $this->programService->getActivePrograms();
        $stats = $this->statisticsService->getSystemStatistics();

        $this->view('home/index', [
            'title' => 'Ana Sayfa - Ders Programı Sistemi',
            'programs' => $programs,
            'stats' => $stats,
            'announcements' => [] // Mockup veri kaldırıldı - database'den gelecek
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

    public function help() {
        $this->view('home/help', [
            'title' => 'Yardım'
        ]);
    }
}
?>
