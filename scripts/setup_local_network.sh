#!/usr/bin/env bash
set -euo pipefail

# Usage: ./scripts/setup_local_network.sh [HOST] [PORT] [APP_URL]
# Example: ./scripts/setup_local_network.sh 0.0.0.0 8000 http://192.168.1.100:8000

HOST="${1:-0.0.0.0}"
PORT="${2:-8000}"
APP_URL="${3:-http://${HOST}:${PORT}}"

echo "Preparing app for LAN access: APP_URL=${APP_URL}"

if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
    echo ".env created from .env.example"
  else
    echo "No .env or .env.example found — create one and re-run." >&2
    exit 1
  fi
fi

# Set production-friendly env values and APP_URL
sed -i "s/^APP_ENV=.*/APP_ENV=production/" .env || true
sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" .env || true
if grep -q '^APP_URL=' .env; then
  sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" .env
else
  echo "APP_URL=${APP_URL}" >> .env
fi

echo "Installing PHP dependencies (composer)..."
if command -v composer >/dev/null 2>&1; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo "composer not found; please install composer and re-run." >&2
fi

if [ -f package.json ]; then
  echo "Building front-end assets..."
  if command -v npm >/dev/null 2>&1; then
    npm ci && npm run build || true
  elif command -v pnpm >/dev/null 2>&1; then
    pnpm install && pnpm run build || true
  else
    echo "No node package manager found; skip front-end build." >&2
  fi
fi

echo "Running Laravel bootstrap tasks..."
php artisan key:generate --ansi || true
php artisan migrate --force --no-interaction || true
php artisan storage:link || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Fixing permissions (www-data user) on storage and cache..."
if id www-data >/dev/null 2>&1; then
  sudo chown -R www-data:www-data storage bootstrap/cache || true
fi

echo "Setup complete. To serve on the LAN now run:"
echo "  php artisan serve --host=${HOST} --port=${PORT}"
echo "Or configure Nginx using scripts/nginx_pos_site.conf.tpl and systemd using scripts/pos.service.tpl"

exit 0
