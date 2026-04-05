#!/bin/bash
# ============================================================================
# TicketDisaster / FanCrash / BuggedOut Games / Rust Bucket Motors
# Auto-deploy script for Fedora 42+ and Ubuntu 24.04+
#
# Installs Apache, PHP, MariaDB, configures everything, seeds the database.
# Works on bare-metal, VMs, and containers (non-Docker).
#
# Usage:
#   sudo bash deploy.sh
#
# After deployment:
#   1. Visit http://<your-ip>/setup.php
#   2. Click "Create / Reset Database"
#   3. Login with admin / password
# ============================================================================

set -euo pipefail

# ── Colors ────────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

log()  { echo -e "${GREEN}[+]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
err()  { echo -e "${RED}[ERROR]${NC} $1"; exit 1; }

# ── Pre-flight checks ────────────────────────────────────────────────────────
if [[ $EUID -ne 0 ]]; then
    err "This script must be run as root (use sudo)"
fi

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="/var/www/html/ticketdisaster"
LOG_DIR="/var/log/ticketdisaster"
DB_NAME="ticketdisaster"
DB_USER="root"
DB_PASS="vulnerable"

# ── Detect OS ─────────────────────────────────────────────────────────────────
detect_os() {
    if [ -f /etc/os-release ]; then
        . /etc/os-release
        OS_ID="${ID}"
        OS_VERSION="${VERSION_ID}"
    else
        err "Cannot detect OS. /etc/os-release not found."
    fi

    case "$OS_ID" in
        fedora)
            if (( $(echo "$OS_VERSION >= 42" | bc -l 2>/dev/null || echo 0) )); then
                OS_FAMILY="fedora"
                log "Detected Fedora $OS_VERSION"
            else
                # Allow older Fedora for testing, just warn
                OS_FAMILY="fedora"
                warn "Detected Fedora $OS_VERSION (script targets 42+, proceeding anyway)"
            fi
            ;;
        ubuntu)
            MAJOR=$(echo "$OS_VERSION" | cut -d. -f1)
            if [ "$MAJOR" -ge 24 ] 2>/dev/null; then
                OS_FAMILY="ubuntu"
                log "Detected Ubuntu $OS_VERSION"
            else
                OS_FAMILY="ubuntu"
                warn "Detected Ubuntu $OS_VERSION (script targets 24.04+, proceeding anyway)"
            fi
            ;;
        debian|linuxmint|pop)
            OS_FAMILY="ubuntu"
            warn "Detected $OS_ID $OS_VERSION — treating as Ubuntu-based"
            ;;
        rhel|centos|rocky|alma)
            OS_FAMILY="fedora"
            warn "Detected $OS_ID $OS_VERSION — treating as Fedora-based"
            ;;
        *)
            err "Unsupported OS: $OS_ID $OS_VERSION. This script supports Fedora 42+ and Ubuntu 24.04+."
            ;;
    esac
}

# ── Install packages ──────────────────────────────────────────────────────────
install_packages_fedora() {
    log "Installing packages via dnf..."
    dnf install -y \
        httpd \
        mariadb-server mariadb \
        php php-mysqlnd php-gd php-curl php-mbstring php-xml php-json \
        php-opcache \
        iputils \
        curl \
        cronie \
        2>&1 | tail -1
    log "Packages installed."
}

install_packages_ubuntu() {
    log "Updating apt cache..."
    export DEBIAN_FRONTEND=noninteractive
    apt-get update -qq 2>&1 | tail -1

    log "Installing packages via apt..."
    apt-get install -y -qq \
        apache2 \
        mariadb-server mariadb-client \
        php libapache2-mod-php php-mysql php-gd php-curl php-mbstring php-xml \
        iputils-ping \
        curl \
        cron \
        2>&1 | tail -1
    log "Packages installed."
}

# ── Configure PHP ─────────────────────────────────────────────────────────────
configure_php() {
    log "Configuring PHP (intentionally insecure — training only)..."

    # Find the PHP ini directory
    PHP_INI_DIR=""
    for d in /etc/php/*/apache2/conf.d /etc/php/*/fpm/conf.d /etc/php.d /etc/php/conf.d; do
        if [ -d "$d" ]; then
            PHP_INI_DIR="$d"
            break
        fi
    done

    if [ -z "$PHP_INI_DIR" ]; then
        # Fallback: try php --ini
        PHP_INI_DIR=$(php --ini 2>/dev/null | grep "Scan for additional" | awk -F: '{print $2}' | xargs)
    fi

    if [ -z "$PHP_INI_DIR" ] || [ ! -d "$PHP_INI_DIR" ]; then
        warn "Could not find PHP conf.d directory. Creating /etc/php.d/"
        mkdir -p /etc/php.d
        PHP_INI_DIR="/etc/php.d"
    fi

    cat > "$PHP_INI_DIR/99-ticketdisaster.ini" <<'PHPINI'
; TicketDisaster — intentionally insecure settings for security training
allow_url_include = On
allow_url_fopen = On
display_errors = On
file_uploads = On
upload_max_filesize = 10M
post_max_size = 12M
PHPINI

    log "PHP configured at $PHP_INI_DIR/99-ticketdisaster.ini"
}

# ── Configure Apache ──────────────────────────────────────────────────────────
configure_apache_fedora() {
    log "Configuring Apache (httpd)..."

    # Enable mod_rewrite
    if ! grep -q "LoadModule rewrite_module" /etc/httpd/conf.modules.d/*.conf 2>/dev/null; then
        warn "mod_rewrite may need manual enabling"
    fi

    # Create virtual host
    cat > /etc/httpd/conf.d/ticketdisaster.conf <<APACHECONF
<VirtualHost *:80>
    DocumentRoot ${APP_DIR}
    <Directory ${APP_DIR}>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${LOG_DIR}/error.log
    CustomLog ${LOG_DIR}/access.log combined
</VirtualHost>
APACHECONF

    log "Apache vhost created."
}

configure_apache_ubuntu() {
    log "Configuring Apache (apache2)..."

    # Enable mod_rewrite
    a2enmod rewrite 2>/dev/null || true

    # Create virtual host
    cat > /etc/apache2/sites-available/ticketdisaster.conf <<APACHECONF
<VirtualHost *:80>
    DocumentRoot ${APP_DIR}
    <Directory ${APP_DIR}>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${LOG_DIR}/error.log
    CustomLog ${LOG_DIR}/access.log combined
</VirtualHost>
APACHECONF

    # Enable site, disable default
    a2dissite 000-default.conf 2>/dev/null || true
    a2ensite ticketdisaster.conf 2>/dev/null || true

    log "Apache vhost created and enabled."
}

# ── Configure MariaDB ─────────────────────────────────────────────────────────
configure_mariadb() {
    log "Configuring MariaDB..."

    # Start MariaDB
    if [ "$OS_FAMILY" = "fedora" ]; then
        systemctl enable mariadb --now 2>/dev/null || service mariadb start
    else
        systemctl enable mariadb --now 2>/dev/null || service mariadb start 2>/dev/null || service mysql start
    fi

    # Wait for MariaDB to be ready
    log "Waiting for MariaDB to be ready..."
    for i in $(seq 1 30); do
        if mysqladmin ping -u root 2>/dev/null | grep -q alive; then
            break
        fi
        sleep 1
    done

    # Create database and set root password
    mysql -u root <<SQL || true
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;
ALTER USER 'root'@'localhost' IDENTIFIED BY '${DB_PASS}';
FLUSH PRIVILEGES;
SQL

    # If the above fails (password already set), try with password
    mysql -u root -p"${DB_PASS}" -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;" 2>/dev/null || true

    log "MariaDB configured. Database '${DB_NAME}' ready."
}

# ── Deploy application files ──────────────────────────────────────────────────
deploy_app() {
    log "Deploying application to ${APP_DIR}..."

    mkdir -p "${APP_DIR}"
    mkdir -p "${LOG_DIR}"

    # Copy all app files (excluding Docker files and this script)
    rsync -a --exclude='deploy.sh' \
             --exclude='Dockerfile' \
             --exclude='Dockerfile.standalone' \
             --exclude='docker-compose.yml' \
             --exclude='docker/' \
             --exclude='.git' \
             "${SCRIPT_DIR}/" "${APP_DIR}/"

    # Create required directories
    mkdir -p "${APP_DIR}/hackable/uploads"
    mkdir -p "${APP_DIR}/assets/images/bands"

    # Set permissions
    if [ "$OS_FAMILY" = "fedora" ]; then
        WEB_USER="apache"
    else
        WEB_USER="www-data"
    fi

    chown -R "${WEB_USER}:${WEB_USER}" "${APP_DIR}"
    chmod -R 755 "${APP_DIR}"
    chmod -R 777 "${APP_DIR}/hackable/uploads"
    chmod -R 777 "${APP_DIR}/assets/images/bands"

    # Set up log directory permissions
    chown -R "${WEB_USER}:${WEB_USER}" "${LOG_DIR}"
    chmod -R 755 "${LOG_DIR}"

    # Update config to use localhost for DB
    cat > "${APP_DIR}/config/config.inc.php" <<'PHPCONFIG'
<?php

// Database configuration
$_DVWA = array();
$_DVWA['db_server']   = getenv('DB_HOST') ?: '127.0.0.1';
$_DVWA['db_database'] = getenv('DB_NAME') ?: 'ticketdisaster';
$_DVWA['db_user']     = getenv('DB_USER') ?: 'root';
$_DVWA['db_password'] = getenv('DB_PASS') ?: 'vulnerable';
$_DVWA['db_port']     = '3306';

// Default security level
$_DVWA['default_security_level'] = 'low';

// Default admin credentials
$_DVWA['default_user'] = 'admin';
$_DVWA['default_pass'] = 'password';

// Recaptcha keys (placeholder)
$_DVWA['recaptcha_public_key']  = '';
$_DVWA['recaptcha_private_key'] = '';
PHPCONFIG

    log "Application deployed to ${APP_DIR}"
}

# ── Configure SELinux (Fedora) ────────────────────────────────────────────────
configure_selinux() {
    if command -v getenforce &>/dev/null; then
        local mode=$(getenforce 2>/dev/null || echo "Disabled")
        if [ "$mode" = "Enforcing" ]; then
            log "Configuring SELinux policies..."
            setsebool -P httpd_can_network_connect_db 1 2>/dev/null || true
            setsebool -P httpd_unified 1 2>/dev/null || true
            chcon -R -t httpd_sys_rw_content_t "${APP_DIR}/hackable/uploads" 2>/dev/null || true
            chcon -R -t httpd_sys_rw_content_t "${APP_DIR}/assets/images/bands" 2>/dev/null || true
            chcon -R -t httpd_log_t "${LOG_DIR}" 2>/dev/null || true
            warn "SELinux is Enforcing — policies set. If issues persist, check audit.log."
        fi
    fi
}

# ── Set up cron job ───────────────────────────────────────────────────────────
setup_cron() {
    log "Setting up daily scraper cron job..."

    if [ "$OS_FAMILY" = "fedora" ]; then
        WEB_USER="apache"
    else
        WEB_USER="www-data"
    fi

    CRON_LINE="0 6 * * * cd ${APP_DIR} && /usr/bin/php cron_scrape.php >> ${LOG_DIR}/scraper.log 2>&1"

    # Add to web user's crontab (avoid duplicates)
    (crontab -u "${WEB_USER}" -l 2>/dev/null | grep -v "cron_scrape.php"; echo "$CRON_LINE") | crontab -u "${WEB_USER}" - 2>/dev/null || true

    log "Cron job set: daily at 6 AM"
}

# ── Start services ────────────────────────────────────────────────────────────
start_services() {
    log "Starting services..."

    if [ "$OS_FAMILY" = "fedora" ]; then
        systemctl enable httpd --now 2>/dev/null || service httpd start
        systemctl enable mariadb --now 2>/dev/null || true
        systemctl enable crond --now 2>/dev/null || true
        # Open firewall
        if command -v firewall-cmd &>/dev/null; then
            firewall-cmd --permanent --add-service=http 2>/dev/null || true
            firewall-cmd --reload 2>/dev/null || true
            log "Firewall opened for HTTP."
        fi
    else
        systemctl enable apache2 --now 2>/dev/null || service apache2 start
        systemctl enable mariadb --now 2>/dev/null || true
        systemctl enable cron --now 2>/dev/null || true
        # Open firewall if ufw is active
        if command -v ufw &>/dev/null && ufw status | grep -q "active"; then
            ufw allow 80/tcp 2>/dev/null || true
            log "UFW opened for HTTP."
        fi
    fi

    log "Services started."
}

# ── Print summary ─────────────────────────────────────────────────────────────
print_summary() {
    # Detect IP
    local ip
    ip=$(hostname -I 2>/dev/null | awk '{print $1}')
    [ -z "$ip" ] && ip="<your-server-ip>"

    echo ""
    echo -e "${CYAN}════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}  Deployment Complete!${NC}"
    echo -e "${CYAN}════════════════════════════════════════════════════════════${NC}"
    echo ""
    echo -e "  ${CYAN}Application:${NC}  http://${ip}/"
    echo -e "  ${CYAN}Setup page:${NC}   http://${ip}/setup.php"
    echo -e "  ${CYAN}App directory:${NC} ${APP_DIR}"
    echo -e "  ${CYAN}Log directory:${NC} ${LOG_DIR}"
    echo -e "  ${CYAN}Database:${NC}      ${DB_NAME} (root / ${DB_PASS})"
    echo ""
    echo -e "  ${YELLOW}Next steps:${NC}"
    echo -e "    1. Visit http://${ip}/ in your browser"
    echo -e "    2. Choose a theme (Music, Sports, Games, or Cars)"
    echo -e "    3. Click 'Create / Reset Database' on the setup page"
    echo -e "    4. Login with: ${GREEN}admin / password${NC}"
    echo ""
    echo -e "  ${YELLOW}Logs:${NC}"
    echo -e "    Apache error:  ${LOG_DIR}/error.log"
    echo -e "    Apache access: ${LOG_DIR}/access.log"
    echo -e "    Scraper:       ${LOG_DIR}/scraper.log"
    echo ""
    echo -e "  ${RED}WARNING: This application is intentionally vulnerable.${NC}"
    echo -e "  ${RED}Do NOT deploy on a public-facing network.${NC}"
    echo -e "${CYAN}════════════════════════════════════════════════════════════${NC}"
    echo ""
}

# ── Main ──────────────────────────────────────────────────────────────────────
main() {
    echo ""
    echo -e "${CYAN}════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}  TicketDisaster — Auto-Deploy Script${NC}"
    echo -e "${CYAN}════════════════════════════════════════════════════════════${NC}"
    echo ""

    detect_os

    if [ "$OS_FAMILY" = "fedora" ]; then
        install_packages_fedora
        configure_php
        configure_apache_fedora
        configure_mariadb
        deploy_app
        configure_selinux
        setup_cron
        start_services
    else
        install_packages_ubuntu
        configure_php
        configure_apache_ubuntu
        configure_mariadb
        deploy_app
        setup_cron
        start_services
    fi

    print_summary
}

main "$@"
