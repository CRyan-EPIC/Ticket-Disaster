#!/usr/bin/env php
<?php
/**
 * CLI script for cron-based scraping.
 * Usage: php cron_scrape.php [--replace] [--theme=music|sports|games|cars]
 */

require_once __DIR__ . '/config/config.inc.php';
require_once __DIR__ . '/config/themes.php';
require_once __DIR__ . '/dvwa/includes/Scraper.php';

// Parse args
$replace = in_array('--replace', $argv ?? []);
$themeKey = 'music';
foreach ($argv ?? [] as $arg) {
    if (strpos($arg, '--theme=') === 0) {
        $themeKey = substr($arg, 8);
    }
}
if (!isset($THEMES[$themeKey])) $themeKey = 'music';

echo "[" . date('Y-m-d H:i:s') . "] Starting scrape for theme: {$themeKey}...\n";

$scraper = new ConcertScraper();

// Scrape events
$events = $scraper->scrapeAll($themeKey);
echo "[" . date('Y-m-d H:i:s') . "] Found " . count($events) . " items.\n";

// Save to database
$saved = $scraper->saveToDatabase($events, $replace);
echo "[" . date('Y-m-d H:i:s') . "] Saved {$saved} items.\n";

// Update images
$updated = $scraper->updateExistingImages($themeKey);
echo "[" . date('Y-m-d H:i:s') . "] Updated {$updated} images.\n";

// Print log
foreach ($scraper->getLog() as $line) {
    echo "  {$line}\n";
}

echo "[" . date('Y-m-d H:i:s') . "] Done.\n";
