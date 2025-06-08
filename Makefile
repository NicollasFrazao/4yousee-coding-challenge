include .env

install:
	composer install

build:
	./vendor/bin/sail up --build

up:
	./vendor/bin/sail up

down:
	./vendor/bin/sail down

start:
	./vendor/bin/sail up -d

stop:
	./vendor/bin/sail stop

restart:
	./vendor/bin/sail restart

sh:
	./vendor/bin/sail exec application bash

sh\:application:
	./vendor/bin/sail exec application bash

sh\:mysql:
	./vendor/bin/sail exec mysql bash -c 'mysql -h ${DB_HOST} --port=${DB_PORT} -u ${DB_USERNAME} -p${DB_PASSWORD} ${DB_DATABASE}'