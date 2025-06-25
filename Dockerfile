FROM php:8.3-fpm

RUN apt-get update \
 && apt-get install -y git unzip libzip-dev zip \
 && docker-php-ext-install zip pdo pdo_mysql \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .


RUN composer install \
    --no-interaction \
    --no-scripts \
    --optimize-autoloader \
    --ignore-platform-reqs

RUN rm -f vendor/composer/platform_check.php

RUN chown -R www-data:www-data var/

EXPOSE 9000


CMD ["php-fpm"]
