all: db cache assets

assets:
	php app/console cache:clear
	php app/console cache:clear --env=prod --no-debug	
	php app/console assetic:dump
	php app/console assets:install
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
	php compose.phar install

db:
	php app/console doctrine:database:drop --force
	php app/console doctrine:database:create
	php app/console doctrine:schema:update --force
	php app/console fos:user:create admin s.superczynski@gmail.com admin
	php app/console fos:user:promote admin ROLE_ADMIN
	php app/console faker:populate
