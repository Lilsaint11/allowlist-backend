
FROM php:8.3-cli

# Install system dependencies and PHP extensions for Laravel and PostgreSQL.
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl libpq-dev libzip-dev \
    libonig-dev libxml2-dev \
    && docker-php-ext-install \
    pdo_pgsql mbstring bcmath zip pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application source.
COPY . .

# Install production PHP dependencies.
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --no-progress

# Prepare writable Laravel directories.
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

EXPOSE 10000

# Use Laravel's built-in server for this simple API deployment.
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
