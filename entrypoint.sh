#!/bin/bash

# Fix permissions (ensure www-data owns files)
chown -R www-data:www-data /var/www/laravel/storage /var/www/laravel/bootstrap/cache

# Run migrations/seeds AS www-data
su -s /bin/bash www-data -c "php artisan migrate --force"
su -s /bin/bash www-data -c "php artisan db:seed --force"
su -s /bin/bash www-data -c "php artisan storage:link"
su -s /bin/bash www-data -c "php artisan optimize"

npm run dev &

# Start Apache
apache2-foreground
