# Updated Dockerfile
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpq-dev libonig-dev \
    libxml2-dev libsqlite3-dev sqlite3 npm nodejs default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy only composer files first for dependency caching
COPY composer.json composer.lock ./

# Install dependencies with increased memory limit
RUN COMPOSER_MEMORY_LIMIT=-1 COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy the rest of the application
COPY . .

# Set directory permissions
RUN chmod -R 775 storage bootstrap/cache

# Run post-install scripts
RUN composer run-script post-install-cmd

# Build frontend assets
RUN npm install && npm run build

# Remove database-related commands from build step
# (Migrations/seeds will run at deployment time instead)

# Use a proper process manager for FPM
CMD ["php-fpm"]
