<?php

namespace App\Models;

use App\Entities\User;
use PDO;

class UserModel {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Create
    public function create(User $user) {
        $sql = "INSERT INTO admin_users (google_id, email, name, picture, role, status, approval_status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $user->getGoogleId(),
            $user->getEmail(),
            $user->getName(),
            $user->getPicture(),
            $user->getRole(),
            $user->getStatus(),
            $user->getApprovalStatus()
        ]);

        if ($result) {
            $user->setId($this->db->lastInsertId());
            return $user;
        }

        return false;
    }

    // Read
    public function findById($id) {
        $sql = "SELECT * FROM admin_users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $data = $stmt->fetch();
        return $data ? new User($data) : null;
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM admin_users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        $data = $stmt->fetch();
        return $data ? new User($data) : null;
    }

    public function findByGoogleId($google_id) {
        $sql = "SELECT * FROM admin_users WHERE google_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$google_id]);

        $data = $stmt->fetch();
        return $data ? new User($data) : null;
    }

    public function findAll($limit = null, $offset = null) {
        $sql = "SELECT * FROM admin_users ORDER BY created_at DESC";

        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
            if ($offset) {
                $sql .= " OFFSET " . intval($offset);
            }
        }

        $stmt = $this->db->query($sql);
        $users = [];

        while ($data = $stmt->fetch()) {
            $users[] = new User($data);
        }

        return $users;
    }

    public function findByRole($role) {
        $sql = "SELECT * FROM admin_users WHERE role = ? AND status = 'active' ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$role]);

        $users = [];
        while ($data = $stmt->fetch()) {
            $users[] = new User($data);
        }

        return $users;
    }

    public function findPendingApprovals() {
        $sql = "SELECT * FROM admin_users WHERE approval_status = 'pending' ORDER BY created_at ASC";
        $stmt = $this->db->query($sql);

        $users = [];
        while ($data = $stmt->fetch()) {
            $users[] = new User($data);
        }

        return $users;
    }

    // Update
    public function update(User $user) {
        $sql = "UPDATE admin_users
                SET google_id = ?, email = ?, name = ?, picture = ?, role = ?, status = ?,
                    approval_status = ?, approved_by = ?, approved_at = ?, rejection_reason = ?,
                    last_login = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $user->getGoogleId(),
            $user->getEmail(),
            $user->getName(),
            $user->getPicture(),
            $user->getRole(),
            $user->getStatus(),
            $user->getApprovalStatus(),
            $user->getApprovedBy(),
            $user->getApprovedAt(),
            $user->getRejectionReason(),
            $user->getLastLogin(),
            $user->getId()
        ]);
    }

    public function updateLastLogin($id) {
        $sql = "UPDATE admin_users SET last_login = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function approve($id, $approved_by) {
        $sql = "UPDATE admin_users
                SET approval_status = 'approved', approved_by = ?, approved_at = NOW(), status = 'active'
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$approved_by, $id]);
    }

    public function reject($id, $approved_by, $reason = null) {
        $sql = "UPDATE admin_users
                SET approval_status = 'rejected', approved_by = ?, approved_at = NOW(),
                    rejection_reason = ?, status = 'inactive'
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$approved_by, $reason, $id]);
    }

    // Delete
    public function delete($id) {
        // Soft delete
        $sql = "UPDATE admin_users SET status = 'deleted' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function hardDelete($id) {
        // Hard delete (permanent)
        $sql = "DELETE FROM admin_users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Statistics
    public function getUserStats() {
        $sql = "SELECT
                    COUNT(*) as total_users,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_users,
                    COUNT(CASE WHEN approval_status = 'pending' THEN 1 END) as pending_users,
                    COUNT(CASE WHEN role = 'super_admin' THEN 1 END) as super_admins,
                    COUNT(CASE WHEN role = 'admin' THEN 1 END) as admins,
                    COUNT(CASE WHEN role = 'teacher' THEN 1 END) as teachers,
                    COUNT(CASE WHEN role = 'guest' THEN 1 END) as guests
                FROM admin_users";

        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    public function getRoleDistribution() {
        $sql = "SELECT role, COUNT(*) as count FROM admin_users WHERE status != 'deleted' GROUP BY role";
        $stmt = $this->db->query($sql);

        $distribution = [];
        while ($row = $stmt->fetch()) {
            $distribution[$row['role']] = $row['count'];
        }

        return $distribution;
    }

    // Search
    public function search($query, $limit = 20) {
        $searchTerm = "%{$query}%";
        $sql = "SELECT * FROM admin_users
                WHERE (name LIKE ? OR email LIKE ?) AND status != 'deleted'
                ORDER BY name
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $limit]);

        $users = [];
        while ($data = $stmt->fetch()) {
            $users[] = new User($data);
        }

        return $users;
    }

    // Validation helpers
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM admin_users WHERE email = ?";
        $params = [$email];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }

    public function googleIdExists($google_id, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM admin_users WHERE google_id = ?";
        $params = [$google_id];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }
}
?>