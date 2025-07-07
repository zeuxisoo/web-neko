COMPOSER := $(shell command -v composer 2>/dev/null || echo "php composer.phar")

all:
	@echo
	@echo "Command       : Description"
	@echo "------------- : ---------------------"
	@echo "make composer : Download the composer tools"
	@echo "make install  : Install the development vendors and assets by composer"
	@echo "make dev      : Start frontned and backend server"
	@echo "make web      : Start the backend development server"
	@echo "make assets   : Start the frontned development server"
	@echo "make format   : format the php file by pint"
	@echo

composer:
	@curl -sS https://getcomposer.org/installer | php

install:
	@$(COMPOSER) install

dev:
	@$(COMPOSER) run dev

web:
	@$(COMPOSER) artisan serve

assets:
	@npm run dev

format:
	@php ./vendor/bin/pint
