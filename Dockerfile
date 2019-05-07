FROM composer:1.8.5 as composer

# install imap extension (to support composer script "post-autoload-dump")
RUN set -xe \
    && apk add --no-cache imap-dev openssl-dev \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS krb5-dev \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap \
    && apk del .build-deps

# copy source
COPY app /app

# install dependencies
RUN composer install --ignore-platform-reqs


# build main image
FROM php:7.1-alpine

RUN set -xe \
    && echo "upload_max_filesize = 128M" >> /usr/local/etc/php/conf.d/0-upload_large_files.ini \
    && echo "post_max_size = 128M" >> /usr/local/etc/php/conf.d/0-upload_large_files.ini \
    && echo "memory_limit = 1G" >> /usr/local/etc/php/conf.d/0-upload_large_files.ini \
    && echo "max_execution_time = 600" >> /usr/local/etc/php/conf.d/0-upload_large_files.ini \
    && echo "max_input_vars = 5000" >> /usr/local/etc/php/conf.d/0-upload_large_files.ini

STOPSIGNAL SIGINT

# install imap extension
RUN set -xe \
    && apk add --no-cache imap-dev openssl-dev \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS krb5-dev \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap \
    && apk del .build-deps

# install sql extension and tools
RUN	set -xe \
    && apk add --no-cache mariadb-client \
    && docker-php-ext-install pdo_mysql

# copy source from first stage
COPY --from=composer app /var/www/html/


RUN set -xe \
    && addgroup -S paperyard \
    && adduser -S -G paperyard paperyard \
    && mkdir -p /var/www/html \
    && mkdir -p /var/www/html/storage/app/documents_new \
    && mkdir -p /var/www/html/storage/app/documents_processing \
    && mkdir -p /var/www/html/storage/app/documents_ocred \
    && mkdir -p /var/www/html/storage/app/documents_images \
    && mkdir -p /var/www/html/storage/app/symfony_process_error_logs \
    && chown -R paperyard:paperyard /var/www/html

RUN set -xe \
    && chgrp -R www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache

COPY entrypoint.sh /usr/local/bin/
ENTRYPOINT [ "entrypoint.sh" ]

USER paperyard
CMD	[ "php", "-S", "[::]:8080", "-t", "/var/www/html/public" ]

EXPOSE 8080
