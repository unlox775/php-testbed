test :
	docker-compose up -d ; \
	docker-compose exec apache_php5 /root/.composer/vendor/bin/phpunit --bootstrap /var/www/application/config/autoload.php /var/www/application/tests

clean :
	rm nothing-yet
