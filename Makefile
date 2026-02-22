install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests

validate:
	composer validate

test:
	composer exec --verbose phpunit tests -- --display-notices --exclude-group debug --testdox

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --exclude-group debug --coverage-clover=build/logs/clover.xml

coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit -- --exclude-group debug --coverage-text

debug:
	composer exec phpunit -- --group debug --testdox