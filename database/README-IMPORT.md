# Öğretim Elemanı Ders Programı Import Rehberi

## 📋 Genel Bakış

`import-teacher-schedule.sql` script'i, `templates/OgretimElemani_DersListesi.txt` dosyasındaki ders programı verilerini veritabanına otomatik olarak aktarır.

## 🚀 Kullanım

### 1. phpMyAdmin ile İmport

1. XAMPP'de MySQL ve Apache'yi başlatın
2. Tarayıcıda `http://localhost/phpmyadmin` adresini açın
3. Sol menüden `ders_programi` veritabanını seçin
4. Üst menüden **SQL** sekmesine tıklayın
5. `import-teacher-schedule.sql` dosyasının içeriğini kopyalayıp yapıştırın veya dosyayı yükleyin
6. **Git** (Go) butonuna tıklayın

### 2. MySQL Komut Satırı ile Import

```bash
cd c:/xampp/htdocs/ders_programi/database
mysql -u root -p ders_programi < import-teacher-schedule.sql
```

## 📊 Script Ne Yapar?

### Adımlar:

1. **Geçici Tablo Oluşturur**: Import edilecek veriler için `temp_ders_import` tablosu
2. **Veri Kontrolü**: Öğretmen ve ders eşleştirmelerini kontrol eder
3. **Fuzzy Matching**: Ders adlarındaki küçük farklılıkları tolere eder
4. **Otomatik Derslik Atama**: Müsait derslikleri otomatik bulur
5. **Çakışma Kontrolü**: Öğretmen ve derslik çakışmalarını engeller
6. **Ders Atamaları**: `ders_atamalari` tablosuna kayıtları ekler
7. **Haftalık Program**: `haftalik_program` tablosuna zaman dilimlerini ekler

### Raporlar:

Script çalıştırıldığında şu bilgileri gösterir:
- ✅ Import öncesi özet (toplam kayıt, öğretmen ve ders sayısı)
- ⚠️ Bulunamayan öğretmenler
- ⚠️ Bulunamayan dersler
- 📈 Import sonuçları (başarılı, atlanan, hatalı kayıtlar)

## ⚠️ Önemli Notlar

1. **Aktif Dönem**: Script varsayılan olarak dönem ID=1 kullanır (2025-2026 Güz)
   - Değiştirmek için script'teki `SET @aktif_donem_id = 1;` satırını düzenleyin

2. **Veritabanı Hazırlığı**: Bu script'i çalıştırmadan önce:
   - `final-database-setup.sql` veya `simple-database-setup.sql` çalıştırılmış olmalı
   - `ogretim_elemanlari` ve `dersler` tabloları dolu olmalı
   - `derslikler` tablosunda kayıtlar olmalı

3. **Çakışmalar**: Eğer öğretmen veya derslik çakışması varsa, o kayıt atlanır

4. **Fuzzy Matching**: Ders adlarında küçük farklılıklar tolere edilir:
   - "Atatürk İlkeleri ve İnkılap T I" → "Atatürk İlkeleri Ve İnkılap Tarihi I"
   - "Elektrik Enerjisi Ürt. İlt. ve Dağ." → "Elektrik Enerjisi Üretim İletim Ve Dağıtımı"

## 🔄 Tekrar Çalıştırma

Script güvenli bir şekilde birden fazla çalıştırılabilir:
- Mevcut ders atamaları korunur
- Sadece yeni kayıtlar eklenir
- Çakışmalar önlenir

## 🐛 Sorun Giderme

### Problem: "Bulunamayan Öğretmenler" hatası
**Çözüm**: `ogretim_elemanlari` tablosunda ilgili öğretmenin `kisa_ad` kolonunu kontrol edin

### Problem: "Bulunamayan Dersler" hatası
**Çözüm**: Script benzer isimleri bulacaktır, ancak çok farklı isimler varsa `dersler` tablosunu kontrol edin

### Problem: Tüm kayıtlar "atlanan" olarak gösteriliyor
**Çözüm**: 
- Derslik çakışması olabilir - `derslikler` tablosunda yeterli derslik olduğundan emin olun
- Öğretmen çakışması olabilir - mevcut program kayıtlarını kontrol edin

## 📝 Örnek Çıktı

```
=== IMPORT ÖNCESİ ÖZET ===
toplam_kayit: 62
ogretmen_sayisi: 12
farkli_ders_sayisi: 38

=== BULUNAMAYAN ÖĞRETMENLER ===
(Boş - Tüm öğretmenler bulundu)

=== BULUNAMAYAN DERSLER ===
import_ders_adi                              | db_ders_adi
--------------------------------------------|------------------
Programlanabilir Denetleyiciler             | NULL
Güç Elektroniği                             | NULL
(Liste devam eder...)

=== IMPORT SONUÇLARI ===
basarili_kayit: 45
atlanan_kayit: 12
hata_sayisi: 5

=== IMPORT TAMAMLANDI ===
```

## 📞 Destek

Sorun yaşarsanız:
1. Script çıktısındaki hata mesajlarını kontrol edin
2. Veritabanı tablolarının doğru oluşturulduğundan emin olun
3. XAMPP/MySQL error log'larını kontrol edin
