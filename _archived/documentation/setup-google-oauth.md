# Google OAuth Kurulum Rehberi

## 1. Google Console Kurulumu

### Google Cloud Console'da Proje Oluşturun
1. [Google Cloud Console](https://console.cloud.google.com/) adresine gidin
2. Yeni proje oluşturun veya mevcut projeyi seçin
3. "APIs & Services" > "Credentials" bölümüne gidin

### OAuth 2.0 Client ID Oluşturun
1. "CREATE CREDENTIALS" > "OAuth client ID" seçin
2. Application type: "Web application" seçin
3. Name: "Ders Programı Sistemi"
4. Authorized redirect URIs:
   - `http://localhost/ders_programi/admin/google-callback.php`
   - (Canlı sunucu için kendi domain'inizi ekleyin)

### API'leri Etkinleştirin
1. "APIs & Services" > "Library" bölümüne gidin
2. "Google+ API" veya "People API" aratın ve etkinleştirin

## 2. Konfigürasyon

### Client ID ve Secret'ı Güncelleyin
`config/google-oauth.php` dosyasını düzenleyin:

```php
define('GOOGLE_CLIENT_ID', 'YOUR_ACTUAL_CLIENT_ID');
define('GOOGLE_CLIENT_SECRET', 'YOUR_ACTUAL_CLIENT_SECRET');
```

## 3. Veritabanı Kurulumu

phpMyAdmin'de aşağıdaki SQL'i çalıştırın:

```sql
USE ders_programi;
source database/admin-users-table.sql;
```

Veya manuel olarak `database/admin-users-table.sql` dosyasını import edin.

## 4. Test

1. http://localhost/ders_programi/admin/ adresine gidin
2. "Google ile Giriş Yap" butonunu görmelisiniz
3. Butona tıklayarak Google OAuth akışını test edin

## Güvenlik Notları

- Production ortamında HTTPS kullanın
- Client Secret'ı güvenli tutun
- Sadece güvendiğiniz domain'leri redirect URI olarak ekleyin

## Sorun Giderme

- Google Console'da doğru redirect URI'nin eklendiğinden emin olun
- XAMPP'ın çalıştığından emin olun
- Browser cache'ini temizleyin
- PHP error loglarını kontrol edin