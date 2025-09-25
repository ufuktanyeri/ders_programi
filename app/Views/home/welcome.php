<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #48bb78;
            --danger-color: #f56565;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .hero-section {
            padding: 100px 0;
            text-align: center;
            color: white;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .feature-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 80px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            margin: 0 auto 20px;
        }

        .feature-icon.schedule { background: linear-gradient(135deg, #667eea, #764ba2); }
        .feature-icon.management { background: linear-gradient(135deg, #48bb78, #38a169); }
        .feature-icon.reports { background: linear-gradient(135deg, #ed8936, #dd6b20); }

        .cta-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 60px;
            margin-top: 80px;
            text-align: center;
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .btn-primary-hero {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
        }

        .btn-primary-hero:hover {
            background: white;
            color: var(--primary-color);
        }

        .btn-outline-hero {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
        }

        .btn-outline-hero:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .stats-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 40px;
            margin: 60px 0;
            backdrop-filter: blur(10px);
        }

        .stat-item {
            text-align: center;
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .cta-section {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-graduation-cap text-primary"></i>
                Ders Programı Sistemi
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Ana Sayfa</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Programlar
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach($programlar as $program): ?>
                            <li><a class="dropdown-item" href="/program/<?= $program['program_kodu'] ?>">
                                <?= htmlspecialchars($program['program_adi']) ?>
                            </a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/login">
                            <i class="fas fa-sign-in-alt"></i> Giriş Yap
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary ms-2" href="/admin">
                            <i class="fas fa-cog"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Ders Programı Yönetim Sistemi</h1>
            <p class="hero-subtitle">
                Modern ve kullanıcı dostu arayüzü ile ders programlarınızı kolayca yönetin
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="#features" class="btn btn-primary-hero btn-hero">
                    <i class="fas fa-rocket me-2"></i>Özellikler
                </a>
                <a href="/auth/login" class="btn btn-outline-hero btn-hero">
                    <i class="fas fa-sign-in-alt me-2"></i>Hemen Başla
                </a>
            </div>

            <!-- Statistics -->
            <?php if(isset($stats)): ?>
            <div class="stats-section">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number"><?= $stats['programs'] ?></span>
                            <span>Program</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number"><?= $stats['teachers'] ?></span>
                            <span>Öğretim Elemanı</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number"><?= $stats['classrooms'] ?></span>
                            <span>Derslik</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number"><?= $stats['courses'] ?></span>
                            <span>Ders</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="container">
        <div class="feature-cards">
            <div class="feature-card">
                <div class="feature-icon schedule">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h4>Otomatik Programlama</h4>
                <p>Akıllı algoritmalar ile çakışmasız ders programları oluşturun. Öğretmen ve derslik kısıtlamaları otomatik kontrol edilir.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon management">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h4>Kaynak Yönetimi</h4>
                <p>Öğretim elemanları, derslikler ve programları tek platformda yönetin. Kapasite ve uygunluk kontrolü yapın.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon reports">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h4>Detaylı Raporlar</h4>
                <p>Öğretmen yükleri, derslik kullanım oranları ve program analizlerini görselleştirin. Excel/PDF export desteği.</p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h2 class="mb-4">Hemen Başlayın</h2>
            <p class="mb-4">Ders programı yönetiminizi bir üst seviyeye taşıyın</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/auth/login" class="btn btn-primary-hero btn-hero">
                    <i class="fas fa-user me-2"></i>Kullanıcı Girişi
                </a>
                <a href="/admin" class="btn btn-outline-hero btn-hero">
                    <i class="fas fa-cog me-2"></i>Admin Paneli
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-4 mt-5" style="background: rgba(255,255,255,0.1); color: white;">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Ders Programı Yönetim Sistemi. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card').forEach((card) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>