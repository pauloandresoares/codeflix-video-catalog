#!/bin/bash

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh

composer install
php artisan config:cache
php artisan key:generate
php artisan migrate

chmod -R ug+rwX storage bootstrap/cache
chgrp -R www-data storage bootstrap/cache


php-fpm
