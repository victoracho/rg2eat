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

# Generate key if not set
php artisan key:generate --force 2>/dev/null || true

# Run migrations in production (optional - comment out if not needed)
php artisan migrate fresh --seed || echo "Migration failed, continuing..."

# Clear and rebuild caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Application setup complete. Starting Apache..."

exec apache2-foreground



