# -*- coding: utf-8 -*-

from flask import request, jsonify, url_for, g
from ...helpers import force_integer
from ...helpers.json import json_form_errors, json_require_login
from ...helpers.blueprint import Blueprint
from ...models import MemoAppend, Memo
from ...forms import CreateMemoAppendForm

blueprint = Blueprint("api_memo_append", __name__)

@blueprint.route('/<int:memo_id>')
@json_require_login
def index(memo_id):
    page = force_integer(request.args.get('page', 1), 1)

    paginator = MemoAppend.query.filter(MemoAppend.memo_id == memo_id).order_by(MemoAppend.update_at.desc()).paginate(page, 1)
    prev_page = url_for('api_memo_append.index', memo_id=memo_id, page=page-1, _external=True) if paginator.has_prev else None
    next_age  = url_for('api_memo_append.index', memo_id=memo_id, page=page+1, _external=True) if paginator.has_next else None

    return jsonify(
        items = [memo_append.to_json() for memo_append in paginator.items],
        prev  = prev_page,
        next  = next_age,
        count = paginator.total
    )

@blueprint.route('/show/<int:memo_append_id>')
@json_require_login
def show(memo_append_id):
    memo_append = MemoAppend.query.get_or_404(memo_append_id)

    return jsonify(memo_append.to_json())

@blueprint.route('/create/<int:memo_id>', methods=['POST'])
@json_require_login
def create(memo_id):
    form = CreateMemoAppendForm(csrf_enabled=False)

    if form.validate_on_submit():
        memo        = Memo.query.get_or_404(memo_id)
        memo_append = form.save(g.user, memo)
        return jsonify(memo_append.to_json())
    else:
        return json_form_errors(form)
