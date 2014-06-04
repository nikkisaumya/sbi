all: db cache assets

assets:
	php app/console assetic:dump
	php app/console assets:install
	php app/console cache:clear
	HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
	sudo setfacl -R -m u:"$(HTTPDUSER)":rwX -m u:`whoami`:rwX app/cache app/logs
	sudo setfacl -dR -m u:"$(HTTPDUSER)":rwX -m u:`whoami`:rwX app/cache app/logs

cache:
	rm -rf app/cache/*
	rm -rf app/logs/*

update:
	php composer.phar update

install:
	bower install
	composer install

db:
	php app/console doctrine:database:drop --force
	php app/console doctrine:database:create
	php app/console doctrine:schema:update --force
