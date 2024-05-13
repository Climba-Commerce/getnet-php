# Use a imagem oficial do PHP 7.4
FROM php:7.4

COPY . /var/www/html

RUN apt-get update && apt-get -y --no-install-recommends install git \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y zlib1g-dev libzip-dev unzip
RUN docker-php-ext-install zip