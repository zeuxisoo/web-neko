all:
	@echo "make watch"
	@echo "make server"

composer:
	@php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
	@php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === 'fd26ce67e3b237fffd5e5544b45b0d92c41a4afe3e3f778e942e43ce6be197b9cdc7c251dcde6e2a52297ea269370680') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); }"
	@php composer-setup.php
	@php -r "unlink('composer-setup.php');"

vendor:
	@php composer.phar install

update-vendor:
	@php composer.phar update

database:
	@php artisan migrate

watch:
	@npm run dev

server:
	@php artisan serve

assets:
	@npm run build
