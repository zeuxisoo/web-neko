# -*- coding: utf-8 -*-

from flask import Blueprint, request, g
from flask import render_template, redirect, url_for, flash
from ..helpers import require_login, force_integer, logout_user
from ..forms import ProfileForm, PasswordForm
from ..models import User

blueprint = Blueprint("setting", __name__)

@blueprint.route('/profile', methods=['GET', 'POST'])
@require_login
def profile():
    form = ProfileForm(obj=g.user)

    if form.validate_on_submit():
        user = User.query.get(g.user.id)
        form.populate_obj(user)
        user.save()

        flash('Your profile updated', 'success')

        return redirect(url_for('setting.profile'))

    return render_template("settings/profile.html", form=form)

@blueprint.route('/password', methods=['GET', 'POST'])
@require_login
def password():
    form = PasswordForm(obj=g.user)

    if form.validate_on_submit():
        user = User.query.get(g.user.id)
        user.change_password(form.new_password.data)

        form.populate_obj(user)

        user.save()

        flash('Your password updated', 'success')
        logout_user()

        return redirect(url_for('index.index'))

    return render_template("settings/password.html", form=form)
