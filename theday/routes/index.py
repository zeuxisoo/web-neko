#!/usr/bin/env python
# coding: utf-8

from flask import Blueprint, request
from flask import render_template, redirect, url_for
from ..forms import SigninForm
from ..helpers import login_user, require_login

blueprint = Blueprint("index", __name__)

@blueprint.route('/', methods=['GET', 'POST'])
def index():
	form = SigninForm()

	if form.validate_on_submit():
		login_user(form.user, form.permanent.data)
		return redirect(url_for('index.home'))

	return render_template("index.html", form=form)

@blueprint.route('/home')
@require_login
def home():
	return render_template("home.html")
