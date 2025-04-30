#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

# -----------------------------------------------------------------------------
# CONFIGURATION (override via environment if you like)
# -----------------------------------------------------------------------------
DEPLOY_USER="${DEPLOY_USER:-$(whoami)}"
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
GIT_REMOTE="${GIT_REMOTE:-origin}"
GIT_BRANCH="${GIT_BRANCH:-master}"
SUPERVISOR_PROCESSES="${SUPERVISOR_PROCESSES:-laravel-queue:*}"

# -----------------------------------------------------------------------------
# NAVIGATE TO PROJECT
# -----------------------------------------------------------------------------
cd "$PROJECT_ROOT"

# -----------------------------------------------------------------------------
# GIT CREDENTIALS: build a temporary git‐askpass helper
# -----------------------------------------------------------------------------
ASKPASS_SCRIPT="$(mktemp)"
cat << 'EOF' > "$ASKPASS_SCRIPT"
#!/usr/bin/env bash
# this file must be chmod 700
cat ~/bitbucket.txt
EOF
chmod 700 "$ASKPASS_SCRIPT"
export GIT_ASKPASS="$ASKPASS_SCRIPT"

# -----------------------------------------------------------------------------
# FETCH & RESET TO REMOTE
# -----------------------------------------------------------------------------
echo "→ Fetching latest from $GIT_REMOTE/$GIT_BRANCH…"
git fetch --prune "$GIT_REMOTE"
git checkout "$GIT_BRANCH"
git reset --hard "$GIT_REMOTE/$GIT_BRANCH"

# cleanup askpass helper
unset GIT_ASKPASS
rm -f "$ASKPASS_SCRIPT"

# -----------------------------------------------------------------------------
# DEPENDENCIES & MIGRATIONS
# -----------------------------------------------------------------------------
echo "→ Installing PHP dependencies…"
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "→ Running database migrations…"
php artisan migrate --force

echo "→ Caching config, routes & views…"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# -----------------------------------------------------------------------------
# FRONT-END ASSETS (if present)
# -----------------------------------------------------------------------------
if [ -f package.json ]; then
  echo "→ Building front-end assets…"
  npm ci
  npm run production
fi

# -----------------------------------------------------------------------------
# RESTART QUEUE WORKERS
# -----------------------------------------------------------------------------
echo "→ Restarting queue workers ($SUPERVISOR_PROCESSES)…"
sudo supervisorctl restart "$SUPERVISOR_PROCESSES"

# -----------------------------------------------------------------------------
# FILE PERMISSIONS
# -----------------------------------------------------------------------------
echo "→ Fixing storage & cache permissions…"
sudo chown -R "$DEPLOY_USER":nginx \
  "$PROJECT_ROOT"/storage "$PROJECT_ROOT"/bootstrap/cache
sudo chmod -R 775 \
  "$PROJECT_ROOT"/storage "$PROJECT_ROOT"/bootstrap/cache

echo "✅ Deployment completed successfully."