<h1>Paperyard</h1>
<h3>
Paperyard will an online application easing the process as it will keep a digital copy of every document, make it searchable and PDF
exportable. In foreseeable future it will be able to automatically determine the sender, recipient, date and subject of a document automatically.
</h3>


# REQUIREMENTS
- Docker, Docker Toolbox, Docker compose
- Nodejs, NPM installed
- git


# CONFIGURATION

====== Linux ======

1. open app/start.sh
2. uncomment everything under "linux make directory if not exist"
- it will make file permission and create directories for documents.
3. uncomment everything under "linux laravel permissions"
- this will remove the errors from laravel when running on linux.

=== WINDOWS, MAC ===
1. open app/start.sh
2. uncomment everything under "windows | mac make directory if not exit"
- it will make directories for documents.

-------------------
- open app/.env
change APP_URL depending on your operating system, docker version or in production server.

- For Linux, Mac,  Microsoft Windows 10 Professional or Enterprise 64-bit.
APP_URL=http://localhost

- For Older windows that uses docker-toolbox.
APP_URL=http://192.168.99.100 or
APP_URL=http://192.168.98.100
you can see check what ip you get from docker-toolbox.

- For production server.
!Note you need a virtual private server like VULTR or DIGITAL OCEAN.
APP_URL = IP GIVEN TO YOU BY your hosting server or DNS if you already configured it.

- change email credentials.
- you can use mailtrap for local testing.

# INSTALLATION
!NOTE. before you proceed to installation, you need to configure some files based on your operating system.
Read the CONFIGURATION for details.
- git clone https://github.com/paperyard/paperyard.git
- cd paperyard
- docker-compose up -d --build
- check if docker image and container is created by running "docker ps".
- if status is up you are good to go.


# START PAPERYARD
- open your preferred browser.
- enter what APP_URL you use. eg localhost, 192.168.99.100.
- access phpmyadmin at URL/phpmyadmin.
--user: root
--pass:































