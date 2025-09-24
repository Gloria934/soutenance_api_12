#!/bin/sh
# Quitte le script en cas d'erreur
set -e

echo "Configuration des permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Exécution des migrations..."
php artisan migrate --force

echo "Génération des caches de production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Démarrage des services..."
# Démarre PHP-FPM en arrière-plan
php-fpm -D

# Démarre Nginx en avant-plan pour que le conteneur reste actif
echo "Démarrage de Nginx..."
nginx -g "daemon off;"