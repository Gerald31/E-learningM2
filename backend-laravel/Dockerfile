FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer.lock and composer.json
COPY composer.lock composer.json ./

# Install PHP dependencies
RUN composer install --no-autoloader --no-scripts --verbose

# Copy existing application directory contents
COPY . .

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for Laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Change current user to www
USER www

# Expose port 8000 and start php-fpm server
EXPOSE 8000

# Run project
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]