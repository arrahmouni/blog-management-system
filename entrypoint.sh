#!/bin/bash

# Fix permissions (ensure www-data owns files)
chown -R www-data:www-data /var/www/laravel/storage /var/www/laravel/bootstrap/cache

# Run migrations/seeds AS www-data
su -s /bin/bash www-data -c "php artisan migrate --force"
su -s /bin/bash www-data -c "php artisan db:seed --force"

# Start Apache
apache2-foreground
