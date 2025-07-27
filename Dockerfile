# Dockerfile

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpq-dev libonig-dev \
    libxml2-dev libsqlite3-dev sqlite3 npm nodejs default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN chmod -R 775 storage bootstrap/cache

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader

RUN npm install && npm run build

RUN php artisan key:generate
RUN php artisan migrate --force --seed

CMD php artisan serve --host=0.0.0.0 --port=8080
