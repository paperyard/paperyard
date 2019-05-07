# Paperyard

> This is a beta branch of Paperyard.

The goal of this branch is to clean up the image and make it ready for production. Many versions of this branch will most likely not work. Many dependencies must first be embedded in the new structure.

# try the "beta"
- git clone https://github.com/paperyard/paperyard.git
- cd paperyard
- ./set-sql-pwd.sh
- docker-compose up -d --build

Go to http://localhost:8080.

# "Roadmap"

A lot has to be added, readded, changed and moved around. See whats still to go or what should work at the moment:

- move mariadb out of container :white_check_mark:
- make 7.3 ready :x:
- mail pulling (imap) :white_check_mark:
- blank page removal (imagemagick) :x:
- ocr (tesseract & ocrmypdf) :x:
- image to pdf (img2pdf) :x:
- barcode reading (zbar) :x:
- removing & rotating pdf pages (pdftk) :x: