# download base image ubuntu 17.10
FROM ubuntu:17.10


# update Ubuntu Software repository
RUN apt-get update

# enable gettext support
RUN apt-get -y install locales \
	&& locale-gen en_US.UTF-8 \
	&& locale-gen de_DE.UTF-8
ENV LC_ALL en_US.UTF8

# install nginx
RUN apt-get -y install nginx

# install php with necessary extensions
RUN apt-get -y install php7.1-cli php7.1-cgi php7.1-fpm php7.1-mbstring php7.1-xml php7.1-zip php7.1-imagick
# php-fpm php-common php-mbstring php-xmlrpc php-soap php-gd php-xml php-mysql php-cli php-mcrypt php-zip

# install mariadb
RUN apt-get -y install mariadb-server mariadb-client

# install tools
RUN apt-get -y install nano
RUN apt-get -y install curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# set working directory
WORKDIR /

# adding config inside ubuntu.
ADD config /config
# moving configuration for webserver
RUN cp config/nginx /etc/nginx/sites-enabled/default

# install phpmyadmin
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install phpmyadmin
RUN ln -s /usr/share/phpmyadmin /var/www/phpmyadmin
RUN cp config/config.inc.php /etc/phpmyadmin/config.inc.php
RUN cp config/config-db.php /etc/phpmyadmin/config-db.php

# enable access to container by exposing port.
EXPOSE 80

# ADD start.sh to ubuntu root dir /
ADD /app/start.sh /

# edit permission
RUN chmod 755 /start.sh

# replace the dos line ending characters to unix format:
RUN sed -i -e 's/\r$//' /start.sh
ENTRYPOINT ./start.sh && /bin/bash
