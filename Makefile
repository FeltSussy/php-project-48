install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests

validate:
	composer validate

test:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --exclude-group debug --coverage-clover=build/logs/clover.xml

debug:
	composer exec phpunit -- --group debug