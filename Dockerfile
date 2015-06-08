FROM everpointer/gy-php-apache:latest
COPY php.ini /usr/local/etc/php/
COPY . /var/www/html

WORKDIR /var/www/html
RUN composer install
CMD 'apache2-foreground'