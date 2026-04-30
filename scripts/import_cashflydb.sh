#!/usr/bin/env bash
set -euo pipefail

# Simple helper to import the cashflydb.sql dump into a local MySQL server.
# Usage: ./scripts/import_cashflydb.sh [DB] [USER] [HOST] [PORT]
# Environment: MYSQL_PWD can be set to provide a password securely.

DB=${1:-cashflydb}
USER=${2:-root}
HOST=${3:-127.0.0.1}
PORT=${4:-3306}
SQL_FILE="cashflydb.sql"
PASSWORD="${MYSQL_PWD:-}"

if [[ ! -f "$SQL_FILE" ]]; then
  echo "SQL dump not found: $SQL_FILE" >&2
  exit 1
fi

if [[ -n "$PASSWORD" ]]; then
  MYSQL_CMD=(mysql -u "$USER" -p"$PASSWORD" -h "$HOST" -P "$PORT" "$DB")
else
  MYSQL_CMD=(mysql -u "$USER" -h "$HOST" -P "$PORT" "$DB")
fi

echo "Ensuring database exists: $DB"
mysql -u "$USER" -h "$HOST" -P "$PORT" -e "CREATE DATABASE IF NOT EXISTS \`$DB\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo "Importing $SQL_FILE into $DB..."
"${MYSQL_CMD[@]}" < "$SQL_FILE"

echo "Import completed. Database '$DB' is ready."
