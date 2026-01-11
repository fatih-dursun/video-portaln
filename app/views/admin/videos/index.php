<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Yönetimi - Admin</title>
    <link rel="stylesheet" href="<?= defined('BASE_PATH') ? BASE_PATH : '/video-portal/public' ?>/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/../_header.php'; ?>
    
    <div class="admin-container">
        <div class="page-header">
            <h1>📹 Video Yönetimi</h1>
            <a href="/admin/videos/create" class="btn btn-primary">+ Yeni Video Ekle</a>
        </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Başlık</th>
                        <th>Kategori</th>
                        <th>Durum</th>
                        <th>Öne Çıkan</th>
                        <th>Görüntülenme</th>
                        <th>Ekleyen</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($videos as $video): ?>
                    <tr>
                        <td>
                            <img src="<?= $video['thumbnail_path'] ?>" alt="" style="width: 80px; border-radius: 4px;">
                        </td>
                        <td><?= htmlspecialchars($video['title']) ?></td>
                        <td><?= htmlspecialchars($video['category_name']) ?></td>
                        <td>
                            <span class="status-badge status-<?= $video['status'] ?>">
                                <?= ucfirst($video['status']) ?>
                            </span>
                        </td>
                        <td><?= $video['is_featured'] ? '⭐ Evet' : 'Hayır' ?></td>
                        <td><?= number_format($video['view_count']) ?></td>
                        <td class="action-buttons">
                            <?php 
                            $canEdit = ($_SESSION['admin_role'] === 'admin') || ($video['created_by'] == $_SESSION['admin_id']);
                            ?>
                            <?php if ($canEdit): ?>
                            <a href="<?= defined('BASE_PATH') ? BASE_PATH : '/video-portal/public' ?>/admin/videos/edit/<?= $video['id'] ?>" class="btn btn-sm btn-edit">Düzenle</a>
                            <a href="<?= defined('BASE_PATH') ? BASE_PATH : '/video-portal/public' ?>/admin/videos/toggle/<?= $video['id'] ?>" class="btn btn-sm btn-warning">
                                <?= $video['status'] === 'active' ? 'Pasif Yap' : 'Aktif Yap' ?>
                            </a>
                            <a href="<?= defined('BASE_PATH') ? BASE_PATH : '/video-portal/public' ?>/admin/videos/delete/<?= $video['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bu videoyu silmek istediğinize emin misiniz?')">Sil</a>
                            <?php else: ?>
                            <span style="color: #888;">Yetkiniz yok</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>