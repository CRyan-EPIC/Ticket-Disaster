<?php
$page_title = 'Data Scraper';
$base_path = '';
require_once 'dvwa/includes/header.php';
require_once 'dvwa/includes/Scraper.php';

$theme = getThemeConfig();
$themeKey = getCurrentTheme() ?? 'music';

$scraper = new ConcertScraper();
$message = '';
$log = [];

// Handle scrape action
if (isset($_POST['scrape_all'])) {
    set_time_limit(300);
    $events = $scraper->scrapeAll($themeKey);
    $count = $scraper->saveToDatabase($events, isset($_POST['replace_all']));
    $log = $scraper->getLog();
    $message = "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Scraping complete! Found and saved {$count} " . strtolower($theme['item_label_plural']) . ".</div>";
}

// Handle image update
if (isset($_POST['update_images'])) {
    set_time_limit(300);
    $updated = $scraper->updateExistingImages($themeKey);
    $log = $scraper->getLog();
    $message = "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Updated {$updated} images.</div>";
}

// Get current items for preview
$conn = dvwaDbConnect();
$result = $conn->query("SELECT * FROM concerts ORDER BY concert_date ASC");
$concerts = [];
while ($row = $result->fetch_assoc()) {
    $concerts[] = $row;
}
$conn->close();
?>

<div class="page-header">
    <h1 class="page-title"><i class="fas fa-spider"></i> Data Scraper</h1>
    <p class="page-subtitle">Scrape and update <?php echo strtolower($theme['item_label_plural']); ?> data from online sources</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-satellite-dish"></i> Scraping Controls</div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Scrapes <?php echo strtolower($theme['item_label_plural']); ?> data from online sources.
                    Images are fetched from Wikipedia and other sources.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <form method="POST">
                            <div class="form-check mb-3">
                                <input type="checkbox" name="replace_all" class="form-check-input" id="replaceAll">
                                <label class="form-check-label text-muted small" for="replaceAll">
                                    Replace all existing <?php echo strtolower($theme['item_label_plural']); ?>
                                </label>
                            </div>
                            <button type="submit" name="scrape_all" class="btn btn-purple w-100">
                                <i class="fas fa-download"></i> Scrape All Sources
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="POST">
                            <p class="text-muted small mb-3">
                                Download/update images for items already in the database.
                            </p>
                            <button type="submit" name="update_images" class="btn btn-outline-purple w-100">
                                <i class="fas fa-images"></i> Update Images Only
                            </button>
                        </form>
                    </div>
                </div>

                <?php echo $message; ?>

                <?php if ($themeKey === 'music'): ?>
                <div class="mt-3">
                    <h6 class="text-muted small">Targeted Venues (Denver / Boulder / Fort Collins):</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($scraper->getVenues() as $v): ?>
                            <span class="genre-tag"><?php echo htmlspecialchars($v['name']); ?> <small class="opacity-75">(<?php echo $v['city']; ?>)</small></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($log)): ?>
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-terminal"></i> Scraper Log</div>
            <div class="card-body">
                <div class="vuln-output" style="max-height: 300px;">
<?php foreach ($log as $line): ?>
<?php echo htmlspecialchars($line); ?>
<?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Current items preview -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-database"></i> Current <?php echo htmlspecialchars($theme['item_label_plural']); ?> in Database (<?php echo count($concerts); ?>)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark mb-0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th><?php echo htmlspecialchars($theme['item_label']); ?></th>
                                <th><?php echo htmlspecialchars($theme['venue_label']); ?></th>
                                <th><?php echo htmlspecialchars($theme['date_label']); ?></th>
                                <th><?php echo htmlspecialchars($theme['price_label']); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($concerts as $c): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($c['poster_url']) && file_exists($c['poster_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($c['poster_url']); ?>" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                    <?php else: ?>
                                        <div style="width:40px;height:40px;border-radius:6px;background:linear-gradient(135deg,<?php echo $theme['accent_dark']; ?>,<?php echo $theme['secondary']; ?>);display:flex;align-items:center;justify-content:center;font-size:12px;color:rgba(255,255,255,0.5);">
                                            <i class="<?php echo $theme['poster_icon']; ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($c['band_name']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($c['venue']); ?>, <?php echo htmlspecialchars($c['city']); ?></td>
                                <td class="text-muted"><?php echo $c['concert_date']; ?></td>
                                <td class="text-purple">$<?php echo number_format($c['price'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-clock"></i> Auto-Update (Cron)</div>
            <div class="card-body">
                <p class="text-muted small">To auto-update data daily, add this cron job:</p>
                <div class="vuln-output" style="font-size: 0.75rem;">
# Run daily at 6am
0 6 * * * cd <?php echo dirname(__FILE__); ?> && php cron_scrape.php >> /var/log/ticketdisaster/scraper.log 2>&1</div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'dvwa/includes/footer.php'; ?>
