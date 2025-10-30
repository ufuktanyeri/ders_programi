<?php
session_start();

// Basit giriş kontrolü
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';
require_once '../config/auth-functions.php';

// Kullanıcı bilgilerini al
$current_user = getCurrentUser($db);
$user_role = getUserRole($db);
$role_display = getRoleDisplayName($user_role);
$role_badge = getRoleBadgeClass($user_role);

// İstatistikler
$stats = [
    'programs' => $db->query("SELECT COUNT(*) FROM programlar")->fetchColumn(),
    'teachers' => $db->query("SELECT COUNT(*) FROM ogretim_elemanlari")->fetchColumn(),
    'classrooms' => $db->query("SELECT COUNT(*) FROM derslikler")->fetchColumn(),
    'courses' => $db->query("SELECT COUNT(*) FROM dersler")->fetchColumn(),
    'conflicts' => $db->query("SELECT COUNT(*) FROM cakisma_loglari WHERE cozuldu = FALSE")->fetchColumn()
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Ders Programı Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid white;
        }

        .sidebar-menu i {
            width: 30px;
            font-size: 18px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }

        .top-header {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }

        .card-icon.blue { background: linear-gradient(135deg, #667eea, #764ba2); }
        .card-icon.green { background: linear-gradient(135deg, #48bb78, #38a169); }
        .card-icon.orange { background: linear-gradient(135deg, #ed8936, #dd6b20); }
        .card-icon.red { background: linear-gradient(135deg, #f56565, #e53e3e); }
        .card-icon.yellow { background: linear-gradient(135deg, #f6e05e, #d69e2e); }

        .data-table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e5e5;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
        }

        .btn-primary-gradient:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
        }

        /* Responsive */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -100%;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
        }

        /* Modals */
        .modal-header-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        /* Quick Actions */
        .quick-actions {
            position: fixed;
            bottom: 30px;
            right: 30px;
        }

        .quick-action-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: all 0.3s;
        }

        .quick-action-btn:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <!-- Mobil Menu Toggle -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-user-shield"></i> Admin Panel</h3>
            <small>Ders Programı Yönetimi</small>
        </div>
        
        <nav class="sidebar-menu">
            <ul>
                <li>
                    <a href="#" class="active">
                        <i class="fas fa-home"></i>
                        <span>Ana Sayfa</span>
                    </a>
                </li>
                <?php if (hasPermission($db, 'programs', 'read')): ?>
                <li>
                    <a href="programs.php">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Programlar</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (hasPermission($db, 'teachers', 'read')): ?>
                <li>
                    <a href="teachers.php">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Öğretim Elemanları</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (hasPermission($db, 'courses', 'read')): ?>
                <li>
                    <a href="courses.php">
                        <i class="fas fa-book"></i>
                        <span>Dersler</span>
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="classrooms.php">
                        <i class="fas fa-door-open"></i>
                        <span>Derslikler</span>
                    </a>
                </li>
                <li>
                    <a href="../course-assignment-form.html">
                        <i class="fas fa-tasks"></i>
                        <span>Ders Atamaları</span>
                    </a>
                </li>
                <li>
                    <a href="../drag-drop-schedule-editor.html">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Haftalık Program</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="loadContent('constraints')">
                        <i class="fas fa-ban"></i>
                        <span>Kısıtlamalar</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="loadContent('conflicts')">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Çakışmalar</span>
                        <?php if($stats['conflicts'] > 0): ?>
                        <span class="badge bg-danger ms-2"><?= $stats['conflicts'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="loadContent('terms')">
                        <i class="fas fa-calendar"></i>
                        <span>Dönem Yönetimi</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="loadContent('reports')">
                        <i class="fas fa-chart-bar"></i>
                        <span>Raporlar</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="loadContent('backup')">
                        <i class="fas fa-database"></i>
                        <span>Yedekleme</span>
                    </a>
                </li>
                <li class="mt-3">
                    <a href="logout.php" class="text-warning">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Çıkış Yap</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div>
                <h4 class="mb-0">Hoş Geldiniz, <?= $current_user['name'] ?? $_SESSION['admin_username'] ?? 'Admin' ?></h4>
                <small class="text-muted">
                    <span class="badge <?= $role_badge ?>"><?= $role_display ?></span>
                    | Son giriş: <?= date('d.m.Y H:i') ?>
                </small>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-primary me-2">
                    <i class="fas fa-sync-alt"></i> Yenile
                </button>
                <a href="../" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-external-link-alt"></i> Ana Sayfa
                </a>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <div class="dashboard-card">
                <div class="card-icon blue">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2><?= $stats['programs'] ?></h2>
                <p class="text-muted mb-0">Program</p>
                <small class="text-success">
                    <i class="fas fa-arrow-up"></i> Aktif
                </small>
            </div>

            <div class="dashboard-card">
                <div class="card-icon green">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h2><?= $stats['teachers'] ?></h2>
                <p class="text-muted mb-0">Öğretim Elemanı</p>
                <small class="text-success">
                    <i class="fas fa-check-circle"></i> Hazır
                </small>
            </div>

            <div class="dashboard-card">
                <div class="card-icon orange">
                    <i class="fas fa-door-open"></i>
                </div>
                <h2><?= $stats['classrooms'] ?></h2>
                <p class="text-muted mb-0">Derslik</p>
                <small class="text-info">
                    <i class="fas fa-info-circle"></i> Kullanımda
                </small>
            </div>

            <div class="dashboard-card">
                <div class="card-icon red">
                    <i class="fas fa-book"></i>
                </div>
                <h2><?= $stats['courses'] ?></h2>
                <p class="text-muted mb-0">Ders</p>
                <small class="text-warning">
                    <i class="fas fa-clock"></i> Bu dönem
                </small>
            </div>

            <div class="dashboard-card">
                <div class="card-icon yellow">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2><?= $stats['conflicts'] ?></h2>
                <p class="text-muted mb-0">Çakışma</p>
                <small class="text-danger">
                    <i class="fas fa-times-circle"></i> Bekliyor
                </small>
            </div>
        </div>

        <!-- Dynamic Content Area -->
        <div id="dynamic-content">
            <!-- Son Aktiviteler -->
            <div class="data-table-container">
                <div class="table-header">
                    <h5>Son Aktiviteler</h5>
                    <button class="btn btn-sm btn-primary-gradient">
                        <i class="fas fa-filter"></i> Filtrele
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tarih</th>
                                <th>İşlem</th>
                                <th>Kullanıcı</th>
                                <th>Detay</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= date('d.m.Y H:i', strtotime('-1 hour')) ?></td>
                                <td><span class="badge bg-success">Ders Eklendi</span></td>
                                <td>Admin</td>
                                <td>Matematik dersi BP-1 programına eklendi</td>
                                <td>
                                    <button class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><?= date('d.m.Y H:i', strtotime('-2 hours')) ?></td>
                                <td><span class="badge bg-warning">Güncelleme</span></td>
                                <td>Admin</td>
                                <td>Derslik kapasitesi güncellendi</td>
                                <td>
                                    <button class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><?= date('d.m.Y H:i', strtotime('-3 hours')) ?></td>
                                <td><span class="badge bg-danger">Çakışma</span></td>
                                <td>Sistem</td>
                                <td>Öğretmen çakışması tespit edildi</td>
                                <td>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-tools"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Hızlı İşlemler -->
            <div class="data-table-container">
                <div class="table-header">
                    <h5>Hızlı İşlemler</h5>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-primary-gradient w-100" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                            <i class="fas fa-user-plus"></i> Öğretmen Ekle
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                            <i class="fas fa-plus-circle"></i> Ders Ekle
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#addClassroomModal">
                            <i class="fas fa-door-open"></i> Derslik Ekle
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-info w-100" onclick="checkAllConflicts()">
                            <i class="fas fa-search"></i> Çakışma Tara
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Button -->
    <div class="quick-actions">
        <button class="quick-action-btn" data-bs-toggle="dropdown">
            <i class="fas fa-plus"></i>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#"><i class="fas fa-user-plus"></i> Öğretmen Ekle</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-book-medical"></i> Ders Ekle</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-door-open"></i> Derslik Ekle</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-file-export"></i> Dışa Aktar</a></li>
        </ul>
    </div>

    <!-- Add Teacher Modal -->
    <div class="modal fade" id="addTeacherModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title">Öğretim Elemanı Ekle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addTeacherForm">
                        <div class="mb-3">
                            <label class="form-label">Kısa Ad</label>
                            <input type="text" class="form-control" name="kisa_ad" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tam Ad</label>
                            <input type="text" class="form-control" name="tam_ad">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unvan</label>
                            <select class="form-select" name="unvan">
                                <option>Öğr. Gör.</option>
                                <option>Öğr. Gör. Dr.</option>
                                <option>Dr. Öğr. Üyesi</option>
                                <option>Doç. Dr.</option>
                                <option>Prof. Dr.</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-posta</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Haftalık Ders Limiti</label>
                            <input type="number" class="form-control" name="haftalik_ders_limiti" value="20">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary-gradient" onclick="saveTeacher()">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Dynamic Content Loading
        function loadContent(page) {
            // Sidebar menü aktif class
            document.querySelectorAll('.sidebar-menu a').forEach(a => {
                a.classList.remove('active');
            });
            event.target.closest('a').classList.add('active');

            // AJAX ile içerik yükle
            $('#dynamic-content').load('pages/' + page + '.php');
        }

        // Save Teacher
        function saveTeacher() {
            const formData = new FormData(document.getElementById('addTeacherForm'));
            
            fetch('api/save-teacher.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Öğretim elemanı başarıyla eklendi!');
                    location.reload();
                } else {
                    alert('Hata: ' + data.message);
                }
            });
        }

        // Check All Conflicts
        function checkAllConflicts() {
            fetch('api/check-conflicts.php')
            .then(response => response.json())
            .then(data => {
                if(data.conflicts > 0) {
                    alert(data.conflicts + ' adet çakışma bulundu!');
                    loadContent('conflicts');
                } else {
                    alert('Çakışma bulunamadı.');
                }
            });
        }

        // Dashboard animasyonları
        document.addEventListener('DOMContentLoaded', function() {
            // Kartları animasyonlu göster
            const cards = document.querySelectorAll('.dashboard-card');
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