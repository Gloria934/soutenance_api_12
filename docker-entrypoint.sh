#!/bin/sh
# Quitte le script en cas d'erreur
set -e

echo "Configuration des permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Dans un scénario réel, on ajouterait ici un script pour attendre que la DB soit prête.

echo "Exécution des migrations et du seeding..."
php artisan migrate --force
# Le seeding est déplacé ici, car il a besoin d'une connexion à la base de données.
php artisan db:seed --force

echo "Démarrage des services..."
# Démarre PHP-FPM en arrière-plan
php-fpm -D

# Démarre Nginx en avant-plan pour que le conteneur reste actif
echo "Démarrage de Nginx..."
nginx -g "daemon off;"