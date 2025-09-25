<?php
namespace App\Repositories;

require_once 'BaseRepository.php';

class ProgramRepository extends BaseRepository {
    protected $table = 'programlar';
    protected $primaryKey = 'program_id';

    public function findActive() {
        return $this->findBy('aktif', true);
    }

    public function findByCode($programCode) {
        return $this->findOneBy('program_kodu', $programCode);
    }

    public function findByType($programType) {
        return $this->findBy('program_turu', $programType);
    }

    public function searchByName($searchTerm) {
        $sql = "SELECT * FROM {$this->table}
                WHERE program_adi LIKE ? OR program_kodu LIKE ?
                ORDER BY program_kodu";

        $searchTerm = "%{$searchTerm}%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    public function getWithCourseCount() {
        $sql = "SELECT p.*, COUNT(d.ders_id) as ders_sayisi
                FROM {$this->table} p
                LEFT JOIN dersler d ON p.program_id = d.program_id AND d.aktif = TRUE
                WHERE p.aktif = TRUE
                GROUP BY p.program_id
                ORDER BY p.program_kodu";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function softDelete($id) {
        return $this->update($id, ['aktif' => false]);
    }

    public function restore($id) {
        return $this->update($id, ['aktif' => true]);
    }

    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total,
                    COUNT(CASE WHEN aktif = TRUE THEN 1 END) as active,
                    COUNT(CASE WHEN program_turu = 'Ön Lisans' THEN 1 END) as on_lisans,
                    COUNT(CASE WHEN program_turu = 'Lisans' THEN 1 END) as lisans
                FROM {$this->table}";

        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
?>