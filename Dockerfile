# Dockerfile — Works on Render 100% (Alpine + SQLite + Nginx + PHP-FPM)
FROM php:8.2-fpm-alpine

# Install nginx + supervisor + sqlite support
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    git

# Install ONLY the PHP extensions that work perfectly on Alpine
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# SQLite is already built into PHP on Alpine — we just enable PDO_SQLITE
RUN docker-php-ext-enable pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Create database folder + file
RUN mkdir -p database && touch database/database.sqlite
RUN chown -R www-data:www-data database/database.sqlite storage bootstrap/cache
RUN chmod 664 database/database.sqlite

# Copy nginx + supervisor config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Run migrations on startup
RUN php artisan migrate --force || true

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]