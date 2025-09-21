# Utiliser une image PHP de base
FROM php:8.2-fpm-alpine

# Installer les dépendances système et les extensions PHP
RUN apk add --no-cache \
      nginx \
      gettext \
      postgresql-dev \
      libzip-dev \
      libpng-dev \
      jpeg-dev \
      freetype-dev \
      libjpeg-turbo-dev \
      gd \
      && docker-php-ext-configure gd --with-freetype --with-jpeg \
      && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers composer et installer les dépendances SANS scripts
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --prefer-dist --no-scripts

# Copier le reste du code de l'application
COPY . .

# Exécuter les scripts Composer qui ont été sautés
RUN composer run-script post-autoload-dump --no-interaction --no-dev

# Définir les bonnes permissions pour le stockage et le cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copier la configuration Nginx
COPY nginx.conf /etc/nginx/nginx.conf.template

# Copier et donner les permissions au script d'entrée
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exposer le port 80
EXPOSE 80

# Définir le point d'entrée
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]


# # Récupéré depuis le projet fait avec Mr KANTE
# FROM richarvey/nginx-php-fpm:3.1.6

# COPY . .

# # Image config
# ENV SKIP_COMPOSER 1
# ENV WEBROOT /var/www/html/public
# ENV PHP_ERRORS_STDERR 1
# ENV RUN_SCRIPTS 1
# ENV REAL_IP_HEADER 1

# # Laravel config
# ENV APP_ENV production
# ENV APP_DEBUG false
# ENV LOG_CHANNEL stderr

# # Allow composer to run as root
# ENV COMPOSER_ALLOW_SUPERUSER 1

# CMD ["/start.sh"]