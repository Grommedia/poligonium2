#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

composer install --no-dev --optimize-autoloader

php artisan migrate --force
php artisan storage:link --force
php artisan cms:publish:assets

php artisan optimize:clear
rm -rf storage/framework/page-cache
rm -rf public/page-cache
mkdir -p storage/framework/page-cache
mkdir -p public/page-cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize

app_url="$(grep -E '^APP_URL=' .env | head -n 1 | cut -d '=' -f 2- | tr -d '\"')"
if command -v curl >/dev/null 2>&1 && [ -n "$app_url" ]; then
    app_url="${app_url%/}"
    for path in "/" "/vfx-showreel" "/courses" "/projects/crm-system"; do
        curl -L -s -o /dev/null "$app_url$path" || true
    done
fi

chmod -R 775 storage bootstrap/cache

printf "\nPoligonium production cache is ready.\n"
