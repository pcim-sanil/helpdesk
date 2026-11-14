
#!/usr/bin/env bash
set -Eeuo pipefail
IFS=$'\n\t'

# --------------------------------------------------
# CONFIGURATION
# --------------------------------------------------

DEPLOY_USER="${DEPLOY_USER:-$(whoami)}"
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

GIT_REMOTE="${GIT_REMOTE:-origin}"
GIT_BRANCH="${GIT_BRANCH:-master}"

PHP_BIN="/usr/bin/php8.4"
COMPOSER_BIN="composer"

PHP_FPM_SERVICE="php8.4-fpm"
WEB_USER="www-data"
WEB_GROUP="www-data"

PM2_CONFIG="ecosystem.config.cjs"
PM2_PROCESS="laravel-queue"

# --------------------------------------------------
# ERROR HANDLING
# --------------------------------------------------

trap 'echo "ERROR: Deployment failed at line $LINENO"; exit 1' ERR

cd "$PROJECT_ROOT"

echo "======================================"
echo " Laravel Production Deployment"
echo "======================================"

echo "Project: $PROJECT_ROOT"
echo "User: $DEPLOY_USER"
echo "Branch: $GIT_BRANCH"

# --------------------------------------------------
# CHECK REQUIREMENTS
# --------------------------------------------------

command -v git >/dev/null
command -v "$PHP_BIN" >/dev/null
command -v "$COMPOSER_BIN" >/dev/null

# --------------------------------------------------
# GIT UPDATE
# --------------------------------------------------

echo "Fetching latest code..."

git fetch --prune "$GIT_REMOTE"

git checkout "$GIT_BRANCH"

git reset --hard "$GIT_REMOTE/$GIT_BRANCH"

# --------------------------------------------------
# COMPOSER DEPENDENCIES
# --------------------------------------------------

echo "Installing Composer dependencies..."

"$COMPOSER_BIN" install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-dev

# --------------------------------------------------
# FRONTEND BUILD
# --------------------------------------------------

if [ -f package.json ]; then

    echo "Building frontend assets..."

    npm ci
    npm run build

fi

# --------------------------------------------------
# LARAVEL CACHE
# --------------------------------------------------

echo "Clearing Laravel caches..."

"$PHP_BIN" artisan optimize:clear

# --------------------------------------------------
# DATABASE MIGRATIONS
# --------------------------------------------------

echo "Running database migrations..."

"$PHP_BIN" artisan migrate --force

# --------------------------------------------------
# FILE PERMISSIONS
# --------------------------------------------------

echo "Setting Laravel permissions..."

sudo chown -R "$DEPLOY_USER:$WEB_GROUP" \
    "$PROJECT_ROOT/storage" \
    "$PROJECT_ROOT/bootstrap/cache"

sudo chmod -R 775 \
    "$PROJECT_ROOT/storage" \
    "$PROJECT_ROOT/bootstrap/cache"

# --------------------------------------------------
# REBUILD LARAVEL CACHE
# --------------------------------------------------

echo "Rebuilding Laravel caches..."

"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

# --------------------------------------------------
# RELOAD PHP-FPM
# --------------------------------------------------

echo "Reloading PHP-FPM..."

sudo systemctl reload "$PHP_FPM_SERVICE"

# --------------------------------------------------
# RESTART QUEUE WORKERS
# --------------------------------------------------

echo "Restarting Laravel queue workers..."

"$PHP_BIN" artisan queue:restart

if command -v pm2 >/dev/null && [ -f "$PM2_CONFIG" ]; then

    pm2 reload "$PM2_CONFIG" --only "$PM2_PROCESS"
    pm2 save

fi

# --------------------------------------------------
# COMPLETE
# --------------------------------------------------

echo "======================================"
echo " Deployment completed successfully!"
echo "======================================"
