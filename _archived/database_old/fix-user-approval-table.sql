-- Kullanıcı Onay Tabloları Düzeltmesi
-- Bu dosyayı phpMyAdmin'de çalıştırın

USE ders_programi;

-- Kullanıcı onay istekleri için tablo
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_request_type (request_type),
    INDEX idx_created_at (created_at),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Foreign key'leri ayrı ayrı ekle
ALTER TABLE user_approval_requests
ADD CONSTRAINT fk_uar_user_id
FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE;

ALTER TABLE user_approval_requests
ADD CONSTRAINT fk_uar_reviewed_by
FOREIGN KEY (reviewed_by) REFERENCES admin_users(id) ON DELETE SET NULL;

-- Kullanıcı bildirimler tablosu
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

-- Foreign key ekle
ALTER TABLE user_notifications
ADD CONSTRAINT fk_un_user_id
FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE;

-- Sistem duyurular tablosu
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

-- Foreign key ekle
ALTER TABLE system_announcements
ADD CONSTRAINT fk_sa_created_by
FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE CASCADE;

-- Sistem ayarları tablosu
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

-- Admin permissions tablosu güncelle (eğer mevcut değilse)
CREATE TABLE IF NOT EXISTS admin_permissions (
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

-- Activity log tablosu
CREATE TABLE IF NOT EXISTS admin_activity_log (
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

-- Foreign key ekle
ALTER TABLE admin_activity_log
ADD CONSTRAINT fk_aal_user_id
FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL;

-- Schedule suggestions tablosu güncelle (eğer mevcut değilse)
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Foreign key'leri ekle (eğer tablolar mevcutsa)
ALTER TABLE schedule_suggestions
ADD CONSTRAINT fk_ss_user_id
FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE;

-- Varsayılan sistem ayarları
INSERT IGNORE INTO system_settings (setting_key, setting_value, setting_type, description, is_public) VALUES
('site_name', 'Ankara Üniversitesi Nallıhan MYO Ders Programı Sistemi', 'string', 'Site adı', 1),
('site_description', 'Modern ders programı yönetim sistemi', 'string', 'Site açıklaması', 1),
('auto_approve_teachers', '0', 'boolean', 'Öğretim elemanları otomatik onaylanacak mı', 0),
('require_admin_approval', '1', 'boolean', 'Admin onayı zorunlu mu', 0),
('max_schedule_conflicts', '5', 'number', 'Maksimum program çakışma sayısı', 0),
('maintenance_mode', '0', 'boolean', 'Bakım modu aktif mi', 0),
('google_oauth_enabled', '1', 'boolean', 'Google OAuth aktif mi', 0);

-- Varsayılan duyurular (admin kullanıcısından)
INSERT IGNORE INTO system_announcements (title, content, type, priority, target_roles, created_by) VALUES
('Hoş Geldiniz!', 'Ders Programı Yönetim Sistemi''ne hoş geldiniz. Bu sistemle ders programlarınızı kolayca yönetebilirsiniz.', 'info', 'normal', '["admin", "teacher", "guest"]', 1),
('Sistem Güncellendi', 'Yeni özellikler ve iyileştirmelerle sistem güncellenmiştir. Google OAuth entegrasyonu ve yetki yönetimi eklendi.', 'success', 'high', '["admin"]', 1);

-- Varsayılan izinleri ekle
INSERT IGNORE INTO admin_permissions (role, permission_name, can_create, can_read, can_update, can_delete, description) VALUES
-- Super Admin - Tüm yetkiler
('super_admin', 'programs', 1, 1, 1, 1, 'Program yönetimi'),
('super_admin', 'teachers', 1, 1, 1, 1, 'Öğretim elemanı yönetimi'),
('super_admin', 'courses', 1, 1, 1, 1, 'Ders yönetimi'),
('super_admin', 'classrooms', 1, 1, 1, 1, 'Derslik yönetimi'),
('super_admin', 'schedules', 1, 1, 1, 1, 'Program çizelgesi yönetimi'),
('super_admin', 'assignments', 1, 1, 1, 1, 'Ders atama yönetimi'),
('super_admin', 'reports', 1, 1, 1, 1, 'Rapor oluşturma ve görüntüleme'),
('super_admin', 'users', 1, 1, 1, 1, 'Kullanıcı yönetimi'),
('super_admin', 'system', 1, 1, 1, 1, 'Sistem ayarları'),

-- Admin - Çoğu yetki
('admin', 'programs', 1, 1, 1, 0, 'Program yönetimi (silme hariç)'),
('admin', 'teachers', 1, 1, 1, 0, 'Öğretim elemanı yönetimi (silme hariç)'),
('admin', 'courses', 1, 1, 1, 1, 'Ders yönetimi'),
('admin', 'classrooms', 1, 1, 1, 1, 'Derslik yönetimi'),
('admin', 'schedules', 1, 1, 1, 1, 'Program çizelgesi yönetimi'),
('admin', 'assignments', 1, 1, 1, 1, 'Ders atama yönetimi'),
('admin', 'reports', 0, 1, 0, 0, 'Rapor görüntüleme'),
('admin', 'users', 0, 1, 0, 0, 'Kullanıcı listesi görüntüleme'),

-- Teacher - Kısıtlı yetkiler
('teacher', 'programs', 0, 1, 0, 0, 'Program görüntüleme'),
('teacher', 'teachers', 0, 1, 1, 0, 'Kendi bilgilerini düzenleme'),
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

SELECT 'Tablolar başarıyla oluşturuldu ve güncellendiǧ' as sonuc;