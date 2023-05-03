ifeq ($(OS),Windows_NT)     # is Windows_NT on XP, 2000, 7, Vista, 10...
    detected_OS := Windows
    copy:= copy /Y
    slash:= "\\"
    remove:= rd /
else
    detected_OS := $(shell uname)  # same as "uname -s"
    copy := cp
    slash:= "/"
    remove:= rm -rf
endif

install:
	$(copy) .$(slash)config$(slash).env.dev .$(slash)src$(slash)sulu$(slash).env
	cd docker && docker-compose up --build -d
	$(MAKE) composer
	cd docker && docker-compose exec -it php bash -c "./bin/adminconsole sulu:build dev --no-interaction"
#	cd docker && docker-compose exec -it php bash -c "./bin/adminconsole sulu:build dev --destroy --no-interaction"
#	cd docker && docker-compose exec -it php bash -c "./bin/adminconsole sulu:build prod --destroy"
	$(MAKE) admin-update-build

up:
	cd docker && docker-compose up --build -d
	$(MAKE) composer

down:
	cd docker && docker-compose down --remove-orphans

enter-app:
	cd docker && docker-compose exec -it php bash

image-format-refresh:
	cd docker && docker-compose exec -it php bash -c "php bin/websiteconsole sulu:media:format:cache:clear" php

clear-cache:
	cd docker && docker-compose exec -it php bash -c "./bin/console cache:clear" php

dump-autoload:
	cd docker && docker-compose exec -it php bash -c "composer dump-autoload" php

admin-update-build:
	cd docker && docker-compose exec -it php bash -c "rm -rf assets/admin/node_modules && rm -rf ../vendor/sulu/sulu/node_modules && rm -rf ../vendor/sulu/sulu/src/Sulu/Bundle/*/Resources/js/node_modules"
	cd docker && docker-compose exec -it php bash -c "npm install --prefix assets/admin --no-audit --no-fund --loglevel=error"
	cd docker && docker-compose exec -it php bash -c "npm run build --prefix assets/admin"

build-watch:
	cd docker && docker-compose exec -it php bash -c "npm run watch --prefix assets/admin"

composer:
	cd docker && docker-compose exec -it php bash -c "composer update" -V

phpcs:
	cd docker && docker-compose exec -it php bash -c "composer run-script lint-php-cs-fix" -V
	
build-database:
	cd docker && docker-compose exec -it php bash -c "./bin/adminconsole sulu:build database"

phpcsf:
	cd docker && docker-compose exec -it php bash -c "composer run-script php-cs-fix" -V

test:
	cd docker && docker-compose exec -it php bash -c "php vendor/bin/codecept run" -V

test-build:
	cd docker && docker-compose exec -it php bash -c "php vendor/bin/codecept generate:cest Api CriteriaCrud" -V

test-coverage:
	cd docker && docker-compose exec -it php bash -c "php vendor/bin/codecept run --coverage --coverage-html" -V

phpstan:
	cd docker && docker-compose exec -it php bash -c "php vendor/bin/phpstan analyse" -V