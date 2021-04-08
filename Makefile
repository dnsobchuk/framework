init: docker-down docker-pull docker-build docker-up framework-init
up: docker-up
down: docker-down
restart: down up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

framework-init: framework-composer-install framework-test

framework-composer-install:
	docker-compose run --rm framework-php-cli composer install --prefer-dist

framework-test:
	docker-compose run --rm framework-php-cli composer test