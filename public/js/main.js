// Sidebar Toggle (Sadece Mobilde)
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');

if (menuToggle && sidebar) {
    // Overlay oluştur
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.id = 'sidebarOverlay';
    document.body.appendChild(overlay);

    // Menü toggle (sadece mobilde çalışsın)
    menuToggle.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
    });

    // Overlay'e tıklayınca kapat
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Sidebar linklerine tıklayınca mobilde kapat
    const navItems = sidebar.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    });
}

// Live Search with Debounce
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
let searchTimeout;

if (searchInput && searchResults) {
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            searchResults.classList.remove('active');
            searchResults.innerHTML = '';
            return;
        }
        
        // Debounce: 300ms
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Enter tuşuna basınca tam arama sayfasına git
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                window.location.href = `/arama?q=${encodeURIComponent(query)}`;
            }
        }
    });

    // Dışarı tıklayınca kapat
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('active');
        }
    });
}

async function performSearch(query) {
    try {
        const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        
        displaySearchResults(data.results);
    } catch (error) {
        console.error('Arama hatası:', error);
    }
}

function displaySearchResults(results) {
    if (results.length === 0) {
        searchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: #888;">Sonuç bulunamadı</div>';
        searchResults.classList.add('active');
        return;
    }

    const html = results.map(video => `
        <a href="/video/${video.slug}" class="search-result-item">
            <img src="${video.thumbnail_path}" alt="${escapeHtml(video.title)}" class="search-result-thumbnail">
            <div class="search-result-info">
                <div class="search-result-title">${escapeHtml(video.title)}</div>
                <div class="search-result-meta">
                    <span class="category-badge" style="background-color: ${video.background_color}; color: ${video.text_color}; margin-right: 8px;">
                        ${escapeHtml(video.category_name)}
                    </span>
                    ${formatNumber(video.view_count)} görüntülenme
                </div>
            </div>
        </a>
    `).join('');

    searchResults.innerHTML = html;
    searchResults.classList.add('active');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatNumber(num) {
    return new Intl.NumberFormat('tr-TR').format(num);
}

// Admin: Color Preset Selection
const colorPresets = document.querySelectorAll('.color-preset');
const bgColorInput = document.getElementById('background_color');
const textColorInput = document.getElementById('text_color');

colorPresets.forEach(preset => {
    preset.addEventListener('click', () => {
        colorPresets.forEach(p => p.classList.remove('active'));
        preset.classList.add('active');
        
        bgColorInput.value = preset.dataset.bg;
        textColorInput.value = preset.dataset.text;
    });
});