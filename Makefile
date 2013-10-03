MIGRATE_FILE_PREFIX = auto_generate
MIGRATE_FILE_NAME   = $(MIGRATE_FILE_PREFIX)/$(shell date +'%y_%m_%d_%H_%M_%S')

help:
	@echo "make install"
	@echo "make server"
	@echo "make migrate-init"
	@echo "make migrate-create"
	@echo "make migrate-autogen"
	@echo "make migrate-upgrade"
	@echo "clean-pyc"

install:
	virtualenv --no-site-package venv
	. venv/bin/activate && pip install -r requirements.txt

server:
	. venv/bin/activate && python manager.py runserver

migrate-create:
	. venv/bin/activate && alembic init alembic

migrate-init:
	. venv/bin/activate && alembic current

migrate-autogen:
	. venv/bin/activate && alembic revision -m $(MIGRATE_FILE_NAME) --autogenerate

migrate-upgrade:
	. venv/bin/activate && alembic upgrade head

clean-pyc:
	@find . -name '*.pyc' -exec rm -f {} +
	@find . -name '*.pyo' -exec rm -f {} +
	@find . -name '*~' -exec rm -f {} +
