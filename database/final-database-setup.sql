-- Final Database Setup
-- Bu dosyayı phpMyAdmin'de çalıştırın

USE ders_programi;

-- 1. Admin Users tablosunu güncelle
ALTER TABLE admin_users
MODIFY COLUMN role ENUM('super_admin', 'admin', 'instructor', 'teacher', 'editor', 'guest') DEFAULT 'guest';

-- Eksik kolonları ekle
ALTER TABLE admin_users
ADD COLUMN IF NOT EXISTS approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER status;

ALTER TABLE admin_users
ADD COLUMN IF NOT EXISTS approved_by INT NULL AFTER approval_status;

ALTER TABLE admin_users
ADD COLUMN IF NOT EXISTS approved_at TIMESTAMP NULL AFTER approved_by;

ALTER TABLE admin_users
ADD COLUMN IF NOT EXISTS rejection_reason TEXT NULL AFTER approved_at;

-- 2. Sistem admin kullanıcısını ekle
INSERT IGNORE INTO admin_users (email, name, role, status, approval_status, created_at)
VALUES ('admin@localhost', 'System Admin', 'super_admin', 'active', 'approved', NOW());

-- 3. Admin Permissions tablosu
DROP TABLE IF EXISTS admin_permissions;
CREATE TABLE admin_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('super_admin', 'admin', 'instructor', 'teacher', 'editor', 'guest') NOT NULL,
    permission_name VARCHAR(50) NOT NULL,
    can_create BOOLEAN DEFAULT FALSE,
    can_read BOOLEAN DEFAULT FALSE,
    can_update BOOLEAN DEFAULT FALSE,
    can_delete BOOLEAN DEFAULT FALSE,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_role_permission (role, permission_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 4. User Approval Requests tablosu
DROP TABLE IF EXISTS user_approval_requests;
CREATE TABLE user_approval_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_type ENUM('registration', 'role_change', 'reactivation') DEFAULT 'registration',
    current_role ENUM('super_admin', 'admin', 'instructor', 'teacher', 'editor', 'guest') NULL,
    requested_role ENUM('super_admin', 'admin', 'instructor', 'teacher', 'editor', 'guest') NOT NULL,
    justification TEXT,
    additional_info TEXT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reviewed_by INT NULL,
    reviewed_at TIMESTAMP NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_request_type (request_type),
    INDEX idx_created_at (created_at),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 5. User Notifications tablosu
DROP TABLE IF EXISTS user_notifications;
CREATE TABLE user_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(500) NULL,
    metadata TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 6. System Announcements tablosu
DROP TABLE IF EXISTS system_announcements;
CREATE TABLE system_announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    target_roles TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    start_date TIMESTAMP NULL,
    end_date TIMESTAMP NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_is_active (is_active),
    INDEX idx_type (type),
    INDEX idx_priority (priority),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 7. System Settings tablosu
DROP TABLE IF EXISTS system_settings;
CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description VARCHAR(255),
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key),
    INDEX idx_is_public (is_public)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 8. Admin Activity Log tablosu
DROP TABLE IF EXISTS admin_activity_log;
CREATE TABLE admin_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100),
    resource VARCHAR(50),
    resource_id INT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 9. Schedule Suggestions tablosu
CREATE TABLE IF NOT EXISTS schedule_suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    instructor_id INT,
    course_id INT,
    current_day INT,
    current_time_start TIME,
    current_time_end TIME,
    suggested_day INT,
    suggested_time_start TIME,
    suggested_time_end TIME,
    classroom_id INT,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 10. Foreign Key Constraints (run separately if needed)
-- User Approval Requests
ALTER TABLE user_approval_requests
ADD CONSTRAINT fk_uar_user_id FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE;

ALTER TABLE user_approval_requests
ADD CONSTRAINT fk_uar_reviewed_by FOREIGN KEY (reviewed_by) REFERENCES admin_users(id) ON DELETE SET NULL;

-- User Notifications
ALTER TABLE user_notifications
ADD CONSTRAINT fk_un_user_id FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE;

-- System Announcements
ALTER TABLE system_announcements
ADD CONSTRAINT fk_sa_created_by FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE CASCADE;

-- Activity Log
ALTER TABLE admin_activity_log
ADD CONSTRAINT fk_aal_user_id FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL;

-- Schedule Suggestions
ALTER TABLE schedule_suggestions
ADD CONSTRAINT fk_ss_user_id FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE;

-- 11. Default System Settings
INSERT IGNORE INTO system_settings (setting_key, setting_value, setting_type, description, is_public) VALUES
('site_name', 'Ankara Üniversitesi Nallıhan MYO Ders Programı Sistemi', 'string', 'Site adı', 1),
('site_description', 'Modern ders programı yönetim sistemi', 'string', 'Site açıklaması', 1),
('auto_approve_instructors', '0', 'boolean', 'Öğretim elemanları otomatik onaylanacak mı', 0),
('require_admin_approval', '1', 'boolean', 'Admin onayı zorunlu mu', 0),
('max_schedule_conflicts', '5', 'number', 'Maksimum program çakışma sayısı', 0),
('maintenance_mode', '0', 'boolean', 'Bakım modu aktif mi', 0),
('google_oauth_enabled', '1', 'boolean', 'Google OAuth aktif mi', 0);

-- 12. Default Announcements
INSERT IGNORE INTO system_announcements (title, content, type, priority, target_roles, created_by) VALUES
('Hoş Geldiniz!', 'Ders Programı Yönetim Sistemi''ne hoş geldiniz. Bu sistemle ders programlarınızı kolayca yönetebilirsiniz.', 'info', 'normal', '["admin", "instructor", "guest"]', 1),
('Sistem Güncellendi', 'Yeni özellikler ve iyileştirmelerle sistem güncellenmiştir. Google OAuth entegrasyonu ve yetki yönetimi eklendi.', 'success', 'high', '["admin"]', 1);

-- 13. Default Permissions
INSERT IGNORE INTO admin_permissions (role, permission_name, can_create, can_read, can_update, can_delete, description) VALUES
-- Super Admin - Tüm yetkiler
('super_admin', 'programs', 1, 1, 1, 1, 'Program yönetimi'),
('super_admin', 'instructors', 1, 1, 1, 1, 'Öğretim elemanı yönetimi'),
('super_admin', 'courses', 1, 1, 1, 1, 'Ders yönetimi'),
('super_admin', 'classrooms', 1, 1, 1, 1, 'Derslik yönetimi'),
('super_admin', 'schedules', 1, 1, 1, 1, 'Program çizelgesi yönetimi'),
('super_admin', 'assignments', 1, 1, 1, 1, 'Ders atama yönetimi'),
('super_admin', 'reports', 1, 1, 1, 1, 'Rapor oluşturma ve görüntüleme'),
('super_admin', 'users', 1, 1, 1, 1, 'Kullanıcı yönetimi'),
('super_admin', 'system', 1, 1, 1, 1, 'Sistem ayarları'),

-- Admin - Çoğu yetki
('admin', 'programs', 1, 1, 1, 0, 'Program yönetimi (silme hariç)'),
('admin', 'instructors', 1, 1, 1, 0, 'Öğretim elemanı yönetimi (silme hariç)'),
('admin', 'courses', 1, 1, 1, 1, 'Ders yönetimi'),
('admin', 'classrooms', 1, 1, 1, 1, 'Derslik yönetimi'),
('admin', 'schedules', 1, 1, 1, 1, 'Program çizelgesi yönetimi'),
('admin', 'assignments', 1, 1, 1, 1, 'Ders atama yönetimi'),
('admin', 'reports', 0, 1, 0, 0, 'Rapor görüntüleme'),
('admin', 'users', 0, 1, 0, 0, 'Kullanıcı listesi görüntüleme'),

-- Instructor - Kısıtlı yetkiler
('instructor', 'programs', 0, 1, 0, 0, 'Program görüntüleme'),
('instructor', 'instructors', 0, 1, 1, 0, 'Kendi bilgilerini düzenleme'),
('instructor', 'courses', 0, 1, 0, 0, 'Ders görüntüleme'),
('instructor', 'classrooms', 0, 1, 0, 0, 'Derslik görüntüleme'),
('instructor', 'schedules', 0, 1, 0, 0, 'Kendi program görüntüleme'),
('instructor', 'assignments', 0, 1, 0, 0, 'Kendi ders atamalarını görebilir'),
('instructor', 'reports', 0, 1, 0, 0, 'Kendi ders raporları'),
('instructor', 'suggestions', 1, 1, 1, 0, 'Ders değişikliği önerisi yapabilir'),

-- Teacher (alias for instructor)
('teacher', 'programs', 0, 1, 0, 0, 'Program görüntüleme'),
('teacher', 'instructors', 0, 1, 1, 0, 'Kendi bilgilerini düzenleme'),
('teacher', 'courses', 0, 1, 0, 0, 'Ders görüntüleme'),
('teacher', 'classrooms', 0, 1, 0, 0, 'Derslik görüntüleme'),
('teacher', 'schedules', 0, 1, 0, 0, 'Kendi program görüntüleme'),
('teacher', 'assignments', 0, 1, 0, 0, 'Kendi ders atamalarını görebilir'),
('teacher', 'reports', 0, 1, 0, 0, 'Kendi ders raporları'),
('teacher', 'suggestions', 1, 1, 1, 0, 'Ders değişikliği önerisi yapabilir'),

-- Guest - Sadece görüntüleme
('guest', 'programs', 0, 1, 0, 0, 'Program görüntüleme'),
('guest', 'schedules', 0, 1, 0, 0, 'Program görüntüleme'),
('guest', 'reports', 0, 1, 0, 0, 'Rapor görüntüleme');

-- 14. Admin users'taki mevcut kullanıcıları güncelle
UPDATE admin_users
SET approval_status = 'approved', role = 'super_admin'
WHERE email LIKE '%ufuk%' AND email LIKE '%tanyeri%';

UPDATE admin_users
SET approval_status = 'approved'
WHERE role = 'super_admin' OR email = 'admin@localhost';

SELECT 'Final Database Setup Completed Successfully!' as result;