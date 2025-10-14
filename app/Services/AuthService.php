<?php

namespace App\Services;

use App\Models\UserModel;
use App\Entities\User;
use PDO;

class AuthService {
    private $db;
    private $userModel;

    public function __construct($database) {
        $this->db = $database;
        $this->userModel = new UserModel($database);
    }

    /**
     * Authenticate user with email and password
     */
    public function authenticate($email, $password) {
        // For now, only basic admin authentication
        if ($email === 'admin@localhost' && $password === 'admin123') {
            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                // Create default admin user
                $user = new User([
                    'email' => $email,
                    'name' => 'System Admin',
                    'role' => 'super_admin',
                    'status' => 'active',
                    'approval_status' => 'approved'
                ]);

                $user = $this->userModel->create($user);
            }

            return $user;
        }

        return false;
    }

    /**
     * Handle Google OAuth authentication
     */
    public function authenticateWithGoogle($google_user_data) {
        $google_id = $google_user_data['id'];
        $email = $google_user_data['email'];
        $name = $google_user_data['name'];
        $picture = $google_user_data['picture'] ?? null;

        // Check if user exists
        $user = $this->userModel->findByGoogleId($google_id);

        if (!$user) {
            // Check by email as fallback
            $user = $this->userModel->findByEmail($email);
        }

        if (!$user) {
            // Create new user
            $defaultRole = $this->determineDefaultRole($email);
            $approvalStatus = ($defaultRole === 'super_admin') ? 'approved' : 'pending';

            $user = new User([
                'google_id' => $google_id,
                'email' => $email,
                'name' => $name,
                'picture' => $picture,
                'role' => $defaultRole,
                'status' => 'active',
                'approval_status' => $approvalStatus
            ]);

            $user = $this->userModel->create($user);

            // Send notification to admins if approval needed
            if ($approvalStatus === 'pending') {
                $this->notifyAdminsOfNewUser($user);
            }
        } else {
            // Update existing user
            $user->setName($name);
            $user->setPicture($picture);
            $user->setLastLogin(date('Y-m-d H:i:s'));

            $this->userModel->update($user);
        }

        return $user;
    }

    /**
     * Determine default role based on email
     */
    private function determineDefaultRole($email) {
        // Ufuk Tanyeri gets super admin
        if (stripos($email, 'ufuk') !== false && stripos($email, 'tanyeri') !== false) {
            return 'super_admin';
        }

        // University email domains get teacher role
        $universityDomains = [
            'ankara.edu.tr',
            'nallihanmyo.ankara.edu.tr'
        ];

        foreach ($universityDomains as $domain) {
            if (strpos($email, $domain) !== false) {
                return 'teacher';
            }
        }

        // Default role
        return 'guest';
    }

    /**
     * Start user session
     */
    public function startSession(User $user) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user_id'] = $user->getId();
        $_SESSION['admin_username'] = $user->getName();
        $_SESSION['admin_email'] = $user->getEmail();
        $_SESSION['admin_picture'] = $user->getPicture();
        $_SESSION['admin_role'] = $user->getRole();
        $_SESSION['login_type'] = 'session';

        // Update last login
        $this->userModel->updateLastLogin($user->getId());

        return true;
    }

    /**
     * End user session
     */
    public function endSession() {
        // Clear all session variables
        $_SESSION = [];

        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destroy the session
        session_destroy();

        return true;
    }

    /**
     * Get current authenticated user
     */
    public function getCurrentUser() {
        if (!isset($_SESSION['admin_logged_in']) || !isset($_SESSION['admin_user_id'])) {
            return null;
        }

        return $this->userModel->findById($_SESSION['admin_user_id']);
    }

    /**
     * Check if user has permission
     */
    public function hasPermission($permission, $action = 'read') {
        $user = $this->getCurrentUser();
        if (!$user || !$user->canAccess()) {
            return false;
        }

        // Super admin has all permissions
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Check permission in database
        $column = 'can_' . $action;
        $stmt = $this->db->prepare("SELECT {$column} FROM admin_permissions WHERE role = ? AND permission_name = ?");
        $stmt->execute([$user->getRole(), $permission]);
        $result = $stmt->fetchColumn();

        return (bool)$result;
    }

    /**
     * Approve user account
     */
    public function approveUser($userId, $approvedBy) {
        $user = $this->userModel->findById($userId);
        if (!$user) {
            return false;
        }

        $result = $this->userModel->approve($userId, $approvedBy);

        if ($result) {
            // Send approval notification
            $this->sendApprovalNotification($user);

            // Log activity
            $this->logActivity('user_approved', 'admin_users', $userId);
        }

        return $result;
    }

    /**
     * Reject user account
     */
    public function rejectUser($userId, $rejectedBy, $reason = null) {
        $user = $this->userModel->findById($userId);
        if (!$user) {
            return false;
        }

        $result = $this->userModel->reject($userId, $rejectedBy, $reason);

        if ($result) {
            // Send rejection notification
            $this->sendRejectionNotification($user, $reason);

            // Log activity
            $this->logActivity('user_rejected', 'admin_users', $userId);
        }

        return $result;
    }

    /**
     * Get pending approval requests
     */
    public function getPendingApprovals() {
        return $this->userModel->findPendingApprovals();
    }

    /**
     * Notify admins of new user registration
     */
    private function notifyAdminsOfNewUser(User $user) {
        $admins = $this->userModel->findByRole('super_admin');
        $admins = array_merge($admins, $this->userModel->findByRole('admin'));

        foreach ($admins as $admin) {
            $this->createNotification($admin->getId(), [
                'title' => 'Yeni Kullanıcı Onay Bekliyor',
                'message' => sprintf('Yeni kullanıcı: %s (%s) onay bekliyor.', $user->getName(), $user->getEmail()),
                'type' => 'info',
                'action_url' => '/admin/users/pending'
            ]);
        }
    }

    /**
     * Send approval notification
     */
    private function sendApprovalNotification(User $user) {
        $this->createNotification($user->getId(), [
            'title' => 'Hesabınız Onaylandı',
            'message' => 'Hesabınız başarıyla onaylanmıştır. Sistemi kullanabilirsiniz.',
            'type' => 'success',
            'action_url' => '/dashboard'
        ]);
    }

    /**
     * Send rejection notification
     */
    private function sendRejectionNotification(User $user, $reason = null) {
        $message = 'Hesap onay talebiniz reddedilmiştir.';
        if ($reason) {
            $message .= ' Sebep: ' . $reason;
        }

        $this->createNotification($user->getId(), [
            'title' => 'Hesap Onayı Reddedildi',
            'message' => $message,
            'type' => 'error'
        ]);
    }

    /**
     * Create user notification
     */
    private function createNotification($userId, $data) {
        $stmt = $this->db->prepare("INSERT INTO user_notifications (user_id, title, message, type, action_url, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([
            $userId,
            $data['title'],
            $data['message'],
            $data['type'],
            $data['action_url'] ?? null
        ]);
    }

    /**
     * Log user activity
     */
    private function logActivity($action, $resource, $resourceId = null) {
        $user = $this->getCurrentUser();
        if (!$user) {
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO admin_activity_log (user_id, action, resource, resource_id, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([
            $user->getId(),
            $action,
            $resource,
            $resourceId,
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
    }

    /**
     * Generate secure password reset token
     */
    public function generateResetToken($email) {
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            return false;
        }

        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database (you'll need a password_resets table)
        // For now, return the token
        return $token;
    }

    /**
     * Validate password reset token
     */
    public function validateResetToken($token) {
        // Implementation would check database for valid, non-expired token
        return false; // Placeholder
    }
}
?>