FROM php:7.4-fpm

RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-configure sockets --enable-sockets \
    && docker-php-ext-install -j$(nproc) \
    pcntl \
    sockets

RUN apt-get update \
    && apt-get -y install telnet
