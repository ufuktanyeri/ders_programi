<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

// Öğretim elemanlarını getir
$teachers = $db->query("SELECT * FROM ogretim_elemanlari ORDER BY kisa_ad")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğretim Elemanları - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .container-fluid { padding: 20px; }
        .back-btn { margin-bottom: 20px; }
        .table-container { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .color-box { width: 20px; height: 20px; border-radius: 3px; display: inline-block; }
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
                <h2><i class="fas fa-chalkboard-teacher"></i> Öğretim Elemanları</h2>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni Öğretmen Ekle
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kısa Ad</th>
                            <th>Tam Adı</th>
                            <th>Unvan</th>
                            <th>Email</th>
                            <th>Renk</th>
                            <th>Haftalık Limit</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($teachers as $teacher): ?>
                        <tr>
                            <td><?= $teacher['ogretmen_id'] ?></td>
                            <td><strong><?= $teacher['kisa_ad'] ?></strong></td>
                            <td><?= $teacher['tam_ad'] ?></td>
                            <td><?= $teacher['unvan'] ?></td>
                            <td><?= $teacher['email'] ?></td>
                            <td>
                                <div class="color-box" style="background: <?= $teacher['renk_kodu'] ?>"></div>
                                <?= $teacher['renk_kodu'] ?>
                            </td>
                            <td><?= $teacher['haftalik_ders_limiti'] ?> saat</td>
                            <td>
                                <?php if($teacher['aktif']): ?>
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