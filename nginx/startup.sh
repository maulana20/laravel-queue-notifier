#!/bin/sh

composer install
php artisan storage:link
php artisan optimize:clear
php artisan migrate
chmod -R 777 storage

php artisan queue:work > /dev/null 2>&1 &
php-fpm -D && nginx -g "daemon off;"
