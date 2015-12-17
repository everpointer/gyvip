FROM everpointer/gy-php-apache:latest

RUN apt-get update
RUN apt-get install -y git vim
RUN apt-get clean -y

COPY php.ini /usr/local/etc/php/
COPY . /var/www/html

WORKDIR /var/www/html
RUN composer install
CMD 'apache2-foreground'