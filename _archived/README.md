# Arşivlenmiş Dosyalar

Bu klasör, production ortamında kullanılmayan ancak geliştirme ve referans amaçlı saklanması gereken dosyaları içerir.

## 📁 Klasör Yapısı

### `/admin_old/`
**İçerik:** Eski admin panel dosyaları (PHP)
**Neden Arşivlendi:** Proje MVC mimarisine geçti, bu dosyalar artık kullanılmıyor
- `access-denied.php`, `login.php`, `logout.php` vb.
- Yeni sistem: `app/Controllers/AuthController.php`, `app/Views/auth/`

**Silinebilir mi?** Evet, ancak referans için saklandı

---

### `/templates_old/`
**İçerik:** Şablon ve örnek dosyalar
**Neden Arşivlendi:** Import işlemi tamamlandı, production'da gereksiz
- Excel/CSV formatında ders listeleri
- Saat dilimi şablonları
- Öğretim elemanı listeleri
- PDF formatında ders programı örnekleri

**Silinebilir mi?** Import tamamlandıysa evet

---

### `/html_prototypes/`
**İçerik:** HTML prototip sayfaları
**Neden Arşivlendi:** MVC view'larına dönüştürüldü
- `course-assignment-form.html`
- `ders-programi-database.html`
- `drag-drop-schedule-editor.html`
- `weekly-schedule-templates.html`

**Silinebilir mi?** Evet, tasarım referansı olarak saklandı

---

### `/documentation/`
**İçerik:** Geliştirme dokümantasyonu
**Neden Arşivlendi:** Production ortamında gerekli değil

**PDF Dosyaları:**
- Program ders listeleri (4 program)
- Veritabanı yapısı dokümantasyonu

**Markdown Dosyaları:**
- `CLAUDE.md` - AI asistan notları
- `IIS-DEPLOYMENT-GUIDE.md` - IIS deployment rehberi
- `IMPROVEMENTS-DOCUMENTATION.md` - İyileştirme önerileri
- `MVC-MIGRATION-PLAN.md` - MVC geçiş planı
- `setup-google-oauth.md` - Google OAuth kurulum

**Silinebilir mi?** Hayır, dokümantasyon önemli - Git'te saklanmalı

---

### `/database_old/`
**İçerik:** Eski veritabanı dump ve fix dosyaları
**Neden Arşivlendi:** Yeni setup scriptleri kullanılıyor
- `dersler_202509250712.txt`
- `dump-ders_programi-202509250708.sql`
- `dump-ders_programi-202509250910.sql`
- `fix-admin-users-table.sql`
- `fix-user-approval-table.sql`

**Aktif Dosyalar:** (database/ klasöründe kalıyor)
- `final-database-setup.sql`
- `simple-database-setup.sql`
- `import-teacher-schedule.sql`
- `README-IMPORT.md`

**Silinebilir mi?** Backup alındıysa evet

---

### `/development_files/`
**İçerik:** Geliştirme araçları ve yapılandırma dosyaları
- `phpstan.neon` - PHPStan yapılandırması (kod analizi)
- `client_secret_...json` - Google OAuth credentials

**Neden Arşivlendi:**
- PHPStan development dependency olarak `composer.json`'da tanımlı
- Google OAuth credentials `.env` dosyasına taşınmalı (güvenlik)

**Silinebilir mi?** 
- `phpstan.neon` - Evet, composer.json'da tanımlı
- `client_secret_...json` - ⚠️ HAYIR, backup olarak sakla veya güvenli yere taşı

---

## ⚠️ Önemli Notlar

### Güvenlik
1. **Google OAuth Credentials**: `client_secret_...json` dosyası hassas bilgi içerir
   - Production'da `.env` dosyasında saklanmalı
   - Bu dosyayı Git'e commitlememeye dikkat edin
   - `.gitignore` dosyasına ekleyin: `client_secret_*.json`

### Yedekleme
2. Arşivlenen dosyaları silmeden önce:
   - Git repository'ye commit yapıldığından emin olun
   - Harici bir backup alın
   - Veritabanı dump'larını güvenli bir yerde saklayın

### Temizlik
3. Production deployment öncesi:
   - `_archived/` klasörünü production sunucusuna yüklemeyin
   - `.gitignore` dosyasına `_archived/` ekleyin (opsiyonel)
   - Veya Git'te saklayıp production'da hariç tutun

---

## 🗑️ Silme Kılavuzu

### Hemen Silinebilir:
```bash
# HTML prototipler
rm -rf _archived/html_prototypes/

# Eski admin dosyaları (MVC'ye geçildiyse)
rm -rf _archived/admin_old/

# Template dosyaları (import tamamlandıysa)
rm -rf _archived/templates_old/
```

### Backup Sonrası Silinebilir:
```bash
# Database dump'ları (yedek alındıysa)
rm -rf _archived/database_old/
```

### Asla Silinmemeli:
```bash
# Dokümantasyon dosyaları
# _archived/documentation/ - GIT'te saklanmalı

# Google OAuth credentials
# _archived/development_files/client_secret_*.json - Güvenli yere taşınmalı
```

---

## 📝 Arşivleme Tarihi

**Tarih:** 30 Ekim 2025
**Sebep:** MVC mimarisi geçişi ve production hazırlığı
**Toplam Boyut:** ~50 MB (PDF dosyaları dahil)

## ✅ Sonraki Adımlar

1. `.gitignore` dosyasını güncelleyin
2. `.env` dosyası oluşturun (Google OAuth credentials için)
3. Production deployment öncesi `_archived/` klasörünü hariç tutun
4. Composer autoload yapılandırmasını tamamlayın
5. Kod iyileştirmelerini uygulayın
