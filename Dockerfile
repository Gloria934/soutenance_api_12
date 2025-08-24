# Utiliser une image PHP de base
FROM php:8.2-fpm-alpine

# Installer les dépendances système et les extensions PHP
RUN apk add --no-cache \
      nginx \
      libzip-dev \
      libpng-dev \
      jpeg-dev \
      freetype-dev \
      libjpeg-turbo-dev \
      gd \
      && docker-php-ext-configure gd --with-freetype --with-jpeg \
      && docker-php-ext-install pdo pdo_mysql zip bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier tous les fichiers de l'application
COPY . .

# Créer le fichier .env et générer la clé d'application AVANT composer install
RUN cp .env.example .env
RUN php artisan key:generate

# Lancer composer install maintenant que l'application est prête
# --optimize-autoloader est une bonne pratique pour la production
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Définir les bonnes permissions pour le stockage et le cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80
EXPOSE 80

# Lancer le serveur
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]