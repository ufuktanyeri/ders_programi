<?php
namespace App\Services;

class ProgramService {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getActivePrograms() {
        $stmt = $this->db->query("SELECT * FROM programlar WHERE aktif = TRUE ORDER BY program_kodu");
        return $stmt->fetchAll();
    }

    public function getProgramById($id) {
        $stmt = $this->db->prepare("SELECT * FROM programlar WHERE program_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getProgramSchedule($programId) {
        $sql = "SELECT hp.*, d.ders_adi, de.ad as derslik_adi, oe.ad as ogretmen_adi
                FROM haftalik_program hp
                JOIN ders_atamalari da ON hp.atama_id = da.atama_id
                JOIN dersler d ON da.ders_id = d.ders_id
                JOIN derslikler de ON hp.derslik_id = de.derslik_id
                JOIN ogretim_elemanlari oe ON da.ogretmen_id = oe.ogretmen_id
                WHERE da.program_id = ?
                ORDER BY hp.gun, hp.baslangic_saat";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$programId]);
        return $stmt->fetchAll();
    }

    public function checkProgramConflicts($programId) {
        // Implement conflict checking logic
        return [];
    }

    public function createProgram($data) {
        $sql = "INSERT INTO programlar (program_kodu, program_adi, program_turu, aktif)
                VALUES (?, ?, ?, TRUE)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['program_kodu'],
            $data['program_adi'],
            $data['program_turu']
        ]);
    }

    public function updateProgram($id, $data) {
        $sql = "UPDATE programlar SET
                program_kodu = ?, program_adi = ?, program_turu = ?
                WHERE program_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['program_kodu'],
            $data['program_adi'],
            $data['program_turu'],
            $id
        ]);
    }

    public function deleteProgram($id) {
        $sql = "UPDATE programlar SET aktif = FALSE WHERE program_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>