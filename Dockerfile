FROM dunglas/frankenphp

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

COPY . /app

RUN composer install

ENV SERVER_NAME=:8080

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
