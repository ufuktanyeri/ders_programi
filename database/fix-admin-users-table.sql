-- Admin Users Tablosunu Güncelle
-- Bu dosyayı phpMyAdmin'de çalıştırın

USE ders_programi;

-- Role enum'unu güncelle
ALTER TABLE admin_users
MODIFY COLUMN role ENUM('super_admin', 'admin', 'teacher', 'editor', 'guest') DEFAULT 'guest';

-- Eksik sütunları ekle (eğer yoksa)
ALTER TABLE admin_users
ADD COLUMN IF NOT EXISTS approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER status,
ADD COLUMN IF NOT EXISTS approved_by INT NULL AFTER approval_status,
ADD COLUMN IF NOT EXISTS approved_at TIMESTAMP NULL AFTER approved_by,
ADD COLUMN IF NOT EXISTS rejection_reason TEXT NULL AFTER approved_at;

-- Foreign key ekle (eğer yoksa)
SELECT COUNT(*) INTO @fk_exists
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'ders_programi'
  AND TABLE_NAME = 'admin_users'
  AND CONSTRAINT_NAME = 'admin_users_approved_by_fk';

SET @sql = IF(@fk_exists = 0,
    'ALTER TABLE admin_users ADD CONSTRAINT admin_users_approved_by_fk FOREIGN KEY (approved_by) REFERENCES admin_users(id) ON DELETE SET NULL',
    'SELECT "Foreign key already exists"');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Mevcut admin kullanıcısını güncelle (eğer varsa)
UPDATE admin_users
SET approval_status = 'approved'
WHERE email = 'admin@localhost' OR role = 'super_admin';

-- Ufuk Tanyeri'yi super admin yap (eğer kayıtlı ise)
UPDATE admin_users
SET role = 'super_admin', approval_status = 'approved', status = 'active'
WHERE email LIKE '%ufuk%' AND email LIKE '%tanyeri%';

-- Sistem admin kullanıcısı ekle (eğer yoksa)
INSERT IGNORE INTO admin_users (email, name, role, status, approval_status, created_at)
VALUES ('admin@localhost', 'System Admin', 'super_admin', 'active', 'approved', NOW());

-- Tabloya index ekle (eğer yoksa)
SELECT COUNT(*) INTO @idx_exists
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = 'ders_programi'
  AND TABLE_NAME = 'admin_users'
  AND INDEX_NAME = 'idx_approval_status';

SET @sql = IF(@idx_exists = 0,
    'ALTER TABLE admin_users ADD INDEX idx_approval_status (approval_status)',
    'SELECT "Index already exists"');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Tabloyu kontrol et
SELECT 'Admin Users Table Updated Successfully' as status;

-- Mevcut kullanıcıları göster
SELECT id, email, name, role, status, approval_status, created_at
FROM admin_users
ORDER BY created_at DESC;