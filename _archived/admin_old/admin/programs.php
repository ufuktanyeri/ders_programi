<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

// Programları getir
$programs = $db->query("SELECT * FROM programlar ORDER BY program_kodu")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programlar - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .container-fluid { padding: 20px; }
        .back-btn { margin-bottom: 20px; }
        .table-container { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="back-btn">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Ana Panele Dön
            </a>
        </div>

        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><i class="fas fa-graduation-cap"></i> Programlar</h2>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni Program Ekle
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Program Kodu</th>
                            <th>Program Adı</th>
                            <th>Bölüm</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($programs as $program): ?>
                        <tr>
                            <td><?= $program['program_id'] ?></td>
                            <td><strong><?= $program['program_kodu'] ?></strong></td>
                            <td><?= $program['program_adi'] ?></td>
                            <td><?= $program['bolum'] ?></td>
                            <td>
                                <?php if($program['aktif']): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Pasif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>