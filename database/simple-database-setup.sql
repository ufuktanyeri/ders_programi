-- Basit Veritabanı Kurulumu
-- Bu dosyayı phpMyAdmin'de adım adım çalıştırın

USE ders_programi;

-- 1. Admin Users tablosunu güncelle
ALTER TABLE admin_users
MODIFY COLUMN role ENUM('super_admin', 'admin', 'teacher', 'editor', 'guest') DEFAULT 'guest';

-- 2. Eksik kolonları ekle
ALTER TABLE admin_users
ADD COLUMN approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER status;

ALTER TABLE admin_users
ADD COLUMN approved_by INT NULL AFTER approval_status;

ALTER TABLE admin_users
ADD COLUMN approved_at TIMESTAMP NULL AFTER approved_by;

ALTER TABLE admin_users
ADD COLUMN rejection_reason TEXT NULL AFTER approved_at;

-- 3. Sistem admin kullanıcısını ekle
INSERT IGNORE INTO admin_users (email, name, role, status, approval_status, created_at)
VALUES ('admin@localhost', 'System Admin', 'super_admin', 'active', 'approved', NOW());

-- 4. Admin Permissions tablosu
DROP TABLE IF EXISTS admin_permissions;
CREATE TABLE admin_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('super_admin', 'admin', 'teacher', 'editor', 'guest') NOT NULL,
    permission_name VARCHAR(50) NOT NULL,
    can_create BOOLEAN DEFAULT FALSE,
    can_read BOOLEAN DEFAULT FALSE,
    can_update BOOLEAN DEFAULT FALSE,
    can_delete BOOLEAN DEFAULT FALSE,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_role_permission (role, permission_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 5. User Approval Requests tablosu
DROP TABLE IF EXISTS user_approval_requests;
CREATE TABLE user_approval_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_type ENUM('registration', 'role_change', 'reactivation') DEFAULT 'registration',
    current_role ENUM('super_admin', 'admin', 'teacher', 'editor', 'guest') NULL,
    requested_role ENUM('super_admin', 'admin', 'teacher', 'editor', 'guest') NOT NULL,
    justification TEXT,
    additional_info TEXT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reviewed_by INT NULL,
    reviewed_at TIMESTAMP NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 6. User Notifications tablosu
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
    read_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 7. System Announcements tablosu
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 8. System Settings tablosu
DROP TABLE IF EXISTS system_settings;
CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description VARCHAR(255),
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 9. Admin Activity Log tablosu
DROP TABLE IF EXISTS admin_activity_log;
CREATE TABLE admin_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100),
    resource VARCHAR(50),
    resource_id INT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- 10. Schedule Suggestions tablosu (eğer yoksa)
CREATE TABLE IF NOT EXISTS schedule_suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    teacher_id INT,
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

SELECT 'Step 1: Tables created successfully' as status;