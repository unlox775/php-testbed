FROM php:fpm

RUN apt-get update; \
	apt-get install openssl libssl-dev libcurl4-openssl-dev -y; \
	pecl install mongo; \
	echo "extension=mongo.so" > /usr/local/etc/php/conf.d/mongo.ini
