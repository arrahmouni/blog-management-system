#!/bin/bash

# Wait for the DB to be ready (optional but recommended)
# sleep 10

# Run Laravel migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Start Apache (or php-fpm, etc.)
apache2-foreground
