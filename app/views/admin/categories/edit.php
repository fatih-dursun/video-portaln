<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Düzenle - Admin</title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/../_header.php'; ?>
    
    <div class="admin-container">
        <div class="page-header">
            <h1>✏️ Kategori Düzenle</h1>
            <a href="/admin/categories" class="btn btn-secondary">← Geri Dön</a>
        </div>

        <form method="POST" class="admin-form">
            <div class="form-group">
                <label>Kategori Adı *</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Arka Plan Rengi *</label>
                    <input type="color" id="background_color" name="background_color" class="form-control" value="<?= $category['background_color'] ?>" required>
                </div>

                <div class="form-group">
                    <label>Yazı Rengi *</label>
                    <input type="color" id="text_color" name="text_color" class="form-control" value="<?= $category['text_color'] ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Hazır Renk Paletleri</label>
                <div class="color-presets">
                    <?php foreach ($colorPresets as $key => $preset): ?>
                    <div class="color-preset" data-bg="<?= $preset['bg'] ?>" data-text="<?= $preset['text'] ?>">
                        <div class="color-preview" style="background: linear-gradient(135deg, <?= $preset['bg'] ?> 0%, <?= $preset['bg'] ?>dd 100%); color: <?= $preset['text'] ?>">
                            <div style="padding-top: 18px; font-size: 18px;">A</div>
                        </div>
                        <div class="color-name"><?= $preset['name'] ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Güncelle</button>
                <a href="/admin/categories" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>

    <script src="/js/main.js"></script>
</body>
</html>