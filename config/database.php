<?php
/**
 * Veritabanı Bağlantı Dosyası
 * config/database.php
 */

// Hata raporlama (development için)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Helper function for input sanitization
if (!function_exists('clean')) {
    function clean($input) {
        if (is_array($input)) {
            return array_map('clean', $input);
        }
        return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
    }
}

// Environment configuration
require_once 'environment.php';

// Veritabanı ayarları environment'dan gelecek
// DB_HOST, DB_NAME, DB_USER, DB_PASS zaten environment.php'de tanımlı

try {
    // PDO bağlantısı
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_turkish_ci"
        ]
    );
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Timezone ayarı
date_default_timezone_set('Europe/Istanbul');

// Session ayarları
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Genel fonksiyonlar
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isAdmin() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

// CSRF Token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Veritabanı yardımcı fonksiyonları
function getActiveTermId($db) {
    $stmt = $db->query("SELECT donem_id FROM akademik_donemler WHERE aktif = TRUE LIMIT 1");
    return $stmt->fetchColumn();
}

function checkConflicts($db, $teacher_id, $day, $start_time, $end_time) {
    $sql = "SELECT COUNT(*) FROM haftalik_program hp
            JOIN ders_atamalari da ON hp.atama_id = da.atama_id
            WHERE da.ogretmen_id = :teacher_id 
            AND hp.gun = :day
            AND hp.baslangic_saat < :end_time 
            AND hp.bitis_saat > :start_time";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'teacher_id' => $teacher_id,
        'day' => $day,
        'start_time' => $start_time,
        'end_time' => $end_time
    ]);
    
    return $stmt->fetchColumn() > 0;
}

function getAvailableClassrooms($db, $day, $start_time, $end_time) {
    $sql = "SELECT d.* FROM derslikler d
            WHERE d.aktif = TRUE 
            AND d.derslik_id NOT IN (
                SELECT hp.derslik_id FROM haftalik_program hp
                WHERE hp.gun = :day
                AND hp.baslangic_saat < :end_time 
                AND hp.bitis_saat > :start_time
            )
            ORDER BY d.kapasite DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'day' => $day,
        'start_time' => $start_time,
        'end_time' => $end_time
    ]);
    
    return $stmt->fetchAll();
}

// Öğretmen renkleri
$teacherColors = [
    'AA' => ['color' => '#FF6B6B', 'pattern' => 'dots'],
    'EA' => ['color' => '#95E1D3', 'pattern' => 'stripes'],
    'EY' => ['color' => '#A8E6CF', 'pattern' => 'dots'],
    'MD' => ['color' => '#FFAAA5', 'pattern' => 'stripes'],
    'MA' => ['color' => '#8FCACA', 'pattern' => 'dots'],
    'MuD' => ['color' => '#FFC0CB', 'pattern' => 'stripes'],
    'OKS' => ['color' => '#B5EAD7', 'pattern' => 'dots'],
    'SB' => ['color' => '#FFD93D', 'pattern' => 'stripes'],
    'SE' => ['color' => '#FFA8A8', 'pattern' => 'dots'],
    'SD' => ['color' => '#B4E7CE', 'pattern' => 'stripes'],
    'TD' => ['color' => '#F5B7B1', 'pattern' => 'dots'],
    'UT' => ['color' => '#AED6F1', 'pattern' => 'stripes']
];

// Saat dilimleri
$timeSlots = [
    '08:30-09:00', '09:00-09:30', '09:30-10:00', '10:00-10:30',
    '10:30-11:00', '11:00-11:30', '11:30-12:00', '12:00-12:30',
    '12:30-14:00', // Öğle arası
    '14:00-14:30', '14:30-15:00', '15:00-15:30', '15:30-16:00',
    '16:00-16:30', '16:30-17:00', '17:00-17:30', '17:30-18:00'
];

// Günler
$days = [
    1 => 'Pazartesi',
    2 => 'Salı',
    3 => 'Çarşamba',
    4 => 'Perşembe',
    5 => 'Cuma'
];
?>