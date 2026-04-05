<?php
// dvwaPage.inc.php already calls session_start()
require_once __DIR__ . '/dvwa/includes/dvwaPage.inc.php';

// Handle theme selection
if (isset($_POST['theme']) && isset($THEMES[$_POST['theme']])) {
    setTheme($_POST['theme']);
    // Re-seed concerts table for the new theme (preserves users + guestbook)
    dvwaReseedForTheme($_POST['theme']);
    header('Location: login.php');
    exit;
}

// If theme already chosen and user isn't explicitly switching, redirect to index
if (getCurrentTheme() !== null && !isset($_GET['switch'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Theme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0c0c14;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(139,92,246,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(236,72,153,0.04) 0%, transparent 50%);
        }
        .chooser-container {
            max-width: 1000px;
            width: 100%;
            padding: 2rem;
        }
        .chooser-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .chooser-header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
        }
        .chooser-header p {
            color: #8892a4;
            font-size: 0.95rem;
        }
        .theme-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }
        .theme-card {
            background: #151521;
            border: 2px solid #252538;
            border-radius: 4px;
            padding: 1.75rem 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .theme-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .theme-card .theme-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }
        .theme-card h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }
        .theme-card .theme-tagline {
            font-size: 0.72rem;
            color: #8892a4;
            margin-bottom: 0.75rem;
            font-style: italic;
        }
        .theme-card .theme-desc {
            font-size: 0.78rem;
            color: #8892a4;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        .theme-card .btn-choose {
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 2px;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Music */
        .card-music:hover { border-color: #8b5cf6; }
        .card-music .theme-icon { color: #8b5cf6; }
        .card-music h3 { color: #a78bfa; }
        .card-music .btn-choose { background: #8b5cf6; }
        .card-music .btn-choose:hover { background: #6d28d9; }

        /* Sports */
        .card-sports:hover { border-color: #f97316; }
        .card-sports .theme-icon { color: #f97316; }
        .card-sports h3 { color: #fb923c; }
        .card-sports .btn-choose { background: #f97316; }
        .card-sports .btn-choose:hover { background: #c2410c; }

        /* Games */
        .card-games:hover { border-color: #22c55e; }
        .card-games .theme-icon { color: #22c55e; }
        .card-games h3 { color: #4ade80; }
        .card-games .btn-choose { background: #22c55e; }
        .card-games .btn-choose:hover { background: #15803d; }

        /* Cars */
        .card-cars:hover { border-color: #ef4444; }
        .card-cars .theme-icon { color: #ef4444; }
        .card-cars h3 { color: #f87171; }
        .card-cars .btn-choose { background: #ef4444; }
        .card-cars .btn-choose:hover { background: #b91c1c; }

        @media (max-width: 600px) {
            .theme-grid { grid-template-columns: 1fr; }
            .chooser-container { padding: 1rem; }
        }
    </style>
</head>
<body>
    <div class="chooser-container">
        <div class="chooser-header">
            <h1><i class="fas fa-palette"></i> Choose Your Theme</h1>
            <p>Pick a theme for your vulnerable web application training environment</p>
            <p style="font-size:0.72rem; color:#ec4899; margin-top:0.5rem; font-style:italic;">
                "Same vulnerabilities, different disguise &mdash; because hackers don't discriminate."
            </p>
        </div>

        <div class="theme-grid">
            <!-- Music -->
            <form method="POST" class="theme-card card-music">
                <input type="hidden" name="theme" value="music">
                <span class="theme-icon"><i class="fas fa-ticket-alt"></i></span>
                <h3>TicketDisaster</h3>
                <div class="theme-tagline">Concert Ticket Shop</div>
                <div class="theme-desc">
                    Buy tickets to real upcoming concerts at Red Rocks, Mission Ballroom, and more Colorado venues. Scraped from real venue listings.
                </div>
                <button type="submit" class="btn-choose"><i class="fas fa-music"></i> Rock On</button>
            </form>

            <!-- Sports -->
            <form method="POST" class="theme-card card-sports">
                <input type="hidden" name="theme" value="sports">
                <span class="theme-icon"><i class="fas fa-football-ball"></i></span>
                <h3>Stadia</h3>
                <div class="theme-tagline">Sports Ticket Shop</div>
                <div class="theme-desc">
                    Grab tickets to Denver Nuggets, Avalanche, Rockies, Broncos, CU Buffs, and more. Real teams, real schedules, real prices.
                </div>
                <button type="submit" class="btn-choose"><i class="fas fa-trophy"></i> Game Time</button>
            </form>

            <!-- Games -->
            <form method="POST" class="theme-card card-games">
                <input type="hidden" name="theme" value="games">
                <span class="theme-icon"><i class="fas fa-gamepad"></i></span>
                <h3>Flux</h3>
                <div class="theme-tagline">Video Game Store</div>
                <div class="theme-desc">
                    Browse and buy popular and upcoming video games. Real titles, real prices, scraped from gaming sources. Think Steam, but less secure.
                </div>
                <button type="submit" class="btn-choose"><i class="fas fa-play"></i> Press Start</button>
            </form>

            <!-- Cars -->
            <form method="POST" class="theme-card card-cars">
                <input type="hidden" name="theme" value="cars">
                <span class="theme-icon"><i class="fas fa-car-crash"></i></span>
                <h3>Rust Bucket Motors</h3>
                <div class="theme-tagline">$1,000 Used Car Lot</div>
                <div class="theme-desc">
                    Every vehicle $1,000 or less. Check engine light is a feature. Duct tape is structural. "Runs and drives" is aspirational.
                </div>
                <button type="submit" class="btn-choose"><i class="fas fa-wrench"></i> I'll Take It</button>
            </form>
        </div>
    </div>
</body>
</html>
