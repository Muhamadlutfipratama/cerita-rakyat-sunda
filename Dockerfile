FROM php:8.2-fpm-bullseye

# Install system dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        git \
        curl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first (better layer caching)
COPY composer.json composer.lock ./

# Install dependencies (add --no-dev for production)
RUN composer install --no-scripts --no-autoloader --no-progress

# Copy the rest of the application
COPY . .

# Optimize autoloader
RUN composer dump-autoload --optimize

# Permissions (adjust as needed for Laravel)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Start PHP-FPM
CMD ["php-fpm"]
