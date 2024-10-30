# Use the official PHP image as a parent image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies and Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . .

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Copy the Nginx configuration file
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

# Set permissions for the storage and bootstrap/cache directories
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose the ports that the application runs on
EXPOSE 80 9000

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm