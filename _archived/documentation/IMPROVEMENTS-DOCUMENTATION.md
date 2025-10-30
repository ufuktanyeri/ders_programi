# İYİLEŞTİRMELER DOKÜMANTASYONU

## Yapılan İyileştirmeler

### 1. PSR-4 Uyumlu Autoloader ✅

**Dosya:** `core/Autoloader.php`

Modern PHP standartlarına uygun, PSR-4 compliant bir autoloader sistemi eklendi.

#### Özellikler

-   Namespace'leri otomatik olarak dosya yollarına map eder
-   Performanslı ve esnek yapı
-   Birden fazla namespace desteği
-   Fallback mekanizması

#### Kullanım

```php
// bootstrap.php içinde
require_once __DIR__ . '/core/Autoloader.php';

Autoloader::register();

// Namespace kayıt
Autoloader::addNamespace('App\\Models', __DIR__ . '/app/Models');
Autoloader::addNamespace('App\\Controllers', __DIR__ . '/app/Controllers');
```

#### Avantajları

-   ✅ Manual require/include'lara gerek kalmadı
-   ✅ PSR-4 standardına uygunluk
-   ✅ Daha temiz ve organize kod
-   ✅ Composer autoloader'a geçiş için hazır yapı

---

### 2. Base Model Sınıfı ✅

**Dosya:** `core/Model.php`

Active Record pattern'e benzer bir base model sınıfı oluşturuldu.

#### Özellikler

-   CRUD operasyonları (Create, Read, Update, Delete)
-   Query builder benzeri metodlar
-   Mass assignment protection (fillable)
-   Automatic timestamps
-   Transaction support
-   Custom queries desteği

#### Temel Metodlar

```php
// Kayıt bulma
$user = $model->find(1);
$user = $model->findOne(['email' => 'test@example.com']);

// Tüm kayıtları getirme
$users = $model->findAll(['aktif' => true], 'name ASC', 10);

// Oluşturma
$id = $model->create([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// Güncelleme
$model->update(1, ['name' => 'Jane Doe']);

// Silme
$model->delete(1);

// Sayma
$count = $model->count(['aktif' => true]);

// Varlık kontrolü
$exists = $model->exists(['email' => 'test@example.com']);

// Transaction
$model->beginTransaction();
try {
    $model->create(['name' => 'Test']);
    $model->commit();
} catch (Exception $e) {
    $model->rollback();
}
```

#### Örnek Model Kullanımı

```php
namespace App\Models;

use Model;

class Program extends Model
{
    protected $table = 'programlar';
    protected $primaryKey = 'program_id';

    protected $fillable = [
        'program_kodu',
        'program_adi',
        'program_adi_en',
        'sure',
        'aktif'
    ];

    protected $timestamps = true;

    // Custom methods
    public function getActivePrograms(): array
    {
        return $this->findAll(['aktif' => true], 'program_kodu ASC');
    }

    public function getByCode(string $code): ?array
    {
        return $this->findOne(['program_kodu' => $code]);
    }
}
```

#### Avantajları

-   ✅ DRY principle (Don't Repeat Yourself)
-   ✅ SQL injection koruması (prepared statements)
-   ✅ Temiz ve okunabilir kod
-   ✅ Kolay genişletilebilir
-   ✅ Mass assignment koruması

---

### 3. Template Engine (View) ✅

**Dosya:** `core/View.php`

Blade ve Twig'e benzer, basit ama güçlü bir template engine.

#### Özellikler

-   Layout inheritance (template miras)
-   Sections (bölümler)
-   Components (bileşenler)
-   Partials (kısmi görünümler)
-   XSS koruması (automatic escaping)
-   Helper metodlar
-   Flash messages
-   CSRF token
-   Old input values

#### Layout Kullanımı

**Layout tanımlama:** `app/Views/layouts/modern.php`

```php
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title ?? 'Default Title'; ?></title>
    <?php $this->yield('styles'); ?>
</head>
<body>
    <nav><!-- Navigation --></nav>

    <main>
        <?php $this->yield('content'); ?>
    </main>

    <footer><!-- Footer --></footer>

    <?php $this->yield('scripts'); ?>
</body>
</html>
```

**Layout kullanma:** `app/Views/home/index.php`

```php
<?php $this->extends('modern'); ?>

<?php $this->section('content'); ?>
    <h1>Welcome</h1>
    <p>This is the content section</p>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
    <script>
        console.log('Custom scripts');
    </script>
<?php $this->endSection(); ?>
```

#### Component Kullanımı

**Component tanımlama:** `app/Views/components/stat-card.php`

```php
<div class="card">
    <div class="card-body">
        <h3><?php echo $this->e($value); ?></h3>
        <p><?php echo $this->e($label); ?></p>
    </div>
</div>
```

**Component kullanma:**

```php
<?php
$this->component('stat-card', [
    'value' => 150,
    'label' => 'Total Users'
]);
?>
```

#### Helper Metodlar

```php
// HTML escape (XSS koruması)
<?php echo $this->e($userInput); ?>

// URL oluşturma
<a href="<?php echo $this->url('/dashboard'); ?>">Dashboard</a>

// Asset URL
<img src="<?php echo $this->asset('images/logo.png'); ?>">

// CSRF token
<form method="POST">
    <?php echo $this->csrf(); ?>
    <!-- form fields -->
</form>

// Flash messages
<?php if ($success = $this->flash('success')): ?>
    <div class="alert alert-success"><?php echo $this->e($success); ?></div>
<?php endif; ?>

// Old input (form repopulation)
<input type="text" name="email" value="<?php echo $this->old('email'); ?>">
```

#### Controller'da Kullanım

```php
class HomeController extends Controller
{
    public function index()
    {
        $this->view('home/modern-welcome', [
            'title' => 'Ana Sayfa',
            'stats' => [
                'programs' => 10,
                'teachers' => 25
            ],
            'programlar' => $this->programService->getActivePrograms()
        ]);
    }
}
```

#### Avantajları

-   ✅ Temiz ve organize HTML kod
-   ✅ Kod tekrarını azaltır (DRY)
-   ✅ Layout inheritance
-   ✅ Reusable components
-   ✅ XSS koruması
-   ✅ Kolay öğrenilebilir syntax
-   ✅ Laravel Blade ve Twig'e benzer yapı

---

## Model Örnekleri

### Program Model

**Dosya:** `app/Models/Program.php`

```php
namespace App\Models;

use Model;

class Program extends Model
{
    protected $table = 'programlar';
    protected $primaryKey = 'program_id';
    protected $fillable = ['program_kodu', 'program_adi', 'aktif'];

    public function getActivePrograms(): array
    {
        return $this->findAll(['aktif' => true], 'program_kodu ASC');
    }

    public function getStatistics(int $programId): array
    {
        $sql = "SELECT COUNT(*) as course_count FROM program_dersleri WHERE program_id = :id";
        return $this->query($sql, ['id' => $programId])[0];
    }
}
```

### Teacher Model

**Dosya:** `app/Models/Teacher.php`

```php
namespace App\Models;

use Model;

class Teacher extends Model
{
    protected $table = 'ogretim_elemanlari';
    protected $primaryKey = 'ogretmen_id';
    protected $fillable = ['ad_soyad', 'unvan', 'email', 'aktif'];

    public function getWorkload(int $teacherId): array
    {
        $sql = "
            SELECT SUM(d.teorik + d.uygulama) as total_hours
            FROM ders_atamalari da
            JOIN dersler d ON da.ders_id = d.ders_id
            WHERE da.ogretmen_id = :id
        ";
        return $this->query($sql, ['id' => $teacherId])[0];
    }

    public function hasConflict(int $teacherId, string $day, string $start, string $end): bool
    {
        $sql = "
            SELECT COUNT(*) as count FROM haftalik_program hp
            JOIN ders_atamalari da ON hp.atama_id = da.atama_id
            WHERE da.ogretmen_id = :id AND hp.gun = :day
            AND hp.baslangic_saat < :end AND hp.bitis_saat > :start
        ";
        $result = $this->query($sql, [
            'id' => $teacherId,
            'day' => $day,
            'start' => $start,
            'end' => $end
        ]);
        return ($result[0]['count'] ?? 0) > 0;
    }
}
```

---

## Kullanım Örnekleri

### 1. Yeni Bir Sayfa Oluşturma

```php
// 1. Route ekle (routes.php)
$router->get('/programs', [ProgramController::class, 'index']);

// 2. Controller metodu oluştur
class ProgramController extends Controller
{
    public function index()
    {
        $programModel = new \App\Models\Program($this->db);
        $programs = $programModel->getActivePrograms();

        $this->view('programs/index', [
            'title' => 'Programlar',
            'programs' => $programs
        ]);
    }
}

// 3. View oluştur (app/Views/programs/index.php)
<?php $this->extends('modern'); ?>

<?php $this->section('content'); ?>
    <h1><?php echo $this->e($title); ?></h1>

    <div class="row">
        <?php foreach ($programs as $program): ?>
            <?php $this->component('program-card', ['program' => $program]); ?>
        <?php endforeach; ?>
    </div>
<?php $this->endSection(); ?>
```

### 2. Model ile CRUD İşlemleri

```php
// Model'i oluştur
$programModel = new \App\Models\Program($this->db);

// Create
$newId = $programModel->create([
    'program_kodu' => 'BT',
    'program_adi' => 'Bilgisayar Teknolojisi',
    'aktif' => true
]);

// Read
$program = $programModel->find($newId);
$allPrograms = $programModel->findAll(['aktif' => true]);

// Update
$programModel->update($newId, [
    'program_adi' => 'Bilgisayar Teknolojileri'
]);

// Delete
$programModel->delete($newId);

// Custom query
$stats = $programModel->getStatistics($newId);
```

---

## Migration Guide (Eski Koddan Yeni Yapıya)

### Eski Kod

```php
// Manual database query
$stmt = $db->prepare("SELECT * FROM programlar WHERE aktif = ?");
$stmt->execute([true]);
$programs = $stmt->fetchAll();

// View rendering
include 'app/Views/home/index.php';
```

### Yeni Kod

```php
// Model kullanımı
$programModel = new \App\Models\Program($db);
$programs = $programModel->findAll(['aktif' => true]);

// Template engine
$this->view('home/index', ['programs' => $programs]);
```

---

## Best Practices

### 1. Model Kullanımı

-   ✅ Her tablo için ayrı model oluştur
-   ✅ Complex queries için custom metodlar yaz
-   ✅ Fillable array'i her zaman tanımla (security)
-   ✅ Business logic'i model'de tut

### 2. View Kullanımı

-   ✅ Her zaman `$this->e()` ile escape yap
-   ✅ Layout inheritance kullan
-   ✅ Tekrar eden HTML'i component'e çevir
-   ✅ JavaScript'i `scripts` section'ına koy

### 3. Controller Kullanımı

-   ✅ Controller'lar ince tutulmalı
-   ✅ Business logic'i Service veya Model'e taşı
-   ✅ View'e sadece gerekli veri gönder

---

## Performance Tips

1. **Model Caching**: Sık kullanılan sorguları cache'le
2. **Eager Loading**: N+1 problem'ini önle
3. **Index Usage**: Database index'lerini doğru kullan
4. **View Caching**: Static content'i cache'le

---

## Güvenlik

1. **SQL Injection**: Model prepared statements kullanır ✅
2. **XSS**: Template engine otomatik escape yapar ✅
3. **CSRF**: `$this->csrf()` helper'ı kullan ✅
4. **Mass Assignment**: Fillable array kullan ✅

---

## Sonuç

Bu iyileştirmelerle:

-   ✅ Daha modern ve maintainable kod yapısı
-   ✅ PSR-4 standardına uygunluk
-   ✅ Güvenli ve temiz kod
-   ✅ Daha hızlı geliştirme süreci
-   ✅ Kolay test edilebilir yapı
-   ✅ Daha iyi developer experience

## İletişim

Sorularınız için:

-   GitHub Issues
-   Email: [email protected]

---

**Version:** 2.0  
**Date:** 2025-10-14  
**Author:** Development Team
