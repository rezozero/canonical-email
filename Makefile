test :
	vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --ansi -vvv
	vendor/bin/phpstan analyse -c phpstan.neon
