#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
from flask.ext.script import Manager
from neko.app import create_app

root = os.path.abspath(os.path.dirname(__file__))
app  = create_app(os.path.join(root, "neko/configs/development.py"))

manager = Manager(app)

@manager.command
def password(password):
    """Generate hashed password for user"""

    from neko.models import User

    password = User.create_password(password)

    print("Password: {0}".format(password))

@manager.command
def cron_twitter():
    """Run the cron for sync twitter to talk"""

    from neko.crons import twitter

    twitter = twitter.Twitter()
    twitter.connect()
    twitter.user_info()
    twitter.fetch()

if __name__ == "__main__":
    manager.run()
