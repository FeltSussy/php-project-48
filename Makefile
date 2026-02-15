installinstall:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests
	composer exec --verbose phpstan analyse src bin tests

validate:
	composer validate

test:
	composer exec --verbose phpunit tests