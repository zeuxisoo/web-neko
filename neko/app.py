#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import sys
import datetime
import re

from flask import Flask, g
from flask.ext.babel import Babel
from flask.ext.babel import format_datetime
from jinja2 import evalcontextfilter, Markup, escape
from .helpers import load_current_user
from .routes import index, article, memo, talk, setting
from .models import db

APP_ROOT        = os.path.abspath(os.path.dirname(__file__))
APP_CONFIG_ROOT = os.path.join(APP_ROOT, 'configs')
APP_STATIC_ROOT = os.path.join(APP_ROOT, 'statics')

def create_app(config=None):
    app = Flask(__name__, static_url_path='/static', template_folder='templates')
    app.static_folder = os.path.abspath('static')
    app.jinja_env.trim_blocks = True

    app.config.from_pyfile(os.path.join(APP_CONFIG_ROOT, 'default.py'))

    if isinstance(config, dict):
        app.config.update(config)
    elif config:
        app.config.from_pyfile(config)

    register_database(app)
    register_babel(app)

    register_hook(app)

    register_template_filter(app)
    register_blueprint(app)

    return app

def register_database(app):
    db.init_app(app)
    db.app = app

def register_babel(app):
    babel = Babel(app)

def register_hook(app):
    @app.before_request
    def current_user():
        g.user = load_current_user()

def register_template_filter(app):
    @app.template_filter('timeago')
    def timeago(value):
        now = datetime.datetime.utcnow()
        delta = now - value

        if delta.days > 365:
            return '{0} years ago'.format(delta.days / 365)
        if delta.days > 30:
            return '{0} months ago'.format(delta.days / 30)
        if delta.days > 0:
            return '{0} days ago'.format(delta.days)
        if delta.seconds > 3600:
            return '{0} hours ago'.format(delta.seconds / 3600)
        if delta.seconds > 60:
            return '{0} minutes ago'.format(delta.seconds / 60)
        return 'just now'

    @app.template_filter('dateformat')
    def dateformat(_datetime, format='yyyy-MM-dd H:mm'):
        return format_datetime(_datetime, format)

    @app.template_filter('ucfirst')
    def ucfirst(value):
        return value.title()

    @app.template_filter()
    @evalcontextfilter
    def nl2br(eval_ctx, value):
        _paragraph_re = re.compile(r'(?:\r\n|\r|\n){2,}')

        result = u'\n\n'.join(u'<p>%s</p>' % p.replace('\n', '<br>\n') for p in _paragraph_re.split(escape(value)))
        if eval_ctx.autoescape:
            result = Markup(result)
        return result

def register_blueprint(app):
    app.register_blueprint(index.blueprint, url_prefix='')
    app.register_blueprint(article.blueprint, url_prefix='/article')
    app.register_blueprint(memo.blueprint, url_prefix='/memo')
    app.register_blueprint(talk.blueprint, url_prefix='/talk')
    app.register_blueprint(setting.blueprint, url_prefix='/setting')
