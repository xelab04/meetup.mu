FROM dunglas/frankenphp

WORKDIR /app

# Install dependencies
RUN apt update
RUN apt install nodejs npm -y
# && docker-php-ext-install pdo pdo_mysql intl dom tokenizer fileinfo \

RUN install-php-extensions \
    pdo \
    pdo_mysql \
    intl \
    dom \
    tokenizer

# Copy application files
COPY . .

# RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Set environment
COPY .env.prod .env
RUN php artisan key:generate
RUN php artisan storage:link

# Expose port
EXPOSE 8000

CMD ["sh", "-c", "frankenphp run --port=8000"]
