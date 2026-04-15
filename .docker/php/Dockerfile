FROM php:8.5-fpm

ARG UNAME=www-data
ARG UGROUP=www-data
ARG USER_ID=1000
ARG GROUP_ID=1000
ARG COMPOSER_VERSION=2.9.5

RUN usermod --uid ${USER_ID} ${UNAME} \
    && groupmod --gid ${GROUP_ID} ${UGROUP}

RUN apt-get update \
    && apt-get install -y --no-install-recommends default-mysql-client git unzip curl \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --version=${COMPOSER_VERSION} --filename=composer

WORKDIR /var/www/html

USER www-data
