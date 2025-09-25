<?php

namespace App\Entities;

class User {
    private $id;
    private $google_id;
    private $email;
    private $name;
    private $picture;
    private $role;
    private $status;
    private $approval_status;
    private $approved_by;
    private $approved_at;
    private $rejection_reason;
    private $created_at;
    private $last_login;

    // Constructor
    public function __construct($data = []) {
        if ($data) {
            $this->fillFromArray($data);
        }
    }

    // Fill entity from database array
    public function fillFromArray($data) {
        $this->id = $data['id'] ?? null;
        $this->google_id = $data['google_id'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->picture = $data['picture'] ?? null;
        $this->role = $data['role'] ?? 'guest';
        $this->status = $data['status'] ?? 'active';
        $this->approval_status = $data['approval_status'] ?? 'pending';
        $this->approved_by = $data['approved_by'] ?? null;
        $this->approved_at = $data['approved_at'] ?? null;
        $this->rejection_reason = $data['rejection_reason'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->last_login = $data['last_login'] ?? null;
    }

    // Convert to array
    public function toArray() {
        return [
            'id' => $this->id,
            'google_id' => $this->google_id,
            'email' => $this->email,
            'name' => $this->name,
            'picture' => $this->picture,
            'role' => $this->role,
            'status' => $this->status,
            'approval_status' => $this->approval_status,
            'approved_by' => $this->approved_by,
            'approved_at' => $this->approved_at,
            'rejection_reason' => $this->rejection_reason,
            'created_at' => $this->created_at,
            'last_login' => $this->last_login
        ];
    }

    // Getters
    public function getId() { return $this->id; }
    public function getGoogleId() { return $this->google_id; }
    public function getEmail() { return $this->email; }
    public function getName() { return $this->name; }
    public function getPicture() { return $this->picture; }
    public function getRole() { return $this->role; }
    public function getStatus() { return $this->status; }
    public function getApprovalStatus() { return $this->approval_status; }
    public function getApprovedBy() { return $this->approved_by; }
    public function getApprovedAt() { return $this->approved_at; }
    public function getRejectionReason() { return $this->rejection_reason; }
    public function getCreatedAt() { return $this->created_at; }
    public function getLastLogin() { return $this->last_login; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setGoogleId($google_id) { $this->google_id = $google_id; }
    public function setEmail($email) { $this->email = $email; }
    public function setName($name) { $this->name = $name; }
    public function setPicture($picture) { $this->picture = $picture; }
    public function setRole($role) { $this->role = $role; }
    public function setStatus($status) { $this->status = $status; }
    public function setApprovalStatus($approval_status) { $this->approval_status = $approval_status; }
    public function setApprovedBy($approved_by) { $this->approved_by = $approved_by; }
    public function setApprovedAt($approved_at) { $this->approved_at = $approved_at; }
    public function setRejectionReason($rejection_reason) { $this->rejection_reason = $rejection_reason; }
    public function setLastLogin($last_login) { $this->last_login = $last_login; }

    // Business Logic Methods
    public function isActive() {
        return $this->status === 'active';
    }

    public function isApproved() {
        return $this->approval_status === 'approved';
    }

    public function isPending() {
        return $this->approval_status === 'pending';
    }

    public function isRejected() {
        return $this->approval_status === 'rejected';
    }

    public function isSuperAdmin() {
        return $this->role === 'super_admin';
    }

    public function isAdmin() {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isTeacher() {
        return $this->role === 'teacher';
    }

    public function isGuest() {
        return $this->role === 'guest';
    }

    public function canAccess() {
        return $this->isActive() && $this->isApproved();
    }

    public function getRoleDisplayName() {
        $roles = [
            'super_admin' => 'Süper Admin',
            'admin' => 'Admin',
            'teacher' => 'Öğretim Elemanı',
            'editor' => 'Editör',
            'guest' => 'Misafir'
        ];

        return $roles[$this->role] ?? 'Bilinmeyen';
    }

    public function getRoleBadgeClass() {
        $classes = [
            'super_admin' => 'bg-danger',
            'admin' => 'bg-primary',
            'teacher' => 'bg-success',
            'editor' => 'bg-warning',
            'guest' => 'bg-secondary'
        ];

        return $classes[$this->role] ?? 'bg-secondary';
    }

    public function getStatusDisplayName() {
        $statuses = [
            'active' => 'Aktif',
            'inactive' => 'Pasif',
            'banned' => 'Yasaklı',
            'deleted' => 'Silinmiş'
        ];

        return $statuses[$this->status] ?? 'Bilinmeyen';
    }

    public function getApprovalStatusDisplayName() {
        $statuses = [
            'pending' => 'Onay Bekliyor',
            'approved' => 'Onaylandı',
            'rejected' => 'Reddedildi'
        ];

        return $statuses[$this->approval_status] ?? 'Bilinmeyen';
    }

    public function hasProfilePicture() {
        return !empty($this->picture);
    }

    public function getInitials() {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
                if (strlen($initials) >= 2) break;
            }
        }

        return $initials ?: 'U';
    }

    // Validation
    public function validate() {
        $errors = [];

        if (empty($this->email)) {
            $errors[] = 'Email adresi gereklidir.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Geçerli bir email adresi giriniz.';
        }

        if (empty($this->name)) {
            $errors[] = 'İsim gereklidir.';
        } elseif (strlen($this->name) < 2) {
            $errors[] = 'İsim en az 2 karakter olmalıdır.';
        }

        if (!in_array($this->role, ['super_admin', 'admin', 'teacher', 'editor', 'guest'])) {
            $errors[] = 'Geçersiz rol.';
        }

        if (!in_array($this->status, ['active', 'inactive', 'banned', 'deleted'])) {
            $errors[] = 'Geçersiz durum.';
        }

        return $errors;
    }
}
?>