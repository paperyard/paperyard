#!/bin/sh

set -e

until mysqladmin ping -h"db" &> /dev/null
do
  sleep 1
done

cd /var/www/html
php artisan migrate

exec "$@"
