# --- Étape de Build ---
# Utilisation de l'image officielle Composer
FROM composer:2 as vendor

WORKDIR /app

# Copier tous les fichiers pour que 'artisan' soit disponible pour les scripts composer
COPY . .

# Installe les dépendances. Les scripts post-install devraient maintenant fonctionner.
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Ne pas générer de cache ici pour éviter les problèmes de chemins absolus.


# --- Étape Finale ---
# Utilisation de l'image officielle PHP-FPM
FROM php:8.2-fpm-alpine

# Installation des dépendances système et Nginx
RUN apk add --no-cache nginx

# Installation des extensions PHP requises pour Laravel (avec les linux-headers)
RUN apk add --no-cache linux-headers postgresql-dev && docker-php-ext-install pdo pdo_mysql pdo_pgsql bcmath sockets

# Définition du répertoire de travail
WORKDIR /var/www/html

# Copie de la configuration Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Copie de l'application entièrement "buildée" (code + vendor + cache) depuis l'étape précédente
COPY --from=vendor /app .

# Copie du script d'entrée et le rendre exécutable
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exposition du port et définition du point d'entrée
EXPOSE 80
ENTRYPOINT ["docker-entrypoint.sh"]