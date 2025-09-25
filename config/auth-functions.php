<?php
/**
 * Admin Yetkilendirme Fonksiyonları
 */

function getCurrentUser($db) {
    if (!isset($_SESSION['admin_logged_in']) || !isset($_SESSION['admin_user_id'])) {
        return null;
    }

    $stmt = $db->prepare("SELECT * FROM admin_users WHERE id = ? AND status = 'active'");
    $stmt->execute([$_SESSION['admin_user_id']]);
    return $stmt->fetch();
}

function hasPermission($db, $permission, $action = 'read') {
    $user = getCurrentUser($db);
    if (!$user) {
        return false;
    }

    // Super admin her şeyi yapabilir
    if ($user['role'] === 'super_admin') {
        return true;
    }

    // Yetki kontrolü
    $column = 'can_' . $action;
    $stmt = $db->prepare("SELECT $column FROM admin_permissions WHERE role = ? AND permission_name = ?");
    $stmt->execute([$user['role'], $permission]);
    $result = $stmt->fetchColumn();

    return (bool)$result;
}

function requirePermission($db, $permission, $action = 'read') {
    if (!hasPermission($db, $permission, $action)) {
        header('HTTP/1.0 403 Forbidden');
        include 'access-denied.php';
        exit;
    }
}

function getUserRole($db) {
    $user = getCurrentUser($db);
    return $user ? $user['role'] : 'guest';
}

function logActivity($db, $action, $resource, $resource_id = null) {
    $user = getCurrentUser($db);
    if (!$user) return;

    $stmt = $db->prepare("INSERT INTO admin_activity_log (user_id, action, resource, resource_id, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $user['id'],
        $action,
        $resource,
        $resource_id,
        $_SERVER['REMOTE_ADDR'] ?? '',
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
}

function isAdmin($db) {
    $role = getUserRole($db);
    return in_array($role, ['super_admin', 'admin']);
}

function isSuperAdmin($db) {
    return getUserRole($db) === 'super_admin';
}

function canCreate($db, $resource) {
    return hasPermission($db, $resource, 'create');
}

function canUpdate($db, $resource) {
    return hasPermission($db, $resource, 'update');
}

function canDelete($db, $resource) {
    return hasPermission($db, $resource, 'delete');
}

function getRoleDisplayName($role) {
    $roles = [
        'super_admin' => 'Süper Admin',
        'admin' => 'Admin',
        'editor' => 'Editör',
        'guest' => 'Misafir'
    ];

    return $roles[$role] ?? 'Bilinmeyen';
}

function getRoleBadgeClass($role) {
    $classes = [
        'super_admin' => 'bg-danger',
        'admin' => 'bg-primary',
        'editor' => 'bg-warning',
        'guest' => 'bg-secondary'
    ];

    return $classes[$role] ?? 'bg-secondary';
}

function getMyTeacherId($db) {
    $user = getCurrentUser($db);
    if (!$user) return null;

    // Kullanıcının email'i ile öğretim elemanı tablosundaki email'i eşleştir
    $stmt = $db->prepare("SELECT ogretmen_id FROM ogretim_elemanlari WHERE email = ?");
    $stmt->execute([$user['email']]);
    return $stmt->fetchColumn();
}

function getMySchedules($db) {
    $teacherId = getMyTeacherId($db);
    if (!$teacherId) return [];

    $sql = "SELECT hp.*, d.ders_adi, dr.derslik_adi_tr, p.program_adi
            FROM haftalik_program hp
            JOIN ders_atamalari da ON hp.atama_id = da.atama_id
            JOIN dersler d ON da.ders_id = d.ders_id
            JOIN derslikler dr ON hp.derslik_id = dr.derslik_id
            JOIN programlar p ON d.program_id = p.program_id
            WHERE da.ogretmen_id = ?
            ORDER BY hp.gun, hp.baslangic_saat";

    $stmt = $db->prepare($sql);
    $stmt->execute([$teacherId]);
    return $stmt->fetchAll();
}

function canAccessTeacherData($db, $targetTeacherId) {
    $user = getCurrentUser($db);
    if (!$user) return false;

    // Super admin ve admin her şeyi görebilir
    if (in_array($user['role'], ['super_admin', 'admin'])) {
        return true;
    }

    // Guest sadece kendi bilgilerini görebilir
    if ($user['role'] === 'guest') {
        return getMyTeacherId($db) == $targetTeacherId;
    }

    return false;
}
?>