.PHONY: lint phpstan phpcs psalm test

lint: phpstan phpcs psalm

phpstan:
	docker compose exec web vendor/bin/phpstan analyse

phpcs:
	docker compose exec web vendor/bin/phpcs -q -n

psalm:
	docker compose exec web vendor/bin/psalm --no-progress

test:
	docker compose exec web vendor/bin/phpunit
