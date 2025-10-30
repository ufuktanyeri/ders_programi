<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/ProgramService.php';

use App\Services\ProgramService;

class ProgramController extends Controller {
    private $programService;

    public function __construct($container = null) {
        parent::__construct($container);
        $this->programService = new ProgramService($this->db);
    }

    /**
     * Tüm programların listesini göster
     */
    public function index() {
        try {
            $programs = $this->programService->getActivePrograms();

            $this->view('programs/index', [
                'title' => 'Eğitim Programları',
                'programs' => $programs
            ]);
        } catch (Exception $e) {
            $this->view('errors/404', [
                'title' => 'Hata',
                'message' => 'Programlar yüklenirken bir hata oluştu.'
            ]);
        }
    }

    /**
     * Belirli bir programın detayını göster
     * @param string $programKodu - Program kodu (örn: BP, ET, YET)
     */
    public function show($programKodu) {
        try {
            // Program bilgilerini çek
            $stmt = $this->db->prepare("SELECT * FROM programlar WHERE program_kodu = ? AND aktif = TRUE");
            $stmt->execute([$programKodu]);
            $program = $stmt->fetch();

            if (!$program) {
                $this->view('errors/404', [
                    'title' => 'Program Bulunamadı',
                    'message' => 'Aradığınız program bulunamadı.'
                ]);
                return;
            }

            // Programa ait dersleri çek
            $stmt = $this->db->prepare("SELECT * FROM dersler WHERE program_kodu = ? ORDER BY donem, ders_kodu");
            $stmt->execute([$programKodu]);
            $dersler = $stmt->fetchAll();

            $this->view('programs/show', [
                'title' => $program['program_adi'] . ' - Program Detayı',
                'program' => $program,
                'dersler' => $dersler
            ]);
        } catch (Exception $e) {
            $this->view('errors/404', [
                'title' => 'Hata',
                'message' => 'Program detayları yüklenirken bir hata oluştu.'
            ]);
        }
    }
}
?>
