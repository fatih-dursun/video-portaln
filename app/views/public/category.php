<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category['name']) ?> - Video Portal</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="header">
        <button class="menu-btn" id="menuToggle">☰</button>
        <div class="logo">
            <a href="/" style="color: white; text-decoration: none;">
                <span class="logo-full">🎬 VideoPortal</span>
                <span class="logo-mobile">🎬</span>
            </a>
        </div>
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Ara..." class="search-input">
            <div id="searchResults" class="search-results"></div>
        </div>
    </header>

    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-nav">
            <a href="/" class="nav-item">🏠 Ana Sayfa</a>
            <div class="nav-separator">Kategoriler</div>
            <?php foreach ($categories as $cat): ?>
                <a href="/kategori/<?= $cat['slug'] ?>" 
                   class="nav-item <?= $cat['id'] == $category['id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <main class="main-content">
        <div class="category-header" style="background: linear-gradient(135deg, <?= $category['background_color'] ?> 0%, <?= $category['background_color'] ?>dd 100%); color: <?= $category['text_color'] ?>">
            <h1><?= htmlspecialchars($category['name']) ?></h1>
            <p><?= count($videos) ?> video</p>
        </div>

        <?php if (empty($videos)): ?>
            <div class="empty-state">
                <p>Bu kategoride henüz video bulunmuyor.</p>
            </div>
        <?php else: ?>
            <div class="video-grid">
                <?php foreach ($videos as $video): ?>
                <a href="/video/<?= $video['slug'] ?>" class="video-card">
                    <div class="video-thumbnail">
                        <img src="<?= $video['thumbnail_path'] ?>" alt="<?= htmlspecialchars($video['title']) ?>">
                    </div>
                    <div class="video-info">
                        <h3 class="video-title"><?= htmlspecialchars($video['title']) ?></h3>
                        <div class="video-meta">
                            <?= number_format($video['view_count']) ?> görüntülenme • 
                            <?= date('d.m.Y', strtotime($video['created_at'])) ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="/js/main.js"></script>
</body>
</html>