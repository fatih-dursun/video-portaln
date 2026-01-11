<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Video Ekle - Admin</title>
    <link rel="stylesheet" href="<?= defined('BASE_PATH') ? BASE_PATH : '/video-portal/public' ?>/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/../_header.php'; ?>
    
    <div class="admin-container">
        <div class="page-header">
            <h1>➕ Yeni Video Ekle</h1>
            <a href="/admin/videos" class="btn btn-secondary">← Geri Dön</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Video Başlığı *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Kategori *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Kategori Seçin</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Açıklama *</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Öne Çıkan Yazı (Opsiyonel)</label>
                <input type="text" name="featured_text" class="form-control" 
                       placeholder="Boş bırakılırsa video başlığı kullanılır">
                <small class="form-help">Bu metin otomatik oluşturulan öne çıkan görselde görünecektir</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Video Dosyası * (.mp4, .webm)</label>
                    <input type="file" name="video" class="form-control" accept="video/*" required>
                </div>

                <div class="form-group">
                    <label>Thumbnail * (.jpg, .png)</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Durum</label>
                    <select name="status" class="form-control">
                        <option value="active">Aktif</option>
                        <option value="passive">Pasif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" value="1">
                        Öne Çıkan Video Olarak İşaretle
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Video Ekle</button>
                <a href="/admin/videos" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</body>
</html>