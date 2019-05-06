<h1>Paperyard</h1>
<h3>
Paperyard is an online application easing the process as it will keep a digital copy of every document, make it searchable and PDF exportable. In foreseeable future it will be able to automatically determine the sender, recipient, date and subject of a document automatically.</h3>

# Requirements
- Docker, Docker compose
- Git

# Configuration.
- Paperyard has functions to send mail notifications, reminders, forgot password etc.
- If you want the emailing function to work, add your mail credentials at app/.env.

# Installation.
- git clone https://github.com/paperyard/paperyard.git
- cd paperyard
- open preferred terminal.

@ for development run
- docker-compose up --build

# Start paperyard.

<h4> Windows using Docker-toolbox.</h4>
- open preferred browser
- access http://192.168.99.100 or http://192.168.98.100. 
- please check your docker machine given ip for exact access.

<h4> Windows using Docker for windows, Linux, Mac.</h4>
- open preferred browser
- access http://localhost

