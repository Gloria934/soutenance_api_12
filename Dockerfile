# Étape 1: Installer les dépendances avec Composer
FROM composer:2.5 as vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --prefer-dist

# Étape 2: Construire l'image finale de l'application
FROM php:8.2-fpm-alpine

# Installer les dépendances système et les extensions PHP nécessaires pour Laravel
RUN apk add --no-cache \
      nginx \
      supervisor \
      libzip-dev \
      libpng-dev \
      jpeg-dev \
      freetype-dev \
      libjpeg-turbo-dev \
      gd \
      && docker-php-ext-configure gd --with-freetype --with-jpeg \
      && docker-php-ext-install pdo pdo_mysql zip bcmath gd

# Créer les répertoires et copier les fichiers de configuration
RUN mkdir -p /run/nginx /var/www/html
WORKDIR /var/www/html

# Copier les dépendances Composer de l'étape précédente
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copier le code de l'application
COPY . .

# Créer le fichier .env à partir de l'exemple
RUN cp .env.example .env

# Définir les permissions pour le stockage et le cache de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Générer la clé d'application Laravel
RUN php artisan key:generate

# Exposer le port 80 pour le serveur web
EXPOSE 80

# Définir le script de démarrage
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
