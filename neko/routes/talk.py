# -*- coding: utf-8 -*-

from flask import Blueprint, request, g
from flask import render_template, redirect, url_for, flash
from ..helpers import require_login, force_integer
from ..forms import CreateTalkForm
from ..models import Talk

blueprint = Blueprint("talk", __name__)

@blueprint.route('/index')
@require_login
def index():
    page = force_integer(request.args.get('page', 1), 0)

    if not page:
        return abort(404)
    else:
        paginator = Talk.query.filter(Talk.service.in_(("system", "api"))).order_by(Talk.create_at.desc()).paginate(page)

        return render_template("talks/index.html", paginator=paginator)

@blueprint.route('/create', methods=['GET', 'POST'])
@require_login
def create():
    form = CreateTalkForm()

    if form.validate_on_submit():
        talk = form.save(g.user)
        return redirect(url_for('talk.index'))

    return render_template("talks/create.html", form=form)

@blueprint.route('/edit/<int:talk_id>', methods=['GET', 'POST'])
@require_login
def edit(talk_id):
    talk = Talk.query.get_or_404(talk_id)
    form = CreateTalkForm(obj=talk)

    if form.validate_on_submit():
        form.populate_obj(talk)
        talk.save()

        return redirect(url_for('talk.index', talk_id=talk_id))

    return render_template('talks/create.html', form=form)

@blueprint.route('/delete/<int:talk_id>')
@require_login
def delete(talk_id):
    talk = Talk.query.get_or_404(talk_id)
    talk.delete()

    flash('Deleted talk', 'success')

    return redirect(url_for('talk.index'))
