<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/AcademicTermService.php';

use App\Services\AcademicTermService;

class ScheduleController extends Controller {
    private $academicTermService;

    public function __construct($container = null) {
        parent::__construct($container);
        $this->academicTermService = new AcademicTermService($this->db);
    }

    /**
     * Ders programları ana sayfası
     */
    public function index() {
        try {
            // Aktif dönemi al
            $aktif_donem = $this->academicTermService->getActiveTerm();
            
            // Tüm programları al
            $stmt = $this->db->prepare("SELECT program_kodu, program_adi FROM programlar WHERE aktif = TRUE ORDER BY program_kodu");
            $stmt->execute();
            $programs = $stmt->fetchAll();

            $this->view('schedules/index', [
                'title' => 'Ders Programları',
                'aktif_donem' => $aktif_donem,
                'programs' => $programs
            ]);
        } catch (Exception $e) {
            $this->view('errors/404', [
                'title' => 'Hata',
                'message' => 'Ders programları yüklenirken bir hata oluştu.'
            ]);
        }
    }

    /**
     * Belirli bir program için ders programını göster
     * @param string $programKodu - Program kodu
     */
    public function show($programKodu) {
        try {
            // Program bilgisini al
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

            // Aktif dönemi al
            $aktif_donem = $this->academicTermService->getActiveTerm();

            // Ders programını çek
            $stmt = $this->db->prepare("SELECT 
                    dp.*,
                    d.ders_adi,
                    d.ders_kodu,
                    d.teorik_saat,
                    d.pratik_saat,
                    o.ad as ogretim_elemani_ad,
                    o.soyad as ogretim_elemani_soyad,
                    dr.derslik_adi
                FROM ders_programi dp
                LEFT JOIN dersler d ON dp.ders_id = d.id
                LEFT JOIN ogretim_elemanlari o ON dp.ogretim_elemani_id = o.id
                LEFT JOIN derslikler dr ON dp.derslik_id = dr.id
                WHERE dp.program_kodu = ?
                ORDER BY dp.gun, dp.saat_dilimi");
            $stmt->execute([$programKodu]);
            $schedule = $stmt->fetchAll();

            $this->view('schedules/show', [
                'title' => $program['program_adi'] . ' - Ders Programı',
                'program' => $program,
                'aktif_donem' => $aktif_donem,
                'schedule' => $schedule
            ]);
        } catch (Exception $e) {
            $this->view('errors/404', [
                'title' => 'Hata',
                'message' => 'Ders programı yüklenirken bir hata oluştu.'
            ]);
        }
    }
}
?>
