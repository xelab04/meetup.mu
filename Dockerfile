FROM registry.suse.com/bci/php:8

WORKDIR /app

COPY . .

RUN zypper -n in php8-intl php8-tokenizer php8-fileinfo php8-dom php8-xmlreader php8-xmlwriter php8-pdo php8-sqlite
RUN zypper -n in nodejs npm

RUN composer install
RUN npm install
RUN npm run build

COPY .env.example .env

RUN php artisan key:generate
RUN php artisan storage:link

RUN touch ./database/database.sqlite

RUN php artisan migrate:fresh --seed

EXPOSE 8000
ENV URL=0.0.0.0

ENTRYPOINT [ "sh", "-c", "php artisan serve --host=$URL --port=8000" ]
