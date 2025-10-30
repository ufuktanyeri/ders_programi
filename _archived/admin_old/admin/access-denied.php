<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erişim Reddedildi - Admin Panel</title>
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

        .access-denied-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            max-width: 500px;
        }

        .access-denied-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .access-denied-title {
            color: #333;
            margin-bottom: 15px;
        }

        .access-denied-text {
            color: #666;
            margin-bottom: 30px;
        }

        .btn-home {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-home:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="access-denied-container">
        <div class="access-denied-icon">
            <i class="fas fa-ban"></i>
        </div>

        <h1 class="access-denied-title">Erişim Reddedildi</h1>

        <p class="access-denied-text">
            Bu işlemi gerçekleştirmek için yetkiniz bulunmuyor.<br>
            Lütfen yöneticinizle iletişime geçin.
        </p>

        <?php if (isset($_SESSION['admin_logged_in'])): ?>
            <a href="index.php" class="btn-home">
                <i class="fas fa-home"></i> Ana Panele Dön
            </a>
        <?php else: ?>
            <a href="login.php" class="btn-home">
                <i class="fas fa-sign-in-alt"></i> Giriş Yap
            </a>
        <?php endif; ?>
    </div>
</body>
</html>