FROM php:7.2-apache AS dom_handler_php

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/html

RUN apt-get update \
    && apt-get install -y zip git

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

RUN composer self-update \
    && composer validate \
    && composer update --prefer-dist --no-scripts --no-progress --no-suggest
