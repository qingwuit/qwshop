#! /bin/bash
cp ../env.example ../env
rm -rf ../node_modules && rm -rf ../vendor
composer install
npm install && npm run prod