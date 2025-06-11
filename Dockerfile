# Stage 1: Build Composer dependencies
FROM composer:2 AS build

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader


# Stage 2: Set up Nginx + PHP
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy Laravel code from build stage
COPY --from=build /app /var/www

# Set working dir
WORKDIR /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Copy nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Expose port
EXPOSE 8080

# Start both PHP and Nginx
CMD php artisan config:cache \
    && php artisan route:cache \
    && service nginx start \
    && php-fpm
