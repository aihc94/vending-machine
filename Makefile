build-dev:
	docker build --target dev -f ./docker/php-fpm/Dockerfile -t php84-fpm:dev .

build-prod:
	docker build --target prod -f ./docker/php-fpm/Dockerfile -t php84-fpm:prod .

up:
	docker compose up -d

down:
	docker compose down

install:
	docker exec -it api composer install
	docker exec -it public composer install

init-database-with-seeders:
	docker exec -it api php bin/console app:init-database true

init-database-without-seeders:
	docker exec -it api php bin/console app:init-database false
