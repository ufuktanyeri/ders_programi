<?php
namespace App\Services;

class StatisticsService {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getSystemStatistics() {
        return [
            'programs' => $this->getProgramCount(),
            'teachers' => $this->getTeacherCount(),
            'classrooms' => $this->getClassroomCount(),
            'courses' => $this->getCourseCount()
        ];
    }

    public function getProgramCount() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM programlar WHERE aktif = TRUE");
        return $stmt->fetchColumn();
    }

    public function getTeacherCount() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM ogretim_elemanlari WHERE aktif = TRUE");
        return $stmt->fetchColumn();
    }

    public function getClassroomCount() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM derslikler WHERE aktif = TRUE");
        return $stmt->fetchColumn();
    }

    public function getCourseCount() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM dersler WHERE aktif = TRUE");
        return $stmt->fetchColumn();
    }

    public function getDashboardData() {
        return [
            'stats' => $this->getSystemStatistics(),
            'active_programs' => $this->getActivePrograms(),
            'recent_activities' => $this->getRecentActivities()
        ];
    }

    private function getActivePrograms() {
        $stmt = $this->db->query("SELECT * FROM programlar WHERE aktif = TRUE ORDER BY program_kodu");
        return $stmt->fetchAll();
    }

    private function getRecentActivities() {
        // Placeholder for activity tracking
        return [];
    }
}
?>