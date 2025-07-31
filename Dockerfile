# # Use the official PHP image with Apache
# FROM php:8.2-apache

# # Set working directory (for Laravel, we copy to /var/www/laravel)
# WORKDIR /var/www/laravel

# # Install PHP extensions and dependencies
# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     libpq-dev \
#     zip \
#     gnupg \
#     && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# # Enable Apache Rewrite Module
# RUN a2enmod rewrite

# # Install Node.js 20
# RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
#     && apt-get install -y nodejs \
#     && node -v && npm -v

# # Install Composer
# COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# # Copy project files into Docker container
# COPY . .

# # Set permissions
# # RUN chown -R www-data:www-data /var/www/laravel \
# #     && chmod -R 755 /var/www/laravel/storage /var/www/laravel/bootstrap/cache

# RUN chown -R www-data:www-data /var/www/laravel \
#     && chmod -R 775 /var/www/laravel/storage /var/www/laravel/bootstrap/cache

# # Set the Apache DocumentRoot to Laravel's public directory
# RUN sed -i "s|DocumentRoot /var/www/html|DocumentRoot /var/www/laravel/public|g" /etc/apache2/sites-available/000-default.conf

# # Install PHP dependencies
# RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# # Install frontend dependencies
# RUN npm install && npm run build

# # Expose port
# EXPOSE 80

# # Copy entrypoint script
# COPY entrypoint.sh /entrypoint.sh
# RUN chmod +x /entrypoint.sh
# CMD ["/entrypoint.sh"]

# ---------- BASE IMAGE ----------
FROM php:8.2-fpm

# ---------- SYSTEM DEPENDENCIES ----------
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    npm \
    libzip-dev \
    supervisor \
    nginx \
    nano

# ---------- PHP EXTENSIONS ----------
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# ---------- COMPOSER ----------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ---------- NODE FOR VUE ----------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm

# ---------- WORK DIR ----------
WORKDIR /var/www/html

# ---------- COPY FILES ----------
COPY . .

# ---------- DEPENDENCIES ----------
RUN composer install --optimize-autoloader --no-dev \
 && npm install && npm run build

# ---------- PERMISSIONS ----------
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ---------- NGINX CONFIG ----------
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default


# ---------- SUPERVISOR ----------
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ---------- EXPOSE ----------
EXPOSE 10000

# ---------- START SCRIPT ----------
COPY entrypoint.sh /entrypoint.sh
# RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

