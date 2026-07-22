#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

composer install --no-dev --optimize-autoloader

php artisan migrate --force
php artisan storage:link
php artisan cms:publish:assets

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize

chmod -R 775 storage bootstrap/cache

printf "\nPoligonium production cache is ready.\n"
