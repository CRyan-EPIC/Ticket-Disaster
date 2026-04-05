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

if ($currentThemeKey === 'games'):
// ======== STEAM-LIKE LAYOUT FOR GAMES THEME ========
$featured = array_slice($concerts, 0, 5);
$remaining = array_slice($concerts, 5);
?>
<style>
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
    .steam-featured-grid { grid-template-columns: 1fr; }
    .steam-featured-grid .steam-feat-card:first-child { grid-row: span 1; }
}
</style>

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

<?php elseif ($currentThemeKey === 'cars'): ?>
<!-- ======== CRAIGSLIST-STYLE LAYOUT FOR CARS THEME ======== -->
<style>
.cl-list { display: flex; flex-direction: column; gap: 0; }
.cl-row {
    display: grid; grid-template-columns: 180px 1fr auto; gap: 0;
    border-bottom: 1px solid #1e1e30; padding: 0; transition: background 0.1s;
    cursor: pointer; background: #0c0c14;
}
.cl-row:hover { background: #151525; }
.cl-row:first-child { border-top: 1px solid #1e1e30; }
.cl-thumb {
    width: 180px; height: 120px; object-fit: cover; display: block;
    border-right: 1px solid #1e1e30;
}
.cl-thumb-ph {
    width: 180px; height: 120px; display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; color: rgba(255,255,255,0.08);
    border-right: 1px solid #1e1e30;
}
.cl-details { padding: 0.6rem 1rem; min-width: 0; display: flex; flex-direction: column; justify-content: center; }
.cl-title {
    font-size: 0.92rem; font-weight: 700; color: #60a5fa;
    margin-bottom: 0.25rem; text-decoration: none;
}
.cl-title:hover { text-decoration: underline; }
.cl-desc {
    font-size: 0.78rem; color: #8892a4; line-height: 1.45;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    margin-bottom: 0.3rem;
}
.cl-meta { font-size: 0.72rem; color: #555; display: flex; gap: 1rem; align-items: center; }
.cl-meta span { display: inline-flex; align-items: center; gap: 0.25rem; }
.cl-price-col {
    padding: 0.6rem 1rem; display: flex; flex-direction: column; align-items: flex-end;
    justify-content: center; min-width: 90px; border-left: 1px solid #1e1e30;
}
.cl-price { font-size: 1.1rem; font-weight: 800; color: #22c55e; }
.cl-type { font-size: 0.68rem; color: #555; margin-top: 0.2rem; }
.cl-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 0.5rem 0; margin-bottom: 0.5rem; border-bottom: 2px solid #ef4444;
}
.cl-header-title { font-size: 0.82rem; font-weight: 700; color: #f87171; text-transform: uppercase; letter-spacing: 1px; }
.cl-header-count { font-size: 0.75rem; color: #555; }
@media (max-width: 640px) {
    .cl-row { grid-template-columns: 100px 1fr; }
    .cl-thumb, .cl-thumb-ph { width: 100px; height: 80px; }
    .cl-price-col { grid-column: 1 / -1; flex-direction: row; justify-content: space-between; border-left: none; border-top: 1px solid #1e1e30; }
}
</style>

<div class="cl-header">
    <div class="cl-header-title"><i class="fas fa-car"></i> Vehicles for Sale by Owner</div>
    <div class="cl-header-count"><?php echo count($concerts); ?> postings</div>
</div>

<div class="cl-list">
    <?php foreach ($concerts as $i => $c):
        $poster = $c['poster_url'] ?? '';
        $hasImage = !empty($poster) && strpos($poster, 'data:') !== 0 && (file_exists($poster) || substr($poster, 0, 4) === 'http');
    ?>
    <div class="cl-row">
        <?php if ($hasImage): ?>
            <img class="cl-thumb" src="<?php echo htmlspecialchars($poster); ?>" alt="" loading="lazy">
        <?php else: ?>
            <div class="cl-thumb-ph" style="background:<?php echo $gradients[$i % count($gradients)]; ?>">
                <i class="fas fa-car"></i>
            </div>
        <?php endif; ?>
        <div class="cl-details">
            <div class="cl-title"><?php echo htmlspecialchars($c['band_name']); ?></div>
            <div class="cl-desc"><?php echo htmlspecialchars($c['description'] ?? ''); ?></div>
            <div class="cl-meta">
                <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($c['venue']); ?>, <?php echo htmlspecialchars($c['city']); ?></span>
                <span><i class="fas fa-calendar"></i> <?php echo date('M j', strtotime($c['concert_date'])); ?></span>
                <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($c['genre']); ?></span>
            </div>
        </div>
        <div class="cl-price-col">
            <div class="cl-price">$<?php echo number_format($c['price'], 0); ?></div>
            <div class="cl-type"><?php echo htmlspecialchars($c['genre']); ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php else: ?>
<!-- ======== DEFAULT CARD GRID LAYOUT (music/sports themes) ======== -->
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
