install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR12 tests
test:
	composer run-script phpunit tests
run:
	php -S localhost:8000 -t public
logs:
	tail -f storage/logs/lumen.log
