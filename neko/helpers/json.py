# -*- coding: utf-8 -*-

import functools
from flask import jsonify, g

def json_error(status, message):
    message = {
        'status' : status,
        'message': message,
    }

    response = jsonify(error=message)
    response.status_code = status

    return response

def json_form_errors(form):
    for field_name, field_errors in sorted(form.errors.iteritems(), reverse=True):
        for error in field_errors:
            return json_error(400, error)

def json_require_login(method):
    @functools.wraps(method)
    def wrapper(*args, **kwargs):
        if not g.user:
            return json_error(400, 'Please signin first')
        return method(*args, **kwargs)
    return wrapper
