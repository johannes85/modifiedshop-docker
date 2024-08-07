FROM debian:bookworm-slim

RUN apt-get update && apt-get install -y apt-transport-https lsb-release ca-certificates curl gnupg

RUN \
  echo 'deb https://packages.sury.org/php/ bookworm main' > /etc/apt/sources.list.d/php.list && \
  curl https://packages.sury.org/php/apt.gpg | apt-key add

RUN apt-get update && apt-get install -y \
    apache2 \
    php8.1-bcmath \
    php8.1-intl \
    php8.1-mbstring \
    php8.1-dom \
    php8.1-xml \
    php8.1-gd \
    php8.1-pdo \
    php8.1-mysqli \
    php8.1-zip \
    php8.1-curl \
    php8.1-soap \
    libapache2-mod-php8.1 \
    msmtp-mta \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /app

ENV APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data \
    APACHE_LOG_DIR=/var/log/apache2 \
    APACHE_PID_FILE=/var/run/apache2/apache2.pid \
    APACHE_RUN_DIR=/var/run/apache2 \
    APACHE_LOCK_DIR=/var/lock/apache2 \
    APACHE_LOG_DIR=/var/log/apache2

COPY docker/apache-vhost.conf /etc/apache2/sites-enabled/apache-vhost.conf
COPY docker/apache-mpm-prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf 
COPY docker/apache-log.conf /etc/apache2/conf-enabled/log.conf
COPY docker/modified-php.ini /opt/modified/
COPY docker/modified-php-dev.ini /opt/modified/
COPY docker/entrypoint.sh /opt/modified/
COPY docker/msmtprc /etc/msmtprc

RUN mkdir /webroot && \
    mkdir -p $APACHE_RUN_DIR && mkdir -p $APACHE_LOCK_DIR && mkdir -p $APACHE_LOG_DIR && \
    chmod a+x /opt/modified/entrypoint.sh && \
    chown www-data:www-data /webroot && \
    chown www-data:www-data /var/log/apache2 && \
    chown www-data:www-data /var/lock/apache2 && \
    a2enmod rewrite && \
    a2enmod headers && \
    a2enmod expires && \
    a2dissite 000-default && \
    touch /var/log/msmtp.log && \
    chown www-data:www-data /var/log/msmtp.log

CMD ["/opt/modified/entrypoint.sh"]
