#!/bin/sh

# Substitute environment variables in the nginx config.
# This will only substitute ${PORT} and leave other '$' variables untouched.
envsubst '$PORT' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# Set permissions for storage and cache
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations and seed the database
echo "Running migrations and seeding..."
php artisan migrate --force --seed

# Optional: Cache configuration for production
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache

# Start PHP-FPM in the background
php-fpm -D

# Start Nginx in the foreground (this will keep the container running)
echo "Starting Nginx..."
nginx -g "daemon off;"