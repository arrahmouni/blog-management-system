#!/bin/bash

# Wait for DB (optional: add wait logic here)

php artisan key:generate
php artisan migrate --force
php artisan storage:link

# Start services
exec /usr/bin/supervisord
