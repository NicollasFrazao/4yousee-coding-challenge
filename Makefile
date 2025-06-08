#PROJECT=`pwd | xargs basename`
#TESTIMAGE=${PROJECT}:testing
USER=nginx

install:
	composer install
up:
	env UID=$$(id -u) GID=$$(id -g) ./vendor/bin/sail up --build
down:
	./vendor/bin/sail down
stop:
	./vendor/bin/sail stop
sh:
	./vendor/bin/sail exec --user=${USER} application bash
sh\:db:
	./vendor/bin/sail exec database bash