FROM php:8.2-fpm

# Install system packages
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Set working directory
WORKDIR /var/www

# Copy Laravel project
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Copy custom nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Copy supervisord config
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose the dynamic port
EXPOSE 8080

# Command to run all services
CMD ["/usr/bin/supervisord"]
