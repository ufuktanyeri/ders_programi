<?php
namespace App\Repositories;

require_once 'BaseRepository.php';
require_once '../app/Entities/User.php';

use App\Entities\User;

class UserRepository extends BaseRepository {
    protected $table = 'admin_users';
    protected $primaryKey = 'id';

    public function findByEmail($email) {
        $data = $this->findOneBy('email', $email);
        return $data ? new User($data) : null;
    }

    public function findByGoogleId($googleId) {
        $data = $this->findOneBy('google_id', $googleId);
        return $data ? new User($data) : null;
    }

    public function findByRole($role) {
        $sql = "SELECT * FROM {$this->table} WHERE role = ? AND status = 'active' ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$role]);

        $users = [];
        while ($data = $stmt->fetch()) {
            $users[] = new User($data);
        }

        return $users;
    }

    public function findPendingApprovals() {
        $sql = "SELECT * FROM {$this->table} WHERE approval_status = 'pending' ORDER BY created_at ASC";
        $stmt = $this->db->query($sql);

        $users = [];
        while ($data = $stmt->fetch()) {
            $users[] = new User($data);
        }

        return $users;
    }

    public function createUser(User $user) {
        $data = [
            'google_id' => $user->getGoogleId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'picture' => $user->getPicture(),
            'role' => $user->getRole(),
            'status' => $user->getStatus(),
            'approval_status' => $user->getApprovalStatus(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $id = $this->create($data);
        if ($id) {
            $user->setId($id);
            return $user;
        }

        return false;
    }

    public function updateUser(User $user) {
        $data = [
            'google_id' => $user->getGoogleId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'picture' => $user->getPicture(),
            'role' => $user->getRole(),
            'status' => $user->getStatus(),
            'approval_status' => $user->getApprovalStatus(),
            'approved_by' => $user->getApprovedBy(),
            'approved_at' => $user->getApprovedAt(),
            'rejection_reason' => $user->getRejectionReason(),
            'last_login' => $user->getLastLogin()
        ];

        return $this->update($user->getId(), $data);
    }

    public function updateLastLogin($id) {
        return $this->update($id, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function approve($id, $approvedBy) {
        $data = [
            'approval_status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];

        return $this->update($id, $data);
    }

    public function reject($id, $approvedBy, $reason = null) {
        $data = [
            'approval_status' => 'rejected',
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason,
            'status' => 'inactive'
        ];

        return $this->update($id, $data);
    }

    public function softDelete($id) {
        return $this->update($id, ['status' => 'deleted']);
    }

    public function search($query, $limit = 20) {
        $searchTerm = "%{$query}%";
        $sql = "SELECT * FROM {$this->table}
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

    public function emailExists($email, $excludeId = null) {
        $conditions = ['email' => $email];
        if ($excludeId) {
            // Custom query for exclude functionality
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = ? AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email, $excludeId]);
            return $stmt->fetchColumn() > 0;
        }

        return $this->count($conditions) > 0;
    }

    public function googleIdExists($googleId, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE google_id = ? AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$googleId, $excludeId]);
            return $stmt->fetchColumn() > 0;
        }

        return $this->count(['google_id' => $googleId]) > 0;
    }

    public function getStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_users,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_users,
                    COUNT(CASE WHEN approval_status = 'pending' THEN 1 END) as pending_users,
                    COUNT(CASE WHEN role = 'super_admin' THEN 1 END) as super_admins,
                    COUNT(CASE WHEN role = 'admin' THEN 1 END) as admins,
                    COUNT(CASE WHEN role = 'teacher' THEN 1 END) as teachers,
                    COUNT(CASE WHEN role = 'guest' THEN 1 END) as guests
                FROM {$this->table}";

        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    public function getRoleDistribution() {
        $sql = "SELECT role, COUNT(*) as count FROM {$this->table} WHERE status != 'deleted' GROUP BY role";
        $stmt = $this->db->query($sql);

        $distribution = [];
        while ($row = $stmt->fetch()) {
            $distribution[$row['role']] = $row['count'];
        }

        return $distribution;
    }
}
?>