# Utiliser l'image PHP officielle avec FPM
FROM php:8.2-fpm

# Installer les extensions nécessaires pour Laravel et autres dépendances
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring gd xml

# Installer Composer (gestionnaire de dépendances PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application dans le conteneur
WORKDIR /var/www/html
COPY . /var/www/html

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader

# Donner les permissions appropriées au répertoire de stockage et cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port par défaut utilisé par PHP-FPM
EXPOSE 9000

# Lancer le processus PHP-FPM
CMD ["php-fpm"]