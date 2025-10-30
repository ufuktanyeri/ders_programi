-- Öğretim Elemanı Ders Programı Import Script
-- templates/OgretimElemani_DersListesi.txt verisini veritabanına aktarır
-- Not: Bu script'i çalıştırmadan önce aktif dönem ID'sini kontrol edin

USE ders_programi;

-- Değişkenler ve hazırlık
SET @aktif_donem_id = 1; -- 2025-2026 Güz dönemi

-- Geçici tablo oluştur
DROP TEMPORARY TABLE IF EXISTS temp_ders_import;
CREATE TEMPORARY TABLE temp_ders_import (
    ogretmen_kisa_ad VARCHAR(10),
    gun_str VARCHAR(10),
    gun INT,
    oglen_str VARCHAR(10),
    baslangic_saat TIME,
    bitis_saat TIME,
    ders_adi VARCHAR(100),
    haftalik_saat INT
);

-- Verileri ekle (OgretimElemani_DersListesi.txt'den)

-- A.A. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('A.A.', 'PZT', 1, 'Ö.S.', '15:00', '17:00', 'Programlama Temelleri', 2),
('A.A.', 'SALI', 2, 'Ö.Ö.', '09:00', '11:00', 'Dönem Projesi', 2),
('A.A.', 'ÇRŞ', 3, 'Ö.S.', '16:00', '18:00', 'Programlama Temelleri', 2);

-- E.A. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('E.A.', 'CUMA', 5, 'Ö.S.', '13:30', '17:30', 'Sayısal Elektronik', 4),
('E.A.', 'CUMA', 5, 'Ö.Ö.', '08:30', '12:30', 'Temel Elektronik', 4),
('E.A.', 'PRŞ.', 4, 'Ö.S.', '13:30', '17:30', 'Programlanabilir Denetleyiciler', 4),
('E.A.', 'PRŞ.', 4, 'Ö.Ö.', '08:30', '12:30', 'Güç Elektroniği', 4),
('E.A.', 'PZT', 1, 'Ö.S.', '13:30', '17:30', 'Elektrik Motorları ve Sürücüleri', 4),
('E.A.', 'PZT', 1, 'Ö.Ö.', '09:00', '13:00', 'Temel Elektronik', 4),
('E.A.', 'SALI', 2, 'Ö.Ö.', '08:30', '12:30', 'Temel Elektronik', 4);

-- E.Y. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('E.Y.', 'SALI', 2, 'Ö.S.', '16:00', '18:00', 'Matematik', 2),
('E.Y.', 'SALI', 2, 'Ö.Ö.', '08:30', '11:30', 'Matematik', 3);

-- M.A. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('M.A.', 'PRŞ.', 4, 'Ö.S.', '13:30', '16:30', 'Doğru Akım Devre Analizi', 3),
('M.A.', 'PRŞ.', 4, 'Ö.Ö.', '09:00', '12:00', 'Doğru Akım Devre Analizi', 3);

-- M.D. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('M.D.', 'PZT', 1, 'Ö.S.', '13:30', '18:00', 'Atatürk İlkeleri ve İnkılap T I', 4),
('M.D.', 'PZT', 1, 'Ö.Ö.', '09:00', '12:30', 'Atatürk İlkeleri ve İnkılap T I', 4);

-- Mu.D. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('Mu.D.', 'CUMA', 5, 'Ö.S.', '13:30', '17:30', 'Elektrik Enerjisi Ürt. İlt. ve Dağ.', 4),
('Mu.D.', 'CUMA', 5, 'Ö.Ö.', '09:00', '13:00', 'Temel Enerji Teknolojileri', 4);

-- O.K.Ş. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('O.K.Ş.', 'SALI', 2, 'Ö.S.', '14:00', '17:30', 'Türk Dili I', 4),
('O.K.Ş.', 'ÇRŞ', 3, 'Ö.S.', '14:00', '17:30', 'Türk Dili I', 4);

-- S.B. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('S.B.', 'SALI', 2, 'Ö.S.', '13:30', '17:30', 'Temel Yabancı Dil', 4),
('S.B.', 'SALI', 2, 'Ö.Ö.', '09:00', '13:00', 'Temel Yabancı Dil', 4),
('S.B.', 'ÇRŞ', 3, 'Ö.S.', '13:30', '17:30', 'Temel Yabancı Dil', 4),
('S.B.', 'ÇRŞ', 3, 'Ö.Ö.', '09:00', '13:00', 'Temel Yabancı Dil', 4);

-- S.D. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('S.D.', 'PZT', 1, 'Ö.S.', '14:00', '15:00', 'Matematik', 1),
('S.D.', 'PZT', 1, 'Ö.Ö.', '08:30', '13:00', 'Matematik', 5);

-- S.E. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('S.E.', 'CUMA', 5, 'Ö.S.', '14:00', '16:00', 'Sunucu İşletim Sistemleri', 2),
('S.E.', 'CUMA', 5, 'Ö.S.', '16:00', '18:00', 'Bilgisayar Destekli Çizim', 2),
('S.E.', 'CUMA', 5, 'Ö.Ö.', '09:00', '13:00', 'Nesne Tabanlı Programlama', 4),
('S.E.', 'PRŞ.', 4, 'Ö.S.', '16:00', '18:00', 'Bilgisayar Destekli Çizim', 2),
('S.E.', 'PRŞ.', 4, 'Ö.Ö.', '09:00', '11:00', 'Bilgisayar Donanımı ve Ağ', 2),
('S.E.', 'PRŞ.', 4, 'Ö.Ö.', '11:00', '13:00', 'Grafik ve Animasyon', 2),
('S.E.', 'SALI', 2, 'Ö.S.', '13:30', '15:30', 'Bilgi ve İletişim Teknolojileri I', 2),
('S.E.', 'SALI', 2, 'Ö.S.', '15:30', '17:30', 'İşletme Yönetimi I', 2),
('S.E.', 'ÇRŞ', 3, 'Ö.S.', '13:30', '17:30', 'Bilgisayar Destekli Çizim', 4),
('S.E.', 'ÇRŞ', 3, 'Ö.Ö.', '09:00', '11:00', 'İşletme Yönetimi I', 2),
('S.E.', 'ÇRŞ', 3, 'Ö.Ö.', '11:00', '13:00', 'Bilgisayar Donanımı ve Ağ', 2);

-- T.D. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('T.D.', 'CUMA', 5, 'Ö.S.', '14:00', '18:00', 'Elektrik Elektronik Ölçme', 4),
('T.D.', 'CUMA', 5, 'Ö.Ö.', '08:30', '12:30', 'Elektrik Elektronik Ölçme', 4),
('T.D.', 'PRŞ.', 4, 'Ö.S.', '13:30', '17:30', 'Doğru Akım Devre Analizi', 4),
('T.D.', 'PRŞ.', 4, 'Ö.Ö.', '08:30', '12:30', 'Ölçme Tekniği', 4),
('T.D.', 'PZT', 1, 'Ö.Ö.', '09:30', '12:30', 'Dönem Projesi', 3);

-- U.T. Verdiği Dersler
INSERT INTO temp_ders_import VALUES
('U.T.', 'PRŞ.', 4, 'Ö.S.', '14:00', '16:00', 'Mikrobilgisayar ve Assembler', 2),
('U.T.', 'PRŞ.', 4, 'Ö.Ö.', '09:00', '13:00', 'Görüntü İşlemenin Temelleri', 4),
('U.T.', 'PZT', 1, 'Ö.S.', '15:00', '18:00', 'Beden Eğitimi', 3),
('U.T.', 'PZT', 1, 'Ö.Ö.', '08:30', '10:30', 'Bilgi ve İletişim Teknolojileri I', 2),
('U.T.', 'PZT', 1, 'Ö.Ö.', '11:30', '12:30', 'Beden Eğitimi', 1),
('U.T.', 'SALI', 2, 'Ö.S.', '13:30', '15:30', 'Mikrobilgisayar ve Assembler', 2),
('U.T.', 'SALI', 2, 'Ö.S.', '16:00', '18:00', 'Web Tasarım Temelleri', 2),
('U.T.', 'SALI', 2, 'Ö.Ö.', '08:30', '10:30', 'Mikrobilgisayar ve Assembler', 2),
('U.T.', 'SALI', 2, 'Ö.Ö.', '11:00', '13:00', 'Bilgi ve İletişim Teknolojileri I', 2),
('U.T.', 'ÇRŞ', 3, 'Ö.S.', '14:00', '18:00', 'Veri Tabanı Uygulamaları', 4),
('U.T.', 'ÇRŞ', 3, 'Ö.Ö.', '09:00', '11:00', 'Web Tasarım Temelleri', 2),
('U.T.', 'ÇRŞ', 3, 'Ö.Ö.', '11:00', '13:00', 'Mikrobilgisayar ve Assembler', 2);

-- Rapor: Import edilecek kayıt sayısı
SELECT 
    '=== IMPORT ÖNCESİ ÖZET ===' AS baslik,
    COUNT(*) AS toplam_kayit,
    COUNT(DISTINCT ogretmen_kisa_ad) AS ogretmen_sayisi,
    COUNT(DISTINCT ders_adi) AS farkli_ders_sayisi
FROM temp_ders_import;

-- Kontrol: Veritabanında bulunamayan öğretmenler
SELECT 
    '=== BULUNAMAYAN ÖĞRETMENLER ===' AS baslik,
    DISTINCT t.ogretmen_kisa_ad
FROM temp_ders_import t
LEFT JOIN ogretim_elemanlari oe ON t.ogretmen_kisa_ad = oe.kisa_ad
WHERE oe.ogretmen_id IS NULL;

-- Kontrol: Veritabanında bulunamayan dersler (benzer isimleri bul)
SELECT 
    '=== BULUNAMAYAN DERSLER ===' AS baslik,
    t.ders_adi AS import_ders_adi,
    d.ders_adi AS db_ders_adi,
    d.ders_id
FROM temp_ders_import t
LEFT JOIN dersler d ON 
    (LOWER(TRIM(t.ders_adi)) = LOWER(TRIM(d.ders_adi)) OR
     LOWER(REPLACE(t.ders_adi, ' ', '')) LIKE CONCAT('%', LOWER(REPLACE(d.ders_adi, ' ', '')), '%') OR
     LOWER(REPLACE(d.ders_adi, ' ', '')) LIKE CONCAT('%', LOWER(REPLACE(t.ders_adi, ' ', '')), '%'))
WHERE d.ders_id IS NULL
GROUP BY t.ders_adi;

-- Ders atamaları ve program ekleme
-- Bu bölüm çakışmaları kontrol eder ve sadece uygun kayıtları ekler

DELIMITER //

DROP PROCEDURE IF EXISTS import_teacher_schedule//

CREATE PROCEDURE import_teacher_schedule()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_ogretmen_kisa_ad VARCHAR(10);
    DECLARE v_gun INT;
    DECLARE v_baslangic_saat TIME;
    DECLARE v_bitis_saat TIME;
    DECLARE v_ders_adi VARCHAR(100);
    DECLARE v_ogretmen_id INT;
    DECLARE v_ders_id INT;
    DECLARE v_atama_id INT;
    DECLARE v_derslik_id INT;
    DECLARE v_success_count INT DEFAULT 0;
    DECLARE v_skip_count INT DEFAULT 0;
    DECLARE v_error_count INT DEFAULT 0;
    
    DECLARE cur CURSOR FOR 
        SELECT ogretmen_kisa_ad, gun, baslangic_saat, bitis_saat, ders_adi
        FROM temp_ders_import
        ORDER BY ogretmen_kisa_ad, gun, baslangic_saat;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO v_ogretmen_kisa_ad, v_gun, v_baslangic_saat, v_bitis_saat, v_ders_adi;
        
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Öğretmen ID'sini bul
        SELECT ogretmen_id INTO v_ogretmen_id
        FROM ogretim_elemanlari
        WHERE kisa_ad = v_ogretmen_kisa_ad
        LIMIT 1;
        
        IF v_ogretmen_id IS NULL THEN
            SET v_error_count = v_error_count + 1;
            ITERATE read_loop;
        END IF;
        
        -- Ders ID'sini bul (fuzzy matching)
        SELECT ders_id INTO v_ders_id
        FROM dersler
        WHERE 
            LOWER(TRIM(ders_adi)) = LOWER(TRIM(v_ders_adi)) OR
            LOWER(REPLACE(ders_adi, ' ', '')) LIKE CONCAT('%', LOWER(REPLACE(v_ders_adi, ' ', '')), '%') OR
            LOWER(REPLACE(v_ders_adi, ' ', '')) LIKE CONCAT('%', LOWER(REPLACE(ders_adi, ' ', '')), '%')
        LIMIT 1;
        
        IF v_ders_id IS NULL THEN
            SET v_skip_count = v_skip_count + 1;
            ITERATE read_loop;
        END IF;
        
        -- Uygun derslik bul (otomatik atama)
        SELECT derslik_id INTO v_derslik_id
        FROM derslikler
        WHERE aktif = TRUE
        AND derslik_id NOT IN (
            SELECT derslik_id FROM haftalik_program
            WHERE gun = v_gun
            AND baslangic_saat < v_bitis_saat
            AND bitis_saat > v_baslangic_saat
        )
        ORDER BY 
            CASE tur 
                WHEN 'Laboratuvar' THEN 1 
                WHEN 'Derslik' THEN 2 
                ELSE 3 
            END,
            kapasite DESC
        LIMIT 1;
        
        IF v_derslik_id IS NULL THEN
            -- Derslik bulunamadı
            SET v_skip_count = v_skip_count + 1;
            ITERATE read_loop;
        END IF;
        
        -- Ders ataması var mı kontrol et
        SELECT atama_id INTO v_atama_id
        FROM ders_atamalari
        WHERE ders_id = v_ders_id
        AND ogretmen_id = v_ogretmen_id
        AND donem_id = @aktif_donem_id
        LIMIT 1;
        
        -- Yoksa oluştur
        IF v_atama_id IS NULL THEN
            INSERT INTO ders_atamalari (ders_id, ogretmen_id, donem_id)
            VALUES (v_ders_id, v_ogretmen_id, @aktif_donem_id);
            
            SET v_atama_id = LAST_INSERT_ID();
        END IF;
        
        -- Haftalık programa ekle (çakışma yoksa)
        IF NOT EXISTS (
            SELECT 1 FROM haftalik_program hp
            JOIN ders_atamalari da ON hp.atama_id = da.atama_id
            WHERE da.ogretmen_id = v_ogretmen_id
            AND hp.gun = v_gun
            AND hp.baslangic_saat < v_bitis_saat
            AND hp.bitis_saat > v_baslangic_saat
        ) THEN
            INSERT INTO haftalik_program (atama_id, gun, baslangic_saat, bitis_saat, derslik_id)
            VALUES (v_atama_id, v_gun, v_baslangic_saat, v_bitis_saat, v_derslik_id);
            
            SET v_success_count = v_success_count + 1;
        ELSE
            SET v_skip_count = v_skip_count + 1;
        END IF;
        
    END LOOP;
    
    CLOSE cur;
    
    -- Sonuçları göster
    SELECT 
        '=== IMPORT SONUÇLARI ===' AS baslik,
        v_success_count AS basarili_kayit,
        v_skip_count AS atlanan_kayit,
        v_error_count AS hata_sayisi;
        
END//

DELIMITER ;

-- Prosedürü çalıştır
CALL import_teacher_schedule();

-- Temizlik
DROP TEMPORARY TABLE IF EXISTS temp_ders_import;
DROP PROCEDURE IF EXISTS import_teacher_schedule;

SELECT '=== IMPORT TAMAMLANDI ===' AS sonuc;
