#!/bin/sh
set -eo xtrace

echo "   INFO  Storage Link"
php artisan storage:link

echo "   INFO  Caching and Optimizing..."

php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

echo "   INFO  Optimization done..."

exec "$@"
