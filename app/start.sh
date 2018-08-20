###!/bin/bash

### start PHP
service php7.1-fpm start
### start NGINX
/usr/sbin/nginx
# start MariaDB
/etc/init.d/mysql start

### phpmyadmin disable plugin usage for root.
mysql -u root <<MY_QUERY
CREATE DATABASE paperyard_db;
use mysql;
update user set plugin='' where User='root';
flush privileges;
\q
MY_QUERY

### cd to laravel application root.
cd /var/www/html

### Windows || Mac make directory if not exit
# mkdir -p public/static/documents_new/
# mkdir -p public/static/documents_processing/
# mkdir -p public/static/documents_ocred/
# mkdir -p public/static/documents_images/

### Linux make directory if not exist
# chmod -R 777 public/static
# mkdir -p -m 777 public/static/documents_new/
# mkdir -p -m 777 public/static/documents_processing/
# mkdir -p -m 777 public/static/documents_ocred/
# mkdir -p -m 777 public/static/documents_images/

### Linux laravel permissions
# chgrp -R www-data storage bootstrap/cache
# chmod -R ug+rwx storage bootstrap/cache

### insall laravel dependencies
composer install

### migrate databae tables
php artisan migrate

echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >>  cronjobs_paperyard
crontab cronjobs_paperyard
service cron start






