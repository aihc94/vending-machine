FROM php:8.4-fpm AS prod

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    zip \
    unzip \
    make \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN usermod -u 1000 www-data \
    && chown -R www-data:www-data /var/www

WORKDIR /var/www

USER www-data

FROM prod AS dev

USER root

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

USER www-data