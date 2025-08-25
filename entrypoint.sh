#!/bin/sh

# Substitute environment variables in the nginx config.
# This will replace ${PORT} with the value of the PORT environment variable.
export DOLLAR='$'
envsubst < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Optional: Cache configuration for production
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache

# Start PHP-FPM in the background
php-fpm -D

# Start Nginx in the foreground (this will keep the container running)
echo "Starting Nginx..."
nginx -g "daemon off;"
