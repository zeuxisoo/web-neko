# -*- coding: utf-8 -*-

import functools
from flask import g, session
from flask import url_for, redirect
from ..models import User

def require_login(method):
    @functools.wraps(method)
    def wrapper(*args, **kwargs):
        if not g.user:
            return redirect(url_for('index.index'))
        return method(*args, **kwargs)
    return wrapper

def login_user(user, permanent=False):
    if not user:
        return None
    else:
        session['id'] = user.id
        session['token'] = user.salt

        if permanent:
            session.permanent = True

    return user

def load_current_user():
    if 'id' in session:
        user = User.query.get(int(session['id']))

        if not user or user.salt != session['token']:
            return None
        else:
            return user
    else:
        return None

def logout_user():
    if 'id' not in session:
        return

    session.pop('id')
    session.pop('token')

def force_integer(value, default=1):
    try:
        return int(value)
    except:
        return default
