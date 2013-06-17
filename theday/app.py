#!/usr/bin/env python
# coding: utf-8

import os
import sys

from flask import Flask, g
from flask.ext.assets import Environment
from flask.ext.babel import Babel
from .helpers import load_current_user
from .routes import index
from .models import db

APP_ROOT        = os.path.abspath(os.path.dirname(__file__))
APP_CONFIG_ROOT = os.path.join(APP_ROOT, 'configs')
APP_STATIC_ROOT = os.path.join(APP_ROOT, 'statics')

def create_app(config=None):
	app = Flask(__name__, static_url_path='/statics', static_folder='statics', template_folder='templates')
	app.jinja_env.trim_blocks = True

	app.config.from_pyfile(os.path.join(APP_CONFIG_ROOT, 'default.py'))

	if isinstance(config, dict):
		app.config.update(config)
	elif config:
		app.config.from_pyfile(config)

	db.init_app(app)
	db.app = app

	assets = Environment()
	assets.init_app(app)

	babel = Babel(app)

	@app.before_request
	def current_user():
		g.user = load_current_user()

	app.register_blueprint(index.blueprint, url_prefix='')

	return app
