# 🎯 PROJE BAĞLAMI

## Proje Bilgileri
- **Proje Adı**: Ders Programı Yönetim Sistemi
- **Kurum**: Ankara Üniversitesi - Nallıhan Meslek Yüksekokulu
- **Başlangıç Tarihi**: 2024
- **Son Güncelleme**: 30 Ekim 2025
- **Versiyon**: 2.0.0 (MVC Refactored)
- **GitHub**: https://github.com/ufuktanyeri/ders_programi

## Teknoloji Stack'i
- **Frontend**: HTML5, CSS3, Bootstrap 5, jQuery, JavaScript ES6+, Font Awesome
- **Backend**: PHP 8.x (Custom MVC Framework)
- **Database**: MySQL 5.7+ (utf8mb4_turkish_ci)
- **Geliştirme Ortamı**: XAMPP, VS Code, Claude Code
- **Versiyon Kontrol**: Git
- **Web Server**: Apache (XAMPP) / IIS destekli

## Proje Mimarisi
- **Pattern**: Model-View-Controller (MVC)
- **Routing**: Custom Router (routes.php)
- **Dependency Injection**: Container pattern
- **Authentication**: Google OAuth 2.0 + Session-based
- **Authorization**: Role-based (Admin, Teacher, Instructor, Guest)
- **Database**: Repository pattern with PDO

## Proje Hedefleri
1. Ders programlarının otomatik oluşturulması ve yönetimi
2. Öğretim elemanları için ders yükü takibi
3. Derslik ve zaman çakışma kontrolü
4. Google OAuth ile güvenli kimlik doğrulama
5. Rol bazlı yetkilendirme sistemi
6. PDF/Excel formatında program dışa aktarma
7. Responsive ve modern kullanıcı arayüzü

## Önemli Kurallar ve Tercihler
- **Database-first yaklaşım**: Tüm veriler database'den gelir, mockup veri kullanılmaz
- **Türkçe yorumlar**: Tüm kodlar Türkçe yorumlarla açıklanmalı
- **Mobile-first responsive**: Bootstrap 5 ile responsive tasarım
- **Modüler yapı**: Service, Repository, Controller ayrımı
- **Error handling**: Try-catch blokları ve custom exception'lar zorunlu
- **Security**: SQL injection koruması (PDO prepared statements)
- **Session management**: Güvenli session yönetimi
- **Code style**: PSR-4 autoloading standardı

## Kritik Bilgiler
- **Environment**: .env dosyasında (veritabanı, Google OAuth keys)
- **Database bağlantı**: config/database.php
- **Google OAuth**: config/google-oauth.php
- **Helper functions**: config/helpers.php
- **Routes**: routes.php (tüm URL tanımları burada)
- **Views**: app/Views/ (Blade-style olmayan, klasik PHP templates)

## Dizin Yapısı
```
ders_programi/
├── .claude/              # Claude hafıza sistemi
├── app/                  # Uygulama katmanı
│   ├── Controllers/      # İstek işleyiciler
│   ├── Models/          # Veri modelleri
│   ├── Repositories/    # Database erişim katmanı
│   ├── Services/        # İş mantığı
│   ├── Views/           # Görünüm dosyaları
│   ├── Entities/        # Varlık sınıfları
│   ├── Exceptions/      # Özel hata sınıfları
│   └── Middleware/      # Middleware'ler
├── core/                # Framework çekirdeği
├── config/              # Yapılandırma dosyaları
├── database/            # SQL dosyaları
├── public/              # Erişilebilir dosyalar
│   └── assets/          # CSS, JS, images
└── storage/             # Log ve cache
```

## Roller ve Yetkiler
1. **Admin**: Tam yetki (tüm CRUD işlemleri)
2. **Teacher**: Kendi ders programlarını görüntüleme ve düzenleme
3. **Instructor**: Sınırlı erişim
4. **Guest**: Sadece program görüntüleme

## Veritabanı Tabloları (Ana)
- `admin_users` - Sistem kullanıcıları ve rolleri
- `programlar` - Eğitim programları
- `ogretim_elemanlari` - Öğretim elemanları
- `dersler` - Ders tanımları
- `derslikler` - Derslik/sınıf bilgileri
- `ders_programi` - Haftalık ders programı
- `akademik_donemler` - Akademik dönem bilgileri
- `user_approvals` - Kullanıcı onay sistemi

## Aktif Özellikler
- ✅ Google OAuth 2.0 girişi
- ✅ Rol bazlı dashboard'lar
- ✅ Responsive tasarım
- ✅ Session yönetimi
- ✅ Error handling
- ✅ Repository pattern
- ✅ Service layer

## Geliştirilmekte Olan Özellikler
- 🚧 Ders programı otomatik oluşturma
- 🚧 Çakışma kontrolü algoritması
- 🚧 PDF/Excel export
- 🚧 Raporlama sistemi
- 🚧 E-posta bildirimleri

## Önemli Notlar
- ⚠️ Mockup veriler kullanılmamalı, tüm veriler database'den gelmelidir
- ⚠️ Tüm route'lar routes.php dosyasında tanımlanmalıdır
- ⚠️ View dosyaları layout sistemi kullanır (app.php, modern.php)
- ⚠️ Google OAuth için .env dosyası gereklidir
- 💡 Composer autoloading henüz aktif değil, manuel require kullanılıyor
