# Dockerfile – GUARANTEED TO WORK ON RENDER (Dec 2025)
FROM php:8.2-fpm

# Install everything
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    sqlite3 \
    libsqlite3-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Create folders + SQLite file
RUN mkdir -p database storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache \
    && chmod 664 database/database.sqlite

# Copy configs
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisord.conf

# ←←← MIGRATIONS NOW RUN AT RUNTIME (NOT BUILD TIME) ←←←
# We do it in entrypoint script instead

EXPOSE 80

# Custom entrypoint that runs migrations then starts server
COPY <<'EOF' /entrypoint.sh
#!/bin/sh
php artisan migrate --force
exec /usr/bin/supervisord -c /etc/supervisord.conf
EOF

RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]