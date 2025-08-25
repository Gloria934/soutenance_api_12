#!/bin/sh

# Attendre que la base de données soit prête - une meilleure pratique serait une boucle
# qui teste la connexion, mais pour l'instant, une simple pause peut aider.
# sleep 10

echo "Lancement des migrations..."
php artisan migrate --force

echo "Lancement des seeders..."
php artisan db:seed --force

echo "Démarrage du serveur..."
# Exécute la commande passée en argument au script (la CMD du Dockerfile)
exec "$@"
