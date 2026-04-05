<?php
$page_title = 'Dashboard';
$base_path = '';
require_once 'dvwa/includes/header.php';

$theme = getThemeConfig();
$conn = dvwaDbConnect();
$result = $conn->query("SELECT * FROM concerts ORDER BY concert_date ASC");
$concerts = [];
while ($row = $result->fetch_assoc()) {
    $concerts[] = $row;
}
$conn->close();

$gradients = [
    'linear-gradient(135deg,#6d28d9,#ec4899)',
    'linear-gradient(135deg,#1e40af,#06b6d4)',
    'linear-gradient(135deg,#b91c1c,#f59e0b)',
    'linear-gradient(135deg,#047857,#06b6d4)',
    'linear-gradient(135deg,#7c3aed,#2563eb)',
    'linear-gradient(135deg,#dc2626,#7c3aed)',
    'linear-gradient(135deg,#0891b2,#6d28d9)',
    'linear-gradient(135deg,#c2410c,#eab308)',
    'linear-gradient(135deg,#4338ca,#ec4899)',
    'linear-gradient(135deg,#15803d,#2563eb)',
    'linear-gradient(135deg,#9333ea,#f97316)',
    'linear-gradient(135deg,#0d9488,#8b5cf6)',
];
?>

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title glow-text"><i class="<?php echo $theme['dash_icon']; ?>"></i> <?php echo htmlspecialchars($theme['dash_title']); ?></h1>
        <p class="page-subtitle"><?php echo $theme['dash_subtitle']; ?></p>
    </div>
    <?php if ((getCurrentTheme() ?? 'music') !== 'games'): ?>
    <div class="card-size-control">
        <label class="size-label"><i class="fas fa-image"></i></label>
        <input type="range" id="cardSizeSlider" min="55" max="220" value="95" title="Adjust card size">
        <label class="size-label"><i class="fas fa-image fa-lg"></i></label>
    </div>
    <?php endif; ?>
</div>

<?php if (empty($concerts)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No <?php echo strtolower($theme['item_label_plural']); ?> yet.
        <a href="setup.php" class="text-purple">Setup the database</a> or
        <a href="scraper.php" class="text-purple">scrape data</a>.
    </div>
<?php endif; ?>

<?php
$currentThemeKey = getCurrentTheme() ?? 'music';

// Group items by genre for Steam-like category sidebar
$genres = [];
foreach ($concerts as $c) {
    $g = $c['genre'] ?? 'Other';
    if (!isset($genres[$g])) $genres[$g] = 0;
    $genres[$g]++;
}
arsort($genres);

if ($currentThemeKey === 'games'):
// ======== STEAM-LIKE LAYOUT FOR GAMES THEME ========
$featured = array_slice($concerts, 0, 5);
$remaining = array_slice($concerts, 5);
?>
<style>
.steam-layout { display: flex; gap: 1rem; }
.steam-sidebar {
    width: 200px; min-width: 200px; flex-shrink: 0;
    background: #0e0e1a; border-radius: 4px; padding: 0; overflow: hidden;
    border: 1px solid #1e1e30; align-self: flex-start; position: sticky; top: 1rem;
}
.steam-sidebar-title {
    font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1px; color: #4ade80; padding: 0.75rem 1rem 0.5rem;
    border-bottom: 1px solid #1e1e30;
}
.steam-cat-link {
    display: flex; justify-content: space-between; align-items: center;
    padding: 0.45rem 1rem; font-size: 0.78rem; color: #a3b1c6;
    text-decoration: none; transition: all 0.15s; cursor: pointer;
    border-bottom: 1px solid rgba(30,30,48,0.5);
}
.steam-cat-link:hover, .steam-cat-link.active { background: #171728; color: #4ade80; }
.steam-cat-count { font-size: 0.68rem; color: #555; }
.steam-main { flex: 1; min-width: 0; }
.steam-featured {
    background: #0e0e1a; border-radius: 4px; padding: 1rem;
    margin-bottom: 1rem; border: 1px solid #1e1e30;
}
.steam-featured-title {
    font-size: 0.82rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1px; color: #4ade80; margin-bottom: 0.75rem;
}
.steam-featured-grid {
    display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 0.6rem;
}
.steam-featured-grid .steam-feat-card:first-child {
    grid-row: span 2;
}
.steam-feat-card {
    background: #151525; border-radius: 3px; overflow: hidden;
    border: 1px solid #252538; transition: all 0.15s; cursor: pointer;
    position: relative;
}
.steam-feat-card:hover { border-color: #22c55e; transform: translateY(-2px); box-shadow: 0 4px 16px rgba(34,197,94,0.15); }
.steam-feat-card .feat-img {
    width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block;
}
.steam-feat-card .feat-placeholder {
    width: 100%; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: rgba(255,255,255,0.15);
}
.steam-feat-card .feat-info {
    padding: 0.5rem 0.6rem;
}
.steam-feat-card .feat-title {
    font-size: 0.82rem; font-weight: 700; color: #e2e8f0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.steam-feat-card .feat-meta {
    display: flex; justify-content: space-between; align-items: center; margin-top: 0.25rem;
}
.steam-feat-card .feat-genre { font-size: 0.68rem; color: #4ade80; }
.steam-feat-card .feat-price { font-size: 0.82rem; font-weight: 700; color: #22c55e; }
.steam-feat-card .feat-price.free { color: #60a5fa; }
.steam-game-list { display: flex; flex-direction: column; gap: 0.4rem; }
.steam-game-row {
    display: flex; align-items: center; gap: 0.75rem; background: #0e0e1a;
    border-radius: 3px; padding: 0.5rem 0.75rem; border: 1px solid #1e1e30;
    transition: all 0.15s; cursor: pointer;
}
.steam-game-row:hover { border-color: #22c55e; background: #151525; }
.steam-game-row .game-thumb {
    width: 80px; height: 36px; border-radius: 2px; object-fit: cover; flex-shrink: 0;
}
.steam-game-row .game-thumb-ph {
    width: 80px; height: 36px; border-radius: 2px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; color: rgba(255,255,255,0.12);
}
.steam-game-row .game-info { flex: 1; min-width: 0; }
.steam-game-row .game-name { font-size: 0.8rem; font-weight: 600; color: #e2e8f0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.steam-game-row .game-genre { font-size: 0.68rem; color: #64748b; }
.steam-game-row .game-platform { font-size: 0.68rem; color: #64748b; }
.steam-game-row .game-price { font-size: 0.82rem; font-weight: 700; color: #22c55e; white-space: nowrap; }
.steam-game-row .game-price.free { color: #60a5fa; }
.steam-section-title {
    font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1px; color: #4ade80; margin: 1rem 0 0.5rem;
    padding-bottom: 0.25rem; border-bottom: 1px solid #1e1e30;
}
@media (max-width: 768px) {
    .steam-layout { flex-direction: column; }
    .steam-sidebar { width: 100%; min-width: 100%; position: static; }
    .steam-featured-grid { grid-template-columns: 1fr; }
    .steam-featured-grid .steam-feat-card:first-child { grid-row: span 1; }
}
</style>

<div class="steam-layout">
    <!-- Left category sidebar (Steam-style) -->
    <div class="steam-sidebar">
        <div class="steam-sidebar-title"><i class="fas fa-tags"></i> Categories</div>
        <a class="steam-cat-link active" data-filter="all">
            All Games <span class="steam-cat-count"><?php echo count($concerts); ?></span>
        </a>
        <?php foreach ($genres as $genre => $count): ?>
        <a class="steam-cat-link" data-filter="<?php echo htmlspecialchars($genre); ?>">
            <?php echo htmlspecialchars($genre); ?> <span class="steam-cat-count"><?php echo $count; ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Main content area -->
    <div class="steam-main">
        <!-- Featured section -->
        <?php if (!empty($featured)): ?>
        <div class="steam-featured">
            <div class="steam-featured-title"><i class="fas fa-star"></i> Featured & Recommended</div>
            <div class="steam-featured-grid">
                <?php foreach ($featured as $fi => $c):
                    $poster = $c['poster_url'] ?? '';
                    $hasImage = !empty($poster) && strpos($poster, 'data:') !== 0 && (file_exists($poster) || substr($poster, 0, 4) === 'http');
                    $isFree = $c['price'] <= 0;
                ?>
                <div class="steam-feat-card" data-genre="<?php echo htmlspecialchars($c['genre']); ?>">
                    <?php if ($hasImage): ?>
                        <img class="feat-img" src="<?php echo htmlspecialchars($poster); ?>" alt="<?php echo htmlspecialchars($c['band_name']); ?>" loading="lazy">
                    <?php else: ?>
                        <div class="feat-placeholder" style="background:<?php echo $gradients[$fi % count($gradients)]; ?>">
                            <i class="fas fa-gamepad"></i>
                        </div>
                    <?php endif; ?>
                    <div class="feat-info">
                        <div class="feat-title" title="<?php echo htmlspecialchars($c['band_name']); ?>"><?php echo htmlspecialchars($c['band_name']); ?></div>
                        <div class="feat-meta">
                            <span class="feat-genre"><?php echo htmlspecialchars($c['genre']); ?></span>
                            <span class="feat-price <?php echo $isFree ? 'free' : ''; ?>"><?php echo $isFree ? 'Free to Play' : '$' . number_format($c['price'], 2); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Game list (Steam-style rows) -->
        <div class="steam-section-title"><i class="fas fa-fire"></i> All Games</div>
        <div class="steam-game-list" id="steamGameList">
            <?php foreach ($remaining as $i => $c):
                $poster = $c['poster_url'] ?? '';
                $hasImage = !empty($poster) && strpos($poster, 'data:') !== 0 && (file_exists($poster) || substr($poster, 0, 4) === 'http');
                $isFree = $c['price'] <= 0;
                $link = $c['spotify_url'] ?? '';
            ?>
            <div class="steam-game-row" data-genre="<?php echo htmlspecialchars($c['genre']); ?>"<?php echo !empty($link) ? ' onclick="window.open(\'' . htmlspecialchars($link) . '\', \'_blank\')"' : ''; ?>>
                <?php if ($hasImage): ?>
                    <img class="game-thumb" src="<?php echo htmlspecialchars($poster); ?>" alt="" loading="lazy">
                <?php else: ?>
                    <div class="game-thumb-ph" style="background:<?php echo $gradients[$i % count($gradients)]; ?>">
                        <i class="fas fa-gamepad"></i>
                    </div>
                <?php endif; ?>
                <div class="game-info">
                    <div class="game-name"><?php echo htmlspecialchars($c['band_name']); ?></div>
                    <div class="game-genre"><?php echo htmlspecialchars($c['genre']); ?></div>
                    <div class="game-platform"><?php echo htmlspecialchars($c['venue']); ?></div>
                </div>
                <div class="game-price <?php echo $isFree ? 'free' : ''; ?>"><?php echo $isFree ? 'Free' : '$' . number_format($c['price'], 2); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
(function() {
    document.querySelectorAll('.steam-cat-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var filter = this.dataset.filter;
            document.querySelectorAll('.steam-cat-link').forEach(function(l) { l.classList.remove('active'); });
            this.classList.add('active');

            // Filter featured cards
            document.querySelectorAll('.steam-feat-card').forEach(function(card) {
                card.style.display = (filter === 'all' || card.dataset.genre === filter) ? '' : 'none';
            });
            // Filter game rows
            document.querySelectorAll('.steam-game-row').forEach(function(row) {
                row.style.display = (filter === 'all' || row.dataset.genre === filter) ? '' : 'none';
            });
        });
    });
})();
</script>

<?php else: ?>
<!-- ======== DEFAULT CARD GRID LAYOUT (non-games themes) ======== -->
<div class="concert-grid">
    <?php foreach ($concerts as $i => $c):
        $poster = $c['poster_url'] ?? '';
        $hasImage = !empty($poster)
            && strpos($poster, 'data:') !== 0
            && (file_exists($poster) || substr($poster, 0, 4) === 'http');
        $link = $c['spotify_url'] ?? '';
    ?>
    <div class="concert-card">
        <?php if ($hasImage): ?>
            <img class="concert-poster" src="<?php echo htmlspecialchars($poster); ?>" alt="<?php echo htmlspecialchars($c['band_name']); ?>" loading="lazy">
        <?php else: ?>
            <div class="concert-poster-placeholder" style="background:<?php echo $gradients[$i % count($gradients)]; ?>">
                <i class="<?php echo $theme['poster_icon']; ?>"></i>
            </div>
        <?php endif; ?>
        <div class="concert-info">
            <span class="genre-tag"><?php echo htmlspecialchars($c['genre']); ?></span>
            <div class="concert-band" title="<?php echo htmlspecialchars($c['band_name']); ?>"><?php echo htmlspecialchars($c['band_name']); ?></div>
            <div class="concert-venue">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo htmlspecialchars($c['venue']); ?>, <?php echo htmlspecialchars($c['city']); ?>
            </div>
            <div class="concert-meta">
                <span class="concert-price">$<?php echo number_format($c['price'], 0); ?></span>
                <span class="concert-date"><?php echo date('M j', strtotime($c['concert_date'])); ?></span>
            </div>
            <?php if (!empty($link)): ?>
            <a href="<?php echo htmlspecialchars($link); ?>" target="_blank" rel="noopener" class="spotify-link" onclick="event.stopPropagation()" style="color:<?php echo $theme['link_color']; ?>">
                <i class="<?php echo $theme['link_icon']; ?>"></i> <?php echo htmlspecialchars($theme['link_label']); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
(function() {
    var slider = document.getElementById('cardSizeSlider');
    if (!slider) return;
    var saved = localStorage.getItem('ticketdisaster_cardsize');
    if (saved) slider.value = saved;
    function apply(v) {
        v = parseInt(v);
        document.documentElement.style.setProperty('--poster-h', v + 'px');
        var minCol = Math.max(100, Math.round(v * 1.65));
        document.documentElement.style.setProperty('--card-min-w', minCol + 'px');
    }
    apply(slider.value);
    slider.addEventListener('input', function() {
        apply(this.value);
        localStorage.setItem('ticketdisaster_cardsize', this.value);
    });
})();
</script>
<?php require_once 'dvwa/includes/footer.php'; ?>
