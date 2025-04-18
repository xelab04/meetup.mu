# SPDX-FileCopyrightText: © 2025 Clifford Weinmann <https://www.cliffordweinmann.com/>
# SPDX-License-Identifier: MIT-0

### NPM
FROM docker.io/library/node:22.14.0-alpine3.21 AS node

WORKDIR /app

COPY package.json package-lock.json .

RUN npm ci

COPY resources/ .
COPY *.js .

### PHP
FROM docker.io/dunglas/frankenphp:1.4.4-php8.3.17-alpine AS frankenphp

WORKDIR /app

COPY --from=node --chown=${MYUSER}:${MYUSER} /app /app

RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    redis \
    opcache \
    xdebug \
    zip \
    bcmath \
    sockets \
    intl \
    gd \
    imagick \
    exif \
    gmp \
    soap \
    xml \
    zip \
    bz2 \
    calendar \
    tokenizer

RUN apk add --no-cache composer

# Enable PHP production settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . .

RUN echo APP_KEY= > .env

# Create a non-root user
ARG MYUSER=appuser
ARG MYUID=1042
RUN echo 'Adding user' \
    && adduser -D -u ${MYUID} ${MYUSER}; \
    setcap -r /usr/local/bin/frankenphp; \
    chown -R ${MYUSER}:${MYUSER} /data/caddy /config/caddy /app

USER ${MYUID}

RUN composer.phar install

RUN php artisan storage:link
RUN php artisan key:generate

ENV SERVER_NAME=:8080
