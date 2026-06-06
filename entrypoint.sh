#!/bin/bash
set -e

echo "Starting application setup..."

# Ensure storage directories exist
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage symlink
php artisan storage:link 2>/dev/null || true

# APP_KEY debe venir como variable de entorno en Dokploy, NO se genera aquí
if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set. Set it in Dokploy environment variables."
    exit 1
fi

# Solo migraciones nuevas, nunca fresh
php artisan migrate --force || echo "Migration failed, continuing..."

# Rebuild caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application setup complete. Starting Apache..."
exec apache2-foreground
