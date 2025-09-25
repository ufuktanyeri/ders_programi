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
            --warning-color: #ed8936;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .header-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .header-section h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .header-section .subtitle {
            color: #666;
            font-size: 14px;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
            color: white;
        }

        .stat-icon.programs { background: linear-gradient(135deg, #667eea, #764ba2); }
        .stat-icon.teachers { background: linear-gradient(135deg, #48bb78, #38a169); }
        .stat-icon.classrooms { background: linear-gradient(135deg, #ed8936, #dd6b20); }
        .stat-icon.courses { background: linear-gradient(135deg, #f56565, #e53e3e); }

        .stat-content h3 {
            font-size: 28px;
            margin: 0;
            color: #333;
        }

        .stat-content p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .action-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .action-card i {
            font-size: 48px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .action-card h4 {
            color: #333;
            margin-bottom: 10px;
        }

        .action-card p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        .program-tabs {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .nav-tabs {
            border-bottom: 2px solid #eee;
        }

        .nav-tabs .nav-link {
            color: #666;
            border: none;
            padding: 10px 15px;
            transition: all 0.3s;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background: none;
            border-bottom: 3px solid var(--primary-color);
        }

        .schedule-table {
            width: 100%;
            margin-top: 20px;
        }

        .schedule-table th {
            background: #f8f9fa;
            padding: 10px;
            text-align: center;
            font-size: 13px;
        }

        .schedule-table td {
            border: 1px solid #dee2e6;
            padding: 5px;
            text-align: center;
            font-size: 11px;
            min-height: 40px;
        }

        .time-slot {
            background: #f8f9fa;
            font-weight: bold;
        }

        .lunch-break {
            background: #fff3cd;
            font-weight: bold;
            color: #856404;
        }

        .course-cell {
            background: linear-gradient(135deg, #84fab0, #8fd3f4);
            border-radius: 5px;
            padding: 5px;
            cursor: pointer;
        }

        .course-cell:hover {
            opacity: 0.8;
        }

        .quick-links {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .quick-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s;
            text-decoration: none;
            color: var(--primary-color);
        }

        .quick-link:hover {
            transform: scale(1.1);
            color: var(--secondary-color);
        }

        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px 15px 0 0;
        }

        @media (max-width: 768px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }

            .action-cards {
                grid-template-columns: 1fr;
            }

            .nav-tabs .nav-link {
                font-size: 10px;
                padding: 8px 10px;
                max-width: 120px;
            }

            .program-tabs {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-calendar-alt"></i> <?= $title ?></h1>
                    <p class="subtitle">
                        <?php if($aktif_donem): ?>
                            Aktif Dönem: <strong><?= $aktif_donem['donem_adi'] ?></strong>
                        <?php else: ?>
                            <span class="text-danger">Aktif dönem tanımlanmamış!</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <a href="admin/" class="btn btn-primary">
                        <i class="fas fa-cog"></i> Admin Panel
                    </a>
                </div>
            </div>
        </div>

        <!-- İstatistik Kartları -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon programs">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['programs'] ?></h3>
                    <p>Program</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon teachers">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['teachers'] ?></h3>
                    <p>Öğretim Elemanı</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon classrooms">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['classrooms'] ?></h3>
                    <p>Derslik</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon courses">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['courses'] ?></h3>
                    <p>Ders</p>
                </div>
            </div>
        </div>

        <!-- Aksiyon Kartları -->
        <div class="action-cards">
            <a href="views/schedule-view.php" class="action-card">
                <i class="fas fa-calendar-week"></i>
                <h4>Haftalık Programı Görüntüle</h4>
                <p>Tüm programların haftalık ders çizelgelerini görüntüleyin</p>
            </a>

            <a href="views/schedule-editor.php" class="action-card">
                <i class="fas fa-edit"></i>
                <h4>Program Düzenleyici</h4>
                <p>Sürükle-bırak ile ders programını düzenleyin</p>
            </a>

            <a href="views/assignment-form.php" class="action-card">
                <i class="fas fa-plus-circle"></i>
                <h4>Ders Ataması Yap</h4>
                <p>Yeni ders ataması ekleyin veya mevcut atamaları düzenleyin</p>
            </a>

            <a href="views/reports.php" class="action-card">
                <i class="fas fa-chart-bar"></i>
                <h4>Raporlar</h4>
                <p>Öğretmen yükü, derslik kullanımı ve çakışma raporları</p>
            </a>

            <a href="views/conflicts.php" class="action-card">
                <i class="fas fa-exclamation-triangle"></i>
                <h4>Çakışma Kontrolü</h4>
                <p>Program çakışmalarını kontrol edin ve düzeltin</p>
            </a>

            <a href="views/print.php" class="action-card">
                <i class="fas fa-print"></i>
                <h4>Yazdır / Dışa Aktar</h4>
                <p>Programları PDF veya Excel formatında kaydedin</p>
            </a>
        </div>

        <!-- Program Tabları -->
        <div class="program-tabs">
            <h3 class="mb-3">Hızlı Bakış</h3>
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach($programlar as $index => $program): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $index == 0 ? 'active' : '' ?>"
                       data-bs-toggle="tab"
                       href="#program-<?= $program['program_id'] ?>"
                       title="<?= htmlspecialchars($program['program_adi']) ?>">
                        <?= htmlspecialchars($program['program_adi']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="tab-content">
                <?php foreach($programlar as $index => $program): ?>
                <div class="tab-pane fade <?= $index == 0 ? 'show active' : '' ?>"
                     id="program-<?= $program['program_id'] ?>">
                    <div class="table-responsive">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th>Saat</th>
                                    <th>Pazartesi</th>
                                    <th>Salı</th>
                                    <th>Çarşamba</th>
                                    <th>Perşembe</th>
                                    <th>Cuma</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $saatler = [
                                    '08:30-09:00', '09:00-09:30', '09:30-10:00', '10:00-10:30',
                                    '10:30-11:00', '11:00-11:30', '11:30-12:00', '12:00-12:30',
                                    '12:30-14:00', '14:00-14:30', '14:30-15:00', '15:00-15:30',
                                    '15:30-16:00', '16:00-16:30', '16:30-17:00', '17:00-17:30', '17:30-18:00'
                                ];

                                foreach($saatler as $saat):
                                ?>
                                <tr>
                                    <td class="time-slot"><?= $saat ?></td>
                                    <?php if($saat == '12:30-14:00'): ?>
                                        <td colspan="5" class="lunch-break">ÖĞLE ARASI</td>
                                    <?php else: ?>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Hızlı Erişim Butonları -->
    <div class="quick-links">
        <a href="#" class="quick-link" data-bs-toggle="modal" data-bs-target="#searchModal">
            <i class="fas fa-search"></i>
        </a>
        <a href="#" class="quick-link" onclick="window.print()">
            <i class="fas fa-print"></i>
        </a>
        <a href="#" class="quick-link" onclick="window.scrollTo(0,0)">
            <i class="fas fa-arrow-up"></i>
        </a>
    </div>

    <!-- Arama Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hızlı Arama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Ders, öğretmen veya derslik ara...">
                        <button class="btn btn-primary">
                            <i class="fas fa-search"></i> Ara
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sayfa yüklendiğinde animasyonlar
        document.addEventListener('DOMContentLoaded', function() {
            // Kartları animasyonlu göster
            const cards = document.querySelectorAll('.stat-card, .action-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>