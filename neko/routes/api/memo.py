# -*- coding: utf-8 -*-

from flask import request, jsonify, url_for, g
from ...helpers import force_integer
from ...helpers.json import json_error, json_form_errors, json_require_login
from ...helpers.blueprint import Blueprint
from ...models import Memo
from ...forms import CreateMemoForm

blueprint = Blueprint("api_memo", __name__)

@blueprint.route('/')
@json_require_login
def index():
    page = force_integer(request.args.get('page', 1), 1)

    paginator = Memo.query.order_by(Memo.update_at.desc()).paginate(page, 12)
    prev_page = url_for('api_memo.index', page=page-1, _external=True) if paginator.has_prev else None
    next_age  = url_for('api_memo.index', page=page+1, _external=True) if paginator.has_next else None

    return jsonify(
        items = [memo.to_json() for memo in paginator.items],
        prev  = prev_page,
        next  = next_age,
        count = paginator.total
    )

@blueprint.route('/show/<int:memo_id>')
@json_require_login
def show(memo_id):
    memo = Memo.query.get_or_404(memo_id)

    return jsonify(memo.to_json())

@blueprint.route('/create', methods=['POST'])
@json_require_login
def create():
    form = CreateMemoForm(csrf_enabled=False)

    if form.validate_on_submit():
        memo = form.save(g.user)
        return jsonify(memo.to_json())
    else:
        return json_form_errors(form)

@blueprint.route('/update/<int:memo_id>', methods=['POST'])
@json_require_login
def update(memo_id):
    memo = Memo.query.get_or_404(memo_id)
    form = CreateMemoForm(obj=memo, csrf_enabled=False)

    if form.validate_on_submit():
        form.populate_obj(memo)

        memo = memo.save()

        return jsonify(memo.to_json())
    else:
        return json_form_errors(form)

@blueprint.route('/delete/<int:memo_id>')
@json_require_login
def delete(memo_id):
    memo = Memo.query.get_or_404(memo_id)
    memo.delete()

    return jsonify(status=200, message='Memo deleted')
