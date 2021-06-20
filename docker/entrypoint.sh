#!/bin/bash
set -e
case "$APP_ENV" in
  prod) 
    echo "Starting in prod mode..." 
    PHPINI_FILE="modified-php.ini" 
    ;;

  *) 
    echo "Starting in dev mode"
    PHPINI_FILE="modified-php-dev.ini"
    ;;
esac

rm -f /etc/php/8.0/apache2/conf.d/30-modified-php.ini
ln -s "/opt/modified/${PHPINI_FILE}" "/etc/php/8.0/apache2/conf.d/30-modified-php.ini"

# Apache gets grumpy about PID files pre-existing
rm -f /var/run/apache2/apache2.pid
exec apache2 -DFOREGROUND
