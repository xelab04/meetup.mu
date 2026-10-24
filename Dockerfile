FROM docker.io/library/node:26.12.1-alpine3.24 AS node

WORKDIR /app

COPY package.json package-lock.json ./

COPY resources ./resources

COPY *.js ./

RUN npm ci

RUN npm run build

FROM docker.io/dunglas/frankenphp:1.13.2-php8.5-alpine AS frankenphp

# ARG MYUSER=appuser
# ARG MYUID=1042

WORKDIR /app

USER 0

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

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Create a non-root user
ARG MYUSER=appuser
ARG MYUID=1042

RUN echo 'Adding user' \
    && adduser -D -u ${MYUID} ${MYUSER}; \
    setcap -r /usr/local/bin/frankenphp; \
    chown -R ${MYUSER}:${MYUSER} /data/caddy /config/caddy /app

RUN wget -qO /usr/bin/dumb-init https://github.com/Yelp/dumb-init/releases/download/v1.2.5/dumb-init_1.2.5_$(uname -m) && \
    chmod +x /usr/bin/dumb-init

# USER ${MYUID}

COPY --from=node --chown=${MYUSER}:${MYUSER} /app /app

COPY . .

COPY --from=docker.io/composer/composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 8080

RUN php artisan storage:link

# RUN php artisan config:cache
# RUN php artisan event:cache
# RUN php artisan route:cache
# RUN php artisan view:cache

CMD ["/usr/bin/dumb-init", "--", "/usr/local/bin/docker-entrypoint.sh"]

CMD ["php", "artisan", "octane:frankenphp", "--port=8080", "--host=0.0.0.0"]

# ENV SERVER_NAME=:8080
