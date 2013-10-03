# -*- coding: utf-8 -*-

from flask import Blueprint, request, g
from flask import render_template, redirect, url_for
from ..forms import SigninForm
from ..helpers import login_user, require_login, logout_user

blueprint = Blueprint("index", __name__)

@blueprint.route('/', methods=['GET', 'POST'])
def index():
	if g.user:
		return redirect(url_for('article.index'))

	form = SigninForm()

	if form.validate_on_submit():
		login_user(form.user, form.permanent.data)
		return redirect(url_for('article.index'))

	return render_template("index.html", form=form)

@blueprint.route('/signout')
def signout():
	logout_user()
	return redirect(url_for('index.index'))
