<?php
session_start();

require_once '../config/database.php';
require_once '../config/google-oauth.php';

// Google OAuth yapılandırılmamışsa hata
if (!isGoogleConfigured()) {
    die('Google OAuth yapılandırması tamamlanmamış.');
}

$client = getGoogleClient();

// Authorization code kontrol et
if (!isset($_GET['code'])) {
    header('Location: login.php?error=google_auth_failed');
    exit;
}

try {
    // Access token al
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        throw new Exception('Token alma hatası: ' . $token['error']);
    }

    $client->setAccessToken($token);

    // Kullanıcı bilgilerini al
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();

    $google_id = $google_account_info->id;
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    $picture = $google_account_info->picture;

    // Kullanıcıyı veritabanında kontrol et
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE google_id = ? OR email = ?");
    $stmt->execute([$google_id, $email]);
    $user = $stmt->fetch();

    if (!$user) {
        // Varsayılan rol belirleme: Ufuk Tanyeri dışında herkes guest
        $default_role = (stripos($email, 'ufuk') !== false && stripos($email, 'tanyeri') !== false) ? 'super_admin' : 'guest';

        // Yeni kullanıcı oluştur
        $stmt = $db->prepare("INSERT INTO admin_users (google_id, email, name, picture, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$google_id, $email, $name, $picture, $default_role]);
        $user_id = $db->lastInsertId();
    } else {
        // Mevcut kullanıcıyı güncelle
        $user_id = $user['id'];
        $stmt = $db->prepare("UPDATE admin_users SET name = ?, picture = ?, last_login = NOW() WHERE id = ?");
        $stmt->execute([$name, $picture, $user_id]);
    }

    // Session oluştur
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user_id'] = $user_id;
    $_SESSION['admin_username'] = $name;
    $_SESSION['admin_email'] = $email;
    $_SESSION['admin_picture'] = $picture;
    $_SESSION['login_type'] = 'google';

    // Admin paneline yönlendir
    header('Location: index.php');
    exit;

} catch (Exception $e) {
    error_log('Google OAuth Error: ' . $e->getMessage());
    header('Location: login.php?error=google_auth_failed&msg=' . urlencode($e->getMessage()));
    exit;
}
?>