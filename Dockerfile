FROM php:8.3-apache
RUN docker-php-ext-install pdo_mysql && printf 'display_errors=Off\nlog_errors=On\ndate.timezone=America/Sao_Paulo\n' > /usr/local/etc/php/conf.d/meridiano.ini
COPY app/ /var/www/app/
COPY pub/ /var/www/html/
