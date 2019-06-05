test :
	docker exec php-testbed_apache_php5_1 /root/.composer/vendor/bin/phpunit --bootstrap /var/www/application/config/autoload.php /var/www/application/tests

clean :
	rm nothing-yet
