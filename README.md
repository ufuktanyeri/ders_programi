# 📚 Ders Programı Yönetim Sistemi

Ankara Üniversitesi Nallıhan Meslek Yüksekokulu için geliştirilmiş modern ders programı yönetim sistemi.

## 🔗 GitHub Repository
**Repository URL:** https://github.com/ufuktanyeri/ders_programi

## 🎯 Proje Hakkında

Bu sistem, eğitim programlarının ders programlarını otomatik olarak oluşturmak, yönetmek ve takip etmek için geliştirilmiştir. Modern MVC mimarisi ile yazılmış, güvenli ve kullanıcı dostu bir web uygulamasıdır.

### Temel Özellikler

- 📅 **Otomatik Ders Programı Oluşturma** - Akıllı algoritmalarla çakışmasız program oluşturma
- 👥 **Öğretim Elemanı Yönetimi** - Ders yükü takibi ve atama sistemi
- 🏫 **Derslik Yönetimi** - Kapasite kontrolü ve kullanım takibi
- 📊 **Raporlama** - PDF/Excel formatında program dışa aktarma
- 🔐 **Google OAuth 2.0** - Güvenli kimlik doğrulama
- 👤 **Rol Bazlı Yetkilendirme** - Admin, Teacher, Instructor, Guest rolleri
- 📱 **Responsive Tasarım** - Tüm cihazlarla uyumlu arayüz

## 🛠️ Teknoloji Stack

### Backend
- **PHP 8.x** - Custom MVC Framework
- **MySQL 5.7+** - utf8mb4_turkish_ci
- **PDO** - Database abstraction layer
- **Composer** - Dependency management (optional)

### Frontend
- **HTML5 / CSS3** - Modern web standartları
- **Bootstrap 5** - Responsive UI framework
- **jQuery** - DOM manipulation
- **JavaScript ES6+** - Modern JavaScript
- **Font Awesome** - Icon library

### DevOps
- **XAMPP** - Local development environment
- **Git** - Version control
- **GitHub Actions** - CI/CD workflow
- **VS Code** - IDE
- **Claude Code** - AI-powered development

## 📂 Proje Yapısı

```
ders_programi/
├── .claude/                    # Claude Memory System
│   ├── context/               # Proje bağlamı
│   │   ├── PROJECT_CONTEXT.md
│   │   └── PROJECT_STRUCTURE.md
│   └── memory/                # Oturum hafızası
│       ├── CURRENT_SESSION.md
│       └── TASK_TRACKER.md
├── .github/                   # GitHub Actions
│   └── workflows/
│       └── claude-sync.yml
├── app/                       # Uygulama katmanı
│   ├── Controllers/          # İstek işleyiciler
│   ├── Models/               # Veri modelleri
│   ├── Repositories/         # Database erişim
│   ├── Services/             # İş mantığı
│   ├── Views/                # Görünüm dosyaları
│   ├── Entities/             # Varlık sınıfları
│   ├── Exceptions/           # Özel hatalar
│   └── Middleware/           # Ara katmanlar
├── core/                      # Framework çekirdeği
│   ├── Router.php            # URL routing
│   ├── Controller.php        # Base controller
│   ├── Model.php             # Base model
│   └── View.php              # View renderer
├── config/                    # Yapılandırma
│   ├── database.php          # DB config
│   ├── environment.php       # Environment
│   └── google-oauth.php      # OAuth config
├── database/                  # SQL dosyaları
│   ├── final-database-setup.sql
│   └── import-teacher-schedule.sql
├── public/                    # Public assets
│   ├── assets/               # CSS, JS, images
│   └── index.php             # Entry point
└── storage/                   # Logs ve cache
```

## 🚀 Kurulum

### Gereksinimler

- PHP 8.0 veya üzeri
- MySQL 5.7 veya üzeri / SQL Server 2016+
- Apache Web Server (mod_rewrite etkin)
- XAMPP (veya benzeri) önerilir

### Adım 1: Projeyi Klonlayın

```bash
git clone https://github.com/ufuktanyeri/ders_programi.git
cd ders_programi
```

### Adım 2: Environment Ayarları

`.env.example` dosyasını kopyalayıp `.env` oluşturun:

```bash
copy .env.example .env
```

`.env` dosyasını düzenleyin:

```env
DB_HOST=localhost
DB_NAME=ders_programi
DB_USER=root
DB_PASS=

GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost/ders_programi/auth/google/callback
```

### Adım 3: Veritabanı Kurulumu

MySQL'e bağlanın ve database oluşturun:

```sql
CREATE DATABASE ders_programi CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;
```

SQL dosyalarını import edin:

```bash
mysql -u root -p ders_programi < database/final-database-setup.sql
```

### Adım 4: Web Server Ayarları

XAMPP kullanıyorsanız projeyi `C:\xampp\htdocs\` dizinine yerleştirin.

Tarayıcıda açın:
```
http://localhost/ders_programi/
```

## 🎮 Kullanım

### URL Yapısı

```
http://localhost/ders_programi/              # Ana sayfa
http://localhost/ders_programi/programs      # Program listesi
http://localhost/ders_programi/program/NBP   # Program detayı
http://localhost/ders_programi/schedules     # Ders programları
http://localhost/ders_programi/auth/login    # Giriş
```

### Roller ve Yetkiler

- **Admin** - Tüm sisteme tam erişim
- **Teacher** - Kendi derslerini görüntüleme/düzenleme
- **Instructor** - Sınırlı erişim
- **Guest** - Sadece görüntüleme

## 🔧 Claude Memory System

Bu proje Claude AI ile geliştirilmiştir ve hafıza sistemi içerir.

### Oturum Başlatma

```powershell
.\claude-start.ps1
```

### Oturum Kapatma

```powershell
.\claude-end.ps1
```

Detaylı bilgi için: [CLAUDE-SETUP.md](CLAUDE-SETUP.md)

## 📝 Geliştirme Kuralları

- **Database-First**: Tüm veriler database'den gelir, mockup kullanılmaz
- **Türkçe Yorumlar**: Tüm kodlar Türkçe açıklanmalıdır
- **PDO Prepared Statements**: SQL injection koruması zorunludur
- **Mobile-First**: Responsive tasarım ilkesi
- **Error Handling**: Try-catch blokları kullanılmalıdır

## 🤝 Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/AmazingFeature`)
3. Commit edin (`git commit -m '✨ feat: Amazing feature eklendi'`)
4. Push edin (`git push origin feature/AmazingFeature`)
5. Pull Request açın

### Commit Mesaj Formatı

```
[emoji] [tip]: [açıklama]

Örnekler:
✨ feat: kullanıcı giriş sistemi eklendi
🐛 fix: form validasyon hatası düzeltildi
📝 docs: API dokümantasyonu güncellendi
🎨 style: kod formatı düzenlendi
♻️ refactor: veritabanı bağlantısı yeniden yazıldı
```

## 📊 Proje Durumu

- ✅ Claude Memory System - Aktif
- ✅ MVC Mimarisi - Tamamlandı
- ✅ Google OAuth - Çalışıyor
- ✅ Rol Bazlı Yetkilendirme - Aktif
- ✅ Responsive Tasarım - Tamamlandı
- 🚧 PDF Export - Geliştiriliyor
- 🚧 Excel Export - Geliştiriliyor
- 🚧 Otomatik Program Oluşturma - Planlama aşamasında

## 📄 Lisans

Bu proje Ankara Üniversitesi için özel olarak geliştirilmiştir.

## 📧 İletişim

**Proje Sahibi:** Ufuk Tanyeri  
**GitHub:** https://github.com/ufuktanyeri  
**Repository:** https://github.com/ufuktanyeri/ders_programi

## 🙏 Teşekkürler

- Ankara Üniversitesi Nallıhan MYO
- Claude AI (Anthropic)
- Bootstrap ekibi
- PHP ve MySQL toplulukları

---

**Son Güncelleme:** 30 Ekim 2025  
**Versiyon:** 2.0.0
