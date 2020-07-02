test :
	./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
	./vendor/bin/phpcbf -p
	./vendor/bin/phpstan analyse -c phpstan.neon -l max src
