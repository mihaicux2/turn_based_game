FROM php:7.3-apache 

RUN apt-get update && apt-get install -y curl git unzip wget libxslt1.1 libxslt1-dev\
    && docker-php-ext-install mysqli xsl

#COMPOSER 
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

#PHPUNIT
RUN composer global require "phpunit/phpunit"

ENV PATH /root/.composer/vendor/bin:$PATH

RUN ln -s /root/.composer/vendor/bin/phpunit /usr/bin/phpunit

#PHPDOX
RUN wget http://phpdox.de/releases/phpdox.phar
RUN chmod +x phpdox.phar
RUN mv phpdox.phar /usr/local/bin/phpdox

#INSTALL PHP dependencies
# instead of binding the local www folder to the container, we copy it's content 
# and install the required dependencies from there :) This commands should be run only if
# the docker-compose.yml `www` service volume is commented out
# WORKDIR /var/www/html
# COPY ./www .
# RUN composer install
