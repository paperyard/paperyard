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

# cd to larave application root.
cd /var/www/html

# linux make directory if not exist
chmod -R 777 public/static
mkdir -p -m 777 public/static/documents/

# windows make directory if not exit
# mkdir -p public/static/documents/

### laravel permissions
chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

# insall laravel dependencies
composer install
# migrate databae tables
php artisan migrate





