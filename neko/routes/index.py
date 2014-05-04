# -*- coding: utf-8 -*-

from datetime import datetime
from flask import Blueprint, request, g
from flask import render_template, redirect, url_for
from ..forms import SigninForm
from ..helpers import login_user, require_login, logout_user
from ..models import User, Article, Memo, Talk

blueprint = Blueprint("index", __name__)

@blueprint.route('/', methods=['GET', 'POST'])
def index():
    if g.user:
        return redirect(url_for('index.home'))

    form = SigninForm()

    if form.validate_on_submit():
        login_user(form.user, form.permanent.data)
        return redirect(url_for('index.home'))

    return render_template("index.html", form=form)

@blueprint.route('/home')
def home():
    counter = {
        'user': User.query.count(),
        'article': Article.query.count(),
        'memo': Memo.query.count(),
        'talk': Talk.query.filter_by(service='system').count()
    }

    return render_template("home.html", counter=counter, time=datetime.now())

@blueprint.route('/signout')
def signout():
    logout_user()
    return redirect(url_for('index.index'))
