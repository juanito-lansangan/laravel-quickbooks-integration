#!/bin/sh

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    (cd /var/www/html && php artisan config:cache && php artisan route:cache && php artisan view:cache)
fi

if [ "$role" = "fms_prototype_app" ]; then
    echo "Running php..."
    exec php-fpm
else
    echo "Could not match the container role \"$role\""
    exit 1
fi