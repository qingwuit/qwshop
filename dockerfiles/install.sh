!/bin/bash

cp /var/www/.env.example /var/www/.env
rm -rf /var/www/node_modules && rm -rf /var/www/vendor
composer install
npm install && npm run prod
php artisan docker:install