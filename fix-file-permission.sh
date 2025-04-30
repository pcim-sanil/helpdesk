#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

# -----------------------------------------------------------------------------
# fix-permissions.sh
#
# Usage:
#   sudo ./fix-permissions.sh /path/to/your/project [owner] [group]
#
# Defaults:
#   owner  → nginx
#   group  → nginx
# -----------------------------------------------------------------------------

PROJECT_ROOT="${1:-$(pwd)}"
OWNER="${2:-nginx}"
GROUP="${3:-nginx}"

echo "🔧 Fixing permissions under: $PROJECT_ROOT"
echo "    Owner: $OWNER, Group: $GROUP"
echo

# 1) Recursively set owner & group
echo "→ Setting ownership to $OWNER:$GROUP…"
chown -R "$OWNER":"$GROUP" "$PROJECT_ROOT"

# 2) Standard perms: dirs 755, files 644
echo "→ Applying 755 to directories…"
find "$PROJECT_ROOT" -type d -exec chmod 755 {} \;

echo "→ Applying 644 to files…"
find "$PROJECT_ROOT" -type f -exec chmod 644 {} \;

# 3) Make Laravel’s writable dirs group-writable
echo "→ Making storage & bootstrap/cache writable (775)…"
chmod -R 775 "$PROJECT_ROOT/storage" "$PROJECT_ROOT/bootstrap/cache"

# 4) Restrict your .env
if [ -f "$PROJECT_ROOT/.env" ]; then
  echo "→ Restricting .env to 640…"
  chmod 640 "$PROJECT_ROOT/.env"
fi

echo
echo "✅ Permissions fixed! Everything under $PROJECT_ROOT is now:"
ls -ld "$PROJECT_ROOT"{,/storage,/bootstrap/cache} | sed 's/^/   /'
