#!/bin/sh

if [ ! -f /var/www/html/.env ]; then
    composer install
    php artisan key:generate
    php artisan migrate:fresh --seed
fi

exec php-fpm
