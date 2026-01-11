<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Yönetimi - Admin</title>
    <link rel="stylesheet" href="<?= defined('BASE_PATH') ? BASE_PATH : '/video-portal/public' ?>/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/../_header.php'; ?>
    
    <div class="admin-container">
        <div class="page-header">
            <h1>📁 Kategori Yönetimi</h1>
            <?php if ($_SESSION['admin_role'] === 'admin'): ?>
            <a href="<?= defined('BASE_PATH') ? BASE_PATH : '/video-portal/public' ?>/admin/categories/create" class="btn btn-primary">+ Yeni Kategori Ekle</a>
            <?php endif; ?>
        </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Önizleme</th>
                        <th>Kategori Adı</th>
                        <th>Slug</th>
                        <th>Video Sayısı</th>
                        <th>Ekleyen</th>
                        <th>Oluşturma</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td>
                            <div style="display: inline-block; padding: 8px 16px; border-radius: 8px; background: <?= $cat['background_color'] ?>; color: <?= $cat['text_color'] ?>; font-weight: 500;">
                                <?= htmlspecialchars($cat['name']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td><code><?= $cat['slug'] ?></code></td>
                        <td><?= $cat['video_count'] ?> video</td>
                        <td><?= $cat['created_by_username'] ?? 'Bilinmiyor' ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($cat['created_at'])) ?></td>
                        <td class="action-buttons">
                            <?php if ($_SESSION['admin_role'] === 'admin'): ?>
                            <a href="<?= defined('BASE_PATH') ? BASE_PATH : '/video-portal/public' ?>/admin/categories/edit/<?= $cat['id'] ?>" class="btn btn-sm btn-edit">Düzenle</a>
                            <a href="<?= defined('BASE_PATH') ? BASE_PATH : '/video-portal/public' ?>/admin/categories/delete/<?= $cat['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz? Bu kategorideki tüm videolar da silinecektir!')">Sil</a>
                            <?php else: ?>
                            <span style="color: #888;">Sadece görüntüleme</span>
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