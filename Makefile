all: cache assets

assets:
	php app/console assetic:dump
	php app/console assets:install
	php app/console cache:clear
	sudo chmod -R 777 app/cache/*
	sudo chmod -R 777 app/logs/*
cache:
	rm -rf app/cache/*
	rm -rf app/logs/*
update:
	php composer.phar update


