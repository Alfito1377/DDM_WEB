#!/bin/sh
set -e

# Fix ownership untuk storage & cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Composer install kalau vendor belum ada atau composer.lock berubah
if [ ! -d "vendor" ] || [ "composer.lock" -nt "vendor/autoload.php" ]; then
    echo "[entrypoint] Running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Storage link kalau belum ada
if [ ! -L "public/storage" ]; then
    echo "[entrypoint] Creating storage link..."
    php artisan storage:link
fi

# Clear cache supaya config/route dari host terbaca
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

echo "[entrypoint] Ready."

exec "$@"
