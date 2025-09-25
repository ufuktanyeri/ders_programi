<?php
namespace App\Services;

class AcademicTermService {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getActiveTerm() {
        $stmt = $this->db->query("SELECT * FROM akademik_donemler WHERE aktif = TRUE LIMIT 1");
        return $stmt->fetch();
    }

    public function getAllTerms() {
        $stmt = $this->db->query("SELECT * FROM akademik_donemler ORDER BY baslangic_tarihi DESC");
        return $stmt->fetchAll();
    }

    public function getTermById($id) {
        $stmt = $this->db->prepare("SELECT * FROM akademik_donemler WHERE donem_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createTerm($data) {
        $sql = "INSERT INTO akademik_donemler (donem_adi, baslangic_tarihi, bitis_tarihi, aktif)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['donem_adi'],
            $data['baslangic_tarihi'],
            $data['bitis_tarihi'],
            $data['aktif'] ?? false
        ]);
    }

    public function setActiveTerm($termId) {
        // First deactivate all terms
        $this->db->query("UPDATE akademik_donemler SET aktif = FALSE");

        // Then activate the selected term
        $stmt = $this->db->prepare("UPDATE akademik_donemler SET aktif = TRUE WHERE donem_id = ?");
        return $stmt->execute([$termId]);
    }

    public function updateTerm($id, $data) {
        $sql = "UPDATE akademik_donemler SET
                donem_adi = ?, baslangic_tarihi = ?, bitis_tarihi = ?
                WHERE donem_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['donem_adi'],
            $data['baslangic_tarihi'],
            $data['bitis_tarihi'],
            $id
        ]);
    }

    public function deleteTerm($id) {
        // Check if term has any assignments
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ders_atamalari WHERE donem_id = ?");
        $stmt->execute([$id]);
        $assignmentCount = $stmt->fetchColumn();

        if ($assignmentCount > 0) {
            throw new \Exception("Bu döneme ait ders atamaları bulunduğu için silinemez.");
        }

        $stmt = $this->db->prepare("DELETE FROM akademik_donemler WHERE donem_id = ?");
        return $stmt->execute([$id]);
    }
}
?>