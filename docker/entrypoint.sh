#!/usr/bin/env sh
set -e

if [ "${RUN_LARAVEL_BOOTSTRAP:-true}" = "true" ]; then
    mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache

    if [ ! -L public/storage ]; then
        rm -rf public/storage
        php artisan storage:link --no-interaction
    fi

    if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
        php artisan migrate --force --no-interaction
    fi

    seed_marker="${SEED_MARKER_FILE:-storage/app/public/.seeded}"
    if [ "${SEED_ON_FIRST_BOOT:-false}" = "true" ] && [ ! -f "$seed_marker" ]; then
        php artisan db:seed --force --no-interaction
        touch "$seed_marker"
        chown www-data:www-data "$seed_marker"
    fi

    if [ "${RUN_OPTIMIZE:-true}" = "true" ]; then
        php artisan optimize:clear --no-interaction
        php artisan config:cache --no-interaction
        php artisan route:cache --no-interaction
        php artisan view:cache --no-interaction
    fi
fi

exec docker-php-entrypoint "$@"
