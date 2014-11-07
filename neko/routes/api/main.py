# -*- coding: utf-8 -*-

from flask import request, jsonify, url_for
from ...helpers import login_user, logout_user
from ...helpers.json import json_error, json_form_errors
from ...helpers.blueprint import Blueprint
from ...forms import SigninForm

blueprint = Blueprint("api_main", __name__)

@blueprint.route('/signin', methods=['POST'])
def signin():
    form = SigninForm(csrf_enabled=False)

    if form.validate_on_submit():
        login_user(form.user, form.permanent.data)
        return jsonify(form.user.to_json())
    else:
        return json_form_errors(form)

@blueprint.route('/signout')
def signout():
    logout_user()
    return jsonify(status=200, message='User signout successed')
