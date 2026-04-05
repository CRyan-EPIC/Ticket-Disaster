#!/bin/bash
set -e

DATADIR=/var/lib/mysql
RUNDIR=/var/run/mysqld

# Ensure runtime dirs exist with correct ownership
mkdir -p "$RUNDIR"
chown -R mysql:mysql "$RUNDIR"
chmod 755 "$RUNDIR"

# ── First-time database initialisation ────────────────────────────────────────
if [ ! -d "$DATADIR/mysql" ]; then
    echo "[ticket-disaster] First run — initialising database..."

    # Initialise the MariaDB data directory
    mysql_install_db --user=mysql --datadir="$DATADIR" --skip-test-db \
        > /dev/null 2>&1

    # Start a temporary instance (TCP only on 127.0.0.1 so PHP can reach it)
    mysqld_safe --user=mysql --skip-networking &
    MYSQL_PID=$!

    # Wait up to 30 s for the socket to appear
    echo "[ticket-disaster] Waiting for MySQL socket..."
    for i in $(seq 1 30); do
        if [ -S "$RUNDIR/mysqld.sock" ]; then break; fi
        sleep 1
    done

    # Bootstrap: create database and set root password
    mysql --socket="$RUNDIR/mysqld.sock" -u root <<-SQL
        CREATE DATABASE IF NOT EXISTS \`${DB_NAME:-ticketdisaster}\`;
        ALTER USER 'root'@'localhost' IDENTIFIED BY '${DB_PASS:-vulnerable}';
        FLUSH PRIVILEGES;
SQL

    echo "[ticket-disaster] Database '${DB_NAME:-ticketdisaster}' ready."

    # Shut down the bootstrap instance
    mysqladmin --socket="$RUNDIR/mysqld.sock" -u root \
        -p"${DB_PASS:-vulnerable}" shutdown 2>/dev/null || kill "$MYSQL_PID"
    wait "$MYSQL_PID" 2>/dev/null || true

    echo "[ticket-disaster] Bootstrap complete."
fi

# ── Hand off to supervisord ────────────────────────────────────────────────────
echo "[ticket-disaster] Starting Apache + MySQL via supervisord..."
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
