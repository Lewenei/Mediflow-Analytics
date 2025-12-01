# Dockerfile — Fixed Nginx + PHP-FPM for MediFlow Analytics (Alpine + SQLite)
FROM php:8.3-fpm-alpine AS php

# Install system dependencies (including SQLite lib for PDO)
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
    sqlite-libs  # ← FIXED: SQLite system library for PDO

# Install PHP extensions (PDO for SQLite, no sqlite3 needed)
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Create required directories + set permissions
RUN mkdir -p storage/logs bootstrap/cache database \
    && chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 755 storage bootstrap/cache database

# Copy configs
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

# Start Supervisor (Nginx + PHP-FPM)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]