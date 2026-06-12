#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

PROG_NAME=$(basename "$0")
HOST="0.0.0.0"
PORT="8000"
APP_URL=""
NO_INSTALL=false
NO_BUILD=false
NO_MIGRATE=false
NO_CACHE=false
ENABLE_NGINX=false
ENABLE_SYSTEMD=false
NGINX_SERVER_NAME="localhost"
SERVICE_USER="www-data"
SERVICE_NAME="posdreampool"
APP_ROOT="$(pwd)"

usage() {
  cat <<EOF
Usage: $PROG_NAME [options]

Options:
  --host HOST                Bind host for local network serve (default: 0.0.0.0)
  --port PORT                Port for local network serve (default: 8000)
  --app-url URL              Set APP_URL in .env (default: auto-detect local IP)
  --no-install               Skip Composer / Node dependency install
  --no-build                 Skip front-end asset build
  --no-migrate               Skip database migration
  --no-cache                 Skip Artisan config/route/view cache
  --enable-nginx             Generate and enable an Nginx site for the app
  --enable-systemd           Generate and enable a systemd queue service
  --nginx-server-name NAME   Nginx server_name (default: localhost)
  --service-user USER        User that runs the systemd service (default: www-data)
  --service-name NAME        Systemd service name (default: posdreampool)
  -h, --help                 Show this help message
EOF
  exit 0
}

require_root_or_sudo() {
  if [ "$(id -u)" -ne 0 ]; then
    if command -v sudo >/dev/null 2>&1; then
      echo "This step requires root privileges. Re-running with sudo..."
      sudo "$0" "$@"
      exit $?
    fi
    echo "Root privileges are required for this step. Run the script as root or install sudo." >&2
    exit 1
  fi
}

set_env() {
  local key="$1"
  local value="$2"
  if grep -qE "^${key}=" .env 2>/dev/null; then
    sed -i "s|^${key}=.*|${key}=${value}|" .env
  else
    echo "${key}=${value}" >> .env
  fi
}

command_exists() {
  command -v "$1" >/dev/null 2>&1
}

parse_args() {
  while [ $# -gt 0 ]; do
    case "$1" in
      --host)
        HOST="${2:-}"
        shift 2
        ;;
      --port)
        PORT="${2:-}"
        shift 2
        ;;
      --app-url)
        APP_URL="${2:-}"
        shift 2
        ;;
      --no-install)
        NO_INSTALL=true
        shift
        ;;
      --no-build)
        NO_BUILD=true
        shift
        ;;
      --no-migrate)
        NO_MIGRATE=true
        shift
        ;;
      --no-cache)
        NO_CACHE=true
        shift
        ;;
      --enable-nginx)
        ENABLE_NGINX=true
        shift
        ;;
      --enable-systemd)
        ENABLE_SYSTEMD=true
        shift
        ;;
      --nginx-server-name)
        NGINX_SERVER_NAME="${2:-}"
        shift 2
        ;;
      --service-user)
        SERVICE_USER="${2:-}"
        shift 2
        ;;
      --service-name)
        SERVICE_NAME="${2:-}"
        shift 2
        ;;
      -h|--help)
        usage
        ;;
      *)
        echo "Unknown option: $1" >&2
        usage
        ;;
    esac
  done
}

main() {
  parse_args "$@"

  echo "[1/9] Checking repository state..."
  if [ ! -f .env ]; then
    if [ -f .env.example ]; then
      cp .env.example .env
      echo "Created .env from .env.example"
    else
      echo "ERROR: .env and .env.example both missing." >&2
      exit 1
    fi
  fi

  if [ -z "$APP_URL" ]; then
    local ip
    ip=$(hostname -I 2>/dev/null | awk '{print $1}')
    APP_URL="http://${ip:-127.0.0.1}:${PORT}"
  fi

  echo "[2/9] Configuring .env for production..."
  set_env "APP_ENV" "production"
  set_env "APP_DEBUG" "false"
  set_env "APP_URL" "$APP_URL"

  if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env; then
    echo "Generating APP_KEY..."
    php artisan key:generate --ansi
  fi

  echo "[3/9] Installing PHP dependencies..."
  if [ "$NO_INSTALL" = false ]; then
    if command_exists composer; then
      composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
    else
      echo "WARNING: composer not found; please install Composer." >&2
    fi
  else
    echo "Skipping dependency install (--no-install)."
  fi

  if [ "$NO_BUILD" = false ] && [ -f package.json ]; then
    echo "[4/9] Building front-end assets..."
    if command_exists pnpm; then
      pnpm install
      pnpm run build
    elif command_exists npm; then
      npm ci
      npm run build
    elif command_exists yarn; then
      yarn install --frozen-lockfile
      yarn build
    else
      echo "WARNING: No npm/pnpm/yarn found; skipping asset build." >&2
    fi
  else
    echo "Skipping front-end build." >&2
  fi

  echo "[5/9] Linking storage and preparing Laravel caches..."
  php artisan storage:link || true

  if [ "$NO_MIGRATE" = false ]; then
    echo "[6/9] Running database migrations..."
    php artisan migrate --force --no-interaction
  else
    echo "Skipping migrations (--no-migrate)."
  fi

  if [ "$NO_CACHE" = false ]; then
    echo "[7/9] Caching configuration, routes, and views..."
    php artisan config:cache
    if ! php artisan route:cache; then
      echo "WARNING: route:cache failed. The application will continue, but route cache is disabled." >&2
      php artisan route:clear || true
    fi
    php artisan view:cache
    php artisan event:cache || true
  else
    echo "Skipping Laravel cache generation (--no-cache)."
  fi

  echo "[8/9] Optimizing autoloader and modules..."
  php artisan optimize:clear || true
  composer dump-autoload -o || true

  if [ -f artisan ] && command_exists php && php artisan list 2>/dev/null | grep -q 'module:'; then
    echo "[9/9] Ensuring modules are enabled..."
    if php artisan module:list | grep -q '\[Disabled\]'; then
      echo "NOTE: Some modules are disabled. Enable them with php artisan module:enable <ModuleName>."
    fi
  fi

  echo "Production-ready application prepared." 
  echo "APP_URL=${APP_URL}"
  echo "To serve on the local network, use:"
  echo "  php artisan serve --host=${HOST} --port=${PORT}"

  if [ "$ENABLE_NGINX" = true ]; then
    setup_nginx
  fi

  if [ "$ENABLE_SYSTEMD" = true ]; then
    setup_systemd
  fi
}

setup_nginx() {
  local nginx_site="/etc/nginx/sites-available/${SERVICE_NAME}.conf"
  local nginx_link="/etc/nginx/sites-enabled/${SERVICE_NAME}.conf"

  echo "[NGINX] Generating Nginx site configuration..."
  require_root_or_sudo "--enable-nginx"
  cat > "$nginx_site" <<EOF
server {
    listen 80;
    server_name ${NGINX_SERVER_NAME};

    root ${APP_ROOT}/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \\.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param APP_ENV production;
    }

    location ~* \\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2)$ {
        try_files \$uri =404;
        access_log off;
        log_not_found off;
        expires max;
    }
}
EOF

  ln -sf "$nginx_site" "$nginx_link"
  nginx -t
  systemctl reload nginx
  echo "Nginx site enabled: ${nginx_site}"
}

setup_systemd() {
  local service_path="/etc/systemd/system/${SERVICE_NAME}.service"
  echo "[SYSTEMD] Creating systemd service: ${service_path}"
  require_root_or_sudo "--enable-systemd"

  cat > "$service_path" <<EOF
[Unit]
Description=PosDreamPool Laravel Queue Worker
After=network.target

[Service]
User=${SERVICE_USER}
Group=${SERVICE_USER}
Restart=always
ExecStart=/usr/bin/php ${APP_ROOT}/artisan queue:work --sleep=3 --tries=3 --timeout=90
RestartSec=5
StandardOutput=syslog
StandardError=syslog
SyslogIdentifier=${SERVICE_NAME}

[Install]
WantedBy=multi-user.target
EOF

  systemctl daemon-reload
  systemctl enable --now "$SERVICE_NAME"
  echo "Systemd service enabled: ${SERVICE_NAME}"
}

main "$@"
