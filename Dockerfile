FROM dunglas/frankenphp

WORKDIR /app

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

COPY . .

COPY .env.prod .env

RUN apt update && apt install composer

RUN composer require laravel/octane

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]


# FROM dunglas/frankenphp

# WORKDIR /app/public

# # Install dependencies
# RUN apt update
# RUN apt install nodejs npm -y
# # && docker-php-ext-install pdo pdo_mysql intl dom tokenizer fileinfo \

# RUN install-php-extensions \
#     pdo \
#     pdo_mysql \
#     intl \
#     dom \
#     tokenizer

# # Copy application files
# COPY . /app

# # RUN composer install --no-dev --optimize-autoloader
# RUN npm install && npm run build

# # Set environment
# COPY .env.prod .env
# RUN php artisan key:generate
# RUN php artisan storage:link

# # Expose port
# EXPOSE 8000
# # ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
# #
# #
