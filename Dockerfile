FROM node:22.14.0-slim AS node-base

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci

FROM dunglas/frankenphp:1.5.0-php8.3-alpine AS php-base

WORKDIR /app

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    bcmath pdo_mysql redis opcache tokenizer xml \
    pcntl gd imagick intl gmp zip

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN wget -qO /usr/bin/dumb-init https://github.com/Yelp/dumb-init/releases/download/v1.2.5/dumb-init_1.2.5_$(uname -m) && \
    chmod +x /usr/bin/dumb-init

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

FROM node-base AS node-deps

WORKDIR /app
COPY . .
RUN npm run build

FROM php-base AS php-deps

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-scripts

FROM php-deps AS php-prod

COPY --from=node-deps /app/ /app/
COPY --from=php-deps /app/vendor/ /app/vendor/

ENV APP_ENV=production
ENV APP_DEBUG=false
# force http in the container
ENV SERVER_NAME=:80

EXPOSE 80

ADD --chmod=0755 docker-entrypoint.sh /usr/local/bin

RUN php artisan octane:install --server=frankenphp

ENTRYPOINT ["/usr/bin/dumb-init", "--", "/usr/local/bin/docker-entrypoint.sh"]
CMD ["php", "artisan", "octane:frankenphp", "--port=80", "--host=0.0.0.0", "--caddyfile=/app/Caddyfile"]
