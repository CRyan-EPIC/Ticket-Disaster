<?php
/**
 * Concert scraper for Colorado Front Range venues.
 * Sources: Bandsintown, venue pages, JSON-LD extraction.
 * Images: Wikipedia REST API + DuckDuckGo fallback.
 * Spotify: Direct search links.
 */
class ConcertScraper {

    private $imageDir;
    private $log = [];

    // Colorado Front Range venues to scrape
    private $venues = [
        // Red Rocks & Morrison
        ['name' => 'Red Rocks Amphitheatre', 'city' => 'Morrison', 'region' => 'Denver'],
        // Denver
        ['name' => 'Mission Ballroom', 'city' => 'Denver', 'region' => 'Denver'],
        ['name' => 'Ball Arena', 'city' => 'Denver', 'region' => 'Denver'],
        ['name' => 'Ogden Theatre', 'city' => 'Denver', 'region' => 'Denver'],
        ['name' => 'Gothic Theatre', 'city' => 'Englewood', 'region' => 'Denver'],
        ['name' => 'Bluebird Theater', 'city' => 'Denver', 'region' => 'Denver'],
        ['name' => 'Cervantes Masterpiece Ballroom', 'city' => 'Denver', 'region' => 'Denver'],
        ['name' => 'Summit', 'city' => 'Denver', 'region' => 'Denver'],
        ['name' => 'Fillmore Auditorium', 'city' => 'Denver', 'region' => 'Denver'],
        ['name' => "Fiddler's Green Amphitheatre", 'city' => 'Greenwood Village', 'region' => 'Denver'],
        ['name' => 'Paramount Theatre', 'city' => 'Denver', 'region' => 'Denver'],
        ['name' => 'Levitt Pavilion', 'city' => 'Denver', 'region' => 'Denver'],
        ['name' => 'Meow Wolf', 'city' => 'Denver', 'region' => 'Denver'],
        // Boulder
        ['name' => 'Boulder Theater', 'city' => 'Boulder', 'region' => 'Boulder'],
        ['name' => 'Fox Theatre', 'city' => 'Boulder', 'region' => 'Boulder'],
        ['name' => 'CU Events Center', 'city' => 'Boulder', 'region' => 'Boulder'],
        // Fort Collins
        ['name' => "Washington's", 'city' => 'Fort Collins', 'region' => 'Fort Collins'],
        ['name' => 'Aggie Theatre', 'city' => 'Fort Collins', 'region' => 'Fort Collins'],
        ['name' => 'The Mishawaka', 'city' => 'Bellvue', 'region' => 'Fort Collins'],
        ['name' => 'Budweiser Events Center', 'city' => 'Loveland', 'region' => 'Fort Collins'],
        ['name' => 'The Coast', 'city' => 'Fort Collins', 'region' => 'Fort Collins'],
    ];

    public function __construct() {
        $this->imageDir = dirname(__FILE__) . '/../../assets/images/bands/';
        if (!is_dir($this->imageDir)) {
            mkdir($this->imageDir, 0777, true);
        }
    }

    public function getLog() { return $this->log; }
    public function getVenues() { return $this->venues; }

    private function log($msg) {
        $this->log[] = date('H:i:s') . ' ' . $msg;
    }

    private function fetch($url, $headers = [], $timeout = 20) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/125.0.0.0 Safari/537.36',
            CURLOPT_HTTPHEADER => array_merge([
                'Accept: text/html,application/json,application/xhtml+xml,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.9',
            ], $headers),
            CURLOPT_ENCODING => 'gzip, deflate',
        ]);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);
        if ($err) { $this->log("CURL err: $err ($url)"); return null; }
        if ($code >= 400) { $this->log("HTTP $code: $url"); return null; }
        return $body;
    }

    /* ------------------------------------------------------------------ */
    /*  MAIN ENTRY                                                         */
    /* ------------------------------------------------------------------ */
    public function scrapeAll($themeKey = 'music') {
        // For non-music themes, use theme-specific scrapers with seed data fallback
        if ($themeKey === 'sports') return $this->scrapeWithFallback('sports');
        if ($themeKey === 'games')  return $this->scrapeWithFallback('games');
        if ($themeKey === 'cars')   return $this->scrapeWithFallback('cars');

        $all = [];

        // 1. Bandsintown city pages (Denver, Boulder, Fort Collins)
        $cities = [
            'denver-co'       => ['Denver', 'Morrison', 'Englewood', 'Greenwood Village'],
            'boulder-co'      => ['Boulder'],
            'fort-collins-co'  => ['Fort Collins', 'Loveland', 'Bellvue'],
        ];
        foreach ($cities as $slug => $cityNames) {
            $this->log("Scraping Bandsintown: $slug");
            $events = $this->scrapeBandsintown($slug, $cityNames[0]);
            $this->log("  -> " . count($events) . " events");
            $all = array_merge($all, $events);
            usleep(400000);
        }

        // 2. Songkick Denver metro
        $this->log("Scraping Songkick Denver metro...");
        $events = $this->scrapeSongkick();
        $this->log("  -> " . count($events) . " events");
        $all = array_merge($all, $events);

        // 3. Individual venue pages (JSON-LD + HTML)
        $venueUrls = [
            ['https://www.redrocksonline.com/concerts-events/', 'Red Rocks Amphitheatre', 'Morrison'],
            ['https://www.missionballroom.com/events', 'Mission Ballroom', 'Denver'],
            ['https://www.ogdentheatre.com/events', 'Ogden Theatre', 'Denver'],
            ['https://www.gothictheatre.com/events', 'Gothic Theatre', 'Englewood'],
            ['https://www.bluebirdtheater.net/events', 'Bluebird Theater', 'Denver'],
            ['https://www.fillmoreauditorium.org/events', 'Fillmore Auditorium', 'Denver'],
            ['https://www.bouldertheater.com/events/', 'Boulder Theater', 'Boulder'],
            ['https://www.foxtheatre.com/events/', 'Fox Theatre', 'Boulder'],
            ['https://www.washingtonsfoco.com/events', "Washington's", 'Fort Collins'],
            ['https://www.aggietheatre.com/events', 'Aggie Theatre', 'Fort Collins'],
            ['https://www.themishawaka.com/events', 'The Mishawaka', 'Bellvue'],
        ];
        foreach ($venueUrls as [$url, $venue, $city]) {
            $this->log("Fetching $venue...");
            $html = $this->fetch($url);
            if ($html) {
                $events = $this->extractFromHtml($html, $venue, $city);
                $this->log("  -> " . count($events) . " events");
                $all = array_merge($all, $events);
            }
            usleep(400000);
        }

        // Deduplicate
        $all = $this->dedupe($all);
        $this->log("Total unique events: " . count($all));

        // Fetch images + Spotify for all
        foreach ($all as &$e) {
            if (empty($e['poster_url'])) {
                $e['poster_url'] = $this->fetchArtistImage($e['band_name']);
                usleep(250000);
            }
            $e['spotify_url'] = $this->spotifySearchUrl($e['band_name']);
        }

        return $all;
    }

    /* ------------------------------------------------------------------ */
    /*  BANDSINTOWN                                                        */
    /* ------------------------------------------------------------------ */
    private function scrapeBandsintown($citySlug, $defaultCity) {
        $events = [];
        $url = "https://www.bandsintown.com/c/{$citySlug}";
        $html = $this->fetch($url);
        if (!$html) return $events;

        // Extract JSON-LD
        $events = array_merge($events, $this->extractJsonLd($html, $defaultCity));

        // Also try __NEXT_DATA__ (Next.js data blob)
        if (preg_match('/<script id="__NEXT_DATA__"[^>]*>(.*?)<\/script>/s', $html, $m)) {
            $data = json_decode($m[1], true);
            $eventsData = $this->findEventsInJson($data);
            foreach ($eventsData as $e) {
                $events[] = $e + ['city' => $defaultCity];
            }
        }

        // HTML fallback: look for artist names in event containers
        $events = array_merge($events, $this->parseHtmlEvents($html, 'Bandsintown Venue', $defaultCity));

        return $events;
    }

    /* ------------------------------------------------------------------ */
    /*  SONGKICK                                                           */
    /* ------------------------------------------------------------------ */
    private function scrapeSongkick() {
        $events = [];
        // Denver metro area ID on Songkick
        $url = 'https://www.songkick.com/metro-areas/11776-us-denver';
        $html = $this->fetch($url);
        if (!$html) return $events;

        $events = array_merge($events, $this->extractJsonLd($html, 'Denver'));
        $events = array_merge($events, $this->parseHtmlEvents($html, 'Colorado Venue', 'Denver'));
        return $events;
    }

    /* ------------------------------------------------------------------ */
    /*  GENERIC HTML + JSON-LD EXTRACTION                                  */
    /* ------------------------------------------------------------------ */
    private function extractFromHtml($html, $venue, $city) {
        $events = [];
        // JSON-LD first (most reliable)
        $jld = $this->extractJsonLd($html, $city);
        foreach ($jld as &$e) {
            if (empty($e['venue']) || $e['venue'] === 'Unknown Venue') $e['venue'] = $venue;
        }
        $events = array_merge($events, $jld);

        // __NEXT_DATA__
        if (preg_match('/<script id="__NEXT_DATA__"[^>]*>(.*?)<\/script>/s', $html, $m)) {
            $data = json_decode($m[1], true);
            $found = $this->findEventsInJson($data);
            foreach ($found as &$e) {
                if (empty($e['venue'])) $e['venue'] = $venue;
                if (empty($e['city'])) $e['city'] = $city;
            }
            $events = array_merge($events, $found);
        }

        // HTML parsing
        $events = array_merge($events, $this->parseHtmlEvents($html, $venue, $city));

        return $events;
    }

    /**
     * Extract JSON-LD MusicEvent / Event blocks
     */
    private function extractJsonLd($html, $defaultCity) {
        $events = [];
        if (!preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches))
            return $events;

        foreach ($matches[1] as $raw) {
            $data = json_decode(trim($raw), true);
            if (!$data) continue;

            $items = isset($data['@graph']) ? $data['@graph'] : (isset($data[0]) ? $data : [$data]);
            foreach ($items as $item) {
                $type = $item['@type'] ?? '';
                if (is_array($type)) $type = $type[0];
                if (!in_array($type, ['MusicEvent', 'Event', 'Festival', 'DanceEvent'])) continue;

                $band = '';
                if (isset($item['performer'])) {
                    $p = is_array($item['performer']) ?
                        (isset($item['performer']['name']) ? $item['performer'] : ($item['performer'][0] ?? [])) :
                        $item['performer'];
                    $band = is_array($p) ? ($p['name'] ?? '') : $p;
                }
                if (!$band) {
                    $band = $item['name'] ?? '';
                    $band = preg_replace('/\s+(at|@|live at|presented by)\s+.*/i', '', $band);
                }
                $band = trim($band);
                if (!$band || strlen($band) < 2 || strlen($band) > 120) continue;

                $date = '';
                if (!empty($item['startDate'])) {
                    $ts = strtotime($item['startDate']);
                    if ($ts) $date = date('Y-m-d', $ts);
                }

                $price = 0;
                if (isset($item['offers'])) {
                    $o = is_array($item['offers']) ?
                        (isset($item['offers']['price']) ? $item['offers'] : ($item['offers'][0] ?? [])) :
                        [];
                    $price = floatval($o['price'] ?? $o['lowPrice'] ?? 0);
                }

                $venueName = '';
                if (isset($item['location'])) {
                    $loc = $item['location'];
                    $venueName = is_array($loc) ? ($loc['name'] ?? '') : '';
                }

                $img = $item['image'] ?? '';
                if (is_array($img)) $img = $img[0] ?? '';

                $events[] = [
                    'band_name' => $band,
                    'venue' => $venueName ?: 'Unknown Venue',
                    'city' => $defaultCity,
                    'concert_date' => $date ?: date('Y-m-d', strtotime('+' . rand(7, 150) . ' days')),
                    'price' => $price ?: (rand(25, 180) + 0.0),
                    'tickets_available' => rand(20, 800),
                    'genre' => $this->guessGenre($band),
                    'description' => "Live at " . ($venueName ?: 'a Colorado venue') . ".",
                    'poster_url' => $img,
                    'spotify_url' => '',
                ];
            }
        }
        return $events;
    }

    /**
     * Recursively find event-like objects in a JSON blob (Next.js data, etc.)
     */
    private function findEventsInJson($data, $depth = 0) {
        $events = [];
        if (!is_array($data) || $depth > 8) return $events;

        // Check if this object looks like an event
        $hasName = isset($data['name']) || isset($data['title']) || isset($data['artistName']) || isset($data['artist']);
        $hasDate = isset($data['date']) || isset($data['startDate']) || isset($data['datetime']) || isset($data['starts_at']);
        if ($hasName && $hasDate) {
            $band = $data['artistName'] ?? $data['artist']['name'] ?? $data['name'] ?? $data['title'] ?? '';
            if (is_array($band)) $band = $band['name'] ?? '';
            $band = trim($band);
            if ($band && strlen($band) > 1 && strlen($band) < 120) {
                $dateRaw = $data['startDate'] ?? $data['datetime'] ?? $data['starts_at'] ?? $data['date'] ?? '';
                $date = $dateRaw ? date('Y-m-d', strtotime($dateRaw)) : '';
                $venue = $data['venue']['name'] ?? $data['venueName'] ?? $data['location'] ?? '';
                if (is_array($venue)) $venue = $venue['name'] ?? '';

                $events[] = [
                    'band_name' => $band,
                    'venue' => $venue ?: 'Unknown Venue',
                    'city' => '',
                    'concert_date' => $date ?: date('Y-m-d', strtotime('+' . rand(7, 150) . ' days')),
                    'price' => rand(25, 180) + 0.0,
                    'tickets_available' => rand(20, 800),
                    'genre' => $this->guessGenre($band),
                    'description' => "Live at " . ($venue ?: 'a Colorado venue') . ".",
                    'poster_url' => $data['image'] ?? $data['imageUrl'] ?? '',
                    'spotify_url' => '',
                ];
            }
        }

        // Recurse
        foreach ($data as $v) {
            if (is_array($v)) {
                $events = array_merge($events, $this->findEventsInJson($v, $depth + 1));
            }
        }
        return $events;
    }

    /**
     * HTML DOM parsing fallback for event listings
     */
    private function parseHtmlEvents($html, $venue, $city) {
        $events = [];
        $dom = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR);
        $xpath = new DOMXPath($dom);

        // Common CSS class patterns for event listings
        $selectors = [
            '//div[contains(@class,"event")]',
            '//article[contains(@class,"event")]',
            '//li[contains(@class,"event")]',
            '//div[contains(@class,"show")]',
            '//div[contains(@class,"listing")]',
            '//a[contains(@class,"event-listing")]',
        ];

        foreach ($selectors as $sel) {
            $nodes = $xpath->query($sel);
            if (!$nodes || $nodes->length < 2) continue;

            foreach ($nodes as $node) {
                // Find title/heading
                $name = '';
                foreach (['h1','h2','h3','h4','a','span','div'] as $tag) {
                    $titles = $xpath->query(".//{$tag}[contains(@class,'title') or contains(@class,'name') or contains(@class,'artist') or contains(@class,'headlin')]", $node);
                    if ($titles->length > 0) { $name = trim($titles->item(0)->textContent); break; }
                }
                if (!$name) {
                    $headings = $xpath->query('.//h2|.//h3|.//h4', $node);
                    if ($headings->length > 0) $name = trim($headings->item(0)->textContent);
                }
                if (!$name || strlen($name) < 2 || strlen($name) > 150) continue;

                // Clean up
                $name = preg_replace('/\s+(at|@|live at|presented by|with special guest)\s+.*/i', '', $name);
                $name = preg_replace('/\s*[-|:]\s*(SOLD OUT|On Sale|Tickets|Ages).*$/i', '', $name);
                $name = trim($name);
                if (!$name) continue;

                // Find date
                $date = '';
                $dateNodes = $xpath->query('.//*[contains(@class,"date")]|.//time|.//*[@datetime]', $node);
                if ($dateNodes->length > 0) {
                    $d = $dateNodes->item(0);
                    $date = $d->getAttribute('datetime') ?: trim($d->textContent);
                    $ts = strtotime($date);
                    $date = $ts ? date('Y-m-d', $ts) : '';
                }

                // Find image
                $img = '';
                $imgNodes = $xpath->query('.//img', $node);
                if ($imgNodes->length > 0) {
                    $img = $imgNodes->item(0)->getAttribute('src') ?: $imgNodes->item(0)->getAttribute('data-src');
                }

                $events[] = [
                    'band_name' => $name,
                    'venue' => $venue,
                    'city' => $city,
                    'concert_date' => $date ?: date('Y-m-d', strtotime('+' . rand(7, 150) . ' days')),
                    'price' => rand(25, 180) + 0.0,
                    'tickets_available' => rand(20, 800),
                    'genre' => $this->guessGenre($name),
                    'description' => "Live at {$venue}, {$city}.",
                    'poster_url' => $img,
                    'spotify_url' => '',
                ];
            }
            if (count($events) > 0) break; // Use first selector that matched
        }
        return $events;
    }

    /* ------------------------------------------------------------------ */
    /*  IMAGE FETCHING (Wikipedia + DuckDuckGo)                            */
    /* ------------------------------------------------------------------ */

    /**
     * Fetch an image for a given name, with theme-aware Wikipedia slug variants.
     * $context: 'music' (default), 'sports', 'games', 'cars'
     */
    public function fetchArtistImage($name, $context = 'music') {
        $safe = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        $local = $this->imageDir . $safe . '.jpg';
        $web   = 'assets/images/bands/' . $safe . '.jpg';

        if (file_exists($local) && filesize($local) > 500) return $web;

        // Build Wikipedia slug variants based on theme context
        $slug = str_replace(' ', '_', $name);
        switch ($context) {
            case 'sports':
                // Map short team names to full Wikipedia article names
                $teamMap = [
                    'Nuggets' => 'Denver_Nuggets', 'Avalanche' => 'Colorado_Avalanche',
                    'Broncos' => 'Denver_Broncos', 'Rockies' => 'Colorado_Rockies',
                    'Rapids' => 'Colorado_Rapids', 'CU Buffs' => 'Colorado_Buffaloes',
                    'CSU Rams' => 'Colorado_State_Rams', 'Air Force' => 'Air_Force_Falcons',
                    'DU Pioneers' => 'Denver_Pioneers', 'Mammoth' => 'Colorado_Mammoth',
                ];
                $mapped = $slug;
                foreach ($teamMap as $short => $full) {
                    if (stripos($name, $short) !== false) { $mapped = $full; break; }
                }
                $variants = [$mapped, $slug];
                $ddgSuffix = 'sports team';
                break;
            case 'games':
                // For games: try exact title, then without subtitle (after colon),
                // then with _(video_game) suffix
                $cleanSlug = preg_replace('/[:_]-.*$/', '', $slug); // strip subtitle
                $variants = [$slug];
                if ($cleanSlug !== $slug) $variants[] = $cleanSlug;
                $variants[] = "{$slug}_(video_game)";
                if ($cleanSlug !== $slug) $variants[] = "{$cleanSlug}_(video_game)";
                $ddgSuffix = 'video game';
                break;
            case 'cars':
                // For cars: try the make/model
                $variants = [$slug];
                $ddgSuffix = 'car';
                break;
            default: // music
                $variants = [$slug, "{$slug}_(band)", "{$slug}_(musician)", "{$slug}_(singer)", "{$slug}_(rapper)"];
                $ddgSuffix = 'band';
                break;
        }

        foreach ($variants as $v) {
            usleep(350000); // rate-limit: ~3 req/sec to avoid Wikipedia 429s
            $json = $this->fetch("https://en.wikipedia.org/api/rest_v1/page/summary/" . rawurlencode($v), ['Accept: application/json'], 10);
            if (!$json) continue;
            $d = json_decode($json, true);
            $imgUrl = $d['thumbnail']['source'] ?? $d['originalimage']['source'] ?? null;
            if (!$imgUrl) continue;

            $imgUrl = preg_replace('/\/\d+px-/', '/400px-', $imgUrl);
            $data = $this->fetch($imgUrl, [], 10);
            // Retry once on 429 (rate limit) after a pause
            if (!$data || strlen($data) <= 500) {
                usleep(2000000); // 2 second backoff
                $data = $this->fetch($imgUrl, [], 10);
            }
            if ($data && strlen($data) > 500) {
                file_put_contents($local, $data);
                $this->log("Image OK: $name (Wikipedia)");
                return $web;
            }
        }

        // DuckDuckGo Instant Answer fallback
        $json = $this->fetch("https://api.duckduckgo.com/?q=" . urlencode($name . " " . $ddgSuffix) . "&format=json&no_html=1", [], 10);
        if ($json) {
            $d = json_decode($json, true);
            $imgUrl = $d['Image'] ?? '';
            if ($imgUrl) {
                if (strpos($imgUrl, 'http') !== 0) $imgUrl = 'https://duckduckgo.com' . $imgUrl;
                $data = $this->fetch($imgUrl, [], 10);
                if ($data && strlen($data) > 500) {
                    file_put_contents($local, $data);
                    $this->log("Image OK: $name (DDG)");
                    return $web;
                }
            }
        }

        $this->log("No image: $name");
        return '';
    }

    /* ------------------------------------------------------------------ */
    /*  SPOTIFY LINK                                                       */
    /* ------------------------------------------------------------------ */
    public function spotifySearchUrl($bandName) {
        return 'https://open.spotify.com/search/' . rawurlencode($bandName);
    }

    /* ------------------------------------------------------------------ */
    /*  HELPERS                                                            */
    /* ------------------------------------------------------------------ */
    private function dedupe($events) {
        $seen = [];
        $out  = [];
        foreach ($events as $e) {
            $key = strtolower(trim($e['band_name']));
            // Allow same band at different venues
            $key .= '|' . strtolower(trim($e['venue'] ?? ''));
            $key .= '|' . ($e['concert_date'] ?? '');
            if (isset($seen[$key])) continue;
            $seen[$key] = true;
            $out[] = $e;
        }
        return $out;
    }

    /**
     * Returns false if the event name contains content inappropriate for schools.
     */
    private function isAppropriate($name) {
        $lower = strtolower($name);
        $blocked = [
            'nude', 'naked', 'topless',
            'sex', 'sexual', 'sexy', 'erotic', 'sexu',
            'cannabis', 'cannabuf', 'marijuana', ' weed', 'cocaine', 'heroin',
            '4/20', '420 ', 'x 4/',
            'burlesque', 'stripper', 'striptease', 'fetish', 'bondage',
            'bitch', 'bitches',
            '18+', 'adults only', 'adult only', '21+',
            'explicit', 'xxx', 'porn', 'pornographic',
            'c u next term',
        ];
        foreach ($blocked as $term) {
            if (strpos($lower, $term) !== false) return false;
        }
        return true;
    }

    private function guessGenre($name) {
        $g = ['Rock','Indie Rock','Alternative','Electronic','Hip Hop','Pop','R&B','Folk',
              'Country','Jazz','Metal','Punk','Bluegrass','Jam Band','Psychedelic','Soul','Reggae','EDM'];
        return $g[abs(crc32(strtolower($name))) % count($g)];
    }

    /* ------------------------------------------------------------------ */
    /*  DATABASE                                                           */
    /* ------------------------------------------------------------------ */
    public function saveToDatabase($events, $replaceAll = false) {
        global $_DVWA;
        require_once dirname(__FILE__) . '/../../config/config.inc.php';
        $conn = new mysqli($_DVWA['db_server'], $_DVWA['db_user'], $_DVWA['db_password'], $_DVWA['db_database'], $_DVWA['db_port']);
        if ($conn->connect_error) { $this->log("DB err: " . $conn->connect_error); return 0; }

        // Ensure spotify_url column exists
        try { $conn->query("ALTER TABLE concerts ADD COLUMN spotify_url VARCHAR(500) DEFAULT ''"); } catch (Exception $e) {};

        if ($replaceAll) {
            $conn->query("DELETE FROM concerts");
            $this->log("Cleared concerts table.");
        }

        $stmt = $conn->prepare(
            "INSERT INTO concerts (band_name, venue, city, concert_date, price, tickets_available, genre, poster_url, description, spotify_url)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $n = 0;
        foreach ($events as $e) {
            if (empty($e['band_name'])) continue;
            if (!$this->isAppropriate($e['band_name'])) {
                $this->log("Skipped (content): " . $e['band_name']);
                continue;
            }
            $band = substr($e['band_name'], 0, 100);
            $venue = substr($e['venue'] ?? '', 0, 200);
            $city = substr($e['city'] ?? 'Denver', 0, 100);
            $date = $e['concert_date'] ?: date('Y-m-d', strtotime('+' . rand(7,150) . ' days'));
            $price = floatval($e['price'] ?: rand(25,180));
            $tickets = intval($e['tickets_available'] ?: rand(20,800));
            $genre = substr($e['genre'] ?? 'Rock', 0, 50);
            $poster = substr($e['poster_url'] ?? '', 0, 500);
            $desc = substr($e['description'] ?? "Live at $venue.", 0, 1000);
            $spotify = substr($e['spotify_url'] ?? $this->spotifySearchUrl($band), 0, 500);
            $stmt->bind_param('ssssdissss', $band, $venue, $city, $date, $price, $tickets, $genre, $poster, $desc, $spotify);
            if ($stmt->execute()) $n++;
        }
        $conn->close();
        $this->log("Saved $n concerts.");
        return $n;
    }

    /* ------------------------------------------------------------------ */
    /*  FALLBACK: try scraper, then fall back to seed data                 */
    /* ------------------------------------------------------------------ */
    private function scrapeWithFallback($themeKey) {
        $events = [];
        switch ($themeKey) {
            case 'sports': $events = $this->scrapeSports(); break;
            case 'games':  $events = $this->scrapeGames(); break;
            case 'cars':   $events = $this->scrapeCars(); break;
        }

        if (empty($events)) {
            $this->log("No results from live scraping for '$themeKey' — using seed data.");
            require_once dirname(__FILE__) . '/../../setup_seeds.php';
            $seedData = getSeedData($themeKey);
            foreach ($seedData as $c) {
                $events[] = [
                    'band_name' => $c[0],
                    'venue' => $c[1],
                    'city' => $c[2],
                    'concert_date' => $c[3],
                    'price' => $c[4],
                    'tickets_available' => $c[5],
                    'genre' => $c[6],
                    'description' => $c[8],
                    'poster_url' => $c[7],
                    'spotify_url' => $c[9],
                ];
            }
            $this->log("Loaded " . count($events) . " items from seed data.");
        }

        return $events;
    }

    /* ------------------------------------------------------------------ */
    /*  SPORTS SCRAPER (Denver area teams)                                 */
    /* ------------------------------------------------------------------ */
    private function scrapeSports() {
        $events = [];
        $this->log("Scraping Denver sports schedules...");

        // Try ESPN API for Denver teams
        $teams = [
            ['Denver Nuggets', 'nba', 'den', 'Ball Arena', 'Denver', 'Basketball (NBA)'],
            ['Colorado Avalanche', 'nhl', 'col', 'Ball Arena', 'Denver', 'Hockey (NHL)'],
            ['Denver Broncos', 'nfl', 'den', 'Empower Field at Mile High', 'Denver', 'Football (NFL)'],
            ['Colorado Rockies', 'mlb', 'col', 'Coors Field', 'Denver', 'Baseball (MLB)'],
        ];

        foreach ($teams as [$teamName, $league, $abbrev, $venue, $city, $sport]) {
            $this->log("  Fetching $teamName schedule...");
            $url = "https://site.api.espn.com/apis/site/v2/sports/{$this->espnSportPath($league)}/teams/{$abbrev}/schedule";
            $json = $this->fetch($url, ['Accept: application/json'], 15);
            if ($json) {
                $data = json_decode($json, true);
                $gameEvents = $data['events'] ?? [];
                foreach ($gameEvents as $ge) {
                    $name = $ge['name'] ?? $ge['shortName'] ?? '';
                    if (!$name) continue;
                    $date = '';
                    if (!empty($ge['date'])) {
                        $ts = strtotime($ge['date']);
                        if ($ts && $ts > time()) $date = date('Y-m-d', $ts);
                    }
                    if (!$date) continue;

                    $events[] = [
                        'band_name' => $name,
                        'venue' => $venue,
                        'city' => $city,
                        'concert_date' => $date,
                        'price' => rand(35, 250) + 0.0,
                        'tickets_available' => rand(100, 5000),
                        'genre' => $sport,
                        'description' => "Live $sport at $venue.",
                        'poster_url' => '',
                        'spotify_url' => '',
                    ];
                }
                $this->log("    -> " . count($gameEvents) . " games found");
            }
            usleep(300000);
        }

        // Fetch images — extract the home team name for Wikipedia lookup
        foreach ($events as &$e) {
            if (empty($e['poster_url'])) {
                // "Nuggets vs Lakers" → "Denver Nuggets"; use the full team name from the teams array
                $teamName = preg_replace('/ (vs?\.?|at|@) .*/i', '', $e['band_name']);
                $e['poster_url'] = $this->fetchArtistImage(trim($teamName), 'sports');
                usleep(200000);
            }
        }

        $this->log("Total sports events: " . count($events));
        return $events;
    }

    private function espnSportPath($league) {
        $map = ['nba' => 'basketball/nba', 'nhl' => 'hockey/nhl', 'nfl' => 'football/nfl', 'mlb' => 'baseball/mlb'];
        return $map[$league] ?? $league;
    }

    /* ------------------------------------------------------------------ */
    /*  GAMES SCRAPER (Steam/gaming sources)                               */
    /* ------------------------------------------------------------------ */
    private function scrapeGames() {
        $events = [];
        $this->log("Scraping video game data...");

        // Steam featured games API
        $this->log("  Fetching Steam featured games...");
        $json = $this->fetch('https://store.steampowered.com/api/featured/', ['Accept: application/json'], 15);
        if ($json) {
            $data = json_decode($json, true);
            $items = array_merge(
                $data['featured_win'] ?? [],
                $data['large_capsules'] ?? []
            );
            foreach ($items as $item) {
                $name = $item['name'] ?? '';
                if (!$name) continue;
                $price = ($item['final_price'] ?? 0) / 100.0;
                if ($price <= 0) $price = ($item['original_price'] ?? 0) / 100.0;

                $events[] = [
                    'band_name' => $name,
                    'venue' => 'PC (Steam)',
                    'city' => 'Valve',
                    'concert_date' => date('Y-m-d', strtotime('+' . rand(1, 180) . ' days')),
                    'price' => $price > 0 ? $price : 0.00,
                    'tickets_available' => 999999,
                    'genre' => $this->guessGameGenre($name),
                    'description' => "Available on Steam.",
                    'poster_url' => $item['large_capsule_image'] ?? $item['header_image'] ?? '',
                    'spotify_url' => isset($item['id']) ? "https://store.steampowered.com/app/{$item['id']}/" : '',
                ];
            }
            $this->log("    -> " . count($items) . " games from Steam featured");
        }

        // Steam top sellers
        $this->log("  Fetching Steam top sellers...");
        $json = $this->fetch('https://store.steampowered.com/api/featuredcategories/', ['Accept: application/json'], 15);
        if ($json) {
            $data = json_decode($json, true);
            $topSellers = $data['top_sellers']['items'] ?? [];
            foreach ($topSellers as $item) {
                $name = $item['name'] ?? '';
                if (!$name) continue;
                $price = ($item['final_price'] ?? 0) / 100.0;
                $events[] = [
                    'band_name' => $name,
                    'venue' => 'PC (Steam)',
                    'city' => 'Valve',
                    'concert_date' => date('Y-m-d', strtotime('+' . rand(1, 60) . ' days')),
                    'price' => $price > 0 ? $price : 0.00,
                    'tickets_available' => 999999,
                    'genre' => $this->guessGameGenre($name),
                    'description' => "Top seller on Steam.",
                    'poster_url' => $item['large_capsule_image'] ?? $item['header_image'] ?? '',
                    'spotify_url' => isset($item['id']) ? "https://store.steampowered.com/app/{$item['id']}/" : '',
                ];
            }
            $this->log("    -> " . count($topSellers) . " top sellers");
        }

        $events = $this->dedupe($events);
        $this->log("Total unique games: " . count($events));
        return $events;
    }

    private function guessGameGenre($name) {
        $g = ['Action', 'Adventure', 'RPG', 'Strategy', 'Simulation', 'Indie', 'FPS', 'Puzzle', 'Platformer', 'Horror', 'Racing', 'Sports', 'Roguelike', 'Survival'];
        return $g[abs(crc32(strtolower($name))) % count($g)];
    }

    /* ------------------------------------------------------------------ */
    /*  CARS SCRAPER (generates funny used car listings)                    */
    /* ------------------------------------------------------------------ */
    private function scrapeCars() {
        $this->log("Generating used car listings...");
        // Cars theme uses seed data primarily - scraping would just generate more funny entries
        // Return empty so it falls through to seed data
        $this->log("Cars theme uses curated seed data for maximum comedy.");
        return [];
    }

    public function updateExistingImages($themeKey = 'music') {
        global $_DVWA;
        require_once dirname(__FILE__) . '/../../config/config.inc.php';
        $conn = new mysqli($_DVWA['db_server'], $_DVWA['db_user'], $_DVWA['db_password'], $_DVWA['db_database'], $_DVWA['db_port']);
        if ($conn->connect_error) return 0;

        // Ensure spotify_url column
        try { $conn->query("ALTER TABLE concerts ADD COLUMN spotify_url VARCHAR(500) DEFAULT ''"); } catch (Exception $e) {};

        $result = $conn->query("SELECT concert_id, band_name, poster_url, spotify_url FROM concerts");
        $n = 0;
        while ($row = $result->fetch_assoc()) {
            $changed = false;
            $poster = $row['poster_url'];
            $spotify = $row['spotify_url'];

            // Update image if missing — use theme-appropriate Wikipedia lookups
            if (empty($poster) || !file_exists(dirname(__FILE__) . '/../../' . $poster)) {
                $searchName = $row['band_name'];
                // For sports, extract the home team from "Team vs Opponent"
                if ($themeKey === 'sports') {
                    $searchName = preg_replace('/ (vs?\.?|at|@) .*/i', '', $searchName);
                }
                $poster = $this->fetchArtistImage(trim($searchName), $themeKey);
                if ($poster) $changed = true;
                usleep(300000);
            }

            // Update Spotify if missing
            if (empty($spotify)) {
                $spotify = $this->spotifySearchUrl($row['band_name']);
                $changed = true;
            }

            if ($changed) {
                $stmt = $conn->prepare("UPDATE concerts SET poster_url = ?, spotify_url = ? WHERE concert_id = ?");
                $stmt->bind_param('ssi', $poster, $spotify, $row['concert_id']);
                $stmt->execute();
                $n++;
            }
        }
        $conn->close();
        $this->log("Updated $n concerts.");
        return $n;
    }
}
