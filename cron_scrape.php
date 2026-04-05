#!/usr/bin/env php
<?php
/**
 * CLI script for cron-based concert scraping.
 * Usage: php cron_scrape.php [--replace]
 */

require_once __DIR__ . '/config/config.inc.php';
require_once __DIR__ . '/dvwa/includes/Scraper.php';

echo "[" . date('Y-m-d H:i:s') . "] Starting concert scrape...\n";

$scraper = new ConcertScraper();
$replace = in_array('--replace', $argv ?? []);

// Scrape events
$events = $scraper->scrapeAll();
echo "[" . date('Y-m-d H:i:s') . "] Found " . count($events) . " events.\n";

// Save to database
$saved = $scraper->saveToDatabase($events, $replace);
echo "[" . date('Y-m-d H:i:s') . "] Saved {$saved} concerts.\n";

// Update images
$updated = $scraper->updateExistingImages();
echo "[" . date('Y-m-d H:i:s') . "] Updated {$updated} images.\n";

// Print log
foreach ($scraper->getLog() as $line) {
    echo "  {$line}\n";
}

echo "[" . date('Y-m-d H:i:s') . "] Done.\n";
