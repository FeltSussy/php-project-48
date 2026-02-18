install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests

validate:
	composer validate

test:
	composer exec --verbose phpunit -- tests --exclude-group debug

debug:
	composer exec phpunit -- --group debug

coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text