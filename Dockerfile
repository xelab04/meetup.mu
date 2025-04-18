# SPDX-FileCopyrightText: © 2025 Clifford Weinmann <https://www.cliffordweinmann.com/>
# SPDX-License-Identifier: MIT-0

FROM docker.io/library/node:22.14.0-alpine3.21 AS node

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY resources/ ./
COPY *.js ./

RUN npm run build

FROM docker.io/dunglas/frankenphp:1.4.4-php8.3.17-alpine AS frankenphp

ARG MYUSER=appuser
ARG MYUID=1042

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

# Enable PHP production settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . .

# Create a non-root user
ARG MYUSER=appuser
ARG MYUID=1042

RUN echo 'Adding user' \
    && adduser -D -u ${MYUID} ${MYUSER}; \
    setcap -r /usr/local/bin/frankenphp; \
    chown -R ${MYUSER}:${MYUSER} /data/caddy /config/caddy /app

USER ${MYUID}

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

RUN wget -qO /usr/bin/dumb-init https://github.com/Yelp/dumb-init/releases/download/v1.2.5/dumb-init_1.2.5_$(uname -m) && \
    chmod +x /usr/bin/dumb-init

ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 8080

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/bin/dumb-init", "--", "/usr/local/bin/docker-entrypoint.sh"]

CMD ["php", "artisan", "octane:frankenphp", "--port=8080", "--host=0.0.0.0"]
