<?php
namespace App\Repositories;

require_once 'BaseRepository.php';

class TeacherRepository extends BaseRepository {
    protected $table = 'ogretim_elemanlari';
    protected $primaryKey = 'ogretmen_id';

    public function findActive() {
        return $this->findBy('aktif', true);
    }

    public function findByInitials($initials) {
        return $this->findOneBy('kisa_ad', $initials);
    }

    public function findByDepartment($department) {
        return $this->findBy('bolum', $department);
    }

    public function searchByName($searchTerm) {
        $sql = "SELECT * FROM {$this->table}
                WHERE ad LIKE ? OR kisa_ad LIKE ? OR email LIKE ?
                AND aktif = TRUE
                ORDER BY ad";

        $searchTerm = "%{$searchTerm}%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    public function getWithWorkload() {
        $sql = "SELECT oe.*, COALESCE(SUM(d.teorik_saat + d.uygulama_saat), 0) as toplam_yuklenme
                FROM {$this->table} oe
                LEFT JOIN ders_atamalari da ON oe.ogretmen_id = da.ogretmen_id
                LEFT JOIN dersler d ON da.ders_id = d.ders_id AND d.aktif = TRUE
                WHERE oe.aktif = TRUE
                GROUP BY oe.ogretmen_id
                ORDER BY oe.ad";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getTeacherSchedule($teacherId, $termId = null) {
        $sql = "SELECT hp.*, d.ders_adi, de.derslik_adi, p.program_kodu
                FROM haftalik_program hp
                JOIN ders_atamalari da ON hp.atama_id = da.atama_id
                JOIN dersler d ON da.ders_id = d.ders_id
                JOIN derslikler de ON hp.derslik_id = de.derslik_id
                JOIN programlar p ON da.program_id = p.program_id
                WHERE da.ogretmen_id = ?";

        $params = [$teacherId];

        if ($termId) {
            $sql .= " AND da.donem_id = ?";
            $params[] = $termId;
        }

        $sql .= " ORDER BY hp.gun, hp.baslangic_saat";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function checkConflict($teacherId, $day, $startTime, $endTime, $excludeAssignmentId = null) {
        $sql = "SELECT COUNT(*) FROM haftalik_program hp
                JOIN ders_atamalari da ON hp.atama_id = da.atama_id
                WHERE da.ogretmen_id = ?
                AND hp.gun = ?
                AND hp.baslangic_saat < ?
                AND hp.bitis_saat > ?";

        $params = [$teacherId, $day, $endTime, $startTime];

        if ($excludeAssignmentId) {
            $sql .= " AND da.atama_id != ?";
            $params[] = $excludeAssignmentId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function getAvailableTeachers($day, $startTime, $endTime) {
        $sql = "SELECT oe.* FROM {$this->table} oe
                WHERE oe.aktif = TRUE
                AND oe.ogretmen_id NOT IN (
                    SELECT DISTINCT da.ogretmen_id
                    FROM haftalik_program hp
                    JOIN ders_atamalari da ON hp.atama_id = da.atama_id
                    WHERE hp.gun = ? AND hp.baslangic_saat < ? AND hp.bitis_saat > ?
                )
                ORDER BY oe.ad";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$day, $endTime, $startTime]);
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
                    COUNT(CASE WHEN unvan = 'Prof. Dr.' THEN 1 END) as professor,
                    COUNT(CASE WHEN unvan = 'Doç. Dr.' THEN 1 END) as associate_prof,
                    COUNT(CASE WHEN unvan = 'Dr.' THEN 1 END) as doctor,
                    COUNT(CASE WHEN unvan = 'Öğr. Gör.' THEN 1 END) as lecturer
                FROM {$this->table}";

        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
?>