# -*- coding: utf-8 -*-

from flask import Blueprint, request, g
from flask import render_template, redirect, url_for, flash
from ..helpers import require_login, force_integer
from ..forms import CreateMemoForm, CreateMemoAppendForm
from ..models import Memo, MemoAppend

blueprint = Blueprint("memo", __name__)

@blueprint.route('/index')
@require_login
def index():
    page = force_integer(request.args.get('page', 1), 0)

    if not page:
        return abort(404)
    else:
        paginator = Memo.query.order_by(Memo.update_at.desc()).paginate(page)

        return render_template("memos/index.html", paginator=paginator)

@blueprint.route('/create', methods=['GET', 'POST'])
@require_login
def create():
    form = CreateMemoForm()

    if form.validate_on_submit():
        memo = form.save(g.user)
        return redirect(url_for('memo.view', memo_id=memo.id))

    return render_template("memos/create.html", form=form)

@blueprint.route('/view/<int:memo_id>', methods=['GET', 'POST'])
@require_login
def view(memo_id):
    memo = Memo.query.get_or_404(memo_id)

    form = CreateMemoAppendForm()

    if form.validate_on_submit():
        memo_append = form.save(g.user, memo)
        return redirect(url_for('memo.view', memo_id=memo_append.memo_id))

    return render_template("memos/view.html", memo=memo, form=form)

@blueprint.route('/delete/<int:memo_id>')
@require_login
def delete(memo_id):
    memo = Memo.query.get_or_404(memo_id)
    memo.delete()

    flash('Deleted memo', 'success')

    return redirect(url_for('memo.index'))

@blueprint.route('/edit/<int:memo_id>', methods=['GET', 'POST'])
@require_login
def edit(memo_id):
    memo = Memo.query.get_or_404(memo_id)
    form = CreateMemoForm(obj=memo)

    if form.validate_on_submit():
        form.populate_obj(memo)
        memo.save()

        return redirect(url_for('memo.view', memo_id=memo_id))

    return render_template('memos/create.html', form=form)
