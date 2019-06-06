FROM php:5-apache
RUN a2enmod rewrite
RUN apt-get update; \
	apt-get install openssl libssl-dev libcurl4-openssl-dev git -y; \
	pecl install mongo; \
	echo "extension=mongo.so" > /usr/local/etc/php/conf.d/mongo.ini
# RUN docker-php-ext-install mysqli pdo pdo_mysql

#COMPOSER 
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

#PHPUNIT
RUN composer global require "phpunit/phpunit"

## THE LIFE SAVER
ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.2.1/wait /wait
RUN chmod +x /wait

CMD /wait && apache2-foreground