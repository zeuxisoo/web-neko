#!/usr/bin/env python
# coding: utf-8

import os
from flask.ext.script import Manager
from flask.ext.assets import ManageAssets
from flask.ext.alembic import ManageMigrations
from theday.app import create_app

app = create_app(os.path.abspath("./theday/configs/developement.py"))

manager = Manager(app)
manager.add_command("assets", ManageAssets())
manager.add_command("migrate", ManageMigrations())

@manager.command
def password(password):
	"""Generate hashed password and salt for user"""

	from theday.models import User

	password = User.create_password(password)

	print("Password: {0}".format(password['content']))
	print("    Salt: {0}".format(password['salt']))

if __name__ == "__main__":
	manager.run()
