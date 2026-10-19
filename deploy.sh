#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

git pull --ff-only

composer install --no-dev --optimize-autoloader --no-interaction

# public/build bị gitignore; storefront và admin lấy CSS/JS từ Vite.
npm ci
npm run build

php artisan migrate --force

if [ ! -e public/storage ]; then
    php artisan storage:link
fi

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
