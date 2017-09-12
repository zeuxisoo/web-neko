all:
	@echo
	@echo "Command            : Description"
	@echo "------------------ : ------------------"
	@echo "make composer      : Download the composer.phar file"
	@echo "make vendor        : Install the application vendors"
	@echo "make update-vendor : Update the application vendors"
	@echo "make database      : Migrate the database schemas"
	@echo "make server        : Start the development web server"
	@echo "make watch-assets  : Watch the assets when changed"
	@echo "make hot-assets    : Start hot server for assets development"
	@echo "make dev-assets    : Generate the development ready assets"
	@echo "make prod-assets   : Generate the production ready assets"
	@echo

composer:
	wget https://getcomposer.org/composer.phar

vendor:
	@php composer.phar install

update-vendor:
	@php composer.phar update

database:
	@php artisan migrate

server:
	@php artisan serve

watch-assets:
	@npm run watch

hot-assets:
	@npm run hot

dev-assets:
	@npm run dev

prod-assets:
	@npm run prod
