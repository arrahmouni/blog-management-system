#!/bin/bash

envsubst '$PORT' < /etc/nginx/sites-available/default > /etc/nginx/sites-enabled/default

# Wait for DB (optional: add wait logic here)

php artisan key:generate
until php artisan migrate --force; do
    echo "Waiting for database..."
    sleep 5
done
php artisan storage:link
php artisan optimize

# Start services
exec /usr/bin/supervisord
