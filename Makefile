all:
	@echo "make watch"
	@echo "make server"

watch:
	@npm run dev

server:
	@php artisan serve
