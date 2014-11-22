# -*- coding: utf-8 -*-

from flask import request, jsonify, url_for, g
from ...helpers import force_integer
from ...helpers.json import json_form_errors, json_require_login
from ...helpers.blueprint import Blueprint
from ...models import Talk
from ...forms import CreateTalkForm

blueprint = Blueprint("api_talk", __name__)

@blueprint.route('/')
@json_require_login
def index():
    page = force_integer(request.args.get('page', 1), 1)

    paginator = Talk.query.filter(Talk.service.in_(("system", "api"))).order_by(Talk.create_at.desc()).paginate(page, 12)
    prev_page = url_for('api_talk.index', page=page-1, _external=True) if paginator.has_prev else None
    next_age  = url_for('api_talk.index', page=page+1, _external=True) if paginator.has_next else None

    return jsonify(
        items = [talk.to_json() for talk in paginator.items],
        prev  = prev_page,
        next  = next_age,
        count = paginator.total
    )

@blueprint.route('/show/<int:talk_id>')
@json_require_login
def show(talk_id):
    talk = Talk.query.get_or_404(talk_id)

    return jsonify(talk.to_json())

@blueprint.route('/create', methods=['POST'])
@json_require_login
def create():
    form = CreateTalkForm(csrf_enabled=False)

    if form.validate_on_submit():
        talk = form.save(g.user, service='api')
        return jsonify(talk.to_json())
    else:
        return json_form_errors(form)

@blueprint.route('/update/<int:talk_id>', methods=['POST'])
@json_require_login
def update(talk_id):
    talk = Talk.query.get_or_404(talk_id)
    form = CreateTalkForm(obj=talk, csrf_enabled=False)

    if form.validate_on_submit():
        form.populate_obj(talk)

        talk = talk.save()

        return jsonify(talk.to_json())
    else:
        return json_form_errors(form)

@blueprint.route('/delete/<int:talk_id>')
@json_require_login
def delete(talk_id):
    talk = Talk.query.get_or_404(talk_id)
    talk.delete()

    return jsonify(status=200, message='Talk deleted')
