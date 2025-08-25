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

# Copier les fichiers composer et installer les dépendances SANS scripts
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --prefer-dist --no-scripts

# Copier le reste du code de l'application
COPY . .

# Créer le .env et générer la clé. Ça fonctionne car /vendor existe.
RUN cp .env.example .env
RUN php artisan key:generate

# Exécuter les scripts Composer qui ont été sautés
RUN composer run-script post-autoload-dump --no-interaction --no-dev

# Définir les bonnes permissions pour le stockage et le cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copier et donner les permissions au script d'entrée
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exposer le port 80
EXPOSE 80

# Définir le point d'entrée
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Lancer le serveur par défaut
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
