#!/bin/bash
set -e

echo ">>> Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo ">>> Clearing caches..."
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Checking if seeding is needed..."
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo ">>> Seeding database (first run)..."
    php artisan db:seed --force
else
    echo ">>> Database already has data ($USER_COUNT users), skipping seed."
fi

echo ">>> Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

echo ">>> Starting PHP-FPM..."
exec php-fpm
