#!/bin/bash

# Wait for the DB to be ready (optional but recommended)
# sleep 10

# Fix permissions at runtime
mkdir -p /var/www/laravel/storage/logs
chown -R www-data:www-data /var/www/laravel/storage /var/www/laravel/bootstrap/cache
chmod -R 775 /var/www/laravel/storage /var/www/laravel/bootstrap/cache


# Run Laravel migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Start Apache (or php-fpm, etc.)
apache2-foreground
