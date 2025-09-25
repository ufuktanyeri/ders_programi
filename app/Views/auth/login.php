<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Giriş Yap' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
            padding: 40px;
        }

        .login-form {
            padding: 40px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-google {
            background: white;
            border: 2px solid #ddd;
            border-radius: 50px;
            padding: 12px 40px;
            color: #333;
            width: 100%;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .btn-google:hover {
            background: #f8f9fa;
            border-color: #bbb;
            color: #333;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #ddd;
        }

        .divider span {
            background: white;
            padding: 0 20px;
            color: #666;
        }

        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            z-index: 10;
        }

        .back-link:hover {
            color: rgba(255,255,255,0.8);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <a href="/" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>Ana Sayfa
    </a>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                        <h2>Hoş Geldiniz</h2>
                        <p class="mb-0">Ders Programı Yönetim Sistemi</p>
                    </div>

                    <div class="login-form">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php
                                switch($error) {
                                    case 'invalid_credentials':
                                        echo 'Kullanıcı adı veya şifre hatalı!';
                                        break;
                                    case 'google_not_configured':
                                        echo 'Google giriş sistemi yapılandırılmamış!';
                                        break;
                                    case 'google_auth_failed':
                                        echo 'Google ile giriş başarısız oldu!';
                                        break;
                                    case 'pending_approval':
                                        echo 'Hesabınız onay bekliyor. Lütfen admin ile iletişime geçin.';
                                        break;
                                    default:
                                        echo $error;
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($_GET['msg'])): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php
                                switch($_GET['msg']) {
                                    case 'logged_out':
                                        echo 'Başarıyla çıkış yaptınız.';
                                        break;
                                    default:
                                        echo htmlspecialchars($_GET['msg']);
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="/auth/login">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Kullanıcı Adı" required>
                                <label for="username"><i class="fas fa-user me-2"></i>Kullanıcı Adı</label>
                            </div>

                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Şifre" required>
                                <label for="password"><i class="fas fa-lock me-2"></i>Şifre</label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Beni hatırla
                                </label>
                            </div>

                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                            </button>
                        </form>

                        <?php if(isset($googleConfigured) && $googleConfigured): ?>
                        <div class="divider">
                            <span>veya</span>
                        </div>

                        <a href="/auth/google" class="btn btn-google">
                            <i class="fab fa-google me-2"></i>Google ile Giriş Yap
                        </a>
                        <?php endif; ?>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                Demo: <strong>admin</strong> / <strong>admin123</strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (!username || !password) {
                e.preventDefault();
                alert('Lütfen tüm alanları doldurun!');
            }
        });

        // Auto hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>