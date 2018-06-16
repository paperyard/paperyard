#!/bin/bash

# start PHP
service php7.1-fpm start
# start NGINX
/usr/sbin/nginx
# start MariaDB
/etc/init.d/mysql start


# phpmyadmin disable plugin usage for root.
mysql -u root <<MY_QUERY
CREATE DATABASE paperyard_db;
use mysql;
update user set plugin='' where User='root';
flush privileges;
\q
MY_QUERY

cd /var/www/html
chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
# insall laravel dependencies
composer install

# migrate tables
php artisan migrate --force





