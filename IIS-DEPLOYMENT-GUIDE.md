# IIS Deployment Guide - Ders Programı

## Problem Analizi

Production ortamında IIS'e taşındıktan sonra **404 Not Found** hataları alınıyordu.

### Temel Sorun

-   Apache için yazılmış `.htaccess` dosyaları mevcut
-   IIS, `.htaccess` dosyalarını okumaz - `web.config` dosyaları kullanır
-   URL rewriting kuralları IIS'e doğru şekilde aktarılmamış

## Çözüm: Web.config Dosyaları

İki adet `web.config` dosyası oluşturuldu:

### 1. Root web.config (/)

**Konum:** `c:\xampp\htdocs\ders_programi\web.config`

**Görevi:**

-   Tüm istekleri `public/` klasörüne yönlendirir
-   Hassas dizinleri (app, config, core, database, vb.) korur
-   Güvenlik dosyalarına erişimi engeller (.env, .log, .sql, vb.)

### 2. Public web.config (/public)

**Konum:** `c:\xampp\htdocs\ders_programi\public\web.config`

**Görevi:**

-   Clean URL routing (index.php'yi gizler)
-   Statik dosyaları ve assets'leri doğru serve eder
-   Güvenlik başlıkları ekler
-   Default document olarak index.php belirler

## IIS Kurulum Adımları

### 1. URL Rewrite Module Kurulumu

IIS'de URL rewriting çalışması için gerekli:

```
https://www.iis.net/downloads/microsoft/url-rewrite
```

**Kontrol:**

```powershell
Get-WindowsFeature Web-Url-Rewrite
```

### 2. IIS Site Yapılandırması

#### ÖNERĐLEN YÖNTEM (Production için en iyi)

**Web root'u doğrudan `public` klasörüne işaret ettirin:**

1. IIS Manager'ı açın
2. Sites > Add Website
3. **Physical path:** `c:\xampp\htdocs\ders_programi\public`
4. **Binding:** Port 80 (veya 443 SSL için)
5. Application Pool: PHP için uygun pool seçin

#### ALTERNATĐF YÖNTEM (Gerekirse)

**Web root'u ana dizine işaret edip rewrite ile yönlendirme:**

1. IIS Manager'ı açın
2. Sites > Add Website
3. **Physical path:** `c:\xampp\htdocs\ders_programi`
4. Root `web.config` tüm istekleri `public/` a yönlendirir

### 3. PHP Yapılandırması

IIS'de PHP'nin doğru çalıştığından emin olun:

**Gereksinimler:**

-   PHP 7.4 veya üzeri
-   PHP FastCGI modülü
-   PDO_MySQL extension aktif

**PHP.ini kontrolleri:**

```ini
extension=pdo_mysql
extension=mysqli
extension=mbstring
display_errors = Off (Production)
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
```

### 4. Dosya İzinleri

Aşağıdaki klasörlere IIS_IUSRS yazma izni verin:

```powershell
icacls "c:\xampp\htdocs\ders_programi\storage\cache" /grant "IIS_IUSRS:(OI)(CI)M"
icacls "c:\xampp\htdocs\ders_programi\storage\logs" /grant "IIS_IUSRS:(OI)(CI)M"
```

### 5. Database Bağlantısı

`config/database.php` dosyasında production ayarlarını kontrol edin:

```php
// Production sunucuda MySQL service çalışıyor olmalı
$host = 'localhost';
$dbname = 'ders_programi';
$username = 'root'; // Production için güvenli kullanıcı
$password = ''; // Production için güçlü şifre
```

## Test Adımları

### 1. Ana Sayfa Testi

```
http://your-domain.com/
```

-   HomeController@index çalışmalı

### 2. Clean URL Testi

```
http://your-domain.com/dashboard
http://your-domain.com/auth/login
```

-   URL'lerde index.php görünmemeli
-   Sayfalar doğru yüklenmeli

### 3. Static Assets Testi

```
http://your-domain.com/assets/css/style.css
http://your-domain.com/assets/js/script.js
```

-   CSS/JS dosyaları yüklenmeli

### 4. Güvenlik Testi

```
http://your-domain.com/config/database.php  -> 404
http://your-domain.com/app/Controllers/     -> 403/404
http://your-domain.com/.env                 -> 403/404
```

-   Hassas dosyalara erişim engellenmeli

## Yaygın Sorunlar ve Çözümleri

### Sorun 1: "500 Internal Server Error"

**Çözüm:**

-   IIS Error Pages'i "Detailed errors" olarak ayarlayın
-   Failed Request Tracing'i aktif edin
-   `storage/logs` klasörüne izin verin

### Sorun 2: "URL Rewrite module is not installed"

**Çözüm:**

-   URL Rewrite Module 2.0'ı indirin ve yükleyin
-   IIS'i restart edin

### Sorun 3: "Handler 'PHP_via_FastCGI' has a bad module"

**Çözüm:**

-   PHP FastCGI kurulumunu kontrol edin
-   Handler Mappings'de PHP yapılandırmasını doğrulayın

### Sorun 4: Static files (CSS/JS) yüklenmiyor

**Çözüm:**

-   `public/assets` klasörüne okuma izni verin
-   MIME types'ları kontrol edin (IIS > MIME Types)

### Sorun 5: Database bağlantı hatası

**Çözüm:**

-   MySQL service'in çalıştığını doğrulayın
-   PDO_MySQL extension'ın aktif olduğunu kontrol edin
-   Firewall ayarlarını kontrol edin

## Performans Optimizasyonları

### 1. Output Caching

```xml
<system.webServer>
    <caching enabled="true" enableKernelCache="true">
        <profiles>
            <add extension=".css" policy="CacheUntilChange" kernelCachePolicy="CacheUntilChange" />
            <add extension=".js" policy="CacheUntilChange" kernelCachePolicy="CacheUntilChange" />
            <add extension=".jpg" policy="CacheUntilChange" kernelCachePolicy="CacheUntilChange" />
            <add extension=".png" policy="CacheUntilChange" kernelCachePolicy="CacheUntilChange" />
        </profiles>
    </caching>
</system.webServer>
```

### 2. Compression

```xml
<system.webServer>
    <httpCompression>
        <dynamicTypes>
            <add mimeType="text/*" enabled="true" />
            <add mimeType="application/javascript" enabled="true" />
            <add mimeType="application/json" enabled="true" />
        </dynamicTypes>
        <staticTypes>
            <add mimeType="text/*" enabled="true" />
            <add mimeType="application/javascript" enabled="true" />
            <add mimeType="application/json" enabled="true" />
        </staticTypes>
    </httpCompression>
</system.webServer>
```

## SSL/HTTPS Yapılandırması (Önerilen)

Production ortamda SSL kullanın:

1. SSL sertifikası edinin (Let's Encrypt, Cloudflare, vb.)
2. IIS'de HTTPS binding ekleyin
3. HTTP'den HTTPS'e yönlendirme ekleyin:

```xml
<rule name="Redirect to HTTPS" stopProcessing="true">
    <match url="(.*)" />
    <conditions>
        <add input="{HTTPS}" pattern="off" ignoreCase="true" />
    </conditions>
    <action type="Redirect" url="https://{HTTP_HOST}/{R:1}" redirectType="Permanent" />
</rule>
```

## Monitoring ve Logging

### IIS Logs

**Konum:** `C:\inetpub\logs\LogFiles\W3SVC1\`

### Application Logs

**Konum:** `storage/logs/`

### Failed Request Tracing

IIS Manager'dan aktif edin, 500 ve 404 hataları için trace alın.

## Sonuç

Web.config dosyaları oluşturuldu ve IIS için gerekli yapılandırmalar tamamlandı.

**Önemli:** Production ortamda güvenlik için:

-   Display errors kapalı olmalı
-   Strong database passwords kullanın
-   HTTPS aktif olmalı
-   Düzenli backup alın
