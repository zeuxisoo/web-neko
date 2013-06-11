help:
	make install
	make server
	make migrate

install:
	virtualenv --no-site-package venv
	. venv/bin/activate && pip install -r requirements.txt

server:
	. venv/bin/activate && python manager.py runserver

migrate:
	. venv/bin/activate && python manager.py migrate init
