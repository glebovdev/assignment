#!/bin/bash
set -e

# Ensure storage directories are writable
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Run database migrations in production if environment variable is set
if [[ "$APP_ENV" == "production" && "$RUN_MIGRATIONS" == "true" ]]; then
    php artisan migrate --force
fi

# Start Apache
apache2-foreground 