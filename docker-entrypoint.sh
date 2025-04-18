#!/bin/sh
set -e

echo "Storage Link"
php artisan storage:link

echo "Caching and Optimizing..."

php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

echo "Optimization done..."

exec "$@"