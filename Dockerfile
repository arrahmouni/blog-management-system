# Use the official PHP image with Apache
FROM php:8.2-apache

# Set working directory (for Laravel, we copy to /var/www/laravel)
WORKDIR /var/www/laravel

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    gnupg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && node -v && npm -v

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy project files into Docker container
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/laravel \
    && chmod -R 755 /var/www/laravel/storage /var/www/laravel/bootstrap/cache

# Set the Apache DocumentRoot to Laravel's public directory
RUN sed -i "s|DocumentRoot /var/www/html|DocumentRoot /var/www/laravel/public|g" /etc/apache2/sites-available/000-default.conf

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install frontend dependencies
RUN npm install && npm run build

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
