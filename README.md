# Docker Container for modified eCommerce Shopsoftware

This is a Docker container I use to run the modified eCommerce Shopsoftware.
There is no support and warranty.

## Features
 * PHP8
 * Apache
 * MariaDB
 * msmtp for sending mails

## Usage
 * Copy modified files into webroot
 * Copy env.template to env
 * Set APP_ENV variable in in ```env``` file to "prod" or "dev"
 * Configure MySQL users in ```env``` file
 * Copy docker/msmtp.template to docker/msmtp
 * Enter your mailserver credentials into docker/msmtp (https://marlam.de/msmtp/msmtp.html)
 * Build container: ```docker compose build```
 * Run container on dev system ```docker compose up```
 * Run container on prod system in background ```docker compose up -d```
