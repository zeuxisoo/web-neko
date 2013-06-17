#!/usr/bin/env python
# coding: utf-8

from flask import Blueprint
from flask import render_template, flash
from ..forms import SigninForm

blueprint = Blueprint("index", __name__)

@blueprint.route('/', methods=['GET', 'POST'])
def index():
	form = SigninForm()

	if form.validate_on_submit():
		return redirect(url_for('index.home'))

	return render_template("index.html", form=form)
