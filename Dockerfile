# SPDX-FileCopyrightText: © 2025 Clifford Weinmann <https://www.cliffordweinmann.com/>
# SPDX-License-Identifier: MIT-0

### NPM
FROM docker.io/library/node:22.14.0-alpine3.21 AS node
RUN mkdir /app
COPY package.json package-lock.json /app/
WORKDIR /app
RUN npm install
COPY . /app/
RUN npm run build

### PHP
FROM docker.io/dunglas/frankenphp:1.4.4-php8.3.17-alpine AS frankenphp
RUN install-php-extensions zip intl
WORKDIR /app
#RUN echo 'Install composer' \
#	&& php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
#	&& php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }" \
#	&& php composer-setup.php \
#	&& php -r "unlink('composer-setup.php');"
RUN apk add --no-cache composer

# Enable PHP production settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Create a non-root user
ARG MYUSER=appuser
ARG MYUID=1042
RUN echo 'Adding user' \
	&& adduser -D -u ${MYUID} ${MYUSER}; \
	setcap -r /usr/local/bin/frankenphp; \
	chown -R ${MYUSER}:${MYUSER} /data/caddy /config/caddy /app

USER ${MYUID}
COPY --from=node --chown=${MYUSER}:${MYUSER} /app /app
RUN php composer.phar install
RUN php artisan storage:link

ENV SERVER_NAME=:8080

### Configure app with seed data
# USER 0
# COPY --chown=${MYUSER}:${MYUSER} .env.example /app/.env
# USER ${MYUID}
# RUN php artisan key:generate
# RUN touch database/database.sqlite && php artisan migrate --seed
