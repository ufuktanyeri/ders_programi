<?php
session_start();

require_once '../config/google-oauth.php';

// Zaten giriş yapmışsa admin paneline yönlendir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';
$googleAuthUrl = '';

// Google OAuth client oluştur
try {
    $client = getGoogleClient();
    $googleAuthUrl = $client->createAuthUrl();
} catch (Exception $e) {
    // Google OAuth yapılandırılmamışsa sessizce devam et
}

// Login form gönderilmişse
if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basit authentication (production'da hash kullanılmalı)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_type'] = 'local';
        header('Location: index.php');
        exit;
    } else {
        $error = 'Hatalı kullanıcı adı veya şifre!';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - Ders Programı Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header i {
            font-size: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }

        .login-header h2 {
            color: #333;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .back-link {
            text-align: center;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e5e5;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #666;
            font-size: 14px;
        }

        .btn-google {
            background: #db4437;
            color: white;
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            border: none;
            margin-bottom: 20px;
        }

        .btn-google:hover {
            background: #c23321;
            color: white;
            transform: translateY(-1px);
        }

        .btn-google i {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-shield-alt"></i>
            <h2>Admin Paneli</h2>
            <p>Ders Programı Yönetim Sistemi</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'google_auth_failed'): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle"></i> Google ile giriş başarısız oldu.
                <?php if (isset($_GET['msg'])): ?>
                    <small>Hata: <?= htmlspecialchars($_GET['msg']) ?></small>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!isGoogleConfigured()): ?>
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i> Google OAuth henüz yapılandırılmamış.
                <a href="../setup-google-oauth.md" target="_blank">Kurulum rehberi</a>ni inceleyin.
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" placeholder="Kullanıcı Adı" required>
                <label for="username"><i class="fas fa-user"></i> Kullanıcı Adı</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Şifre" required>
                <label for="password"><i class="fas fa-lock"></i> Şifre</label>
            </div>

            <button type="submit" class="btn btn-primary btn-login">
                <i class="fas fa-sign-in-alt"></i> Giriş Yap
            </button>
        </form>

        <?php if (isGoogleConfigured() && !empty($googleAuthUrl)): ?>
            <div class="divider">
                <span>veya</span>
            </div>

            <a href="<?= htmlspecialchars($googleAuthUrl) ?>" class="btn btn-google">
                <i class="fab fa-google"></i> Google ile Giriş Yap
            </a>
        <?php endif; ?>

        <div class="back-link">
            <a href="../index.php">
                <i class="fas fa-arrow-left"></i> Ana Sayfaya Dön
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>