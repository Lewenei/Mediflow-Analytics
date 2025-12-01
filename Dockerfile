# Dockerfile — Nginx + PHP-FPM (fast & pro)
FROM php:8.3-fpm-alpine AS php

# Install system deps + PHP extensions
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    sqlite-libs 

RUN docker-php-ext-install pdo pdo_sqlite sqlite3 mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create app directory
WORKDIR /var/www/html

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Create required directories
RUN mkdir -p storage/logs bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache database

# Copy Nginx + Supervisor config
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

# Start Supervisor (runs PHP-FPM + Nginx)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]