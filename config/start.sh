#!/bin/bash

# if routes folder doesn't exist, clone repo to get app source code.
# else volume is mounted. getting source code locally.

if [ ! -d '/var/www/html/routes' ]; then 
# clone paperyard app inside container. 
git clone https://github.com/paperyard/paperyard.git
rm -rf /var/www/html
ln -s /paperyard/app/ /var/www/html
fi

# set installing pages while installing dependencies
cp config/init_pages/installing_index/index.php var/www/html/public/index.php

# start PHP
service php7.1-fpm start
#start NGINX
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

# make documents directories.
chmod -R 777 storage/app
mkdir -p -m 777 storage/app/documents_new/
mkdir -p -m 777 storage/app/documents_processing/
mkdir -p -m 777 storage/app/documents_ocred/
mkdir -p -m 777 storage/app/documents_images/
mkdir -p -m 777 storage/app/symfony_process_error_logs/
# linux laravel permissions
chgrp -R www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache


# insall laravel dependencies
composer install

### migrate databae tables
php artisan migrate

# set orgin index after installing
cp /config/init_pages/original_index/index.php /var/www/html/public/index.php



# initiate cronjobs
if [ ! -e '/var/www/html/cronjobs_paperyard' ]; then
   echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >>  cronjobs_paperyard
else
   rm -rf cronjobs_paperyard
   echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >>  cronjobs_paperyard
fi 
crontab cronjobs_paperyard
# start cron
service cron start







