# Use the official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install required PHP extensions and dependencies
RUN apt-get update && apt-get install -y curl gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && node -v && npm -v

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy Laravel app files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install frontend dependencies (optional, if you use Laravel Mix)
RUN npm install && npm run build

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
